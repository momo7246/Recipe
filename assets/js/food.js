$(document).ready(function () {
	(function() {
		var $body = $('body'),
			menuTab = $('.navbar-nav li', $body);
			menuTab.on('click', function() {
				menuTab.removeClass('active');
				$(this).addClass('active');
			});
	})();

	(function() {
<<<<<<< Updated upstream
		var $select = $('.course-selection'),
			enablePrice = $('input[name="AFF"]', $select),
=======
		var $selection = $('.course-selection'),
			checkboxes = $('input[type="checkbox"]', $selection);
			checkboxes.tooltip();
	})();

	(function() {
		var elem = $('.menu-information');

		if (elem.length > 0) {
			priceSelect();
		}

	})();

	(function() {
		var section = $('.menu-information');

		$("#unbindStep2", section).on('click', function() {
			var menu = getMenuData('.menu-information'),
				stepOnePath = $('#stepOne').attr('action');

			$.post( stepOnePath, menu).done(function( data ) {
				var json = $.parseJSON(data);

				if (json.status == "success") {
					$('#menuId', $('.menu-information')).attr('value', json.menuId);
					$('.progress-bar').attr('style','width:50%');
    				$('#menuStep li:eq(1) a').tab('show');
				}

				return false;
			});
		});
		$("#unbindStep3", section).on('click', function() {
			$('.progress-bar').attr('style','width:75%');
    		$('#menuStep li:eq(2) a').tab('show');
		});
		$('#unbindStep4', section).on('click', function() {
			var image = $('.panel-heading', '#step3').find('img').attr('src');
			
			if (image == '') {
				alert('Please select at least 1 photo!');
				return false;
			}
			$('.progress-bar').attr('style','width:100%');
    		$('#menuStep li:eq(3) a').tab('show');
		});
	})();

	(function() {
		var section = $('.course-information'),
			aff = $('input[name="AFF"]', section),
			priceInfo = $('.price-info');

		if (section.length > 0) {
			coursePriceSelect(priceInfo);
		}

		aff.change(function() {
			coursePriceSelect(priceInfo);
		});

		$('#unbindStep2', section).on('click', function() {
			var course = getCourseData('.course-information'),
				path = $('#stepOne').attr('action');

			$.post(path, course).done(function(data) {
				json = $.parseJSON(data);
				if (json.status = 'success') {
					$('#courseId', $('.course-information')).attr('value', json.courseId);
					$('.progress-bar').attr('style','width:70%');
					$('#courseStep li:eq(1) a').tab('show');
				}	
			});

		});

		$('#unbindStep3', section).on('click', function() {
			var image = $('.panel-heading', '#step2').find('img').attr('src');

			if (image == '') {
				alert('Please select at least 1 photo!');
				return false;
			}
			$('.progress-bar').attr('style','width:100%');
			$('#courseStep li:eq(2) a').tab('show');
		});		
	})();

	(function() {
		var menuEdit = $('.menu-edit'),
			aff = $('input[name="AFF"]', menuEdit),
			pSelect = $('.price-selection', menuEdit);

			if (aff.attr('data-value') == '1') {
				aff.attr('checked', 'checked');
			}

			if (pSelect.attr('data-value') == '1') {
				$('input[name="coursePrice"]',menuEdit).attr('checked', 'checked');
				$('input[name="ownPrice"]', menuEdit).prop('checked', false);
			} else {
				$('input[name="ownPrice"]', menuEdit).attr('checked', 'checked');
				$('input[name="coursePrice"]',menuEdit).prop('checked', false);
			}

			if (menuEdit.length > 0) {
				priceSelect();
			}


			courseSelect = $('.course-selection', menuEdit),
			countrySelect = $('.country-selection', menuEdit);

			courseSelect.find('option[value="' + courseSelect.attr('select-value') + '"]').prop('selected', true);
			countrySelect.find('option[value="' + countrySelect.attr('select-value') + '"]').prop('selected', true);

	})();

	(function() {
		var section = $('.course-edit'),
			aff = $('input[name="AFF"]', section),
			show = $('input[name="show"]', section),
			path = $('#stepOne', section).attr('action'),
			btn = $('#editCourseInfo'),
			priceInfo = $('.price-info');

		if (aff.attr('data-value') == 0) {
			aff.attr('checked', 'checked');
		} else {
			aff.removeAttr('checked');
		}

		if (show.attr('data-value') == 1) {
			show.attr('checked', 'checked');
		}

		if (section.length > 0) {
			coursePriceSelect(priceInfo);
		}
		

		aff.change(function() {
			coursePriceSelect(priceInfo);
		});

		btn.on('click', function() {
			course = getCourseData('.course-edit');
			course['id'] = $('#stepOne', section).attr('data-value');

			$.post(path, course).done(function(data) {
				if (data == true) {
					$('#alert-menu-success').removeClass('hide');
					$('#alert-menu-warning').addClass('hide');
				} else {
					$('#alert-menu-warning').removeClass('hide');
					$('#alert-menu-success').addClass('hide');
				}

				return false;
			});
		});

	})();

	(function() {
		var section = $('.menu-edit'), 
			btn = $('#editMenuInfo'),
			ajaxPath = $('#stepOne', section).attr('action');

		btn.on('click', function() {
			menu = getMenuData('.menu-edit');
			menu['id'] = $('#stepOne', section).attr('data-value');

			$.post(ajaxPath, menu).done(function (data) {
				
				if (data == 'true') {
					$('#alert-menu-success').removeClass('hide');
					$('#alert-menu-warning').addClass('hide');
				} else {
					$('#alert-menu-warning').removeClass('hide');
					$('#alert-menu-success').addClass('hide');
				}

				return false;
			});
		});
	})();

	/*(function() {
		var	enablePrice = $('input[name="AFF"]'),
>>>>>>> Stashed changes
			priceInfo = $('.price-info');

		enablePrice.change(function() {
<<<<<<< Updated upstream
			if ($('input[name="AFF"]:checked', $select).length > 0) {
				priceInfo.find('input').removeAttr('disabled');
			} else {
				priceInfo.find('input').attr('disabled', 'disabled');
=======
			coursePriceSelect(priceInfo);
		});
	})();*/

	(function() {
		var section = $('.unlock-course'),
			course = $('.ul-course-sl', section);

			getMenuFromCourse(section);

			course.on('change', function() {
				getMenuFromCourse(section);
			});
	})();

	(function() {
		var path = '/food_course/unlock/getStudentSuggestion',
			list = $('.li-student'),
			section = $('.unlock-course'),
			obj = {};

		$('#searchStudent').on('click', function(e) {
			obj['keyword'] = $('input[name="keyword"]').val();

			$.post(path, obj).done(function(data) {
				json = $.parseJSON(data);
				$.each(json['students'], function(i, v) {
					list.append('<li value="'+v.ID+'">'+v.name+' '+v.surname+'<br><span>'+v.email+'</span></li>');
				});
				list.removeClass('hide');
			});
		});

		$('.li-student').delegate('li', 'click', function () {
    		$('.fi-student').find('input[name="keyword"]').val($(this).contents().get(0).nodeValue);
    		$('input[name="studentID"]', section).attr('value', $(this).val());
    		$('.li-student').trigger('open.enrollment', $(this).val());
		});

	})();

	(function() {
		var obj = {},
			section = $('.unlock-course'),
			path = $('form', section).attr('action'),
			btn = $('#paymentConfirm', section);

		btn.on('click', function () {
			obj['studentID'] = $('input[name="studentID"]', section).val();

			if ($('.ul-menu-sl').val() != 0) {
				obj['courseType'] = 1;
				obj['courseID'] = $('.ul-menu-sl').val();
			} else if ($('.ul-course-sl').val() != 0) {
				obj['courseType'] = 0;
				obj['courseID'] = $('.ul-course-sl').val();	
			}

			$.post(path, obj).done(function(data) {
				if (data == true) {
					$('.success-payment').removeClass('hide');
				} else {
					alert('This student has already enroll in this course!');
				}
			});
		});

		section.on('open.enrollment', function(e, param) {
			$('.delete-student-enroll').find('tbody tr').remove();
			$.get('/food_course/unlock/getStudentEnrollment', {id : param}).done(function(data) {
				json = $.parseJSON(data);
				$.each(json, function(i, k) {
					var template = '<tr><td>'+k.name+'</td><td>'+k.typeText+'</td><td><a class="btn btn-danger btn-xs" href="/food_course/unlock/deleteStudentEnroll?id='+k.ID+'">Delete</a></td></tr>';
					$('.delete-student-enroll').find('tbody').append(template);
				});
			});
		});
		
	})();

	(function() {
		var body = $('body');
		body.on('click', function() {
			$('.li-student').empty();
			$('.li-student').addClass('hide');
		});
	})();

	(function() {
		var section = $('#studentReport');
			row = $('.st-info tbody').find('tr'),
			panel = $('.panel-info', section),
			cBtn = panel.find('#cPanel');

		row.on('click', function() {
			var $id = $(this).children('td').first('td').text(),
				focusPanel = $('.st-info-' + $id);

			row.not($(this)).removeClass('info');
			$(this).addClass('info');
			focusPanel.removeClass('hide');
			panel.not(focusPanel).addClass('hide');
		});

		cBtn.on('click', function() {
			$(this).parent().closest('div.panel-info').addClass('hide');
		})
	})();

	(function() {
		var year = $('select[name="year"]'),
			currentYear = new Date().getFullYear();

		$('#enrollReport select').find('option[value="'+currentYear+'"]').prop('selected', true);

		year.on('change', function() {
			$.post('/food_course/home/index', {'year' : year.val()}).done(function(data) {
				$('.home-partial').html(data);
			});
		});

	})();

	(function() {
		var btn = $('#deleteStudent'),
			closeBtn = $('#closeModal'),
			modal = $('#myModal'),
			id = modal.find('input').val();
		
		modal.modal('toggle');

		btn.on('click', function() {
			$.post('/food_course/admin/deleteStudent', {'proceed' : true, 'id' : id}).done(function() {
				modal.modal('hide');
				$('.alert-success').removeClass('hide');
			});
		});

		closeBtn.on('click', function() {
			modal.modal('hide');
			parent.history.back();

        	return false;
		});

	})();

	function getMenuData(elem) {
		var menuInfo = $(elem),
			elemInput = ['title', 'subTitle', 'topic'],
			elemTArea = ['definition'],
			elemRText = ['ingredient', 'howto']
			obj = {};

		obj['courseID'] = $('.course-selection', menuInfo).val();
		obj['country'] = $('.country-selection', menuInfo).val();
		$.each(elemInput, function(i,v) {
			obj[v + 'TH'] = $('input[name="' +v+ 'TH"]', menuInfo).val();
			obj[v + 'EN'] = $('input[name="' +v+ 'EN"]', menuInfo).val();
			obj[v + 'CN'] = $('input[name="' +v+ 'CN"]', menuInfo).val();
		});

		$.each(elemTArea, function(i,v) {
			obj[v + 'TH'] = $('textarea[name="' +v+ 'TH"]', menuInfo).val();
			obj[v + 'EN'] = $('textarea[name="' +v+ 'EN"]', menuInfo).val();
			obj[v + 'CN'] = $('textarea[name="' +v+ 'CN"]', menuInfo).val();
		});

		$.each(elemRText, function(i,v) {
			obj[v + 'TH'] = CKEDITOR.instances['t'+v+'TH'].getData();
			obj[v + 'EN'] = CKEDITOR.instances['t'+v+'EN'].getData();
			obj[v + 'CN'] = CKEDITOR.instances['t'+v+'CN'].getData();
		});

		obj['AFF'] = 0;
		if ($('input[name="AFF"]:checked').length > 0) {
			obj['AFF'] = 1;
		}

		obj['price'] = $('input[name="price"]', menuInfo).val();
		obj['discount'] = $('input[name="discount"]', menuInfo).val();
		obj['coursePrice'] = 0;
		if ($('input[name="coursePrice"]:checked').length > 0) {
			obj['coursePrice'] = 1;
		}

		return obj;
	}

	function priceSelect() {
		var aff = $('input[name="AFF"]'),
			priceSelection = $('.price-selection'),
			priceInfo = $('.price-info'),
			priceCheck = $('input[type="checkbox"]', priceSelection),
			country = $('.country-selection'),
			course = $('.course-selection');

		country.on('change', function() {
			if (country.val() != "") {
				aff.attr('checked', 'checked');
				priceSelection.hide();
				priceInfo.hide();
			} else if (country.val() == "") {
				aff.removeAttr('checked');
				priceSelection.show();
				priceInfo.show();
			}
		});

		course.on('change', function() {
			if (course.val() != "0") {
				$('input[name="coursePrice"]', priceSelection).prop('checked', true);
				$('input[name="ownPrice"]', priceSelection).removeAttr('checked');
				$('input', priceInfo).attr('disabled', 'disabled');
			} else if (course.val() == "0") {
				$('input[name="ownPrice"]', priceSelection).prop('checked', true);
				$('input[name="coursePrice"]', priceSelection).removeAttr('checked');
				$('input', priceInfo).removeAttr('disabled');
			}
		});

		if (aff.is(':checked')) {
			priceSelection.hide();
			priceInfo.hide();
		}

		aff.on('click', function() {
			if (aff.is(':checked')) {
				priceSelection.hide();
				priceInfo.hide();
			} else {
				priceSelection.show();
				priceInfo.show();
			}
		});

		if ($('input[name="coursePrice"]', priceSelection).is(':checked')) {
			$('input', priceInfo).attr('disabled', 'disabled');
		}

		if ($('input[name="ownPrice"]', priceSelection).is(':checked')) {
			$('input', priceInfo).removeAttr('disabled');
		}
		
		priceCheck.on('click', function() {
			priceCheck.not(this).prop('checked', false);
			if ($('input[name="coursePrice"]', priceSelection).is(':checked')) {
				$('input', priceInfo).attr('disabled', 'disabled');
			}

			if ($('input[name="ownPrice"]', priceSelection).is(':checked')) {
				$('input', priceInfo).removeAttr('disabled');
			} else if ($('input[name="ownPrice"]', priceSelection).is(':checked') == false) {
				$('input', priceInfo).attr('disabled', 'disabled');
>>>>>>> Stashed changes
			}
		});

<<<<<<< Updated upstream
	})();
=======
		$.each(lang, function(i,v) {
			obj['header' + v] = $('input[name="header'+v+'"]', courseInfo).val();
		});

		$.each(price, function(i,v) {
			obj[v] = $('input[name="'+v+'"]', courseInfo).val();
		});

		obj['AFF'] = 1;
		if ($('input[name="AFF"]:checked').length > 0) {
			obj['AFF'] = 0;
		}

		obj['show'] = 0;
		if ($('input[name="show"]:checked').length > 0) {
			obj['show'] = 1;
		}
		obj['status'] = 'ACT';

		return obj;
	}

	function coursePriceSelect(priceInfo) {
		if ($('input[name="AFF"]:checked').length > 0) {
			priceInfo.find('input').removeAttr('disabled');
			
		} else if ($('input[name="AFF"]:checked').length == 0) {
			priceInfo.find('input').attr('disabled', 'disabled');
		}
	}
>>>>>>> Stashed changes
});
