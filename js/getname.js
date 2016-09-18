var getname=function(param){
	if (param['errorCode']==0) {
		// alert(param['errorMsg']);
		$("#usernameAll").append('<div >'+param['errorMsg']+'</div>');
	}
}