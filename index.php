<?php
require("calc.php");

/*define your own token*/
define("TOKEN", "1415926535897932384626");
$wechatObj = new wechatCallbackapiTest();
/*
delete the // before $wechatObj->valid(); and add // before $wechatObj->responseMsg(); when you are setting the url
and then restore
*/
//$wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $type = $postObj->MsgType;
            $time = time();
            $msgType = "text";
            if($type == "text"){
                $contentStr = $postObj->Content;
                $keyword = trim($contentStr);
                if(!empty( $keyword )){
                    $contentStr = trim(gate($contentStr,$fromUsername));
                    if($contentStr=="")$contentStr="No Response";
                    $contentStr=str_replace("]]>","] ]>",$contentStr);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                }else{
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, "Input something...");
                }
            }else{
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, "Only Text Available");
            }
            echo $resultStr;
        }else{
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
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
}

?>
