import elgg from 'elgg';
import $ from 'jquery';
import embed from 'elgg/embed';
import lightbox from 'elgg/lightbox';
import Ajax from 'elgg/Ajax';
import * as popup from 'elgg/popup';

$(document).off('submit', '.elgg-form-embed-file-upload').on('submit', '.elgg-form-embed-file-upload', function (e) {
	e.preventDefault();

	var $elem = $(this);

	var textAreaId = embed.textAreaId;
	var textArea = $('#' + textAreaId);

	var value = textArea.val();
	var result = textArea.val();

	var ajax = new Ajax();
	ajax.action($elem.attr('action'), {
		data: ajax.objectify($elem),
		beforeSend: function() {
			$elem.find('[type="submit"]').prop('disabled', true);
		}
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
	}).fail(function() {
		if ($elem.closest('.embed-toolbar-popup').length) {
			popup.close();
		} else {
			lightbox.close();
		}
	});

});
