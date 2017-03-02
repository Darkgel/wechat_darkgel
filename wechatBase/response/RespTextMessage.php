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
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                </xml>";
        $response = sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $content);
        return $response;
    }
}