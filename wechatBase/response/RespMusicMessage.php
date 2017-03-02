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
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[music]]></MsgType>
                <Music>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <MusicUrl><![CDATA[%s]]></MusicUrl>
                <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
                </Music>
                </xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $message['title'], $message['description'], $message['music_url'], $message['hq_music_url'], $message['media_id']);
    }
}