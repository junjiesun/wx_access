function OrgchartDemo(){
	this.define();
}
OrgchartDemo.prototype = {
	define:function(){
		this.isWrapDraggable = false;//总开关
		this.isWrapDraggableSub = false;//子开关
	
	},
	htmlContent:[
	'<div class="org_node_c small_ort_nci" id="org_node_c_{id}" nid="{id}"><div class="org_node_c_inner">',//0
	'	<div class="main_name_nci" style="display:block">',
	'		<table width="100%" height="100%" cellspacing="0" cellpadding="0">',
	'			<tr>',
	'				<td><a>{name}</a></td>',//13
	'			</tr>',
	'		</table>',
	'	</div>',
	'</div></div>'
	],
	onCreateAllTreeCallback:function(data, isExec){
		var that = this;
		if(data.length === 0)return;
		var wrap = this.context.conf.wrap;
		if(!isExec){
			wrap.find("td.org_td").fadeIn(800);
			wrap.fadeIn(800);
			return;
		}		
		
		var id = this.context.DataObject.adapter.id;	

		var tWrap = wrap.find("table.org_table:first");//根节点table
		var allNodes;//需要设置缩放后大小的元素

		var scaleValue = wrap.data("scaleValue") || 0;
		for(var i in data[0]){
			if($("#org_td_" + i).length){
				for(var j in data[0][i]){
					var curNode = $("#org_td_" + data[0][i][j][id]);
					if(scaleValue !== 0){//如果组件被缩放过，则新元素需要重置
						allNodes = curNode.find("div.org_node_c, div.org_node_c_inner");
						
						allNodes.each(function(i, e){
							if(e){
								var ew = parseFloat(that.context.COE.getFinalStyle(e, "width")) + scaleValue;
								e.style.width = ew + "px";
								e.style.height = ew * .5 + "px";

							}
						});
						
						window.Demo.changeNodeBySize(wrap, curNode);
					}
					
					curNode.find("td.org_td").not(function(){
						return !!$(this).find("table.org_table").length;
					}).find("a.unfold_nci, a.switch_nci").hide();
					if(curNode.parents("table[type=column]").length){
						allNodes.find("a.single_nci, a.unfold_nci, a.switch_nci").hide();
					}
					
					curNode.fadeIn(800);
				}
			}else{
				wrap.find("td.org_td").not(function(){
					return !!$(this).find("table.org_table").length;
				}).find("a.unfold_nci, a.switch_nci").hide();			
			
				wrap.fadeIn(800);
				break;
			}
		}

		// var isIE6 = $.browser.msie && ($.browser.version == "6.0");
		wrap.unbind("mousewheel");
	},
	createHtmlContent:function(data){
		var node, tp = data;
		var pid = this.context.DataObject.adapter.pid;
		var id = this.context.DataObject.adapter.id;
		var logo = this.context.DataObject.adapter.logo;
		var name = this.context.DataObject.adapter.name;
		
		var content = [];//克隆模板
		$.each(this.html.htmlContent, function(idx, val){
			content.push(val);
		});
		content[4] = content[4].replace("{name}", tp[name]);
			
		node = $(content.join(""));
		
		this.addEvent(node);
		return node;
	},
	
};