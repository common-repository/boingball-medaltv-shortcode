<?php
/**
 * Plugin Name: BoingBall Medal.TV ShortCode Plugin
 * Plugin URI: https://www.boingball.uk/medaltv-get-latest-posts
 * Description: A plugin to connect to the Medal.TV API to get the latest posts from a UserID - Use the shortcode in a page [medaltv videos=x title=0/1 grid=0/1]
 * Version: 1.2
 * Author: BoingBall
 * Author URI: https://www.boingball.uk/
 * Usage : [MedalTV videos=x title=0 or 1 grid=0 or 1] - Default Values is 1 video with Grid turned off and Title turned on
  */
  
  // The Settings Menu - Wordpress Admin > Settings > BB MedalTV Plugin
function add_BBMedaltv_plugin_menu() {
	add_submenu_page('options-general.php', 'BB MedalTV Plugin', 'BB MedalTV Plugin', 'manage_options', 'BBMedalTV-plugin', 'BBMedalTV_plugin_function');
}
add_action('admin_menu', 'add_BBMedalTV_plugin_menu');

// The Settings Options (API Key and UserID)
function BBMedalTV_settings_init() {

    register_setting( 'BBMedalTV-setting', 'BBMedalTV_settings', 'BBMedalTV_ValidateInput' );

    add_settings_section('BBMedalTV-plugin-section', __( 'BBMedalTV Section', 'BBMedalTV-plugin' ), 'BBMedalTV_settings_section_callback', 'BBMedalTV-setting' );

    add_settings_field( 'BBMedalTV_text_1', __( 'MedalTV Private API Key - https://docs.medal.tv/api.html :' , 'BBMedalTV-plugin' ), 'BBMedalTV_text_1', 'BBMedalTV-setting', 'BBMedalTV-plugin-section' );
    add_settings_field( 'BBMedalTV_text_2', __( 'Medal UserID:', 'BBMedalTV-plugin' ), 'BBMedalTV_text_2', 'BBMedalTV-setting', 'BBMedalTV-plugin-section' );
	
}
add_action( 'admin_init', 'BBMedalTV_settings_init' );

// The Settings Title Page
function BBMedalTV_settings_section_callback(  ) {
    echo __( 'Medal.TV - Get Latest User Videos ShortCode by BoingBall - Settings', 'BBMedalTV-plugin' );
}

//Function to validate the input on save - if theres any HTML in there it strips the values out
function BBMedalTV_ValidateInput( $input ) {
	// Create our array for storing the validated options
    $output = array();
     
    // Loop through each of the incoming options
    foreach( $input as $key => $value ) {
         
        // Check to see if the current option has a value. If so, process it.
        if( isset( $input[$key] ) ) {
         
            // Strip all HTML and PHP tags and properly handle quoted strings
            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
             
        } // end if
         
    } // end foreach
     
    // Return the array processing any additional functions filtered by this action
    return apply_filters( 'BBMedalTV_ValidateInput', $output, $input );
}
// The Settings Options values
function BBMedalTV_text_1(){
    $options = get_option( 'BBMedalTV_settings' ); ?>

    <input type='text' name='BBMedalTV_settings[BBMedalTV_text_1]' value='<?php echo esc_textarea( $options["BBMedalTV_text_1"] ); ?>'> <?php
}
function BBMedalTV_text_2(){
    $options = get_option( 'BBMedalTV_settings' ); ?>

    <input type='number' name='BBMedalTV_settings[BBMedalTV_text_2]' value='<?php echo esc_textarea( $options["BBMedalTV_text_2"] ); ?>'> <?php
}
// The Settings Save Changes Button
function BBMedalTV_plugin_function(){ ?>

    <form action='options.php' method='post'> <?php
        settings_fields( 'BBMedalTV-setting' );
        do_settings_sections( 'BBMedalTV-setting' );
        submit_button(); ?>
    </form> <?php
}


// The Main Class - BBMedalTV_GetLatest_Post
class BBMedalTV_GetLatest_Post {

			function BBMedalTV_GetLatest_Post () {
			if ( !function_exists('add_shortcode') ) return;
			
			// Register the shortcode [MedalTV]
			add_shortcode( 'medaltv' , array(&$this, 'shortcode_medaltv') );
			}

			function shortcode_medaltv($atts){

			//Extrace the Atts from the Shortcode and if Video and Title varible not set - uses default values (1)
			$atts = shortcode_atts(array(
											'videos'   => '1',
											'title'   => '1',
											'grid'	=> '0',
										), $atts);
								
			$videos = $atts['videos'];
			
			//** APIKEY and USERID needs to be filled in for this plugin to work
			//$apikey = Set this in BB Medal TV Plugin Settings Page - Medal Private API Key - get yours from https://docs.medal.tv/api.html
			//$userid = Set this in BB Medal TV Plugin Settings Page - Medal UserID you want to show - Get this by visting your medal page the URL will show the number
			
			//Read the Setting Options back
			$options = get_option( 'BBMedalTV_settings' );
			//Fetch the API Key and UserID from the Settigns Menu
			$apikey = $options["BBMedalTV_text_1"];
			$userid = $options["BBMedalTV_text_2"];

			
			//Medals.TV Latest Videos API URL - Pass the USERID and How Many Videos we want to fetch
			$url = 'https://developers.medal.tv/v1/latest?userId='. $userid . '&limit='. $videos .'';
			
			//Setup the connection request Header with the APIKEY
			$args = array(
			'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $apikey )
								)
			);
			
			// Now, lets try the HTTP request to Medal.TV			
			$response = wp_remote_get( $url, $args );
			$http_code = wp_remote_retrieve_response_code( $response );

			//Retrieve the MedalTV Response and but it in $body
			$body = wp_remote_retrieve_body( $response );
			
			//As this response is JSON we need to decode this
			$data = json_decode($body, true);
			
			//Check if GridFormat has been select, if so create a HTML Table at the start of the iframe
				if ( $atts['grid']==1){
				$iframe = $iframe . '<table>';
				$gridcounter==0;
				}
			//for loop to create the HTML IFrame Output with the clips found in the search above
			for ($x = 0; $x <= $videos-1; $x++) {
				//This is manually Generating iFrames HTML code to keep displaying videos
				
				//Check for GridFormat and start building the Table Rows and Table Data
				if ( $atts['grid']==1 && $gridcounter==0)
					$iframe = $iframe . '<tr><td>';
				if ( $atts['grid']==1 && $gridcounter==1){
					$iframe = $iframe . '<td>';
					$gridcounter = 3;
				}
				
				//If title=1 or not set, Grab the Title of the video and add this to the IFrame String
				if ( $atts['title']==1 ) 
					$iframe = $iframe . '<h3><b>' . $data['contentObjects'][$x]['contentTitle'] . '</b></h3>';
				//Add the Video embed to the IFrame output	
				$iframe = $iframe . '<iframe width=\'640\' height=\'360\' src=\'' . $data['contentObjects'][$x]['directClipUrl'] . '?loop=1&autoplay=0&cta=1\' frameborder=\'0\' allow=\'autoplay\' allowfullscreen class=\'medal-clip\' id=\'contentId-5042841\'></iframe></br>';
				
				//Chech for Gridormat and Work on how to end or start the next table, layout is 2 videos in a row
				if ( $atts['grid']==1 && $gridcounter==0){
					$iframe = $iframe . '</td>';
					$gridcounter = 1;
					}
				if ( $atts['grid']==1 && $gridcounter==3){
					$iframe = $iframe . '</td></tr>';
					$gridcounter = 0;
					}
			}
			//Check if Gridformat was on and end the HTML Table
			if ( $atts['grid']==1)
					$iframe = $iframe . '</table>';			
			//Once the output is completed - return the $iframe content to Wordpress
			return $iframe;

			}

}
// Start this plugin once all other plugins are fully loaded
add_action( 'plugins_loaded', create_function( '', 'global $BBMedalTV_GetLatest_Post; $BBMedalTV_GetLatest_Post = new BBMedalTV_GetLatest_Post();' ) );

?>