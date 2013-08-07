(function() {
	$(document).ready(function() {
		var $contact_form = $('#addon-contact-form');

		function display_general_error(msg) {
			var $error_msg = $('#addon-contact-form-error');
			if (!$error_msg.length) {
				$error_msg = $('<p id="addon-contact-form-error" class="addon-contact-form-error" style="display: none;"></p>');
				$contact_form.prepend($error_msg);
			}
			$error_msg.html(msg);
			$error_msg.fadeIn();
		}

		$contact_form.submit(function() {
			$('.error', this).removeClass('.error');
			$('.addon-contact-form-error', this).hide();

			var data = $(this).serialize();
			var url = $(this).prop('action');
			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'JSON',
				data: data
			})
			.done(function(data, textStatus, jqXHR) {
				if (typeof data.errors != 'undefined') {
					for (key in data.errors) {
						if (key == 'general') {
							display_general_error(data.errors[key]);
							continue;
						}

						var html_id = '#error-' + key + '-' + data.errors[key];
						var $container = $(html_id);
						if (!$container.length) {
							$container = $('<p id="' + html_id.substring(1) + '" class="addon-contact-form-error" style="display: none;">' + data.errors[key] + '</p>');
							$('[name=' + key + ']').after($container).addClass('error');
						}
						$container.hide().fadeIn();
					}
				}
				else {
					$contact_form.hide();
					var $success_msg = $('#addon-contact-form-success');
					if (!$success_msg.length) {
						$success_msg = $('<p id="addon-contact-form-success" style="display: none;">Message sent. Thank you!</p>');
						$contact_form.after($success_msg);
					}
					$success_msg.hide().fadeIn();
				}
			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				display_general_error('There was a problem submitting the form: ' + textStatus + ' ' + errorThrown);
			});
			return false;
		});

	});

})(jQuery);