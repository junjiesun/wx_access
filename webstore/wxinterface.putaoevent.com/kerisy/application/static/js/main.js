cc.game.onStart = function(){


	//if(!cc.sys.isNative && document.getElementById("cocosLoading")) //If referenced loading.js, please remove it
	//	document.body.removeChild(document.getElementById("cocosLoading"));

	//var designSize = cc.size(320, 480);
	//var screenSize = cc.view.getFrameSize();
	//console.log(screenSize)

	//if(!cc.sys.isNative && screenSize.height < 800){
	//	designSize = cc.size(320, 480);
	//	//cc.loader.resPath = "res/Normal";
		cc.loader.resPath = "/images/res";
	//}else{
	//	//cc.loader.resPath = "res/HD";
	//	cc.loader.resPath = "images";
	//}
	cc.view.adjustViewPort(true);
	cc.view.setDesignResolutionSize(640, 1136, cc.ResolutionPolicy.FIXED_WIDTH);
	//cc.view.setDesignResolutionSize(640, 1136, cc.ResolutionPolicy.FIXED_HEIGHT);
	//cc.view.setDesignResolutionSize(640, 1136, cc.ResolutionPolicy.EXACT_FIT);
	//cc.view.setDesignResolutionSize(screenSize.width*2, screenSize.height*2, cc.ResolutionPolicy.FIXED_WIDTH);
	cc.view.resizeWithBrowserSize(true);
	//cc.view.setDesignResolutionSize(320, 480, cc.ResolutionPolicy.SHOW_ALL);
	winSize = cc.director.getWinSize();
	//winSize = screenSize;
	//load resources
	cc.LoaderScene.preload(g_resources, function () {
		//cc.director.runScene(new MyLayer());
		cc.director.runScene(new HomeMenuLayer());
	}, this);
};
cc.game.run('gameCanvas');