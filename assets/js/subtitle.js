$(document).ready(function () {
	//edit subtitle
	(function() {
		var step = $('#editStep2'),
			menuId = step.siblings().val(),
			btn = $('#subUpload', step);

		btn.on('click', function() {
			subtitleUpload();
		});
	})();

	//add subtitle
	(function() {
		var step = $('#addStep2'),
			btn = $('#subUpload', step);

		btn.on('click', function() {
			subtitleUpload();
		});
	})();

	function subtitleUpload() {
		var menuId = $('#menuId').val(),
			data = new FormData($('form#uploadSubtitles')[0]),
			target = $('#target');
		data.append("id", menuId);
		$.ajax({
			url: "/food_course/media/uploadSubtitles",
			type: "POST", 
			enctype: "multipart/form-data",
			data: data,
			contentType: false,
			cache: false,
			processData:false,
			beforeSend: function()
			{
				if (target.hasClass('hide')) {
					target.removeClass('hide');
				}
			},
			success: function(data)
			{
				target.addClass('hide');
				lang = ['th', 'en', 'cn'];
				json = $.parseJSON(data);
				if (json.status == 'success') {
					$.each(lang, function(i, v) {
						$name = 'subtitle' + v.toUpperCase();
						if (typeof json.subtitles[$name] !== 'undefined') {
							$('div[name="lsub'+v+'"]').removeClass('hide').html(json.subtitles[$name]);
						}
					});
				} else {
				}
			}
		});
	}
});