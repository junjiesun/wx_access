/**
 * 碰触单位精灵对象
 */
var UnitSprite = cc.Node.extend({
	isHide:false,//是否隐藏,
	isMoving:false,//是否正在下落
	numberValue:0,
	isSelected:false,
	ctor:function(number,space,gameLayer,gametopHeght,boundaryBox){
		this._super();
		this.space = space;
		this.gameLayer = gameLayer;
		this.gametopHeght = gametopHeght;
		this.number = number;
		this.boundaryBox = boundaryBox;
		//alert(this.number);

		this.bg = CU.numberSpriteBg[number];
		//this.hideUnit = hideUnit;
		//this.scope = scope;
		//this.position = positon;
		//var WIN_SIZE = cc.winSize;
		//this.scheduleUpdate();
		//this.schedule(function() {
		var randomWid = parseInt(Math.random() *(winSize.width-110)+70);
		//console.log(WIN_SIZE.height-(winSize.height-this.gametopHeght)/8);
		//console.log('topLayer'+(winSize.height-this.gametopHeght)/3)
		//this.createSprite({x:randomWid, y:1050});
		//alert(parseInt(winSize.height-this.gametopHeght));
		//this.createSprite({x:randomWid, y:700});
		this.createSprite({x:randomWid, y:parseInt(winSize.height-this.gametopHeght)-30});
		//},2);
	},
	createSprite:function(position){

		var body = new cp.Body(1, cp.momentForCircle(1, 0, 10, cp.v(0, 0)));
		body.setPos(position);  //set body's position  
		//body.setAngle(90)//  
		body.v = new cp.Vect(30, 30);      //set body's velocity, (v along x axis, y)   
		this.body = body;                               
		this.space.addBody(body);

		//add the shape   
		var shape = new cp.CircleShape(body,  49, cp.v(0, 0));//触碰体积40
		shape.setElasticity(0);  //弹性
		shape.setFriction(0);  //摩擦
		shape.setCollisionType(1);
		this.shape = shape;
		this.space.addShape(shape);

		//add the sprite 
		var sprite = new cc.PhysicsSprite(res[this.bg]);
		//sprite.setScale(1);
		//sprite.setTag(a++);
		//sprite.setAngle(1)
		sprite.setTag(this.number);
		sprite.setBody(body);
		sprite.setPosition(cc.p(position.x, position.y));
		this.sprite = sprite;
		//console.log(this.space.activeShapes);
		var spritebox = sprite.getBoundingBox();
		//console.log('spritebox'+spritebox);
		//var self = this;
		//var si = setInterval(function(){
		//	if(cc.rectIntersectsRect(spritebox, self.boundaryBox)){
		//		alert('触碰了');
		//	}
		//},500);
		//this.body.si = si;

		this.initEvent(sprite);
		this.gameLayer.addChild(sprite);
	},
	initEvent:function(sprite){
		var self=this;
		this.listener1 = cc.EventListener.create({
			event: cc.EventListener.TOUCH_ONE_BY_ONE,
			swallowTouches: true,
			onTouchBegan: function (touch, event) {
				//if(self.isActionDown||self.isActionLeft||self.isClick||self.isHide){
				//	return false;
				//}

				var target = event.getCurrentTarget();
				var locationInNode = target.convertToNodeSpace(touch.getLocation());
				var s = target.getContentSize();
				var rect = cc.rect(0, 0, s.width, s.height);

				if (cc.rectContainsPoint(rect, locationInNode)) {
					//if(target.isHide){
					//	return false;
					//}

					self.isClick=true;
					self.target = target;
					self.body = self.body;
					self.shape = self.shape;
					self.sprite = self.sprite;
					//alert('点中了');
					CommonGame.hideUnit(self,target.tag);

					return true;
				}
				return false;
			},
			onTouchMoved: function (touch, event) {
			},
			onTouchEnded: function (touch, event) {
				//self.isActionDown=false;
				self.isClick=false;
			}
		});
		cc.eventManager.addListener(this.listener1, sprite);
	},
	setNum:function(){
		this.numberValue = this.number;
	},
	/**隐藏方块之前*/
	hideBefore:function(){
		//this.downSprite.setVisible(true);
		//this.upSprite.setScale(UnitSprite.scale-0.1);
	},
	reset:function(){
		//sprite.setTexture(res.numspritesel)
	},
	setHided:function(){
		this.isHide = true;
		cc.eventManager.removeListener(this.listener1);
	}
});

