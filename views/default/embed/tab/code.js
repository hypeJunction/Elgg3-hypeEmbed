import elgg from 'elgg';
import embed from 'elgg/embed';
import lightbox from 'elgg/lightbox';
import Ajax from 'elgg/Ajax';
import * as popup from 'elgg/popup';

var ajax = new Ajax();

$(document).on('submit', '.elgg-form-embed-code', function (e) {
	e.preventDefault();

	var $elem = $(this);

	var textAreaId = embed.textAreaId;
	var textArea = $('#' + textAreaId);

	var value = textArea.val();
	var result = textArea.val();

	ajax.action($elem.attr('action'), {
		data: ajax.objectify($elem)
	}).done(function (content) {
		textArea.focus();
		if (!elgg.isNullOrUndefined(textArea.prop('selectionStart'))) {
			var cursorPos = textArea.prop('selectionStart');
			var textBefore = value.substring(0, cursorPos);
			var textAfter = value.substring(cursorPos, value.length);
			result = textBefore + content + textAfter;
		} else if (document.selection) {
			// IE compatibility
			var sel = document.selection.createRange();
			sel.text = content;
			result = textArea.val();
		}

		// See the ckeditor plugin for an example of this hook
		result = elgg.trigger_hook('embed', 'editor', {
			textAreaId: textAreaId,
			content: content,
			value: value,
			event: e
		}, result);
		if (result || result === '') {
			textArea.val(result);
		}

		// Close toolbar popup or lightbox depending on context
		if ($elem.closest('.embed-toolbar-popup').length) {
			popup.close();
		} else {
			lightbox.close();
		}
	});

});
