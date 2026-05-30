(function () {
	'use strict';

	document.addEventListener('click', function (event) {
		var link = event.target.closest('.gspcp-root a[href^="#"]');
		if (!link) {
			return;
		}

		var target = document.querySelector(link.getAttribute('href'));
		if (!target) {
			return;
		}

		event.preventDefault();
		target.scrollIntoView({ behavior: 'smooth', block: 'start' });
	});
}());
