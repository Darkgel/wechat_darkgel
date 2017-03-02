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
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[video]]></MsgType>
                <Video>
                <MediaId><![CDATA[%s]]></MediaId>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                </Video>
                </xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $message['media_id'], $message['title'], $message['description']);
    }
}