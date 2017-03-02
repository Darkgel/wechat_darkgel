<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:13
 */
namespace app\wechatBase;

class VoiceMessage extends BaseReqMessage
{
    public $MediaId;    //语音消息媒体id，可以调用多媒体文件下载接口拉取该媒体
    public $Format; //语音格式：amr
    public $Recognition;    //语音识别结果，UTF8编码
    public $MsgID;  //消息id，64位整型
    // 重写init方法，处理该类型消息
    protected function init()
    {
        RespMessage::replyVoice($this->MediaId);
    }

    public static function handle($oMessage, $className=__CLASS__)
    {
        return parent::handle($oMessage, $className);
    }
}