<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:47
 */
namespace app\wechatBase\response;

class RespVideoMessage extends BaseRespMessage
{
    public function getXML($message)
    {
        $tpl = "<xml>\r\n"
            ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
            ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
            ."  <CreateTime>%s</CreateTime>\r\n"
            ."  <MsgType><![CDATA[video]]></MsgType>\r\n"
            ."  <Video>\r\n"
            ."      <MediaId><![CDATA[%s]]></MediaId>\r\n"
            ."      <Title><![CDATA[%s]]></Title>\r\n"
            ."      <Description><![CDATA[%s]]></Description>\r\n"
            ."  </Video>\r\n"
            ."</xml>";

        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $message['media_id'], $message['title'], $message['description']);
    }
}