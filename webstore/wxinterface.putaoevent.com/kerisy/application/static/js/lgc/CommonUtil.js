/**
 * 通用工具
 */

var CU=CU||{};
CU.numberSpriteBg = {
	1:'numsprite1',
	2:'numsprite2',
	3:'numsprite3',
	4:'numsprite4',
	5:'numsprite5',
	6:'numsprite6',
	7:'numsprite7',
	8:'numsprite8',
	9:'numsprite9'
};
CU.kingSprite = null;
CU.resulteTime = 0;
/**播放音效*/
CU.playerEffect=function(url,loop){
	if(loop){
		cc.audioEngine.playMusic(url,loop);
	}else{
		cc.audioEngine.playEffect(url);
	}
};

/**分数移动*/
CU.moveScore=function(x,y,score,parent,callBack){
	var labelBMFont = new cc.LabelBMFont(score, res.num_fnt);

	var w=labelBMFont.width;
	var h=labelBMFont.height;

	labelBMFont.attr({
		anchorX: 0,
		anchorY: 0,
		scaleX:0.9,
		scaleY:0.9
	});
	var w=labelBMFont.width*0.5;
	var h=labelBMFont.height*0.5;
	labelBMFont.x=x+(CommonGame.unitW-w)/2,
		labelBMFont.y=y+(CommonGame.unitH-h)/2,

		parent.addChild(labelBMFont);

	//var action=cc.moveTo(0.6,ClassicsGameLayer.scoreMoveEndPoint);
	var action=cc.moveTo(0.6,GameLayer.scoreMoveEndPoint);
	labelBMFont.runAction(new cc.Sequence(action,new cc.CallFunc(function(){
		callBack();
		labelBMFont.setVisible(false);
	},this)));
};
CU.accAdd = function(arg1, arg2) { //加法
	var r1, r2, m;
	try {
		r1 = arg1.toString().split(".")[1].length
	} catch (e) {
		r1 = 0
	}
	try {
		r2 = arg2.toString().split(".")[1].length
	} catch (e) {
		r2 = 0
	}
	m = Math.pow(10, Math.max(r1, r2));
	return (arg1 * m + arg2 * m) / m;
};


/**设置控件的宽度——在cocos里是不会出现缩放的；但是导入的js里就会*/
CU.textBMFontWidth=0;//1个字符的label的宽度（——————在cocos里是不会出现缩放的；但是导入的js里就会。。）
CU.setTextBmFontString=function(textBMFont,text){
	var num=text.length;
	textBMFont.width=num*CU.textBMFontWidth;
	textBMFont.string=text;
}