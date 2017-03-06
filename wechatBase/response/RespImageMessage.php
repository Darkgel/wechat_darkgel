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
        $tpl = "<xml>\r\n"
            ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
            ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
            ."  <CreateTime>%s</CreateTime>\r\n"
            ."  <MsgType><![CDATA[image]]></MsgType>\r\n"
            ."  <Image>\r\n"
            ."      <MediaId><![CDATA[%s]]></MediaId>\r\n"
            ."  </Image>\r\n"
            ."</xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $mediaId);
    }
}