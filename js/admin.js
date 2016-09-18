  //   var data = [];
  //   for(var i=0;i<1008;i++){
  //       data[i] = {id:i+1,name:"jason"+(i+1),sex:"男",control:'<button type="button" class="btn btn-success" data-toggle="modal" data-target="#scan">查看详情</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update">修改基本信息</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-psw">修改登陆密码</button> <button type="button" class="btn btn-danger" onclick="delete1(1)">删除</button>'};
  //   }
  //   var cs = new table({
  //       "tableId":"cs_table",    //必须
  //       "headers":["学号","姓名","性别","操作"],   //必须
  //       "data":data,        //必须
  //       "displayNum": 10,    //必须   默认 10
  //      "groupDataNum":4  //可选    默认 10
		// });


	//点击按钮后当前要使用的参数列表；
	var nowId;
	var nowName;
	var nowGender;
	var nowPhoto;
	var nowGrade;

    $(document).ready(function(){
		var dataTable=[];//保存表格信息的数据
		$.ajax({
		    type: 'POST',
		    cache : false,  
		    dataType: 'json',
		    url: VIEWURL,   //修改URL
		    data: "callback=Callback",  //请求参数
			success:function(msg){
				//在此作判断逻辑

				if (msg["isAdmin"] == 1) {
					//管理员
					for(var s in msg["array"]){
						var tempId=msg["array"][s]["id"];
						var tempName=msg["array"][s]["name"];
						var tempGender=(msg["array"][s]["gender"]==1)?"男":"女";
						var tempPhoto=msg["array"][s]["photo"];
						var tempGrade=msg["array"][s]["grade"];

						var tempString="'"+tempId+"#"+tempName+"#"+tempGender+"#"+tempPhoto+"#"+tempGrade+"'";
						dataTable.push({
							id:tempId,
							name:tempName,
							sex:tempGender,
							control:'<button type="button" class="btn btn-success" data-toggle="modal" data-target="#scan" onclick="setNowId('+tempString+');showDetail()">查看详情</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update" onclick="setNowId('+tempString+');updateDetail()">修改基本信息</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update-psw" onclick="setNowId('+tempString+')">修改登陆密码</button> <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete" data-toggle="modal" data-target="#delete" onclick="setNowId('+tempString+');setDeleteId()">删除</button>'
						});
					}

				}else{
					//非管理员.修改和删除操作被禁用
					$("#create-button").css("visibility","hidden");
					for(var s in msg["array"]){
						var tempId=msg["array"][s]["id"];
						var tempName=msg["array"][s]["name"];
						var tempGender=(msg["array"][s]["gender"]==1)?"男":"女";
						var tempPhoto=msg["array"][s]["photo"];
						var tempGrade=msg["array"][s]["grade"];
						var tempString="'"+tempId+"#"+tempName+"#"+tempGender+"#"+tempPhoto+"#"+tempGrade+"'";
						dataTable.push({
							id:tempId,
							name:tempName,
							sex:tempGender,
							control:'<button type="button" class="btn btn-success" data-toggle="modal" data-target="#scan" onclick="setNowId('+tempString+');showDetail()">查看详情</button>'
						});
					}
				}
				//设置表格填充
				var cs = new table({
			        "tableId":"cs_table",    				//id选择
			        "headers":["学号","姓名","性别","操作"],//表头信息
			        "data":dataTable,        				//填充数据
			        "displayNum": 10,    					//每页显示条数   默认 10
			        "groupDataNum":4  						//可选分页数量   默认 10
				});

			},
			error:function(){
				console.log("服务器异常");
			}
		});
	});

    function setDeleteId(){
    	$("#delete-id").html(nowId);
    	$("#delete-name").html(nowName);
    }
    //设置设置当前的状态
    function setNowId(obj){
    	var temp=obj.split("#");	
    	console.log(obj);
    	nowId=temp[0];
    	nowName=temp[1];
    	nowGender=temp[2];
    	nowPhoto=temp[3];
    	nowGrade=temp[4];
    	// alert(nowId);
    }

    function showDetail(){
    	$("#detail-photo").attr('src',nowPhoto);
    	$("#detail-name").html(nowName);
    	$("#detail-id").html(nowId);
    	$("#detail-gender").html(nowGender);
    	$("#detail-grade").html(nowGrade);
    }
    function updateDetail(){
    	$("#update-id").html(nowId);
    	$("#update-name").attr('placeholder','原姓名为：'+nowName);
    }
    $("#update-submit").click(function(){
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
    	formData.append("id",nowId);
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
		location.reload(true);
    	// alert("ok");
    });
    //新建
    $("#create-submit").click(function(){
    	// alert($("#update-name").val()+" "+$("#update-gender").val()+" "+$("#update-grade").val());
    	//更新信息不能留空
    	if ($("#create-id").val()==""||
    		$("#create-name").val()==""||
    		$("#create-gender").val()==""||
    		$("#create-grade").val()==""||
    		$("#create-photo").val()=="") {
    		alert("参数不正确");
    		return false;
    	}
    	var formData=new FormData($('#create-form')[0]);
    	formData.append("action","add");
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
					alert("新建成功");
				}else{
					alert("新建失败");
				}
			}
		});
		// location.reload(true);
    	// alert("ok");
    });
    //修改密码
    $("#update-psw-submit").click(function(){
    	if ($("#update-psw-new").val()==""||
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
		    data: {id:nowId,newpwd:$("#update-psw-new").val(),callback:"Callback"},  //请求参数
			success:function(msg){
				console.log(msg);
				if (msg["errorCode"]==0) {
					alert("更新密码成功");
				}else{
					alert("更新失败失败");
				}
			}
    	});
    	// location.reload(true);
    });

    //删除
    $("#delete-submit").click(function(){
    	$.ajax({
    		type: 'POST',
		    cache : false,  
		    dataType: 'json',
		    url: MODIFYURL,   //修改URL
		    data: {id:nowId,callback:"Callback",action:"delete"},  //请求参数
			success:function(msg){
				if (msg["errorCode"]==0) {
					alert("删除成功");
				}else{
					// alert("删除失败");
				}
			}
		});
		location.reload(true);
    })