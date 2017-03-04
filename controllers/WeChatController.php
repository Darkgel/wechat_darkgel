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
use app\models\LoginForm;
use app\models\ContactForm;

class WeChatController extends Controller
{
    //禁用csrf拦截，以使微信服务器的post请求可以通过
    public $enableCsrfValidation = false;
    // 设置为true后不做业务处理，直接输出调试信息（即输出从微信服务器接收到的xml）
    public $debug = false;
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
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

    public function actionWeChatHandler(){
        if($this->checkSignature()){
//            $this->responseMsg();
            echo $_GET['echostr'];
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
        if($refresh||false === $accessToken){
//            $appId = "wx65fe8c42d8a79457";
//            $secret = "c1b26c4873f86771633a6169b6b08b6e";
            //测试号
            $appId = "wx5e168823829e9838";
            $secret = "12d9fc513e2f7e9dda45f5b6fe913d24";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appId."&secret=".$secret;
            //初始化
            $curl = curl_init();
            //设置抓取的url
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //执行命令
            $data = curl_exec($curl);
            //关闭URL请求
            curl_close($curl);
            //将json格式的数据解析成数组
            $dataAsArray = json_decode($data,true);
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
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;

            $button1_1 = new \stdClass();
            $button1_1->type = "view";
            $button1_1->name = "搜狗搜索";
            $button1_1->url = "http://www.soso.com/";

            $button1_2 = new \stdClass();
            $button1_2->type = "view";
            $button1_2->name = "百度搜索";
            $button1_2->url = "http://www.baidu.com/";

            $button1_3 = new \stdClass();
            $button1_3->type = "view";
            $button1_3->name = "新浪";
            $button1_3->url = "http://www.sina.com.cn/";

            $button1 = new \stdClass();
            $button1->name = "网站";
            $button1->sub_button = array($button1_1,$button1_2,$button1_3);

            $button2 = new \stdClass();
            $button2->type = "pic_weixin";
            $button2->name = "发张图片";
            $button2->key = "V333_PIC";
            $button2->sub_button = [];

            $button = array($button1, $button2);

            $menu = new \stdClass();
            $menu->button = $button;

            //初始化
            $curl = curl_init();
            //设置抓取的url
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置post数据
            $post_data = json_encode($menu, JSON_UNESCAPED_UNICODE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            //执行命令
            $data = curl_exec($curl);
            //关闭URL请求
            curl_close($curl);
            //显示获得的数据
            print_r($data);
        }else{
            echo "非法请求";
        }
    }


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


}
