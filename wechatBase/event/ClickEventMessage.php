<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:24
 */

namespace app\wechatBase\event;
use app\wechatBase\BaseReqMessage;
//菜单点击事件
class ClickEventMessage extends BaseReqMessage
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