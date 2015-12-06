$(document).ready(function () {
	var	content = $('#enrollContent'),
		modal = $('#enrollModal'),
		id = content.find('input[name="id"]').val(),
		courseId = content.find('input[name="courseId"]').val(),
		btn = $('#delEnroll');

	btn.on('click', function() {
		modal.show();
		var del = $('#delEnrollCourse', modal),
			close = $('#closeEnrollModal', modal);

		close.on('click', function() {
			modal.hide();
		});

		del.on('click', function() {
			$.post('/food_course/home/deleteCourse', {'proceed' : true, 'id' : id, 'courseId' : courseId}).done(function() {
				modal.hide();
				window.location.reload(true);
			});
		});
	});
});