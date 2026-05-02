(function ($) {
	function mediaSelector($button) {
		const frame = wp.media({
			title: 'Select media',
			multiple: false,
			library: { type: $button.data('type') === 'file' ? '' : 'image' }
		});
		frame.on('select', function () {
			const attachment = frame.state().get('selection').first().toJSON();
			const $input = $button.siblings('.tce-media-url');
			$input.val(attachment.url);
		});
		frame.open();
	}

	$(document).on('click', '.tce-media-button', function () {
		mediaSelector($(this));
	});

	$(document).on('click', '.tce-repeater-add', function () {
		const $wrap = $(this).closest('.tce-repeater');
		const key = $wrap.data('key');
		const $rows = $wrap.find('.tce-repeater-rows');
		const index = $rows.find('.tce-repeater-row').length;
		const template = wp.template('tce-repeater-' + key);
		$rows.append(template({ index: index }));
	});

	$(document).on('click', '.tce-repeater-remove', function () {
		$(this).closest('.tce-repeater-row').remove();
	});
})(jQuery);
