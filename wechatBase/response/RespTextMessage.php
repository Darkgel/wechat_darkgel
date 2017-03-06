<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:42
 */
namespace app\wechatBase\response;

class RespTextMessage extends BaseRespMessage
{
    public function getXML($content)
    {
        $tpl = "<xml>\r\n"
                ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
                ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
                ."  <CreateTime>%s</CreateTime>\r\n"
                ."  <MsgType><![CDATA[text]]></MsgType>\r\n"
                ."  <Content><![CDATA[%s]]></Content>\r\n"
                ."</xml>";
        $response = sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $content);
        return $response;
    }
}