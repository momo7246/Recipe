$(document).ready(function () {
	(function() {
    	$('#file_upload').uploadify({
	        'swf'      : '/food_course/assets/js/uploadify/uploadify.swf',
	        'uploader' : '/food_course/media/uploadMultiImages',
	        'formDate' : {'logged_in' : '<?= $this->session->userdata("logged_in"); ?>'}
	        //'debug'	   : true
	        // Put your options here
    	});
	})();
	
	//--- Image showing dialog ---//
	(function() {
		var imgBtn = $('#imageBtn'),
			imgli = $('.images-display li');

		imgli.on('click', function() {
			var img = $(this).find('img');
			$path = img.attr('src');

			img.addClass('is-select');
			imgli.not($(this)).find('img').removeClass('is-select');

		});

		imgBtn.on('click', function() {
			if (typeof $path == 'undefined') {
				alert('please select 1 photo!');
				return false;
			}
			var dialog = window.opener.CKEDITOR.dialog.getCurrent(),
				id = dialog.getContentElement('info', 'txtUrl').getInputElement().$.id;
				$(window.opener.document).find('input[id="'+id+'"]').val($path);
				window.close();
		});
	})();
	//--- Image showing dialog ---//

	(function() {
		var step = $('#editStep3'),
			btn = $('#cUpload', step);

		btn.on('click', function() {
			var	form = step.find('form')[0],
				id = step.siblings().val();
			imageUpload(form, id, 'image/menu');
		});
	})();

	(function() {
		var step = $('#addStep3'),
			btn = $('#cUpload', step);

		btn.on('click', function() {
			var	form = step.find('form')[0],
				id = $('#menuId', $('.menu-information')).val();
			imageUpload(form, id, 'image/menu');
		});
	})();

	(function() {
		var step = $('#aCourseMedia'),
			btn = $('#cUpload', step);
		btn.on('click', function() {
			var form = step.find('form')[0],
			id = $('#courseId', $('.course-information')).val();
			imageUpload(form, id, 'image/course');
		})
	})();

	(function() {
		var step = $('#eCourseMedia'),
			btn = $('#cUpload', step);
		btn.on('click', function() {
			var form = step.find('form')[0],
			id = $('#courseId', $('.course-edit')).val();
			imageUpload(form, id, 'image/course');
		})
	})();

	//edit video
	(function() {
		var step = $('#editStep2'),
			menuId = step.siblings().val(),
			fbtn = $('#fUpload', step),
			ybtn = $('#yUpload', step);

		openVideoDialog(step);
		videoSelection(step);

		fbtn.on('click', function() {
			fileUpload();
		});

		ybtn.on('click', function() {
			var path = $(this).siblings('input').val();
			youtubeUpload(path, menuId);
		});

		$('#dUpload').on('click', function() {
			deleteVideo();
		});

	})();

	//add video

	(function() {
		var step = $('#addStep2'),
			fbtn = $('#fUpload', step),
			ybtn = $('#yUpload', step);

		openVideoDialog(step);
		videoSelection(step);
		fbtn.on('click', function() {
			fileUpload();
		});

		ybtn.on('click', function() {
			var path = $(this).siblings('input').val(),
				menuId = $('#menuId', $('.menu-information')).val();
			youtubeUpload(path, menuId);
		});

		$('#dUpload').on('click', function() {
			deleteVideo();
		});
	})();

	// add course coverImage
	(function() {

	})();

	function openVideoDialog(step)
	{
		var file = $('.btn-file', step),
			youtube = $('.video-group', step);

		if ($('input[name="path"]', step).is(':checked')) {
			file.removeClass('hide');
			youtube.addClass('hide');

		} else if ($('input[name="youtube"]', step).is(':checked')) {
			file.addClass('hide');
			youtube.removeClass('hide');
		}
	}

	function videoSelection(step)
	{
		var fSelect = $('.file-selection input[type="radio"]', step);

		fSelect.on('change', function() {
			
			if($(this).is(':checked')) {
				fSelect.not($(this)).prop('checked', false);
			}
			openVideoDialog(step);
		});
	}

	function fileUpload()
	{
		var files = $('#embedded')[0].files[0],
			maxSize = 200000000,
			menuId = $('#menuId').val(),
			data = new FormData($('form#uploadEmbeded')[0]),
			xhr = new window.XMLHttpRequest(),
			target = $('#target');

		if (typeof files == 'undefined') {
			alert('Please select at least 1 file!');
			return false;
		}
		if (files.size > maxSize) {
			alert('The file is too large, please upload a new file!');
			return false;
		}

		data.append("id", menuId);

		$.ajax({
			url: "/food_course/media/uploadEmbedded",
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
				json = $.parseJSON(data);
				if (json.status == 'success') {
					$('.text-overflow').children('div').text(json.name);
					$('.margin-100').removeClass('hide');
				} else {
					$('.margin-100').addClass('hide');
				}
			}
		});
	}

	function youtubeUpload(path, menuId)
	{
		var obj = {};

			obj['menuID'] = menuId;
			obj['mediaPath'] = path;
			obj['type'] = 'video';
		$.post('/food_course/media/uploadVideoPath', obj).done(function(data) {
			if (data) {
				$('.text-overflow').children('div').text(path);
				$('.margin-100').removeClass('hide');
			} else {
				$('.margin-100').addClass('hide');
			}
		});
	}

	function deleteVideo()
	{
		var obj = {};
			obj['id'] = $('#menuId').val();
		$.post('/food_course/media/deleteVideo', obj, function(data) {
			if (data == true) {
				$('.margin-100').addClass('hide');
				$('.text-overflow').children('div').text();
			}
		});
	}

	function imageUpload(form, id, section)
	{
		var file = $('#cover')[0].files[0],
			maxSize = 2000000,
			data = new FormData(form),
			target = $('#menutarget');

		if (typeof file == 'undefined') {
			alert('Please select at least 1 file!');
			return false;
		}
		if (file.size > maxSize) {
			alert('The file is too large, please upload a new file!');
			return false;
		}

		data.append("id", id);
		data.append("section", section);

		$.ajax({
			url: "/food_course/media/uploadCoverImage",
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
				var step = '#step2';
				if (section == 'image/menu') {
					step = '#step3';
				}
				var elem = $(step).children('div').first(),
					json = $.parseJSON(data);
				if (json.status == 'success') {
					elem.find('img').attr('src', json.path);
					elem.removeClass('hide');
				} else {
					elem.addClass('hide');
				}
			}
		});
	}
});