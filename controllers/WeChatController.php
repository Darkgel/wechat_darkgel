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
    const debug = true;
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
        $echostr = $_GET["echostr"];

        $token = "youarenotdarkgel";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return $echostr;
        }else{
            return false;
        }
    }

    public function actionWeChatHandler(){
        $this->responseMsg();
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
    private function setMenu(){
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
        $Token = Yii::$app->request->get('token');
        if('darkgel' == $Token) {
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
            $button2->type = "click";
            $button2->name = "文字游戏";
            $button2->key = "V333_WORD_GAME";

            $button3 = new \stdClass();
            $button3->type = "pic_weixin";
            $button3->name = "发张图片";
            $button3->key = "V333_PIC";
            $button3->sub_button = [];

            $button = array($button1, $button2, $button3);

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
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            define('FROM_USER_NAME', $postObj->FromUserName);
            define('TO_USER_NAME', $postObj->ToUserName);
            $msgType = $postObj->MsgType;

            //将信息存储到缓存中
            Yii::$app->cache->set("postXml",$postStr);

            $this->handleMessage($msgType, $postObj);

        }else {
            echo '';
            exit;
        }
    }

    protected function handleMessage($msgType, $oMessage)
    {
        switch ($msgType) {
            case 'text':
                TextMessage::handle($oMessage);
                break;
            case 'image':
                ImageMessage::handle($oMessage);
                break;
            case 'voice':
                VoiceMessage::handle($oMessage);
                break;
            case 'video':
                VideoMessage::handle($oMessage);
                break;
            case 'location':
                LocationMessage::handle($oMessage);
                break;
            case 'link':
                LinkMessage::handle($oMessage);
                break;
            case 'event':
                EventMessage::handle($oMessage);
                break;
            default:
                echo '';
                exit;
        }
    }
}
