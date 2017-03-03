<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:20
 */
namespace app\wechatBase\event;
use app\wechatBase\BaseReqMessage;
//取消关注事件
class UnsubscribeEventMessage extends BaseReqMessage
{
    // 重写init方法，处理该类型消息
    public function init()
    {

    }

    public static function handle($postObj, $className=__CLASS__)
    {
        return parent::handle($postObj, $className);
    }
}