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
		var $select = $('.course-selection'),
			enablePrice = $('input[name="AFF"]', $select),
			priceInfo = $('.price-info');

		enablePrice.change(function() {
			if ($('input[name="AFF"]:checked', $select).length > 0) {
				priceInfo.find('input').removeAttr('disabled');
			} else {
				priceInfo.find('input').attr('disabled', 'disabled');
			}
		});

	})();
});
