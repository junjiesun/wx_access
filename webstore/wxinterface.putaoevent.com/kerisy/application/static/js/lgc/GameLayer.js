/**
 * 游戏界面
 */
var DEBUG_NODE_SHOW = false;
var GameLayer = cc.Layer.extend({
	score:0,//分数
	scoreBeginX:0,//显示分数的初始位置
	scoreUnitWidth:0,//一个分数的宽度
	preNumber:parseInt(9*Math.random()+1),
	hasEnded:false,//防止重复判断结束
	gameTime:0,
	ctor:function(){
		this._super();
		this.init();
		this.createBoundary();
		this.scheduleUpdate();

		this.createPreBall();
		var self = this;
		var waitForCheckBody = null;
		this.si = setInterval(function(){
			var bodies = self.space.bodies;
			for(var i= 0,l=bodies.length;i<l;i++){
				var body = bodies[i];
				//arr.push(body.p.y);
				//if(body.p.y>= (this.downbound-4)&&body.p.y<= (this.upbound +4)){
				//if(body.p.y>= (winSize.height*1/12-52)&&body.p.y<= (winSize.height*1/12-40)){
				if(body.p.y>= (self.downbound-self.preBall.height/2)){
					waitForCheckBody = body;
					self.checkIsFull(waitForCheckBody,body.p.y);
				}
			};
		},100);
		//return true;
	},
	initSchedule:function(){
		//this.schedule(this.scheduleFn,1);
		this.schedule(this.scheduleFn,this.speed);
	},
	setSpeed:function(){
		var self = this;
		this.speed = 1.2;
		this.SItime = 10000;
		this.checkTime = 1500;
		this.gravitySI = setInterval(function(){
			self.speed -=.1;
			if(self.speed <=0){
				self.speed = 0;
			}
			if(self.checkTime <500){
				self.checkTime = 500;
			}else {
				self.checkTime -= 150;
			};
			self.initSchedule();
			//self.getScheduler().setTimeScale(self.speed);
		},this.SItime);
		this.gameTime =  0;
		var self = this;
		this.countSI = setInterval(function(){
			self.gameTime++;
			self.scoreLabel.setString(self.gameTime)
		},1000);
	},
	scheduleFn:function(){
		//if(this.preNumber) {
		//	this.preNumber = parseInt(9*Math.random()+1);
		var num  = this.preNumber;
			var unitSprite = new UnitSprite(num, this.space, this, this.gametopHeght, this.boundary);
			this.addChild(unitSprite);
		this.createPreBall();
		//this.preNumber = parseInt(9*Math.random()+1);


		//}
		//var num = this.preNumber = parseInt(9*Math.random()+1);
	},
	createPreBall:function(){
		this.preNumber = parseInt(9*Math.random()+1);
		//alert(this.preNumber);
		var imgKey = CU.numberSpriteBg[this.preNumber];

		if(!this.preBall){
			this.preBall = new cc.Sprite(res[imgKey]);
			this.addChild(this.preBall);
			this.preBall.x = winSize.width/2-5;
			this.preBall.y = winSize.height - this.gametopHeght/2-30;

		}else{
			//var nextNumImg = cc.textureCache.addImage(res[imgKey]);
			this.preBall.setTexture(res[imgKey]);
		}
	},
	checkIsFull:function(body,preY){
		if(body&&!this.hasEnded){
			//console.log(body.p.y);
			//clearInterval(si);
			var self = this;
			setTimeout(function(){
				//console.log('st: '+body.p.y)
				if(body.p.y>= (self.downbound-self.preBall.height/2) && Math.abs(body.p.y-preY)<3){
					//alert('你太菜了!');
					CU.resulteTime = self.gameTime;
					self.unschedule(self.scheduleFn);
					clearInterval(self.si);
					clearInterval(self.countSI);
					clearInterval(CU.gravitySI);
					if(!self.hasEnded){
						cc.director.runScene(new cc.TransitionFade(0.8,GameOverLayer.scene()));
						self.cleanup();
					}
					self.hasEnded = true;
				}
			//},1500)
			},800);
		};
	},
	update: function (dt) {
		var timeStep = 0.03;
		this.space.step(timeStep);
		//debugger
		//var shape = this.shape;
	},
	createBoundary:function(){
		console.log('line:'+winSize.height*3/4-50);
		this.upbound = winSize.height*3/4-158;
		this.downbound = winSize.height*3/4-160;
		var dn = new cc.DrawNode();
		var ltp = cc.p(0, this.upbound);
		var rbp = cc.p(winSize.width, this.downbound);
		dn.drawRect(ltp, rbp, cc.color(255, 255, 255));
		this.addChild(dn);
	},
	init:function(){
		//初始化物理效果
		this.initPhysics();

		//初始化背景
		this.initBacgground();

		//初始化中间的布局
		this.initCenter();



		//初始化上方的layer
		this.initTopLayer();

		//初始化中间提示
		this.initTip();

		//初始化下方的layer
		//this.initBelowLayer();

		//初始化战斗结果layer
		//this.initResultLayer();

		//if (cc.sys.isMobile && !cc.sys.browserType) {
		//	//创建横幅广告
		//	var ret = jsb.reflection.callStaticMethod("NativeOcClass","callNativeCreateBanner");
		//}
	},
	initTip:function(){

		var playtipmask = this.playtipmask =new cc.Sprite(res.playtipmask);
		//gametop.setScale(1)
		this.addChild(playtipmask);

		playtipmask.width = winSize.width
		playtipmask.x = 0;
		//gametop.y = topLayer.height*3/2 - gametop.height;
		playtipmask.y = winSize.height/2;

		var playtipball = this.playtipball = new cc.Sprite(res.playtipball);
		//gametop.setScale(1)
		this.addChild(playtipball);

		playtipball.x = winSize.width/2;
		//gametop.y = topLayer.height*3/2 - gametop.height;
		playtipball.y = winSize.height/2;

		//this.playtipmask = new cc.DrawNode();
		//var ltp = cc.p(0, winSize.height/2+playtipball.height/2);
		//var rbp = cc.p(winSize.width, winSize.height/2-playtipball.height/2);
		//this.playtipmask.drawRect(ltp, rbp, cc.color(0, 0, 0,100));
		//this.addChild(this.playtipmask);

		setTimeout(function(){
			this.playtipmask.setVisible(false);
			this.playtipball.setVisible(false);
			this.setSpeed();
			//this.schedule(this.scheduleFn,1);
			this.initSchedule();
		}.bind(this),3000)

	},
	initTopLayer:function(){

		var topLayer=this.topLayer=new cc.LayerColor(cc.color(0, 0, 0, 0),winSize.width,(winSize.height-this.centerLayer.height)/2);
		this.addChild(topLayer);
		topLayer.x=(winSize.width-topLayer.width)/2;
		topLayer.y=winSize.height-topLayer.height;

		var gametop=new cc.Sprite(res.gametop);
		//gametop.setScale(1)
		this.addChild(gametop);

		gametop.x = topLayer.width/2;
		//gametop.y = topLayer.height*3/2 - gametop.height;
		gametop.y = winSize.height-gametop.height/2;

		this.gametopHeght = gametop.height;
		console.log('toplayH:'+(winSize.height-this.centerLayer.height)/4 +" | "+this.gametopHeght);

		var blockSize = cc.size(this.width, 35);
		var textLabel = new cc.LabelTTF("时间:", "Arial", 35, blockSize, cc.TEXT_ALIGNMENT_CENTER, cc.VERTICAL_TEXT_ALIGNMENT_CENTER);
		this.addChild(textLabel);
		console.log('textLabel:' + textLabel.height/2)
		textLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		textLabel.x=winSize.width*2/5;
		textLabel.y=winSize.height - textLabel.height*2;


		var scoreLabel=this.scoreLabel= new cc.LabelBMFont('0', res.num_fnt);
		this.addChild(scoreLabel);
		//scoreLabel.textAlign = cc.TEXT_ALIGNMENT_RIGHT;
		scoreLabel.textAlign = cc.TEXT_ALIGNMENT_CENTER;
		scoreLabel.x=winSize.width*3/5;
		scoreLabel.y=winSize.height - textLabel.height*2;
		//this.scoreUnitWidth=scoreLabel.width;

		CU.kingSprite = this.kingSprite = new cc.Sprite(res.king);
		CU.kingSprite.setScale(.5)
		CU.kingSprite.x = winSize.width*4/5;
		CU.kingSprite.y = winSize.height - this.gametopHeght/2;
		this.addChild(CU.kingSprite);
	},
	/**初始化背景*/
	initBacgground:function(){
		var bgSprite=new cc.Sprite(res.mainbg);
		bgSprite.attr({
			x: winSize.width / 2,
			y: winSize.height / 2
		});
		//console.log(winSize.width+"|"+winSize.height)
		this.addChild(bgSprite);

	},
	initCenter:function(){
		var width=height=winSize.width*9/10;
		var layer=this.centerLayer=new cc.LayerColor(cc.color(0, 0, 0, 0), width,height);
		this.addChild(layer);
		layer.x=(winSize.width-layer.width)/2;
		layer.y=(winSize.height-layer.height)/2;

		CommonGame.ctor(layer,this,this.space);
		//var commonGame=this.commonGame=new CommonGame(layer,this);
		//commonGame.initUnit(this.space);
	},
	setupDebugNode: function () {
		this._debugNode = new cc.PhysicsDebugNode(this.space);
		this._debugNode.visible = DEBUG_NODE_SHOW;
		this.addChild(this._debugNode);
	},
	initPhysics: function () {


		var winSize = cc.director.getWinSize();


		this.space = new cp.Space();
		this.setupDebugNode();


		// 设置重力
		this.space.gravity = cp.v(0, -400);
		var staticBody = this.space.staticBody;
		//console.log(winSize)
		//console.log(winSize);
		// 设置空间边界
		//alert(winSize.height);
		var walls = [ new cp.SegmentShape(staticBody, cp.v(0, 0),
			cp.v(winSize.width, 0), 0),
			new cp.SegmentShape(staticBody, cp.v(0, winSize.height),
				cp.v(winSize.width, winSize.height), 0),
			new cp.SegmentShape(staticBody, cp.v(0, 0),
				cp.v(0, winSize.height), 0),
			new cp.SegmentShape(staticBody, cp.v(winSize.width, 0),
				cp.v(winSize.width, winSize.height), 0)
		];
		for (var i = 0; i < walls.length; i++) {
			var shape = walls[i];
			shape.setElasticity(1);
			shape.setFriction(1);
			this.space.addStaticShape(shape);
		}
	},
});

GameLayer.scene=function(){
	var scene=new cc.Scene();
	var layer=new GameLayer();
	scene.addChild(layer);
	return scene;
};