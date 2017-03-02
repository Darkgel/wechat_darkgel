<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:11
 */
namespace app\wechatBase;

class ImageMessage extends BaseReqMessage
{
    //图片链接
    public $PicUrl;
    //图片消息媒体id，可以调用多媒体文件下载接口拉取数据。
    public $MediaId;
    //消息id，64位整型
    public $MsgId;
    // 重写init方法，处理该类型消息
    protected function init()
    {
//        $service = new UnitnetSearchService4();
//        $content = $service->startup();
        $content = "这张图片正好看";
        RespMessage::replyText($content);
//         RespMessage::replyImage($this->MediaId);
    }

    public static function handle($oMessage, $className=__CLASS__)
    {
        return parent::handle($oMessage, $className);
    }
}