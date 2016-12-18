/**游戏配置等信息*/
var GC=GC||{};

/**10行*/
GC.rowNum=13;
/**10列*/
GC.column=10;

/**各方块中对应的图片（0~4)*/
GC.UnitImgArray=[res.p1_png,res.p2_png,res.p3_png,res.p4_png,res.p5_png];

/**冒险模式中的关卡得星*/
GC.StarImgArray=[res.star_png,res.star_gold_png];

/**冒险模式关卡数*/
GC.AdventureTollgateNum=120;

/**各关卡对应的目标分数*/
GC.getTollgateScore=function(tollgate){
	if(tollgate==1){
		score=1500;
	}else if(tollgate==2){
		score=2500;
	}else if(tollgate==3){
		score=4500;
	}else{
		score=4500+2000*(tollgate-3)+GC.getTollgateScore2(tollgate);
	}
	
	//TODO
	//score=score/10*1;
	score=50;
	return parseInt(score);
};

/**获取剩余方块对应的分数*/
GC.getLeftScore=function(num){
	if(num>9){
		return 0;
	}
	
	var base=380;
	var sum=0;
	for(var i=num;i<=9;i++){
		sum=sum+base-40*(9-i);
	}
	//TODO
	//sum=parseInt(sum/100);
	return sum;
};


/**辅助计算*/
GC.getTollgateScore2=function(tollgate){
	if(tollgate<4){
		return 0;
	}
	var sum=0;
	for(var i=4;i<=tollgate;i++){
		sum+=20*(i-3);
	}
	return sum;
};

/**获取消除的单元格的分数*/
GC.getUnitScore=function(num){
	if(num<2){
		return 0;
	}
	
	var sum=0;
	for(var i=1;i<=num;i++){
		sum+=GC.getUnitScore2(i);
	}
	return sum;
};
/**获取消除的单元格第x个的分数*/
GC.getUnitScore2=function(num){
	if(num<1){
		return 0;
	}
	var sum=(num*2-1)*5;
	return sum;
};

/**给array添加contains方法*/
Array.prototype.contains = function (arr){    
	for(var i=0;i<this.length;i++){//this指向真正调用这个方法的对象  
		if(this[i] == arr){  
			return true;  
		}  
	}     
	return false;  
}  

