<?php
require("calc.php");

/*define your own token*/
define("TOKEN", "1415926535897932384626");

checkSQL();
responseMsg();

function checkSQL(){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql",$con);
    mysql_query("CREATE TABLE IF NOT EXISTS lang(name CHAR(32), lang CHAR(32), if_cache CHAR(4), cache CHAR(255))",$con);
    mysql_close($con);
    return 0;
}
function valid(){
    $echoStr = $_GET["echostr"];
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
            return $echoStr;;
    }else{
            return "";
    }
}

function responseMsg(){
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    $resultStr = "";
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
                if($contentStr=="") $resultStr="";
                else $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }else{
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, "Input something...");
            }
        }else{
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, "Only Text Available");
        }
    }else{
        $resultStr = valid();
    }
    echo $resultStr;
}

?>
