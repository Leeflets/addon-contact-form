<?php
namespace Leeflets\User\Addon;

class Contact_Form extends \Leeflets\Addon\Base {

	/* Always need this boilerplace */
	function __construct() {
		parent::__construct( __FILE__ );
	}

	function init() {
		require $this->basepath . '/controller/contact-form.php';

		$script_url = $this->get_url( 'assets/js/script.js' );
		$this->template_script->add_enqueue( 'addon-contact-form', $script_url, array( 'jquery' ), false, true );

		$this->data_file_path = $this->config->data_path . '/addon-contact-form.json.php';

		$this->hook->add( 'template_render_objects', array( $this, 'template_render_objects' ) );

		$this->hook->add( 'template_get_content_fields', array( $this, 'get_content_fields' ) );

		$this->hook->add( 'content_set_data', array( $this, 'content_set_data' ) );
		$this->hook->add( 'content_get_data', array( $this, 'content_get_data' ) );
	}

	function template_render_objects( $objs ) {
		require $this->basepath . '/template/contact-form.php';
		$objs['contact_form'] = new \Leeflets\Template\Contact_Form( $this, $this->router, $this->config, $this->settings );
		return $objs;
	}

	function content_set_data( $values ) {
		if ( isset( $values['addon-contact-form'] ) ) {
			$addon_values = $values['addon-contact-form'];
			unset( $values['addon-contact-form'] );
			
			$file = new \Leeflets\Data_File( $this->data_file_path, $this->config );
			$file->write( $addon_values, $this->filesystem );
		}

		return $values;
	}

	function content_get_data( $values ) {
		$values['addon-contact-form'] = $this->get_data();
		return $values;
	}

	function get_data() {
		if ( !file_exists( $this->data_file_path ) ) {
			return array();
		}

		$file = new \Leeflets\Data_File( $this->data_file_path, $this->config );
		return $file->read();
	}

	function get_content_fields( $fields ) {
		$fields['addon-contact-form'] = array(
	    	'type' => 'fieldset',
	    	'elements' => array(
	    		'recipient_name' => array(
	    			'type' => 'text',
	    			'label' => 'Recipient Name'
	    		),
	    		'recipient_email' => array(
	    			'type' => 'email',
	    			'label' => 'Recipient Email'
	    		),
	    		'email_subject' => array(
	    			'type' => 'text',
	    			'label' => 'Email Subject',
	    			'class' => 'input-xlarge'
	    		),
	    		'additional_fields' => array(
	    			'type' => 'text',
	    			'label' => 'Additional Field Names',
	    			'class' => 'input-block-level',
	    			'tip' => 'Normally any additional fields are stripped out, 
	    				but here you can specify fields to include in the message.
	    				Separate field names with commas.'
	    		)
	    	)
		);

		return $fields;
	}
}
