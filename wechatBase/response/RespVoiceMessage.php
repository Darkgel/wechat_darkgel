<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:46
 */
namespace app\wechatBase\response;

class RespVoiceMessage extends BaseRespMessage
{
    public function getXML($mediaId)
    {
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[voice]]></MsgType>
                <Voice>
                <MediaId><![CDATA[%s]]></MediaId>
                </Voice>
                </xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $mediaId);
    }
}