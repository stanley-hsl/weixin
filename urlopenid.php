<?php
/*
 *网页授权获取用户openid
*/
header("Content-type:text/html;charset=utf-8");
$code=$_GET['code'];
$state=$_GET['state'];
echo '得到了Code:'.$code.'<br/>';
echo '得到了state:'.$state.'<br/>';
$appid="wx1700f0bbdf2b8f18";
$secret="83bb877f35965f13f09a56db69ec4456";

$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
echo '取令牌的URL:'.$url.'<br/>';
$openidarr=json_decode(gettoken($url),true);
echo '<br/>返回令牌:';
print_r($openidarr);
$token=$openidarr['access_token'];
$openid=$openidarr['openid'];
echo '<br/>用户openId:';
echo $openid;
echo '<br/>';

echo '<br/>OAuth2.0令牌 token:';
echo $token;
echo '<br/>';

$infourl="https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid."&lang=zh_CN";
$userinfoarr=json_decode(gettoken($infourl),true);
echo '<br/>用户详情:';
print_r($userinfoarr);

echo $userinfoarr['nickname']."<br />";
echo $userinfoarr['city']."<br />";
echo $userinfoarr['headimgurl'];
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