/**
 * 游戏逻辑
 */
var CommonGame = {
	isActionDown:false,//是否处于移动中，处于移动中不能继续点击（由上向下移动）
	isActionLeft:false,//是否处于移动中，处于移动中不能继续点击（由右向左移动）
	isHide:false,//是否处于隐藏中
	isClick:false,//是否处于点击状态

	isStart:0,//是否正式开始了
	totalVal:0,
	hideUnitArray:[],

	ctor:function(centerPanel,gameLayer,space){
		CommonGame.centerPanel=centerPanel;
		CommonGame.gameLayer=gameLayer;
		CommonGame.space = space;

		//var WIN_SIZE = cc.winSize;
	},
	/**初始化星星单元*/
	initUnit:function(space){
		//return
		var width=this.centerPanel.width;
		var height=this.centerPanel.height;
		this.space = space;

		//var unitSprite = new UnitSprite(res.numsprite,space,this.gameLayer,this.hideUnit,this);
		//this.gameLayer.addChild(unitSprite);
	},
	checkHasAdded:function(arr,id){
		for(var i= 0,l=arr.length;i<l;i++){
			if(arr[i].__instanceId == id){
				return true;
			}
		};
	},
	/**隐藏符合条件的方块*/
	hideUnit:function(target,num){
		if(target.isHide || target.isSelected){
			return;
		};
		if(CommonGame.checkHasAdded(CommonGame.hideUnitArray,target.__instanceId)){
			return;
		}
		CommonGame.hideUnitArray.push(target);
		//CommonGame.totalVal = parseInt(CommonGame.totalVal+target.number);
		CommonGame.totalVal = parseInt(CommonGame.totalVal+num);

		//var k = 'selsprite' + target.number;
		var k = 'selsprite' + num;
		target.sprite.setTexture(res[k]);

		//最少2个才可以消除
		if(CommonGame.hideUnitArray.length != 2){
			return;
		};

		if(CommonGame.totalVal != 10){
			console.log(CommonGame.hideUnitArray[0].number +"+"+CommonGame.hideUnitArray[1].number+"="+CommonGame.totalVal);
			CommonGame.totalVal = 0;
			for(var i= 0,l=CommonGame.hideUnitArray.length;i<l;i++){
				var item = CommonGame.hideUnitArray[i];
				var k = 'numsprite' + item.number;
				item.sprite.setTexture(res[k]);
			};
			CommonGame.hideUnitArray = [];
			CommonGame.onAction(CU.kingSprite,6,'l');
			CU.playerEffect(res.lmusic);
			return;
		};

		CommonGame.isHide=true;


		//设置消除后的得分标签
		//this.gameLayer.setScoreInfo(length,GC.getUnitScore(length));

		(function(){
			for(var i = 0,l=CommonGame.hideUnitArray.length;i<l;i++) {
				var model = CommonGame.hideUnitArray[i].target;
				var body = CommonGame.hideUnitArray[i].body;
				var shape = CommonGame.hideUnitArray[i].shape;
				//CommonGame.gameLayer.scheduleOnce(function () {
					CU.playerEffect(res.vmusic);
					CommonGame.onAction(CU.kingSprite,5,'v');

					//TODO
					//CommonGame.space.removeBody(body);
					CommonGame.space.removeShape(shape);
					target.setHided();
					model.setVisible(false);
				//target.removeFromParent();
					//model.setScale(0.6);

					////消失的粒子效果
					//self.centerPanel.addChild(CommonGame.particle(model.x,model.y,t));

					//飘分数
					//var score=GC.getUnitScore2(num+1)+self.gameLayer.score;  //算法重新来
					var score = 100;
					CommonGame.gameLayer.score = score;

					CU.moveScore(model.x, model.y, score, model.parent, function () {
						//设置获得的分数
						//CommonGame.gameLayer.setScore(score);
					});
				//}, .1);
			};
			CommonGame.hideUnitArray = [];
			CommonGame.totalVal = 0;
		})();
	},
	/**获取方块对象*/
	getUnif:function(x0,y0,type){
		if(y0<0){
			return null;
		}
		if(x0<0){
			return null;
		}

		var array=CommonGame.unitInfo[x0];
		if(array){
			var unit=array[y0];
			if(!unit){
				return null;
			}
			if(CommonGame.hideUnitArray.contains(unit)){
				return null;
			}
			if(!unit.isHide&&unit.type==type&&unit.type!=-1){
				//cc.log(unit.type);
				CommonGame.hideUnitArray.push(unit);
				return unit;
			}
		}
		return null;
	},
	/**重置被选中的对象*/
	resetUnit:function(){
		for(var i = 0,l=CommonGame.hideUnitArray.length;i<l;i++){
			var unit = CommonGame.hideUnitArray[i];
			unit.reset();
		}
	},
	onAction: function (sprite,len,type) {
		//if (sender.isPlaying != true) {
			///////////////动画开始//////////////////////
			var animation = new cc.Animation();
            for (var i = 0; i <len; i++) {
				var frameName = type + i;
                cc.log("frameName = " + frameName);
				animation.addSpriteFrameWithFile(res[frameName]);
			}

			animation.setDelayPerUnit(.08);           //设置两个帧播放时间
			animation.setRestoreOriginalFrame(true);    //动画执行后还原初始状态

			var action = cc.animate(animation);
            sprite.runAction(cc.repeat(action,2));
			//////////////////动画结束///////////////////

		//	sender.isPlaying = true;
		//} else {
		//	sprite.stopAllActions();
		//	sender.isPlaying = false;
		//}
	},
	setOverText:function(t){
		var r = '';
		if(t>0&&t<=50){
			r = '大家好，我就是猴子派来的炮灰！';
		}else if(t>50&&t<=60){
			r = '快恭喜我，智商足够算算术了！';
		}else if(t>60&&t<=70){
			r = '连我都超不过，你没资格做我朋友！';
		}else if(t>70&&t<=80){
			r = '谁还敢说我数学不好，有种你来超过我呀！';
		}else if(t>80){
			r = '事实证明在智商的战场上，我就是独孤求败！';
		}
		window.shareTitle = '我坚持了'+t+'秒，'+r;
		return r;
	}
}