$("#sublime-login").click(function () {
    $.ajax({
        type: 'POST',
        url: LOGINURL ,
        data: {id:$("#username").val(),pwd:$("#password").val(),callback:"Callback"} ,
        success: function (msg) {
            console.log(msg);
		console.log(typeof msg);
            if (msg["errorCode"]=="0"){
                top.location='./admin.html';//登陆成功后页面跳转
            }else{
                alert("wrong username or password!");//登陆失败警告
            }
        }
    });
})
