<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:22
 */
namespace app\wechatBase\event;
use app\wechatBase\BaseReqMessage;
//场景二维码扫描事件
class ScanEventMessage extends BaseReqMessage
{
    // 重写init方法，处理该类型消息
    public function init()
    {

    }

    public static function handle($oMessage, $className=__CLASS__)
    {
        return parent::handle($oMessage, $className);
    }
}