<?php 
/*Plugin Name: MJ20 Newsletter
Description: Newsletter for Wordpress.
Version: 1.0
Author: mazen ajab
Author URI: https://www.linkedin.com/in/mazen-ajab-87630a70/
*/

add_action( 'admin_menu', 'mj_newsletter_menu' );
function mj_newsletter_menu(){

  $page_title = 'MJ Setting';
  $menu_title = 'MJ20 newsletter';
  $capability = 'manage_options';
  $menu_slug  = 'extra-newsletter-info';
  $function   = 'Newsletter_info_page';
  $icon_url   = 'dashicons-media-code';
  $position   = 6;

  add_menu_page( $page_title,
                 $menu_title,
                 $capability,
                 $menu_slug,
                 $function,
                 $icon_url,
                 $position );

  // Call update_newsletter_info function to update database
  add_action( 'admin_init', 'update_newsletter_info' );

}


function newsletter_info_page(){
?>
  <h1 style="text-align:center">MJ20 Newsletter</h1>
  <form method="post" action="options.php">
    <?php settings_fields( 'extra-newsletter-info-settings2' ); ?>
    <?php do_settings_sections( 'extra-newsletter-info-settings2' ); ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row">Receiver Email Address:</th>
      <td><input type="text" name="contact_field_info" value="<?php echo get_option('contact_field_info'); ?>"/></td>
      </tr>
	  <p> Use the Shortcode:&nbsp;&nbsp; [mj20_newsletter]</p>
    </table>
  <?php submit_button(); ?>
  </form>
<?php
}


function update_newsletter_info() {
  register_setting( 'extra-newsletter-info-settings2', 'contact_field_info' );
}


function html_newsletter_code() {
	echo '<form id="clear_form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Signup for Our Newsletter <br/><br/>';
	echo '<input class="form-control" style="width:250px;" type="email" name="nf-email" placeholder="Your Email" value="' . ( isset( $_POST["nf-email"] ) ? esc_attr( $_POST["nf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<input  class="form-control" type="hidden" name="nf-subject" value="Newsletter Request" size="40" />';
	echo '</p>';
	echo '<p><input class="btn btn-warning" type="submit" name="nf-submitted" value="Subscribe"></p>';
	echo '</form>';}
	
	
	
	
function deliver_newsletter_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['nf-submitted'] ) ) {

		// sanitize form values
		
		$email   = sanitize_email( $_POST["nf-email"] );
		$subject = sanitize_text_field( $_POST["nf-subject"] );
		$message = "Add the Following Email to Your Newsletter list:".$email."";


		// you can use administrator's email address admin_email
		$to = get_option( 'contact_field_info' );

		$headers = "From: Subscriber <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $message, $headers ) ) {
			echo '<div>';
			echo '<p style="color:green">Thank you for Subscribing to our Newsletter, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo '<p style="color:red"> Please Fill in All the Required Fields</P>';
		}
	}}
function nf_shortcode() {
	ob_start();
	deliver_newsletter_mail();
	html_newsletter_code();

	return ob_get_clean();}
add_shortcode( 'mj20_newsletter', 'nf_shortcode' );
?>
