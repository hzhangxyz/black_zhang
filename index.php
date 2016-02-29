<?php
require("calc.php");

/*define your own token*/
define("TOKEN", "1415926535897932384626");
/*
delete the // before $wechatObj->valid(); and add // before $wechatObj->responseMsg(); when you are setting the url
and then restore
*/
//valid();
if(checkSignature())responseMsg();

function valid(){
    $echoStr = $_GET["echostr"];
    if($this->checkSignature()){
        echo $echoStr;
        exit;
    }
}

function response_text($postObj,$text){
    $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>0</FuncFlag>
</xml>";
    return sprintf($textTpl,$postObj->FromUserName,$postObj->ToUserName,time(),"text",$text);
}

function response_news($postObj,$title,$description,$img,$url){
    $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>1</ArticleCount>
<Articles>
<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
</Articles>
</xml> ";
    return sprintf($newsTpl,$postObj->FromUserName,$postObj->ToUserName,time(),"news",$title,$description,$img,$url);
}

function responseMsg(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    $resultStr = "";
    if (!empty($postStr)){
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $who = $postObj->FromUserName;
        $type = $postObj->MsgType;
        switch($type){
            case "text":
                $what = $postObj->Content;
                $ans = trim(gate($what,$who));
                if($ans=="") echo "";
                else echo response_text($postObj,$ans);
                break;
            default:
                echo response_text($postObj,"Only Text Available");
        }
    }else{
        echo "";
    }
}

function checkSignature(){
    if (!defined("TOKEN")) {
        throw new Exception('TOKEN is not defined!');
    }
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
    $token = TOKEN;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    if( $tmpStr == $signature ){
        return true;
    }else{
        return false;
    }
}

?>
