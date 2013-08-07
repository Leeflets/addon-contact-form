<div id="addon-contact-form-container" data-lf-edit="addon-contact-form">

	<h3>Contact Us</h3>

	<form action="<?php $contact_form->action(); ?>" method="post" id="addon-contact-form">

	    <p class="required-note"><span class="required">*</span> <em>Indicates required fields</em></p>

		<div class="field field-name">
			<label for="sender_name">Name <span class="required">*</span></label>
			<input type="text" name="sender_name" id="sender_name" />
			<p id="error-sender_name-required" class="addon-contact-form-error" style="display: none;">
				Please enter your name.
			</p>
		</div>
		<div class="field field-email">
			<label for="sender_email">Email <span class="required">*</span></label>
			<input type="text" name="sender_email" id="sender_email" /> 
			<p id="error-sender_email-required" class="addon-contact-form-error" style="display: none;">
				Please enter your email.
			</p>
			<p id="error-sender_email-invalid" class="addon-contact-form-error" style="display: none;">
				Please enter a valid email address.
			</p>
	    </div>
	    <div class="field field-message">
			<label for="message">Message <span class="required">*</span></label>
			<textarea name="message" id="message"></textarea>
			<p id="error-message-required" class="addon-contact-form-error" style="display: none;">
				Please enter a message.
			</p>
		</div>
		
		<button type="submit">Send Message</button>
	</form>

	<p id="addon-contact-form-success" style="display: none;">
	    Message sent. Thank you!
	</p>

</div>
