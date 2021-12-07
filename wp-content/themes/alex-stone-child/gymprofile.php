<?php 
/* Template Name: GymProfile Template */ 
get_header();
if (isset($_POST['update-form'])){
global $wpdb;
 	if(is_user_logged_in()){
 		$curren_user = get_current_user_id();
 	}
 	else{
 		$curren_user = '';
 	}

    $tablename=$wpdb->prefix.'gym_member_meta';

    $data=array(
        'unique_token' => $_SESSION['unique_token'], 
        'user_id' => $curren_user,
        'heart_condition' => $_POST['heart_condition'],
        'chest_pain' => $_POST['chest_pain'], 
        'old_chest_pain' => $_POST['old_chest_pain'], 
        'lose_consciousness' => $_POST['lose_consciousness'],
        'joint_problem' => $_POST['joint_problem'], 
        'blood_pressure' => $_POST['blood_pressure'], 
        'currently_pregnant' => $_POST['currently_pregnant']);
        // 'terms_conditions' => $_POST['terms_conditions'], 
        // 'signature' => $_POST['signature'], 
        // 'user_file' => $_POST['user_file']);

	$wpdb->update($tablename, $data, array('unique_token'=>$_SESSION['unique_token']));
}


if (isset($_POST['update-couple-form'])){

global $wpdb;
 	if(is_user_logged_in()){
 		$curren_user = get_current_user_id();
 	}
 	else{
 		$curren_user = '';
 	}

    $tablename=$wpdb->prefix.'gym_member_meta';

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
    		'is_couple' => '1');
        // 'terms_conditions' => $_POST['terms_conditions'], 
        // 'signature' => $_POST['signature'], 
        // 'user_file' => $_POST['user_file']);

  $result = $wpdb->get_results("SELECT * FROM wp_gym_member_meta WHERE user_id='{$curren_user}' AND is_couple=true");


  if($result){
  	 $wpdb->update($tablename, $data, array('user_id'=>$curren_user,'is_couple'=>true));
  }
  else{
  	 $wpdb->insert($tablename, $data);
  }
}

?>
<style>

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 9% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}




.modal2 {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content2 {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close2 {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close2:hover,
.close2:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>

<table>
<thead>
	<tr>
		<th>Question</th>
		<th>Answer</th>
	</tr>
</thead>
<tbody>
<tr>
	<td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td>
	<td><?php echo get_gym_meta('heart_condition'); ?></td>
</tr>
<tr>
	<td>Do you feel fain in your chest when you do physical activity?</td>
	<td><?php echo get_gym_meta('chest_pain'); ?></td>
</tr>
<tr>
	<td>In the past month, have you had chest pain when you were not doing physical activity?</td>
	<td><?php echo get_gym_meta('old_chest_pain'); ?></td>
</tr>
<tr>
	<td>Do you lose balance because of dizziness or do you ever lose consciousness?</td>
	<td><?php echo get_gym_meta('lose_consciousness'); ?></td>
</tr>
<tr>
	<td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td>
	<td><?php echo get_gym_meta('joint_problem'); ?></td>
</tr>
<tr>
	<td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td>
	<td><?php echo get_gym_meta('blood_pressure'); ?></td>
</tr>
<tr>
	<td>Are you currently pregnant?</td>
	<td><?php echo get_gym_meta('currently_pregnant'); ?></td>
</tr>
</tbody>
</table>

<button id="myBtn">Update Detail</button>
<br>
<br>


<?php 

$type = get_gym_meta('type');
if($type=='1')
{
?>
<table>
<thead>
	<tr>
		<th>Question</th>
		<th>Answer</th>
	</tr>
</thead>
<tbody>
<tr>
	<td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td>
	<td><?php echo get_gym_meta('heart_condition','couple'); ?></td>
</tr>
<tr>
	<td>Do you feel fain in your chest when you do physical activity?</td>
	<td><?php echo get_gym_meta('chest_pain','couple'); ?></td>
</tr>
<tr>
	<td>In the past month, have you had chest pain when you were not doing physical activity?</td>
	<td><?php echo get_gym_meta('old_chest_pain','couple'); ?></td>
</tr>
<tr>
	<td>Do you lose balance because of dizziness or do you ever lose consciousness?</td>
	<td><?php echo get_gym_meta('lose_consciousness','couple'); ?></td>
</tr>
<tr>
	<td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td>
	<td><?php echo get_gym_meta('joint_problem','couple'); ?></td>
</tr>
<tr>
	<td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td>
	<td><?php echo get_gym_meta('blood_pressure','couple'); ?></td>
</tr>
<tr>
	<td>Are you currently pregnant?</td>
	<td><?php echo get_gym_meta('currently_pregnant','couple'); ?></td>
</tr>
</tbody>
</table>


<button id="myBtn2">Update Detail</button>


<?php
}

?>


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <form method="POST">
    <table>
<thead>
	<tr>
		<th>Question</th>
		<th>Yes</th>
		<th>No</th>
	</tr>
</thead>
<tbody>
	
<tr>
	<td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td>
	<td><input class="radio-input" type="radio" id="heart-condition-yes" name="heart_condition" value="yes" <?php echo (get_gym_meta('heart_condition') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input class="radio-input" type="radio" id="heart-condition-no" name="heart_condition" value="no" <?php echo (get_gym_meta('heart_condition') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you feel fain in your chest when you do physical activity?</td>
	<td><input class="radio-input" type="radio" id="chest-pain-yes" name="chest_pain" value="yes" <?php echo (get_gym_meta('chest_pain') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input class="radio-input" type="radio" id="chest-pain-no" name="chest_pain" value="no" <?php echo (get_gym_meta('chest_pain') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>In the past month, have you had chest pain when you were not doing physical activity?</td>
	<td><input type="radio" id="old-chest-pain-yes" name="old_chest_pain" value="yes" <?php echo (get_gym_meta('old_chest_pain') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="old-chest-pain-no" name="old_chest_pain" value="no" <?php echo (get_gym_meta('old_chest_pain') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you lose balance because of dizziness or do you ever lose consciousness?</td>
	<td><input type="radio" id="lose-consciousness-yes" name="lose_consciousness" value="yes" <?php echo (get_gym_meta('lose_consciousness') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="lose-consciousness-no" name="lose_consciousness" value="no" <?php echo (get_gym_meta('lose_consciousness') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td>
	<td><input type="radio" id="joint-problem-yes" name="joint_problem" value="yes" <?php echo (get_gym_meta('joint_problem') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="joint-problem-no" name="joint_problem" value="no" <?php echo (get_gym_meta('joint_problem') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td>
	<td><input type="radio" id="blood-pressure-yes" name="blood_pressure" value="yes" <?php echo (get_gym_meta('blood_pressure') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="blood-pressure-no" name="blood_pressure" value="no" <?php echo (get_gym_meta('blood_pressure') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Are you currently pregnant?</td>
	<td><input type="radio" id="currently-pregnant-yes" name="currently_pregnant" value="yes" <?php echo (get_gym_meta('currently_pregnant') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="currently-pregnant-no" name="currently_pregnant" value="no" <?php echo (get_gym_meta('currently_pregnant') == 'no') ?'checked="checked"':'';?>></td>
</tr>
</tbody>
<tfoot>
	<tr>
    <td><input type="submit" value="update" name="update-form"></td>
    </tr>
</tfoot>
</table>
</form>

  </div>

</div>

<div id="myModal2" class="modal2">

  <!-- Modal content -->
  <div class="modal-content2">
    <span class="close2">&times;</span>
    <form method="POST">
    <table>
<thead>
	<tr>
		<th>Question</th>
		<th>Yes</th>
		<th>No</th>
	</tr>
</thead>
<tbody>
	
<tr>
	<td>Do you have a heart condition that you should only do physical activity recommended by a doctor?</td>
	<td><input type="radio" id="heart-condition-yes" name="heart_condition" value="yes" <?php echo (get_gym_meta('heart_condition','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="heart-condition-no" name="heart_condition" value="no" <?php echo (get_gym_meta('heart_condition','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you feel fain in your chest when you do physical activity?</td>
	<td><input type="radio" id="chest-pain-yes" name="chest_pain" value="yes" <?php echo (get_gym_meta('chest_pain','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="chest-pain-no" name="chest_pain" value="no" <?php echo (get_gym_meta('chest_pain','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>In the past month, have you had chest pain when you were not doing physical activity?</td>
	<td><input type="radio" id="old-chest-pain-yes" name="old_chest_pain" value="yes" <?php echo (get_gym_meta('old_chest_pain','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="old-chest-pain-no" name="old_chest_pain" value="no" <?php echo (get_gym_meta('old_chest_pain','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you lose balance because of dizziness or do you ever lose consciousness?</td>
	<td><input type="radio" id="lose-consciousness-yes" name="lose_consciousness" value="yes" <?php echo (get_gym_meta('lose_consciousness','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="lose-consciousness-no" name="lose_consciousness" value="no" <?php echo (get_gym_meta('lose_consciousness','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Do you have bone or joint problem (for example, back, knee, or hip) that could be worsen by a change in your physical activity?</td>
	<td><input type="radio" id="joint-problem-yes" name="joint_problem" value="yes" <?php echo (get_gym_meta('joint_problem','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="joint-problem-no" name="joint_problem" value="no" <?php echo (get_gym_meta('joint_problem','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Is your doctor currently prescribing drugs for your blood pressure or heart condition?</td>
	<td><input type="radio" id="blood-pressure-yes" name="blood_pressure" value="yes" <?php echo (get_gym_meta('blood_pressure','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="blood-pressure-no" name="blood_pressure" value="no" <?php echo (get_gym_meta('blood_pressure','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
<tr>
	<td>Are you currently pregnant?</td>
	<td><input type="radio" id="currently-pregnant-yes" name="currently_pregnant" value="yes" <?php echo (get_gym_meta('currently_pregnant','couple') == 'yes') ?'checked="checked"':'';?>></td>
	<td><input type="radio" id="currently-pregnant-no" name="currently_pregnant" value="no" <?php echo (get_gym_meta('currently_pregnant','couple') == 'no') ?'checked="checked"':'';?>></td>
</tr>
</tbody>
<tfoot>
	<input type="submit" value="update" name="update-couple-form">
</tfoot>
</table>
</form>

  </div>

</div>





<script>
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}



var modal2 = document.getElementById("myModal2");

// Get the button that opens the modal
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span2 = document.getElementsByClassName("close2")[0];

// When the user clicks on the button, open the modal
btn2.onclick = function() {
  modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal2.style.display = "none";
  }
}
</script>

<?php
get_footer();
?>