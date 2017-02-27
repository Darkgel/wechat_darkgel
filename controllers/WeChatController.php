<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/2/27
 * Time: 14:41
 */
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\ContactForm;

class WeChatController extends Controller
{
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
        return $this->checkSignature();
    }

}
