<?php
namespace Leeflets\Controller;

class Contact_Form extends \Leeflets\Controller {
    protected $no_auth_actions = array( 'submit' );
    
	function submit() {
        $addon = $this->addon->get_instance( 'contact-form' );
        $addon_settings = $addon->get_data();

        $errors = array();

        if ( empty( $addon_settings['recipient_email'] ) ) {
            $errors['general'] = 'The recipient email is not set.';
        }

        if ( !empty( $errors ) ) {
            echo json_encode( compact( 'errors' ) );
            exit;
        }

        $required = array( 'sender_name', 'sender_email', 'message' );
        foreach ( $required as $req ) {
            if ( empty( $_POST[$req] ) ) {
                $errors[$req] = 'required';
            }
        }

        if ( !empty( $errors ) ) {
            echo json_encode( compact( 'errors' ) );
            exit;
        }

        if ( !\Leeflets\String::valid_email( $_POST['sender_email'] ) ) {
            $errors['sender_email'] = 'invalid';
        }

        $malicious = array( 'sender_name', 'sender_email' );
        foreach ( $malicious as $mal ) {
            if ( $this->is_malicious( $_POST[$mal] ) ) {
                $errors[$mal] = 'malicious';
            }
        }

        if ( !empty( $errors ) ) {
            echo json_encode( compact( 'errors' ) );
            exit;
        }

        $to = trim( $addon_settings['recipient_name'] . ' <' . $addon_settings['recipient_email'] . '>' );
        $from = $_POST['sender_name'] . ' <' . $_POST['sender_email'] . '>';
        $subject = empty( $addon_settings['email_subject'] ) ? $this->settings->get( 'site-meta', 'title' ) : $addon_settings['email_subject'];

        $message = '';
        if ( !empty( $addon_settings['additional_fields'] ) ) {
            $fields = explode( ',', $addon_settings['additional_fields'] );
            $fields = array_map( 'trim', $fields );
            foreach ( $fields as $field ) {
                $nicename = ucwords( str_replace( '_', ' ', $field ) );
                $message .= $nicename . ': ';
                if ( !empty( $_POST[$field] ) ) {
                    $message .= ( strpos( $_POST[$field], "\n" ) !== false ) ? "\r\n" : '';
                    $message .= $_POST[$field];
                }
                $message .= "\r\n";
            }
            $message .= "\r\n";
        }
        $message .= $_POST['message'];
        $message = wordwrap( $message, 80, "\r\n");

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "From: $from\r\n";
        $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";

        //header( 'Content-Type: text/plain' ); print_r( compact( 'to', 'subject', 'message', 'headers' ) ); exit;
        
        if ( !mail( $to, $subject, $message, $headers ) ) {
            $errors['general'] = 'For some reason the call to mail() failed. Best to contact the web host.';
            echo json_encode( compact( 'errors' ) );
            exit;
        }

        echo json_encode( array( 'success' => true ) );
        exit;
	}

    function is_malicious( $input ) {
        $bad_inputs = array( "\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:","to:","cc:" );
        foreach ( $bad_inputs as $bad_input ) {
            if ( stripos( strtolower( $input ), strtolower( $bad_input ) ) !== false ) {
                return true;
            }
        }
        return false;
    }
}
