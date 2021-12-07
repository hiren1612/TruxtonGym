<?php 
/* Template Name: session-details Template */ 
get_header();


if(isset($_POST['session_submit'])){

$i = 0;
foreach ($_POST as $param_name => $param_val) {
    $i++;
}

$remaining_session = $i-2;

global $wpdb; 
$tablename=$wpdb->prefix.'session_member_meta'; 
$wpdb->update($tablename, array('remaining_session'=>$remaining_session), array('user_id'=>$_POST['session-user']));




}
?>
<div class="vc_col-sm-6">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
the_content();
endwhile; endif;?>
</div>
<?php







$current_user = wp_get_current_user();


global $wpdb;
$sessions = $wpdb->get_results("SELECT * FROM `wp_session_member_meta` WHERE (trainer_user_id = $current_user->ID) and (is_purchased = '1')");
echo $current_user->ID;
if($sessions){

foreach($sessions as $session){

?>

<style>
input[type="radio"], input[type="checkbox"]{
display:block !important;
}

</style>

<div class="vc_col-sm-6">
<form class="membership-form" method="POST">
<?php 


for ($i = 1; $i <= $session->count; $i++) {
  
  ?>
	<div class="input-group">
<input type="radio" class="radio-input" name="session<?php echo $i; ?>" value="yes" <?php echo ($session->remaining_session >= $i) ?'checked="checked"':'';?>><?php echo "session no ".$i;?>
	</div>
  <?php
}
?><br/>

<input type="hidden" id="session-user" name="session-user" value="<?php echo $session->user_id  ?>">

<input type="submit" name="session_submit" value="update">
</form>
</div>

<?php
}
}
else{
echo "You Dont have any sessions";
}

get_footer();
?>