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
        $tpl = "<xml>\r\n"
            ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
            ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
            ."  <CreateTime>%s</CreateTime>\r\n"
            ."  <MsgType><![CDATA[voice]]></MsgType>\r\n"
            ."  <Voice>\r\n"
            ."      <MediaId><![CDATA[%s]]></MediaId>\r\n"
            ."  </Voice>\r\n"
            ."</xml>";

        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $mediaId);
    }
}