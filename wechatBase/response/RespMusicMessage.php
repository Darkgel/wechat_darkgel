<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:48
 */
namespace  app\wechatBase\response;

class RespMusicMessage extends BaseRespMessage
{
    public function getXML($message)
    {
        $tpl = "<xml>\r\n"
            ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
            ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
            ."  <CreateTime>%s</CreateTime>\r\n"
            ."  <MsgType><![CDATA[music]]></MsgType>\r\n"
            ."  <Music>\r\n"
            ."      <Title><![CDATA[%s]]></Title>\r\n"
            ."      <Description><![CDATA[%s]]></Description>\r\n"
            ."      <MusicUrl><![CDATA[%s]]></MusicUrl>\r\n"
            ."      <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>\r\n"
            ."      <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>\r\n"
            ."  </Music>\r\n"
            ."</xml>";

        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $message['title'], $message['description'], $message['music_url'], $message['hq_music_url'], $message['media_id']);
    }
}