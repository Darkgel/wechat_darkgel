<?php
/**
 * Created by PhpStorm.
 * User: michael.shi
 * Date: 2017/3/2
 * Time: 14:49
 */
namespace app\wechatBase\response;

class RespNewsMessage extends BaseRespMessage
{
    public function getXML($message)
    {
        $articleCount = count($message);
        $items = $this->packageItems($message);
        $tpl = "<xml>\r\n"
                ."  <ToUserName><![CDATA[%s]]></ToUserName>\r\n"
                ."  <FromUserName><![CDATA[%s]]></FromUserName>\r\n"
                ."  <CreateTime>%s</CreateTime>\r\n"
                ."  <MsgType><![CDATA[news]]></MsgType>\r\n"
                ."  <ArticleCount>%s</ArticleCount>\r\n"
                ."  <Articles>\r\n"
                ."%s"
                ."  </Articles>\r\n"
                ."</xml>";
        return sprintf($tpl, FROM_USER_NAME, TO_USER_NAME, time(), $articleCount, $items);
    }

    protected function packageItems($items)
    {
        $itemTpl = "    <item>\r\n"
                    ."      <Title><![CDATA[%s]]></Title>\r\n"
                    ."      <Description><![CDATA[%s]]></Description>\r\n"
                    ."      <PicUrl><![CDATA[%s]]></PicUrl>\r\n"
                    ."      <Url><![CDATA[%s]]></Url>\r\n"
                    ."  </item>\r\n";
        $ret = '';
        foreach($items as $item)
        {
            $ret .= sprintf($itemTpl, $item['title'], $item['description'], $item['picurl'], $item['url']);
        }
        return $ret;
    }
}