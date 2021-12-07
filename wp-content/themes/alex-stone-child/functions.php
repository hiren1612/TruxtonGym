<?php
/**
 * Child-Theme functions and definitions
 */


add_action( 'wp', 'wpse69369_special_thingy' );
function wpse69369_special_thingy()
{
	if(isset($_SESSION['unique_token'])){

	}
	else{
		global $wpdb;
		if(is_user_logged_in()){
			$current_user = get_current_user_id();

			$token = $wpdb->get_results("SELECT `unique_token` FROM `wp_gym_member_meta` WHERE (user_id = $current_user)");
			if($token){
				$_SESSION['unique_token'] = $token[0]->unique_token;
			}
			else{
			$pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
			$_SESSION['unique_token'] = $pass;
			}
		}
		else{
		$pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
		$_SESSION['unique_token'] = $pass;
		}
	}
}

function your_function( $user_login, $user ) {
			global $wpdb;
			$token = $wpdb->get_results("SELECT `unique_token` FROM `wp_gym_member_meta` WHERE (user_id = $user->id)");
			if($token){
				$_SESSION['unique_token'] = $token[0]->unique_token;
			}
			else{
			$pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 16);
				$_SESSION['unique_token'] = $pass;
			}
}
add_action('wp_login', 'your_function', 10, 2);



function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_stylesheet_directory_uri() . '/style.css' );
	wp_register_script('custom_script', get_stylesheet_directory_uri().'/js/custom.js');
	wp_enqueue_script('custom_script');
   

}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// [dotiavatar] shoercode created
function dotiavatar_function() {
	if(is_user_logged_in())
		{
			global $current_user;
			$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);
            if($current_user->membership_level){
            
			$current_date = date("Y/m/d");
			$expire_date = date("Y-m-d", substr($current_user->membership_level->enddate, 0, 10));
			$today_time = strtotime($current_date);
			$expire_time = strtotime($expire_date);
			if ($expire_time > $today_time) {
					$orders = wc_get_orders(array(
						'customer_id' => get_current_user_id(),
						'return' => 'ids',
					));
					foreach($orders as $order_id)
					{
						$i = 0;
						$order = wc_get_order( $order_id );
						$items = $order->get_items();
						foreach ( $items as $key => $item ) {
							$product_id = $item->get_product_id();
							$terms = get_the_terms( $product_id, 'product_cat' );
                           $array_result = json_encode($terms);
                            if (strpos($array_result,'subscribr') !== false) {
                            return do_shortcode( '[kaya_qrcode_dynamic title_align="alignnone" ecclevel="L" align="alignnone"]' . $order_id . '[/kaya_qrcode_dynamic]' );
                            }
                           
                           
							if($terms[$i]->slug == 'subscribr'){
                            
								return do_shortcode( '[kaya_qrcode_dynamic title_align="alignnone" ecclevel="L" align="alignnone"]' . $order_id . '[/kaya_qrcode_dynamic]' );
						   	}
						}
						$i++;
					}
				}
			}
}
}
add_shortcode('dotiavatar', 'dotiavatar_function');

add_action('woocommerce_single_product_summary', 'remove_product_description_add_cart_button', 1 );
function remove_product_description_add_cart_button()
{
	if(is_user_logged_in())
	{
		global $current_user;

		$current_user->membership_level = pmpro_getMembershipLevelForUser($current_user->ID);

		$current_date = date("Y/m/d");
		$expire_date = date("Y-m-d", substr($current_user->membership_level->enddate, 0, 10));
		
		$today_time = strtotime($current_date);
		$expire_time = strtotime($expire_date);

		if ($expire_time > $today_time) {
			global $product;
			$category = $product->get_categories();
			if (strpos($category, 'subscribr') !== false) {
    				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			add_action( 'woocommerce_single_product_summary', 'my_extra_button_on_product_page', 30);
			}
		}
	}
}

 	// $orders = wc_get_orders(array(
    // 'customer_id' => get_current_user_id(),
    // 'return' => 'ids',
    // ));
    // foreach ($orders as $order){
    // 	$derails = new WC_Order($order);
    // 	foreach ($derails->get_items() as $item_id => $item) {
    // 	$terms = get_the_terms( $item['product_id'], 'product_cat' );
    // 	foreach($terms as $term)
    // 	{
    // 		if($term->name == 'subscribr'){
    // 			if ( has_term( 'subscribr', 'product_cat', get_the_id() ) ) {
	//         		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
	//         		add_action( 'woocommerce_single_product_summary', 'disable_button', 30 );
    // 				}
	// 			}
    // 		}
    // 	}
	// }
// }

function disable_button() {
	global $product;
	echo '<button disabled>Can not Active Multiple subscription</button>';
  }


// code for adding button
// add_action( 'woocommerce_single_product_summary', 'my_extra_button_on_product_page', 30 );

function my_extra_button_on_product_page() {
  global $product;
  echo '<button id="myModal">Extra Button</button>';
}
function pop_up() {
?>
<script>
jQuery(document).ready(function($) {
    $(".wcpa_type_paragraph" ).hide();
    jQuery("#myModal").click(function(){
        jQuery(".wcpa_type_paragraph").slideToggle();
    });
});
</script>
<?php
}
add_action( 'wp_footer', 'pop_up' );



// hide specific product category
function prefix_custom_pre_get_posts_query( $q ) {
	
	if( is_shop() || is_page('shop') ) { // set conditions here
	    $tax_query = (array) $q->get( 'tax_query' );
	
	    $tax_query[] = array(
	           'taxonomy' => 'product_cat',
	           'field'    => 'slug',
	           'terms'    => array( 'pendants' ), // set product categories here
	           'operator' => 'NOT IN'
	    );
	
	
	    $q->set( 'tax_query', $tax_query );
	}
}
// add_action( 'woocommerce_product_query', 'prefix_custom_pre_get_posts_query' );

// add_action ('woocommerce_email_order_details', 'including_global_email_data', 2, 4 );
// function including_global_email_data( $order, $sent_to_admin, $plain_text, $email ){
//     // Set global email variable

// 	$customer_email = $order->get_billing_email();
//     $GLOBALS['email_data'] = array( 'email' => $email, 'admin' => $customer_email,'orderid' => $order->id);
// }

// add_filter ('woocommerce_email_footer_text', 'custom_email_footer_text', 20, 1 );
// function custom_email_footer_text( $footer_text ){
//     // Get global variables
//     $refNameGlobalsVar = $GLOBALS;
//     $sent_to_admin = $refNameGlobalsVar['email_data']['admin'];
//     $email         = $refNameGlobalsVar['email_data']['email'];
// 	$order_id = $refNameGlobalsVar['email_data']['orderid'];

//     // Only for admin "New order" email notification
//     if( $email->id === 'new_order' ) // Or also:  if( $sent_to_admin )
//     {
//     $footer_text = "https://chart.googleapis.com/chart?cht=qr&chl='".order_id."'&chs=350&choe=UTF-8";
//     }
//     return $footer_text;
// }

// add_action( 'woocommerce_email_after_order_table', 'mm_email_after_order_table', 10, 4 );
// function mm_email_after_order_table( $order, $sent_to_admin, $plain_text, $email ) { 
//        echo "<img src='https://chart.googleapis.com/chart?cht=qr&chl=".$order->id."&chs=350&choe=UTF-8'>";
// }

// function add_content_after_addtocart() {

// 	$current_product_id = get_the_ID();
	
// 	$product = wc_get_product( $current_product_id );
	
// 	$checkout_url = wc_get_checkout_url();
	
// 	if( $product->is_type( 'simple' ) ){
// 	echo '<a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="buy-now button">Buy Now</a>"';
// 	}
// 	}
// add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );

add_action( 'woocommerce_thankyou', 'misha_poll_form', 4 );

function misha_poll_form( $order_id ) {
	 

	 	$order = wc_get_order( $order_id );
		$items = $order->get_items();
		global $wpdb; 


		foreach ( $items as $item ) {
    		$product_id = $item->get_product_id();
    		$terms = get_the_terms( $product_id, 'product_cat' );
    		foreach ($terms as $term){


    		if($term->name == 'subscribr'){

    		echo do_shortcode( '[kaya_qrcode_dynamic title_align="alignnone" ecclevel="L" align="alignnone"]' . $order_id . '[/kaya_qrcode_dynamic]' );

    		$tablename=$wpdb->prefix.'gym_member_meta'; 
			$current_user = get_current_user_id();
			$type_pack = get_field( "couple_plan", $product_id);

			$wpdb->update($tablename, array('user_id'=>$current_user, 'order_id'=>$order_id,'type' =>$type_pack), array('unique_token'=>$_SESSION['unique_token']));

    		}
            
            if($term->name == 'sessions'){

    		$session_count = get_field( "session_count", $product_id);

	  		$tablename=$wpdb->prefix.'session_member_meta'; 
			$current_user = get_current_user_id();

			$wpdb->update($tablename, array('user_id'=>$current_user, 'order_id'=>$order_id,'count' => $session_count,'is_purchased' => '1'), array('unique_token'=>$_SESSION['unique_token']));

    		}
            
    	}
    }	
}
// global function which gets data from wp_gym_member_meta.
function get_gym_meta($metakey,$couple = NULL,$order_id = NULL){
	GLOBAL $wpdb;
	
	$current_user = get_current_user_id();
	$result = $wpdb->get_results("SELECT `".$metakey."` FROM wp_gym_member_meta WHERE user_id='{$current_user}'");
	if($order_id){
		$result = $wpdb->get_results("SELECT `".$metakey."` FROM wp_gym_member_meta WHERE order_id='{$order_id}'");
	}

	if($couple)
	{
	$result = $wpdb->get_results("SELECT `".$metakey."` FROM wp_gym_member_meta WHERE user_id='{$current_user}' AND is_couple=true");
	if(isset($result)){
		$result = json_decode( json_encode($result), true);	
		return $result[0][$metakey];
	}
	else{
		return;
	}
}
	
		$result = json_decode( json_encode($result), true);	
		return $result[0][$metakey];
}
function member_function() {

echo "<div class='woocommerce'><table>
<thead>
	<tr>
		<th>Question</th>
		<th>Answer</th>
	</tr>
</thead>
<tbody>
<tr>
	<td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td>
	<td>".get_gym_meta('heart_condition')."</td>
</tr>
<tr>
	<td>Do you feel fain in your chest when you do physical activity?</td>
	<td>".get_gym_meta('chest_pain')."</td>
</tr>
	<tr>
	<td>In the past month, have you had chest pain when you were not doing physical activity?</td>
	<td>".get_gym_meta('old_chest_pain')."</td>
<tr>
	<td>Do you lose balance because of dizziness or do you ever lose consciousness?</td>
	<td>".get_gym_meta('lose_consciousness')."</td>
</tr>
<tr>
	<td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td>
	<td>".get_gym_meta('joint_problem')."</td>
</tr>
<tr>
	<td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td>
	<td>".get_gym_meta('blood_pressure')."</td>
</tr>
<tr>
	<td>Are you currently pregnant?</td>
	<td>".get_gym_meta('currently_pregnant')."</td>
</tr>
</tr>
</tbody>
</table>
</div>";
}

add_shortcode('Membership-profile', 'member_function');

add_action( 'wp_ajax_my_ajax_action', function(){
    $order_id = isset( $_POST['id'] ) ? $_POST['id'] : 'N/A';
    date_default_timezone_set("Asia/Kolkata");
    $starting_time = "3:00 pm";
	$end_time = "5:00 pm";
    $order = wc_get_order( $order_id );

		if($order)
			{
				$product = $order->get_items();
				$member_id = $order->get_user_id();
				$membership_level = pmpro_getMembershipLevelForUser($member_id);
                
				$current_date = date("Y/m/d");
				$expire_date = date("Y-m-d", substr($membership_level->enddate, 0, 10));
				$today_time = strtotime($current_date);
				$expire_time = strtotime($expire_date);
				$valid = 'invalid';
					if ($expire_time > $today_time) 
						{
							$orders = wc_get_orders(array(
							'customer_id' => $order->get_user_id(),
							'return' => 'ids',
							));
							foreach($orders as $order_id)
								{
									$i = 0;
									$order = wc_get_order( $order_id );
									$items = $order->get_items();
									
										foreach ( $items as $key => $item ) 
											{
												$product_id = $item->get_product_id();
												$terms = get_the_terms( $product_id, 'product_cat' );	
												if($terms[$i]->slug == 'off-peak')
													{							
														$current_time = date("h:i a");
														$date1 = DateTime::createFromFormat('h:i a', $current_time);
														$date2 = DateTime::createFromFormat('h:i a', $starting_time);
														$date3 = DateTime::createFromFormat('h:i a', $end_time);
															if ($date1 > $date2 && $date1 < $date3)
																{
				 													$valid = 'valid';
                                                                    $packtype = 'off-peack';
																}
	        												else
	        													{
	        														$valid = 'No permitt time';
                                                                    $packtype = 'off-peack';
	       														}
	        											$i++;
	        										}
						
												else
													{
														$valid = 'valid';
                                                        $packtype = 'normal-peack';
													}
											}
									}
	wp_send_json_success( array( 
    'detail' => $valid, 
    'name' => $membership_level->name, 
    'end_date' => $expire_date,
    'pack_type' => $packtype,
    'first_name' => $order->get_billing_first_name(),
    'last_name' => $order->get_billing_last_name(),
    
), 200 );
	}
	else{
		wp_send_json_success( array( 
    'detail' => 'no valid pack', 
    'name' => 'no pack name', 
    'end_date' => 'pack is not active',
    'pack_type' => 'no pack type',
), 200 );
	}
}
else{
	wp_send_json_success( array( 
    'detail' => 'Order id not found', 
    'name' => 'no pack name', 
    'end_date' => 'pack is not active',
    'pack_type' => 'no pack type',
), 200 );
}


} );

add_filter ( 'woocommerce_account_menu_items', 'example_forum_link' );
function example_forum_link( $menu_links ){
  
    $new = array( 'member-detail' => 'Member Detail');
  
    // array_slice() is good when you want to add an element between the other ones
    $menu_links = array_slice( $menu_links, 0, 1, true ) 
    + $new 
    + array_slice( $menu_links, 1, NULL, true );
  
    return $menu_links;
}

add_filter( 'woocommerce_get_endpoint_url', 'forum_example_hook_endpoint', 10, 4 );
function forum_example_hook_endpoint( $url, $endpoint, $value, $permalink ){
  

  		$user = wp_get_current_user();
	if ( in_array( 'trainer', (array) $user->roles ) ) {
    	if( $endpoint === 'my-sessions' ) {
        	$url = site_url() .'/'.'my-sessions';
    	}
	}
    
    if( $endpoint === 'member-detail' ) {
        $url = site_url() .'/my-account/'.'member-detail';
    }
    
    
    return $url;
}

add_action( 'woocommerce_before_order_itemmeta', 'so_32457241_before_order_itemmeta', 10, 3 );
function so_32457241_before_order_itemmeta( $item_id, $item, $_product ){
	$order_id = $item['order_id'];
	$order = new WC_Order( $order_id );
	echo "<table><thead><tr><th>Question</th><th>Answer</th></tr></thead>
	<tbody><tr><td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td><td>". get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Do you feel fain in your chest when you do physical activity?</td><td>No</td></tr>
		<tr><td>In the past month, have you had chest pain when you were not doing physical activity?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Do you lose balance because of dizziness or do you ever lose consciousness?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>Are you currently pregnant?</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
		<tr><td>I have read, understood and completed this questionnaire. Any questions I had were answered to my full satisfaction.</td><td>".get_gym_meta('heart_condition',$couple = NULL,$order_id = $item['order_id'])."</td></tr>
	</tbody></table>
	<br>
	<h3>Print QR</h3>
	<div class='print_area' id='print_area'>
	<img src='https://chart.googleapis.com/chart?cht=qr&chl=".$item['order_id']."&chs=350&choe=UTF-8' style='width:150px;'/>
	<p>First Name : <span>". $order->get_billing_first_name()."</span></p>
	<p>Last Name : <span>".$order->get_billing_last_name()."</span></p>
	<p>Email : <span>".$order->get_billing_email()."</span></p>
	<p>Phone : <span>".$order->get_billing_phone()."</span></p>
	<p>Plan : <span>".$item['name']."</span></p>
	</div>
		<input type='button' id='print' value='Print' style='background:#2271b1; color:#fff; border:none;cursor:pointer;padding:10px'>";
// 	echo"<pre>"; print_r($item); 
// 	$customer_id = $order->get_customer_id();
// 	$user_id = $order->get_user_id();
	//$user = $order->get_user();
	//print_r($user);
	//echo $is = $user->display_name;
	//echo $is = $user->user_email;
// 	die();
}

add_action( 'admin_enqueue_scripts', 'load_custom_admin_script' );
function load_custom_admin_script() {
    wp_enqueue_script('custom_admin_js_script', get_stylesheet_directory_uri().'/js/custom-admin_script.js', array('jquery'), '5.8.6');
}






/*
add_filter('wp_nav_menu_items', 'login_logout',10,2);
function login_logout() {
    if (is_user_logged_in()) : ?>
    <li><a role="button" href="<?php echo wp_logout_url('/'); ?>">Log Out</a></li>
   <?php  else :  ?>
    <li><a role="button" href="<?php echo wp_login_url(get_permalink()); ?>">Log In</a></li>
   <?php endif; }*/
   
   
  
  add_role('trainer', __(
   'trainer'),
   array(
       'read'            => true, // Allows a user to read
       'create_posts'      => true, // Allows user to create new posts
       'edit_posts'        => true, // Allows user to edit their own posts
       'edit_others_posts' => true, // Allows user to edit others posts too
       'publish_posts' => true, // Allows the user to publish posts
       'manage_categories' => true, // Allows user to manage post categories
       )
);
/*add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
if (is_user_logged_in()) {
$items .= '<li><a href="'.  home_url('my-account')  .'">My Account</a></li>';
}
return $items;
}*/
add_filter( 'wp_nav_menu_items', 'add_loginout_link', 10, 2 );
function add_loginout_link( $items, $args ) {
    if (is_user_logged_in()) {
		$items .= '<li><a href="'. site_url('my-account') .'">My Account</a></li>';
        $items .= '<li><a href="'. wp_logout_url() .'">Log Out</a></li>';
    }
    elseif (!is_user_logged_in()) {
        $items .= '<li><a href="'. site_url('login') .'">Log In</a></li>';
    }
    return $items;
}

add_filter ( 'woocommerce_account_menu_items', 'example_forum_link2' );
function example_forum_link2( $menu_links ){
  
  
  	$user = wp_get_current_user();
	if ( in_array( 'trainer', (array) $user->roles ) ) {
    	 $new = array( 'my-sessions' => 'My Sessions');
            $menu_links = array_slice( $menu_links, 0, 1, true ) 
            + $new 
            + array_slice( $menu_links, 1, NULL, true );
	}
    return $menu_links;
}

add_filter ( 'woocommerce_account_menu_items', 'add_cam_menu', 40 );
function add_cam_menu( $menu_links ){
     
    $menu_links = array_slice( $menu_links, 0, 5, true ) 
    + array( 'profile-picture' => 'profile picture' )
    + array_slice( $menu_links, 5, NULL, true );
     
    return $menu_links;
}

add_action( 'init', 'misha_add_endpoint' );
function misha_add_endpoint() {
    add_rewrite_endpoint( 'profile-picture', EP_PAGES );
}

add_action( 'woocommerce_account_profile-picture_endpoint', 'misha_my_account_endpoint_content' );


add_action( 'woocommerce_account_profile-picture_endpoint', 'misha_my_account_endpoint_content' );
function misha_my_account_endpoint_content() {



	if(get_user_meta(get_current_user_id(),'wp_user_avatar')){
    
    $media_id = get_user_meta(get_current_user_id(),'wp_user_avatar');
   echo wp_get_attachment_image($media_id[0], $size = 'thumbnail', $icon = false, $attr = '' );
    }

	else{
    echo "<form method='POST' enctype='multipart/form-data'>
    <input type='file' name='file' >
    <input type='submit' value='submit' name='user_file_submit'>
    </form>
    ";
    }
}

function fn_upload_file() {
    if ( isset($_POST['user_file_submit']) ) {
     $upload_dir = wp_upload_dir();
    
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/product-images';
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
             
           $success = move_uploaded_file($_FILES['file']['tmp_name'], $user_dirname .'/'. $filename);
           
       $file= $upload_dir['baseurl'].'/product-images/'.$filename;

        $wp_filetype = wp_check_filetype( $filename, null );
        $attachment = array(
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => sanitize_file_name( $filename ),
          'post_content' => '',
          'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file );
		
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, ABSPATH.'wp-content/uploads/product-images/'.$filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        
        global $wpdb;
         update_user_meta(get_current_user_id(), $wpdb->get_blog_prefix() . 'user_avatar', $attach_id);
           
        }
    }
}
add_action('init', 'fn_upload_file');


 add_action( 'admin_menu', 'add_google_to_admin' );
function add_google_to_admin() {
add_menu_page( 'add_google_to_admin', 'Qr-code Scanner', 'read', '/qr-scanner/', '', 'dashicons-camera', 9);
}


apply_filters( 'pmpro_memberslist_extra_cols', ['Username' =>'hello'] );

add_Action('pmpro_memberslist_extra_cols_body','pp_memberslist_extra_cols_body',10,1);
function pp_memberslist_extra_cols_body(){
  echo 'hello';
}




?>