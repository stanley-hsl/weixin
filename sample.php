<?php
require_once "./JSSDK/jssdk.php";
$jssdk = new JSSDK("wx1700f0bbdf2b8f18", "83bb877f35965f13f09a56db69ec4456");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;"/>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>极客学院JSSDK案例</title>
</head>
<body>
  <div>
    极客学院开发JSSDK实例讲解分享到朋友圈及分享给朋友
  </div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  /*
   * 注意：
   * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
   * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
   * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
   *
   * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
   * 邮箱地址：weixin-open@qq.com
   * 邮件主题：【微信JS-SDK反馈】具体问题
   * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
   */
  wx.config({
    debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
      // 所有要调用的 API 都要加到这个列表中
      'onMenuShareTimeline',
      'onMenuShareAppMessage'
    ]
  });
  wx.ready(function () {
    // 分享到朋友圈的实例
    wx.onMenuShareTimeline({
    title: '微拍宝成长分享', // 分享标题
    link: 'http://zsl.lluck.cn/index.html', // 分享链接
    imgUrl: 'http://zsl.lluck.cn/image/1.jpg', // 分享图标
    success: function () {
        // 用户确认分享后执行的回调函数
        alert("分享wx朋友成功");
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
        alert("分享wx朋友失败");
    }
    });

    //分享给朋友
    wx.onMenuShareAppMessage({
    title: '分享给朋友案例', // 分享标题
    desc: '现在在开发JSSDK分享给朋友', // 分享描述
    link: 'http://www.jikexueyuan.com/course/1946.html', // 分享链接
    imgUrl: 'http://zsl.lluck.cn/image/1.jpg', // 分享图标
    type: '', // 分享类型,music、video或link，不填默认为link
    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
    success: function () { 
        // 用户确认分享后执行的回调函数
        alert("分享friend成功");
    },
    cancel: function () { 
        // 用户取消分享后执行的回调函数
        alert("分享失败");
    }
    });
  });
</script>
</html>
