$(document).ready(function () {
	var section = $('.forgetPassword'),
		action = $('form', section).attr('action'),
		btn = $('button', section);

		btn.on('click', function() {
			var user = $('input[name="user"]').val(),
				password = $('input[name="password"]').val(),
				repassword = $('input[name="repassword"]').val();
			if (password == '' && repassword == '') {
				alert('Please insert password and re-password');
				return false;
			}
			if (password != repassword) {
				alert('Please insert matching password and re-password');
				return false;
			}
			$.post(action, {'user' : user, 'password' : password, 'repassword' : repassword}).done(function(data) {
				if (data) {
					alert('Successfully changed password!');
					window.location.assign("http://www.thevschool.com/");
				}
			});
		});
});