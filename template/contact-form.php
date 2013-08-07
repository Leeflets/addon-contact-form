<?php
namespace Leeflets\Template;

class Contact_Form {
	private $contact_form, $router, $config, $settings;

	function __construct( \Leeflets\User\Addon\Contact_Form $contact_form, \Leeflets\Router $router, \Leeflets\Config $config, \Leeflets\Settings $settings ) {
		$this->contact_form = $contact_form;
		$this->router = $router;
		$this->config = $config;
		$this->settings = $settings;
	}

	function action() {
		echo $this->router->admin_url( '/contact-form/submit/' );
	}

	function template_path() {
		$data = $this->contact_form->get_data();
		if ( empty( $data['recipient_email'] ) ) {
			return $this->contact_form->basepath . '/view/no-recipient-email.php';
		}
		$active_template = $this->settings->get( 'template', 'active' );
		$path = $this->config->templates_path . '/' . $active_template . '/contact-form.php';
		if ( !file_exists( $path ) ) {
			$path = $this->contact_form->basepath . '/view/contact-form.php';
		}

		return $path;
	}
}