<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if($_GET['echostr'])
{
    $wechatObj->valid();
}else
{
    $wechatObj->responseMsg();
}



class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
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
                if($MsgType == "image")
                {
                    $MsgType = "text";
                    $Content = "您发送了一个图片信息";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                if($Event == "CLICK" and $EventKey == "V1001_TODAY_MUSIC")
                {
                    $MsgType = "text";
                    $Content = "您点击了今日歌曲";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                $EventKeystr=substr($EventKey,0,8);

                if($Event == "subscribe" && $EventKeystr=="qrscene_")
                {
                    /*
                        数据库插入
                        插入  value值=$EventKey=qrscene_生成参数
                    */

                    $MsgType = "text";
                    $Content = "您之前未关注平台并且扫描了带参数的二维码";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

                if($Event == "subscribe")
                {
                    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[欢迎参加极客学院]]></Title> 
                    <Description><![CDATA[极客学院微信公众平台开发视频教程]]></Description>
                    <PicUrl><![CDATA[http://www.sinaimg.cn/dy/slidenews/4_img/2015_11/704_1575962_849639.jpg]]></PicUrl>
                    <Url><![CDATA[http://www.jikexueyuan.com]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;
                }

                if($Event == "SCAN")
                {
                    $MsgType = "text";
                    $Content = "您之前已关注平台并且扫描了带参数的二维码";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }

        if(!empty( $keyword ))
                {
                if($keyword == "你好")
                {
                    $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[您发送了你好]]></Title> 
                    <Description><![CDATA[极客学院微信公众平台关键字回复]]></Description>
                    <PicUrl><![CDATA[http://www.sinaimg.cn/dy/slidenews/4_img/2015_11/704_1575962_849639.jpg]]></PicUrl>
                    <Url><![CDATA[http://www.jikexueyuan.com]]></Url>
                    </item>
                    </Articles>
                    </xml>";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time);
                    echo $resultStr;

                }
                $substr=mb_substr($keyword, 0,2,'utf8');
                if($substr == "天气")
                {
                    $cityname=mb_substr($keyword, 2,5,'utf8');
                    $urlcityname=urlencode($cityname);
                    $url="http://v.juhe.cn/weather/index?format=2&cityname=".$urlcityname."&key=29a2c9607b13d34c8998ac646d840728";
                    $weather=json_decode(gettoken($url),ture);

                    //print_r($weather['result']['today']['dressing_advice']);

                    $wen="当前温度：".$weather['result']['sk']['temp'];//当前温度
                    $fen="当前风向风级：".$weather['result']['sk']['wind_direction']."-".$weather['result']['sk']['wind_strength'];//当前风向风级
                    $city="城市：".$weather['result']['today']['city'];//城市
                    $riqi="日期：".$weather['result']['today']['date_y'];//日期
                    $wendu="今日温度：".$weather['result']['today']['temperature'];//温度
                    $chuan="穿衣指数".$weather['result']['today']['dressing_advice'];//穿衣指数

                    $dangweather=$city."\n".$riqi."\n".$wendu."\n".$wen."\n".$fen."\n".$chuan;

                    $MsgType = "text";
                    $Content = $dangweather;
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $MsgType, $Content);
                    echo $resultStr;
                }


                }else{
                	echo "Input something...";
                }

        }else {
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
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

}


function gettoken($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
    curl_setopt($ch, CURLOPT_ENCODING ,'gzip'); //加入gzip解析
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
?>