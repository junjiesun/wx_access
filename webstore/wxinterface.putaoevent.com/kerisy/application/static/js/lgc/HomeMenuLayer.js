/**
 * 首页界面
 */

var HomeMenuLayer = cc.Layer.extend({
	ctor:function(){
		this._super();

		var bgSprite=new cc.Sprite(res.mainbg);
		bgSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 2
		});
		this.addChild(bgSprite);

		var btn1=new ButtonSprite("",res.startbtn,res.startbtn,this,null,this.onTouchEndedBack.bind(this),0);
		btn1.x = winSize.width / 2;
		btn1.y = winSize.height /2+30;

		var logoSprite=new cc.Sprite(res.logo);
		logoSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 4*3
		});
		this.addChild(logoSprite);

		var kingTipSprite=new cc.Sprite(res.tipking);
		kingTipSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 4
		});
		this.addChild(kingTipSprite);

		cc.eventManager.addCustomListener(cc.game.EVENT_HIDE, function(){
			cc.audioEngine.pauseMusic();
		});
		cc.eventManager.addCustomListener(cc.game.EVENT_SHOW, function(){
			cc.audioEngine.resumeMusic();
		});
	},
	onTouchEndedBack:function(){
		CU.playerEffect(res.select_wav);
		CU.playerEffect(res.bgmusic,true);
		cc.director.runScene(new cc.TransitionFade(0.5,GameLayer.scene()));
		//cc.director.runScene(new cc.TransitionFade(0.5,GameOverLayer.scene()));
	},
});