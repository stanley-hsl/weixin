<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->log("echo:");

if ($_GET['echostr']) {
    $wechatObj->valid();
} else {
    $wechatObj->responseMsg();
}



class wechatCallbackapiTest
{
    public function log($str)
    {
        // $fp = fopen("ok.log", "a");
        // fwrite($fp, ": ".$str." \n");
        // fclose($fp);
        file_put_contents("ok.log", $str, FILE_APPEND);
    }

    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $Event = $postObj->Event;
            $EventKey = $postObj->EventKey;
            $MsgType = $postObj->MsgType;
            $time = time();
            $textTpl = "<xml>
    			<ToUserName><![CDATA[%s]]></ToUserName>
    			<FromUserName><![CDATA[%s]]></FromUserName>
    			<CreateTime>%s</CreateTime>
    			<MsgType><![CDATA[%s]]></MsgType>
    			<Content><![CDATA[%s]]></Content>
    			<FuncFlag>0</FuncFlag>
    			</xml>";
            if ($MsgType == "image") {
                $MsgType = "text";
                $Content = "您发送了一个图片信息";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                echo $resultStr;
            }

            if ($Event == "CLICK" and $EventKey == "V1001_TODAY_MUSIC") {
                $MsgType = "text";
                $Content = "您点击了今日歌曲";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                echo $resultStr;
            }

            $EventKeystr=substr($EventKey, 0, 8);

            if ($Event == "subscribe" && $EventKeystr=="qrscene_") {
                /*
                    数据库插入
                    插入  value值=$EventKey=qrscene_生成参数
                */

                $MsgType = "text";
                $Content = "您之前未关注平台并且扫描了带参数的二维码";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                echo $resultStr;
            }

            if ($Event == "subscribe") { // unsubscribe 是取消关注
                $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[欢迎关注微拍宝成长]]></Title> 
                    <Description><![CDATA[微拍宝欢迎您,发送\"你好\"获取最新资迅]]></Description>
                    <PicUrl><![CDATA[http://zsl.lluck.cn/image/1.jpg]]></PicUrl>
                    <Url><![CDATA[http://zsl.lluck.cn/son.html]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
            }

            if ($Event == "SCAN") {
                $MsgType = "text";
                $Content = "您之前已关注平台并且扫描了带参数的二维码";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                echo $resultStr;
            }

            if (!empty($keyword)) {
                $resultStr="";
                $img="";
                $url="content/girl.html";
                if ($keyword == "你好") {
                    $resultStr="请阅读最新资讯";
                    $img="b1.jpg";
                } else if($keyword == "a") {
                    $resultStr="上期内容";
                    $img="1.png";
                    $url="son.php";
                } else {
                    $resultStr="您发送了 \"".$keyword."\" 无匹配关键字";
                    $img="4.png";
                }
                $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[今天最新资讯-太阳底下无新事]]></Description>
                <PicUrl><![CDATA[http://zsl.lluck.cn/image/%s]]></PicUrl>
                <Url><![CDATA[http://zsl.lluck.cn/%s]]></Url>
                </item>
                </Articles>
                </xml>";
                 
                echo sprintf($textTpl, $fromUsername, $toUsername, $time, $resultStr, $img,$url);
            } else {
                echo "Input something...";
            }
        } else {
            echo "";
            exit;
        }
    }
        
    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
