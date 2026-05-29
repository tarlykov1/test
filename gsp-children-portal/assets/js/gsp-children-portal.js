(function () {
  'use strict';

  function initAccordion(root) {
    var buttons = root.querySelectorAll('.gspcp-accordion__button');
    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        var panel = button.nextElementSibling;
        var isExpanded = button.getAttribute('aria-expanded') === 'true';

        buttons.forEach(function (otherButton) {
          var otherPanel = otherButton.nextElementSibling;
          otherButton.setAttribute('aria-expanded', 'false');
          if (otherPanel) {
            otherPanel.hidden = true;
          }
        });

        button.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
        if (panel) {
          panel.hidden = isExpanded;
        }
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gspcp-accordion]').forEach(initAccordion);
  });
}());
