import $ from 'jquery';
import Ajax from 'elgg/Ajax';
import 'embed/lists/item';
import * as popup from 'elgg/popup';

$(document).on('click', '.elgg-menu-embed > li > a', function (e) {
	e.preventDefault();

	var $trigger = $(this);
	var $toolbar = $trigger.closest('.embed-toolbar');
	var $target = $toolbar.find('.embed-toolbar-popup');

	popup.open($trigger, $target, {
		'collision': 'fit none',
		'my': 'left top+8px',
		'at': 'left bottom',
		'of': $toolbar
	});

	$target.on('open', function () {
		var $module = $(this);
		var $menuTrigger = $module.data('trigger');

		var ajax = new Ajax(false);

		$target.html('');
		$target.addClass('elgg-ajax-loader');

		ajax.path($menuTrigger.data('href'))
			.done(function (view) {
				$menuTrigger.parent().addClass('elgg-state-active');
				$target.html(view);
				$target.find('textarea,input[type="text"],input[type="url"]').first().focus();
			})
			.always(function () {
				$target.removeClass('elgg-ajax-loader');
			});
	}).on('close', function () {
		var $menuTrigger = $(this).data('trigger');
		$menuTrigger.parent().removeClass('elgg-state-active');
	});
});
