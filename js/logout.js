$("#logout").click(function () {
    $.ajax({
        type: 'POST',
        url: "logout.php" ,
        success: function (msg) {
            top.location='login.html';//跳转到登陆页面
            console.log(msg);
        }
    });
})
