<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/2/27
 * Time: 14:41
 */
namespace app\controllers;

use app\wechatBase\TextMessage;
use app\wechatBase\ImageMessage;
use app\wechatBase\VoiceMessage;
use app\wechatBase\VideoMessage;
use app\wechatBase\LocationMessage;
use app\wechatBase\LinkMessage;
use app\wechatBase\EventMessage;

use Yii;
use yii\web\Controller;


class WeChatController extends Controller
{
    //禁用csrf拦截，以使微信服务器的post请求可以通过
    public $enableCsrfValidation = false;
    // 设置为true后不做业务处理，直接输出调试信息（即输出从微信服务器接收到的xml）
    public $debug = false;

    public $wehatServerUrl = "https://api.weixin.qq.com/cgi-bin/";

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 功能：检验请求是真的来自微信服务器
     * @author shiweihua
     * @return bool
     */
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = "youarenotdarkgel";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr === $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 功能：来自微信服务器的请求的处理入口
     * @author shiweihua
     */
    public function actionWeChatHandler(){
        if(isset($_GET['nosign']) && 1 == $_GET['nosign']){//这个用于本地的微信开发调试小工具
            $this->responseMsg();
            exit;

        }elseif (isset($_GET['test']) && 1 == $_GET['test']){//用于测试或进行类似菜单设置等操作
            echo "<pre>";
            echo "获取用户列表";
            print_r($this->getUserList());
            echo "<br/>";
            echo "发送模板信息";
            print_r($this->sendTemplateMessage());
            echo "</pre>";


        }elseif($this->checkSignature()){//用于正常响应微信服务器发过来的消息
            $this->responseMsg();
            //echo $_GET['echostr'];
        }
    }

    /**
     *功能：获取access_token
     * @author shiweihua
     * @param bool $refresh 是否刷新access_token,默认不刷新
     * @return string 返回access_token
     * */
    public function getAccessToken($refresh=false){
        $accessToken = Yii::$app->cache->get("accessToken");
        if($refresh || false === $accessToken){
            if(WECHAT_ONLINE){
                //线上公众号
                $appId = "wx65fe8c42d8a79457";
                $secret = "c1b26c4873f86771633a6169b6b08b6e";
            }else{
                //测试号
                $appId = "wx5e168823829e9838";
                $secret = "12d9fc513e2f7e9dda45f5b6fe913d24";
            }

            $url = $this->wehatServerUrl."token?grant_type=client_credential&appid=".$appId."&secret=".$secret;

            $dataAsArray = $this->curlInGet($url);
            //缓存获得的数据
            Yii::$app->cache->set("accessToken",$dataAsArray['access_token'],$dataAsArray['expires_in']);
            return $dataAsArray['access_token'];
        }
        return $accessToken;
    }

    /**
     * 功能：设置菜单
     * @author shiweihua
     * */
    public function actionSetMenu(){
        $Token = Yii::$app->request->get('token');
        if('darkgel' == $Token) {
            $accessToken = $this->getAccessToken();
            $url = $this->wehatServerUrl."menu/create?access_token=".$accessToken;

            $button1_1 = new \stdClass();
            $button1_1->type = "view";
            $button1_1->name = "搜狗搜索";
            $button1_1->url = "http://www.soso.com/";

            $button1_2 = new \stdClass();
            $button1_2->type = "view";
            $button1_2->name = "百度搜索";
            $button1_2->url = "http://www.baidu.com/";

            $baseUrl = \urldecode("http://wechat-darkgel.s1.natapp.cc/we-chat/test-oauth-base");
            $button1_3 = new \stdClass();
            $button1_3->type = "view";
            $button1_3->name = "基本授权";
            $button1_3->url = "https://open.weixin.qq.com/connect/oauth2/authorize?"
                            ."appid=wx5e168823829e9838&redirect_uri=".$baseUrl
                            ."&response_type=code&scope=snsapi_base&state=33#wechat_redirect";

            $highUrl = \urldecode("http://wechat-darkgel.s1.natapp.cc/we-chat/test-oauth-user-info");
            $button1_4 = new \stdClass();
            $button1_4->type = "view";
            $button1_4->name = "高级授权";
            $button1_4->url = "https://open.weixin.qq.com/connect/oauth2/authorize?"
                            ."appid=wx5e168823829e9838&redirect_uri=".$highUrl
                            ."&response_type=code&scope=snsapi_userinfo&state=333#wechat_redirect";

            $button1 = new \stdClass();
            $button1->name = "网站";
            $button1->sub_button = array($button1_1, $button1_2, $button1_3, $button1_4 );

            $button2 = new \stdClass();
            $button2->type = "pic_weixin";
            $button2->name = "发张图片";
            $button2->key = "V333_PIC";
            $button2->sub_button = [];

            $button = array($button1, $button2);

            $menu = new \stdClass();
            $menu->button = $button;
            
            //设置post数据
            $post_data = json_encode($menu, JSON_UNESCAPED_UNICODE);
            $data = $this->curlInPost($url,$post_data);
            //显示获得的数据
            print_r($data);
        }else{
            echo "非法请求";
        }
    }

    /**
     *功能：响应微信服务器发过来的消息
     *@author shiwehua
     */
    public function responseMsg()
    {
        define('WEIXIN_DEBUG', $this->debug);
        $postStr = file_get_contents("php://input");

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            define('FROM_USER_NAME', $postObj->FromUserName);
            define('TO_USER_NAME', $postObj->ToUserName);
            $msgType = $postObj->MsgType;

            $this->handleMessage($msgType, $postObj);

        }else {
            echo '';
            exit;
        }
    }

    /**
     * 功能：按类型处理微信服务器发过来的信息
     * @author shiweihua
     * @param $msgType string 对应xml中的<MsgType>标签
     * @param $postObj object xml对象
     * */
    protected function handleMessage($msgType, $postObj)
    {
        switch ($msgType) {
            case 'text':
                TextMessage::handle($postObj);
                break;
            case 'image':
                ImageMessage::handle($postObj);
                break;
            case 'voice':
                VoiceMessage::handle($postObj);
                break;
            case 'video':
                VideoMessage::handle($postObj);
                break;
            case 'location':
                LocationMessage::handle($postObj);
                break;
            case 'link':
                LinkMessage::handle($postObj);
                break;
            case 'event':
                EventMessage::handle($postObj);
                break;
            default:
                echo '';
                exit;
        }
    }

    /**
     * 功能：获取用户列表
     * @author shiweihua
     * 注意：关注者数量超过10000时需要多次获取，该方法并没有实现这一步
     * */
    public function getUserList(){
        $accessToken = $this->getAccessToken(true);
        $url = $this->wehatServerUrl."user/get?access_token=".$accessToken;
        return $usersAsArray = $this->curlInGet($url);

    }

    /**
     * 功能：通过curl的get方法获取数据
     * @author shiweihua
     * @param $url string 请求的url
     * @return array 由json解析来的数据，存为数组形式
     * */
    protected function curlInGet($url){
        $curl = curl_init();

        //避免ssl 证书错误,作为本地测试,直接关掉验证就好
        if(!WECHAT_ONLINE){
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
        }

        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //将json格式的数据解析成数组
        return $dataAsArray = json_decode($data,true);
    }

    /**
     * 功能：通过curl的post方式来提交数据
     * @author shiweihua
     * @param $url string 请求提交的url
     * @param $postData array json_encode()函数处理后的数组，作为需要提交的数据
     * @return object 微信服务器的响应
     * */
    protected function curlInPost($url,$postData){
        //初始化
        $curl = curl_init();

        //避免ssl 证书错误,作为本地测试,直接关掉验证就好
        if(!WECHAT_ONLINE){
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false );
        }
        
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        //执行命令
        $responseData = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $responseData;
        
    }

    /**
     * 功能：发送模板消息
     * @author shiweihua
     * */
    public function sendTemplateMessage(){
        $accessToken = $this->getAccessToken();
        $url = $this->wehatServerUrl."message/template/send?access_token=".$accessToken;

        $hello = new \stdClass();
        $hello->value = "欢迎来到darkgel的世界";
        $hello->color = #173177;

        $name = new \stdClass();
        $name->value = "darkgel";
        $name->color = "#173177";

        $num = new \stdClass();
        $num->value = "33";
        $num->color = "#173177";

        $time = new \stdClass();
        $time->value = date("F j, Y, g:i a");
        $time->color = "#173177";

        $data = new \stdClass();
        $data->hello = $hello;
        $data->name = $name;
        $data->num = $num;
        $data->time = $time;

        $template = new \stdClass();
        $template->touser = "oGsm61E_u5tSxLwP_u1KJB5ROw88";
        $template->template_id = "pUE-yHBo7fXVL_5_b92j9l_A2rSmRSg1u5WgE01w_FE";
        $template->url = "";
        $template->topcolor = "#FF0000";
        $template->data = $data;

        $postData = json_encode($template, JSON_UNESCAPED_UNICODE);

        return $this->curlInPost($url,$postData);
    }

    /**
     * 功能：测试scope为snsapi_userinfo的第三方登录授权
     * @author shiweihua
     * */
    public function actionTestOauthUserInfo(){
        $code = $_GET['code'];
        $state = $_GET['state'];

        //通过这个url获得授权的access_token
        $accessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?"
                ."appid=wx5e168823829e9838"
                ."&secret=12d9fc513e2f7e9dda45f5b6fe913d24"
                ."&code=".$code
                ."&grant_type=authorization_code ";

        $accessData = $this->curlInGet($accessTokenUrl);

        //获取access_token,openid
        $accessToken = $accessData['access_token'];
        $openId = $accessData['openid'];

        //获取用户信息
        $userInfoUrl = "https://api.weixin.qq.com/sns/userinfo?"
                    ."access_token=".$accessToken
                    ."&openid=".$openId
                    ."&lang=zh_CN";

        $userData = $this->curlInGet($userInfoUrl);

        echo "<pre>";
        var_dump($accessData);
        var_dump($userData);
        echo "</pre>";
        echo "state : ".$state;
    }

    /**
     * 功能：测试scope为snsapi_base的第三方登录授权
     * */
    public function actionTestOauthBase(){
        $code = $_GET['code'];
        $state = $_GET['state'];

        //通过这个url获得授权的access_token
        $accessTokenUrl = "https://api.weixin.qq.com/sns/oauth2/access_token?"
            ."appid=wx5e168823829e9838"
            ."&secret=12d9fc513e2f7e9dda45f5b6fe913d24"
            ."&code=".$code
            ."&grant_type=authorization_code ";

        $accessData = $this->curlInGet($accessTokenUrl);

        echo "<pre>";
        var_dump($accessData);
        echo "</pre>";
        echo "state : ".$state;


    }
}
