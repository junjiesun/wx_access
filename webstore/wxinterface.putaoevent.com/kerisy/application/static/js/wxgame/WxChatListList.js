// JavaScript Document
//===== BEGIN: class Addmap =====/
function WxChatListList( domSelector , submitAdd, submitEdit)
{
    this.domSelector = domSelector;
	this.submitAdd = submitAdd;
	this.submitEdit = submitEdit;
    this.lock = false;
	this.parentNode = null;
    this.domReady();
}
WxChatListList.prototype.domReady = function()
{
	var ui = $(this.domSelector);
	var uiMode = $(this.submitAdd);
	var uiMode2 = $(this.submitEdit);
	var self = this;

	$(ui).on('click','[name=createWxChat]',function(){
		$('[name=wxChatAdd]').modal('show');
		// $.getUser.checkedpicker = "#select-user-lists";
		// $.getUser.selectChecked = false;
		// $.getUser.domReady(0,'','');
	});

	$(ui).on('click','[name=editWxChat]',function(){
		$('[name=wxChatEdit]').modal('show');
		self.parentNode = $(this).closest('tr');

		var wx_account_id = $('[name=wx_account_id]',self.parentNode).val();
		var appId = $('[name=appId]',self.parentNode).val();
		var name = $('[name=name]',self.parentNode).val();
		var appSecret = $('[name=appSecret]',self.parentNode).val();
		var type = $('[name=type]',self.parentNode).val();
		var authorization = $('[name=authorization]',self.parentNode).val();

		$('[name=wx_account_id]',uiMode2).val(wx_account_id);
		$('[name=appid]',uiMode2).val(appId);
		$('[name=name]',uiMode2).val(name);
		$('[name=appSecret]',uiMode2).val(appSecret);

		$('[name=type]',uiMode2).val(type);
		$('[name=authorization]',uiMode2).val(authorization);
		$('[name=type]',uiMode2).empty();
		$('[name=authorization]',uiMode2).empty();

		if( type == 2 )
		{
			$('[name=type]',uiMode2).append("<option value='2'>公众号</option><option value='1'>服务号</option>")
		}else{
			$('[name=type]',uiMode2).append("<option value='1'>服务号</option><option value='2'>公众号</option>>")
		}

		if( authorization == 2 )
		{
			$('[name=authorization]',uiMode2).append("<option value='2'>web授权</option><option value='1'>静默授权</option>")
		}else{
			$('[name=authorization]',uiMode2).append("<option value='1'>静默授权</option><option value='2'>web授权</option>>")
		}

	});

	$('[name=submit]',uiMode).click(function(){
		var name	= $('[name=name]',uiMode).val();
		var appid	= $('[name=appid]',uiMode).val();
		var appSecret	= $('[name=appSecret]',uiMode).val();
		var type	= $('[name=type]',uiMode).val();
		var authorization	= $('[name=authorization]',uiMode).val();
		// $('[name=uid]').val();



		if(!name){
			alert('请填写名称');
			return false;
		}

		if(!appid){
			alert('请填写appid');
			return false;
		}

		if( !appSecret )
		{
			// alert(effective_end_time);
			alert('请填写appSecret');
			return false;
		}

		if( !type )
		{
			// alert(effective_end_time);
			alert('请填写type');
			return false;
		}

		if( !authorization )
		{
			// alert(effective_end_time);
			alert('请填写authorization');
			return false;
		}

		if (!self.lock) {
			self.lock = true;
			$.ajax({
				url: '/service/wechat/add',
				data: {
					name: name,
					appid: appid,
					appSecret: appSecret,
					type: type,
					authorization: authorization
				},
				type: 'POST',
				dataType: 'json',
				success: function (response) {
					self.lock = false;
					if (response.httpStatusCode == 200) {
						// alert('添加成功');
						showmodal('添加成功');
						location.replace(location.href)
					}
					else {
						showmodal('添加失败');
						// alert('添加失败');
						// location.replace(location.href)
					}
					$('[name=wxChatAdd]').modal('hide');
				}
			});
		}
	});

	// $('[name=attendance_configid]',uiMode).change(function(){
    //
	// 	// console.log($(this).attr('attendance_type',"str"));
	// 	var attendance_type = $('[name=attendance_configid]',uiMode).find("option:selected").attr("attendance_type");
	// 	var free_attendance = $('[name=attendance_configid]',uiMode).find("option:selected").attr("free_attendance");
    //
	// 	$('[name=effective_start_time]',uiMode).val('').attr('disabled',false);
	// 	$('[name=effective_end_time]',uiMode).val('').attr('disabled',false);
	// 	// console.log(free_attendance);
    //
	// 	if( attendance_type == 'PERIOD' )
	// 	{
	// 		var free_attendance = eval(free_attendance);
	// 		// console.log(free_attendance);
    //
	// 		var effective_start_time = free_attendance[0]['attendance_start_time'];
	// 		// alert(effective_start_time);
	// 		var attendance_end_time = free_attendance[0]['attendance_end_time'];
	// 		$('[name=effective_start_time]').val(effective_start_time).attr('disabled',true);
	// 		$('[name=effective_end_time]').val(attendance_end_time).attr('disabled',true);
	// 	}
	// });

	// $('[name=remove]',ui).click(function(){
	// 	self.parentNode = $(this).closest('tr');
	// 	var attendance_config_user_rdd_id = $('[name=attendance_config_user_rdd_id]',self.parentNode).val();
	// 	// alert(attendance_config_user_group_rdd_id);return;
	// 	if(confirm('确定要删除吗？')){
	// 		if ( !self.lock )
	// 		{
	// 			self.lock = true;
	// 			$.ajax({
	// 				url: '/service/attendance/userdel',
	// 				data:{
	// 					attendance_config_user_rdd_id:attendance_config_user_rdd_id
	// 				},
	// 				type:'POST',
	// 				dataType:'json',
	// 				success: function(response){
	// 					self.lock = false;
	// 					if ( response.httpStatusCode == 200 )
	// 					{
	// 						showmodal('删除成功');
	// 						self.parentNode.remove();
	// 					}
	// 					else
	// 					{
	// 						showmodal('删除失败');
	// 					}
	// 				}
	// 			});
	// 		}
	// 	}
	// });

	// $('[name=chang]',ui).click(function(){
	// 	self.parentNode = $(this).closest('tr');
	// 	var attendance_config_user_rdd_id = $('[name=attendance_config_user_rdd_id]',self.parentNode).val();
	// 	// alert(attendance_config_user_rdd_id);
	// 	// return;
    //
	// 	if(confirm('确定要失效吗？')){
	// 		if ( !self.lock )
	// 		{
	// 			// self.eq('已失效');
	// 			// $(this).remove();
	// 			// $('[name=attendance_config_user_rdd_id]',self.parentNode).val('已失效');
    //
	// 			self.lock = true;
	// 			$.ajax({
	// 				url: '/service/attendance/usereffectiveedit',
	// 				data:{
	// 					attendance_config_user_rdd_id:attendance_config_user_rdd_id
	// 				},
	// 				type:'POST',
	// 				dataType:'json',
	// 				success: function(response){
	// 					self.lock = false;
	// 					if ( response.httpStatusCode == 200 )
	// 					{
	// 						showmodal('已失效');
	// 						// $(this).remove();
	// 						// $(this).text('已失效');
	// 						setTimeout(function(){ location.replace(location.href)}, 1000);
	// 					}
	// 					else
	// 					{
	// 						showmodal('操作失败');
	// 						// alert('操作失败');
	// 					}
	// 				}
	// 			});
	// 		}
	// 	}
	// });

	$('[name=submit]',uiMode2).click(function(){
		var wx_account_id	= $('[name=wx_account_id]',uiMode2).val();
		var name	= $('[name=name]',uiMode2).val();
		var appid	= $('[name=appid]',uiMode2).val();
		var appSecret	= $('[name=appSecret]',uiMode2).val();
		var type	= $('[name=type]',uiMode2).val();
		var authorization	= $('[name=authorization]',uiMode2).val();

		// alert(wx_account_id);
		// // alert(appid);
		// // alert(appSecret);
		// // alert(type);
		// // alert(authorization);
		// return

		if(!name){
			alert('请填写名称');
			return false;
		}

		if(!appid){
			alert('请填写appid');
			return false;
		}

		if( !appSecret )
		{
			alert('请填写appSecret');
			return false;
		}

		if( !type )
		{
			alert('请填写type');
			return false;
		}

		if( !authorization )
		{
			alert('请填写authorization');
			return false;
		}

		if (!self.lock) {
			self.lock = true;
			$.ajax({
				url: '/service/wechat/edit',
				data: {
					wx_account_id: wx_account_id,
					name: name,
					appid: appid,
					appSecret: appSecret,
					type: type,
					authorization: authorization
				},
				type: 'POST',
				dataType: 'json',
				success: function (response) {
					self.lock = false;
					if (response.httpStatusCode == 200) {
						// alert('添加成功');
						showmodal('修改成功');
						location.replace(location.href)
					}
					else {
						showmodal('修改失败');
						// alert('添加失败');
						// location.replace(location.href)
					}
					$('[name=wxChatAdd]').modal('hide');
				}
			});
		}
	});


};


