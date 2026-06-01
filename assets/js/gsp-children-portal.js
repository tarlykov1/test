(function () {
	'use strict';

	document.documentElement.classList.add('gspcp-has-portal');

	function findAnchorTarget(link) {
		var href = link.getAttribute('href');

		if (!href || href.length < 2 || href.charAt(0) !== '#') {
			return null;
		}

		return document.getElementById(href.slice(1));
	}

	document.addEventListener('click', function (event) {
		var link = event.target.closest('.gspcp-root a[href^="#"]');
		var target;

		if (!link) {
			return;
		}

		target = findAnchorTarget(link);
		if (!target) {
			return;
		}

		event.preventDefault();
		target.scrollIntoView({ behavior: 'smooth', block: 'start' });
	}, { passive: false });
}());
