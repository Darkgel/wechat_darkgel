<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:17
 */
namespace app\wechatBase;

use app\wechatBase\event\SubscribeEventMessage;
use app\wechatBase\event\UnsubscribeEventMessage;
use app\wechatBase\event\ScanEventMessage;
use app\wechatBase\event\LocationEventMessage;
use app\wechatBase\event\ClickEventMessage;
use app\wechatBase\event\ViewEventMessage;

class EventMessage extends BaseReqMessage
{
    //事件类型
    public $Event;

    public static function handle($oMessage, $className=__CLASS__)
    {
        parent::handle($oMessage, $className);

        switch($oMessage->Event)
        {
            case 'subscribe':
                SubscribeEventMessage::handle($oMessage);
                break;
            case 'unsubscribe':
                UnsubscribeEventMessage::handle($oMessage);
                break;
            case 'SCAN':
                ScanEventMessage::handle($oMessage);
                break;
            case 'LOCATION':
                LocationEventMessage::handle($oMessage);
                break;
            case 'CLICK':
                ClickEventMessage::handle($oMessage);
                break;
            case 'VIEW':
                ViewEventMessage::handle($oMessage);
                break;
            default:
                echo '';
                exit;
        }
    }
}