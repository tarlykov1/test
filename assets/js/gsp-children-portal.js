(function () {
	'use strict';

	function initSmoothLinks(root) {
		root.querySelectorAll('a[href^="#gsp-children-"]').forEach(function (link) {
			link.addEventListener('click', function (event) {
				var target = document.querySelector(link.getAttribute('href'));

				if (!target) {
					return;
				}

				event.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.gsp-children').forEach(function (root) {
			initSmoothLinks(root);
		});
	});
}());
