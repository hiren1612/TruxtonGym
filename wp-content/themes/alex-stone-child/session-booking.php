<?php 
/* Template Name: session-booking Template */ 

if (isset($_POST['session_form_submit'])){
global $wpdb;
 	if(is_user_logged_in()){
 		$curren_user = get_current_user_id();
 	}
 	else{
 		$curren_user = '';
 	}

    $tablename=$wpdb->prefix.'session_member_meta';
    $data=array(
        'unique_token' => $_SESSION['unique_token'], 
        'user_id' => $curren_user,
        'heart_condition' => $_POST['heart_condition'],
        'chest_pain' => $_POST['chest_pain'], 
        'old_chest_pain' => $_POST['old_chest_pain'], 
        'lose_consciousness' => $_POST['lose_consciousness'],
        'joint_problem' => $_POST['joint_problem'], 
        'blood_pressure' => $_POST['blood_pressure'], 
        'currently_pregnant' => $_POST['currently_pregnant'],
        'terms_conditions' => $_POST['terms_conditions'],
        'trainer_user_id' => $_POST['user_id']
        );
     $reslut = $wpdb->insert( $tablename, $data);
	 WC()->cart->empty_cart();
     WC()->cart->add_to_cart( $_POST['product_id']);
     wp_safe_redirect(site_url().'/checkout');
}
get_header();



// Get featured products by category. on this case its "shirts" which is the slug of the category.
$query_args = array(
    'category' => array( 'sessions' ),
    'posts_per_page'        => -1,
);
$products = wc_get_products( $query_args );


$args = array(
    'role'    => 'Trainer',
    'orderby' => 'last_name',
    'order'   => 'ASC'
);
$users = get_users( $args );

?>
<style>
.membership-form .input-group {
    display: flex;
	margin:5px 0 15px 0;
}
	.membership-form .radio-label{
		margin-right:30px;
		padding-left: 15px;
		cursor: pointer;
		display: flex;
		align-items: center;
		text-transform: capitalize;
	}
	form.membership-form {
    padding-left: 20px;
}
	.membership-form .select_container {
    margin: 30px 0;
 }
	.membership-form .select_container select{
		font-size:14px;
	}
	.membership-form .radio-input {
    display: flex;
    appearance: none;
    position: relative;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid  #a0342c;
    align-items: center;
    justify-content: center;
}
	.membership-form .radio-input:checked:after {
    content: "";
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 10px;
    height: 10px;
}
	.membership-form .radio-label:before{
		content: unset !important;
	}
	.membership-form .input-group{
		align-items: center;
	}
</style>
<div class="vc_col-sm-6">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post();
the_content();
endwhile; endif;?>
</div>
<div class="vc_col-sm-6">
<div class="woocommerce-info" style="display:none">
		Please fill Required fields</div>
<form class="membership-form" name="product_form" action="" method="POST" onsubmit="return validateForm()">
	<span class="form-text"><span class="required">*</span>Do you have a heart condition that you should only do physical activity recommended by a doctor?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="heart-condition-yes" name="heart_condition" value="yes" >
	<label class="radio-label" for="heart-condition-yes">yes</label><br>
	<input class="radio-input" type="radio" id="heart-condition-no" name="heart_condition" value="no" >
	<label class="radio-label" for="heart-condition-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>Do you feel pain in your chest when you do physical activity?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="chest-pain-yes" name="chest_pain" value="yes" >
	<label class="radio-label" for="chest-pain-yes">yes</label><br>
	<input class="radio-input" type="radio" id="chest-pain-no" name="chest_pain" value="no" >
	<label class="radio-label" for="chest-pain-no">no</label><br>
	</div>
<span class="form-text"><span class="required">*</span>In the past month, have you had chest pain when you were not doing physical activity?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="old-chest-pain-yes" name="old_chest_pain" value="yes" >
	<label class="radio-label" for="old-chest-pain-yes">yes</label><br>
	<input class="radio-input" type="radio" id="old-chest-pain-no" name="old_chest_pain" value="no" >
	<label class="radio-label" for="old-chest-pain-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>Do you lose balance because of dizziness or do you ever lose consciousness?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="lose-consciousness-yes" name="lose_consciousness" value="yes" >
	<label class="radio-label" for="lose-consciousness-yes">yes</label><br>
	<input class="radio-input" type="radio" id="lose-consciousness-no" name="lose_consciousness" value="no" >
	<label class="radio-label" for="lose-consciousness-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="joint-problem-yes" name="joint_problem" value="yes" >
	<label class="radio-label" for="joint-problem-yes">yes</label><br>
	<input class="radio-input" type="radio" id="joint-problem-no" name="joint_problem" value="no" >
	<label class="radio-label" for="joint-problem-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="blood-pressure-yes" name="blood_pressure" value="yes" >
	<label class="radio-label" for="blood-pressure-yes">yes</label><br>
	<input class="radio-input" type="radio" id="blood-pressure-no" name="blood_pressure" value="no" >
	<label class="radio-label" for="blood-pressure-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>Are you currently pregnant?</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="currently-pregnant-yes" name="currently_pregnant" value="yes" >
	<label class="radio-label" for="currently-pregnant-yes">yes</label><br>
	<input class="radio-input" type="radio" id="currently-pregnant-no" name="currently_pregnant" value="no" >
	<label class="radio-label" for="currently-pregnant-no">no</label><br>
	</div>

<span class="form-text"><span class="required">*</span>I have read, understood and completed this questionnaire. Any questions I had were answered to my full satisfaction.</span>
	<div class="input-group">
	<input class="radio-input" type="radio" id="terms-conditions-yes" name="terms_conditions" value="yes" >
	<label class="radio-label" for="terms-conditions-yes">yes</label><br>
	<input class="radio-input" type="radio" id="terms-conditions-no" name="terms_conditions" value="no" >
	<label class="radio-label" for="terms-conditions-no">no</label><br>
	</div>
    <span class="required">*</span><label class="" for="product_id">Choose Plan</label>
	<select name="product_id"  id="product_id">'
    <option value="" disabled selected>Select Session</option>
		<?php 
		foreach($products as $key => $product){
			?>
			<option value="<?php echo $product->get_id();?>" <?php echo ($_GET['level'] == $product->get_name()) ? 'selected' : ''; ?> data-price="<?php echo $product->get_regular_price(); ?>"><?php echo $product->get_name(); ?></option>
        <?php
		}
        ?>	
	</select>
   	<p>Price: <span class="pack-price"></span></p>

<span class="required">*</span><label class="" for="product_id">Choose Trainer</label>
   	<select name="user_id"  id="user_id">
	<option value="" disabled selected>Select your Trainer</option>
        <?php
		foreach($users as $user){
			?>
			<option value="<?php echo $user->ID ?>" data-profileimage="<?php  get_avatar_url( $user->ID, $args = null )?>"><?php echo $user->user_login ?></option>
        <?php
		}
        ?>	
	</select>

	<input type="submit" name="session_form_submit" value="BUY NOW">
</form>
</div>


<?php 
get_footer();
?>
<script>

jQuery(document).ready(function(){
		jQuery('#product_id').on('change', function() {
        var price = jQuery(this).find(':selected').attr('data-price');
  			jQuery('.pack-price').text('€ '+price);
});
});
function validateForm() {
  let heart_condition = document.forms["product_form"]["heart_condition"].value;
  let chest_pain = document.forms["product_form"]["chest_pain"].value;
  let old_chest_pain = document.forms["product_form"]["old_chest_pain"].value;
  let lose_consciousness = document.forms["product_form"]["lose_consciousness"].value;
  let joint_problem = document.forms["product_form"]["joint_problem"].value;
  let blood_pressure = document.forms["product_form"]["blood_pressure"].value;
  let currently_pregnant = document.forms["product_form"]["currently_pregnant"].value;
  let terms_conditions = document.forms["product_form"]["terms_conditions"].value;
    if (heart_condition == "" || chest_pain == "" || old_chest_pain == "" || lose_consciousness == "" || joint_problem == "" || blood_pressure == "" || currently_pregnant == "" || terms_conditions == "") {
    jQuery('.woocommerce-info').css('display','block');
    jQuery("html, body").animate({ scrollTop: 0 }, "slow");
	setTimeout(function() {
    jQuery('.woocommerce-info').css('display','none');
  }, 5000);   
    return false;
  }
}

</script>