//修改个人详细信息
$("#personal-detail-update").click(function(){
    	// alert($("#update-name").val()+" "+$("#update-gender").val()+" "+$("#update-grade").val());
    	//更新信息不能留空
	if ($("#update-name").val()==""||
		$("#update-gender").val()==""||
		$("#update-grade").val()==""||
		$("#update-photo").val()=="") {
		alert("参数不正确");
		return false;
	}

	var formData=new FormData($('#update-form')[0]);
	// formData.append("id",nowId); 不传id 修改自己的
	formData.append("action","modify");
	formData.append("callback","Callback");
	//执行更新操作
	$.ajax({
	    type: 'POST',
	    cache : false,  
	    processData: false,
	    contentType: false,
	    dataType: 'json',
	    enctype:"multipart/form-data",
	    url: MODIFYURL,   //修改URL
	    data: formData,  //请求参数
		success:function(msg){
			console.log(msg);
			if (msg["errorCode"]==0) {
				alert("更新成功");
			}else{
				alert("更新失败");
			}
		}
	});
    // location.reload(true);
	// alert("ok");
});

//修改个人登陆密码
$("#personal-psw-update").click(function(){
	if ($("#update-psw-old").val()==""||
        $("#update-psw-new").val()==""||
		$("#update-psw-new2").val()==""||
		$("#update-psw-new").val()!=$("#update-psw-new2").val()
		) {
		alert("参数不正确");
		return false;
	}

	$.ajax({
		type: 'POST',
	    cache : false,  
	    dataType: 'json',
	    url: CHANGEPSWURL,   //修改URL
	    data: {oldpwd:$("#update-psw-old").val(),newpwd:$("#update-psw-new").val(),callback:"Callback"},  //请求参数
		success:function(msg){
			console.log(msg);
			if (msg["errorCode"]==0) {
				alert("更新密码成功");
			}else{
				alert("更新失败失败");
			}
		}
	});
    location.reload(true);
});