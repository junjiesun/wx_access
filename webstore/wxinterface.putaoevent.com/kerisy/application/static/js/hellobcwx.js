   
(function(window){

    window.shareMessage = function(title, message){
        title = title || wxData.title;
        message = message || wxData.desc;
        //if(wxData.title)
        //{
        //    title = wxData.title;
        //}else{
        //    title = '快来加入“麦斯丝”军团，赢取价值299元的六一礼物！';
        //}
        wx.onMenuShareTimeline({
            title: title, // 分享标题
            link: wxData.link, // 分享链接
            imgUrl: wxData.imgUrl, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
				//alert("sdfadsf");
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareAppMessage({
            title: '老爸、萌娃并肩大战“麦斯丝”，赢《海底总动员2》电影票！', // 分享标题
            desc: '我刚发现了一款好游戏，你要不要也来试试？', // 分享描述
            link: wxData.link, // 分享链接
            imgUrl: wxData.imgUrl, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
               // alert("sdfadsf");

            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

    };
})(window);
    
wx.config({
    debug: false,
    appId: wxConfig.appId,
	timestamp: wxConfig.timestamp,
	nonceStr: wxConfig.nonceStr,
	signature: wxConfig.signature,
	jsApiList: [
	  'onMenuShareTimeline',
	  'onMenuShareAppMessage',
	]
});

//wx.ready(function () {
//    //setup to share
//    shareMessage();
//});