<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:00
 */
namespace app\wechatBase\response;

class RespImageMessage extends BaseRespMessage
{
    public function getXML($mediaId)
    {
        $tpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[image]]></MsgType>
                <Image>
                <MediaId><![CDATA[%s]]></MediaId>
                </Image>
                </xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $mediaId);
    }
}