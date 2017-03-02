<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:16
 */
namespace app\wechatBase;

class LinkMessage extends BaseReqMessage
{
    // 重写init方法，处理该类型消息
    protected function init()
    {

    }

    public static function handle($oMessage, $className=__CLASS__)
    {
        return parent::handle($oMessage, $className);
    }
}