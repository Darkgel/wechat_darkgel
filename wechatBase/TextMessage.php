<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:08
 */
namespace app\wechatBase;
use app\wechatBase\response\RespMessage;

class TextMessage extends BaseReqMessage
{
    public $Content; 	//文本消息内容
    public $MsgId;  	//消息id，64位整型
    // 重写init方法，处理该类型消息
    protected function init()
    {
        if($this->Content == '图文'){
            RespMessage::replyNews(array(
                array(
                    'title'=>'大图',
                    'description'=>'描述',
                    'picurl'=>'https://drscdn.500px.org/photo/126001591/m%3D900/3323bf7e2b79e5c63f6220a998f2af21',
                    'url'=>'http://m.jfz.com'
                ),
                array(
                    'title'=>'大图',
                    'description'=>'描述',
                    'picurl'=>'https://drscdn.500px.org/photo/83136235/m%3D900/e8f360801a3a55c20d7d59e982a9cdcf',
                    'url'=>'http://m.jfz.com'
                ),
                array(
                    'title'=>'大图',
                    'description'=>'描述',
                    'picurl'=>'https://drscdn.500px.org/photo/123084989/m%3D900/c79102bb35fdd864651aa56248958e8f',
                    'url'=>'http://m.jfz.com'
                ),
                array(
                    'title'=>'大图',
                    'description'=>'描述',
                    'picurl'=>'https://mmbiz.qlogo.cn/mmbiz/u7HRqqgV0kMwWIDPxJ2w3A9x1PeHxQ7DTvXiaOzibjfIsANYKrfFwFcC9xBXjdJTTCncFJXHXgtQTb40ibrvAiarrQ/0',
                    'url'=>'http://m.jfz.com'
                )
            ));
        }else{
            RespMessage::replyText("hello");
        }
    }

    public static function handle($postObj, $className=__CLASS__)
    {
        return parent::handle($postObj, $className);
    }
}