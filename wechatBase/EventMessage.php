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

    public static function handle($postObj, $className=__CLASS__)
    {
        parent::handle($postObj, $className);

        switch($postObj->Event)
        {
            case 'subscribe':
                SubscribeEventMessage::handle($postObj);
                break;
            case 'unsubscribe':
                UnsubscribeEventMessage::handle($postObj);
                break;
            case 'SCAN':
                ScanEventMessage::handle($postObj);
                break;
            case 'LOCATION':
                LocationEventMessage::handle($postObj);
                break;
            case 'CLICK':
                ClickEventMessage::handle($postObj);
                break;
            case 'VIEW':
                ViewEventMessage::handle($postObj);
                break;
            default:
                echo '';
                exit;
        }
    }
}