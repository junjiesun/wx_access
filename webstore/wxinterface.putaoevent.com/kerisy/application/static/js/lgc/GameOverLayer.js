/**
 * Created by admin on 16/5/6.
 */
var GameOverLayer = cc.Layer.extend({
	maskShowed:false,
	ctor:function() {
		this._super();
		this._initSprites();

	},
	_initSprites:function(){
		var bgSprite=new cc.Sprite(res.mainbg);
		bgSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 2
		});
		this.addChild(bgSprite);

		var logoSprite=new cc.Sprite(res.logo);
		logoSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 4*3
		});
		this.addChild(logoSprite);

		var timebord=new cc.Sprite(res.timebord);
		timebord.attr({
			x: winSize.width / 2,
			y: winSize.height*5/9
		});
		this.addChild(timebord);

		var blockSize = cc.size(this.width, 35);
		var textLabel = new cc.LabelTTF("时间:", "Arial", 35, blockSize, cc.TEXT_ALIGNMENT_CENTER, cc.VERTICAL_TEXT_ALIGNMENT_CENTER);
		this.addChild(textLabel);
		textLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		textLabel.x=winSize.width/3;
		textLabel.y=winSize.height*5/9-10


		var scoreLabel=this.scoreLabel= new cc.LabelBMFont(CU.resulteTime, res.num_fnt2);
		this.addChild(scoreLabel);
		//scoreLabel.textAlign = cc.TEXT_ALIGNMENT_RIGHT;
		scoreLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		scoreLabel.x=winSize.width*3/5;
		scoreLabel.y=winSize.height*5/9


		var contentBlockSize = cc.size(this.width*3/4, 105);
		var text = CommonGame.setOverText(CU.resulteTime);
		var contentLabel = new cc.LabelTTF(text, "Arial", 35, contentBlockSize, cc.TEXT_ALIGNMENT_CENTER, cc.VERTICAL_TEXT_ALIGNMENT_CENTER);
		this.addChild(contentLabel);
		contentLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		contentLabel.x=winSize.width/2;
		contentLabel.y=winSize.height*.45;

		var againBtn=new ButtonSprite("",res.againbtn,res.againbtn,this,null,this.palyAgain.bind(this),0);
		againBtn.x = winSize.width / 4;
		againBtn.y = winSize.height/3+50;
		var sharebtn=new ButtonSprite("",res.sharebtn,res.sharebtn,this,null,this.share.bind(this),0);
		sharebtn.x = winSize.width *3/4;
		sharebtn.y = winSize.height/3+50;


		var careBlockSize = cc.size(this.width*4/5, 105);
		var caretext = '晒成绩截图赢大奖！长按二维码了解详情';
		var careLabel = new cc.LabelTTF(caretext, "Arial", 32, careBlockSize, cc.TEXT_ALIGNMENT_CENTER, cc.VERTICAL_TEXT_ALIGNMENT_CENTER);
		this.addChild(careLabel);
		careLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		careLabel.x=winSize.width/2;
		careLabel.y=winSize.height*.3;

		//this.qr = new cc.Sprite(res.qr);
		//this.qr.x = winSize.width/2;
		//this.qr.y = winSize.height/6+70;
		//this.addChild(this.qr);

		document.getElementById('qrbox').style.display = 'block';

		this.shareMask = new cc.Sprite(res.maskbg);
		this.shareMask.width = winSize.width;
		this.shareMask.height = winSize.height;
		this.shareMask.x = winSize.width/2;
		this.shareMask.y = winSize.height/2;
		this.addChild(this.shareMask);
		this.shareMask.visible = false;

		shareMessage(window.shareTitle);

		var shareTip = new cc.Sprite(res.sharetip);
		shareTip.attr({
			x: winSize.width / 2,
			y: winSize.height*4/5
		});
		this.shareMask.addChild(shareTip);

		var touchListener = {
			event: cc.EventListener.TOUCH_ONE_BY_ONE,
			swallowTouches: true,
			onTouchBegan: this.hideShareMask.bind(this)
		};
		cc.eventManager.addListener(touchListener, this);
	},
	palyAgain:function(){
		if(this.maskShowed)return;
		document.getElementById('qrbox').style.display = 'none';
		cc.director.runScene(new cc.TransitionFade(0.5,GameLayer.scene()));

	},
	share:function(){
		if(this.maskShowed)return;
		this.shareMask.setVisible(true);
		this.maskShowed = true;
	},
	hideShareMask:function(){
		this.shareMask.setVisible(false);
		this.maskShowed = false;
	}
});

GameOverLayer.scene=function(){
	var scene=new cc.Scene();
	var layer=new GameOverLayer();
	scene.addChild(layer);
	return scene;
};