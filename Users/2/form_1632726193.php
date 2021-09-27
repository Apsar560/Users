
<?php

//  Display the course home page.
require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
global $DB,$PAGE,$OUTPUT;
// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('dashboard');
$PAGE->set_title('Career');
$PAGE->set_heading('Career');
$coursenode = $PAGE->navbar->add('Career List', new moodle_url('/local/career/career_list.php'));
$coursenode = $PAGE->navbar->add('Create Career');
$coursenode->make_active();
echo $OUTPUT->header();

if(isset($_REQUEST['did'])) {
	
	$delete_id = $_REQUEST['did'];
	$edit_data = $DB->execute("DELETE FROM `wss_career` WHERE id = $delete_id");
	redirect($CFG->wwwroot."/local/career/career_list.php", "Deleted Successfully");
	
}

if(isset($_REQUEST['id'])) {
	
	$eid = $_REQUEST['id'];
	$edit_data = $DB->get_record_sql("SELECT * FROM `wss_career` WHERE id = $eid");
	if($edit_data->status == 1) {
		$checked="checked";
	}		
}

// Insert
if (isset($_POST['submit'])) {

	$filename = $_FILES["uploadfile"]["name"];
	$tempname = $_FILES["uploadfile"]["tmp_name"];    
	$folder = "images/".$filename;
	
	$caption = $_POST["caption"];
	$description = $_POST["description"];
	$status = $_POST["status"];
	$file = $filename;
	
	if($status == '') {
		$status = 0;
	}else{
		$status = 1;
	}
	
	$job = new stdClass();
	$job->caption = $caption;
	$job->description = $description;
	$job->filename = $file;
	$job->timecreated = time();
	$job->updatedon = time();
	$job->status = $status;
	  
	// Now let's move the uploaded image into the folder: image
	if (move_uploaded_file($tempname, $folder))  {
		
		// Execute query
		$insert = $DB->insert_record("career",$job);
		//$msg = "Image uploaded successfully";
		redirect($CFG->wwwroot."/local/career/career_list.php", "Created successfully");
		
	}else{
		
		redirect($CFG->wwwroot."/local/career/career_list.php", "Upload failed");
		
	}
}

// Update 
if (isset($_POST['update'])) {

	$filename = $_FILES["uploadfile"]["name"];
	$tempname = $_FILES["uploadfile"]["tmp_name"];    
	$folder = "images/".$filename;
	
	$id = $_POST["id"];
	$caption = $_POST["caption"];
	$description = $_POST["description"];
	$status = $_POST["status"];
	$file = $filename;
	
	if($status == '') {
		$status = 0;
	}else{
		$status = 1;
	}
	
	$job = new stdClass();
	$job->id = $id;
	$job->caption = $caption;
	$job->description = $description;
	$job->status = $status;
	$job->updatedon = time();
	if($file) {
		$job->filename = $file;
	}
	
	// Execute query
	$update = $DB->update_record("career",$job);
	
	if($file) {
		// Now let's move the uploaded image into the folder: image
		if (move_uploaded_file($tempname, $folder))  {
			redirect($CFG->wwwroot."/local/career/career_list.php", "Updated Successfully");
		}else{
			redirect($CFG->wwwroot."/local/career/career_list.php", "Update failed");
		}
	}else{
		redirect($CFG->wwwroot."/local/career/career_list.php", "Updated Successfully");
	}
}



?>

<form method="POST" action="" enctype="multipart/form-data">
<div class="row">
	<div class="col-lg-6 col-md-6">
	  <div class="form-group">      
		  <div class="col-sm-6">     
		  <label>Caption * :</label>      
			<input type="text" class="form-control" id="typedesc" placeholder="Type Caption" name="caption" value="<?php echo $edit_data->caption; ?>" required />
		  </div>
	   </div>
	</div>
	
	<div class="col-lg-6 col-md-6">
	  <div class="form-group">      
		  <div class="col-sm-6">     
		  <label>Description * :</label>      
			<textarea class="form-control" id="typedesc" placeholder="Type Description" name="description" required><?php echo $edit_data->description; ?></textarea>
		  </div>
	   </div>
	</div>
</div>
<br/>
<div class="row">
	<div class="col-lg-6 col-md-6">
	  <div class="form-group">      
		  <div class="col-sm-6">     
		  <label>Image * :</label>      
			<input type="file" name="uploadfile" value="" /> Note: image size to be (width:1500px,height:400px)
		  </div>
	   </div>
	</div>
	<div class="col-lg-6 col-md-6">
	  <div class="form-group">      
		  <div class="col-sm-6">     
		  <label>Current Image: :</label>      
			<img src="images/<?php echo $edit_data->filename; ?>" width="300px" height="100px"/>
		  </div>
	   </div>
	</div>
</div>	
<br/>
<div class="row">
	<div class="col-lg-6 col-md-6">
	  <div class="form-group">      
		  <div class="col-sm-6">     
		  <label>Active * :</label>      
			<input type="checkbox" id="status" name="status" value="<?php echo $edit_data->status; ?>" <?php echo $checked; ?>>
		  </div>
	   </div>
	</div>
</div>	
<br/>
<div style="padding-left:15px;">
<?php
	if($edit_data) {
?>		
	<input class="btn btn-primary" type="submit" name="update" value="Update"/>
	<input type="hidden" name="id" value="<?php echo $edit_data->id; ?>"/>
<?php
	}else{
?>	
	<input class="btn btn-primary" type="submit" name="submit" value="Submit"/>
<?php
	}
?>
	<a class="btn btn-primary" style="color:white;" href="<?php echo $CFG->wwwroot; ?>/local/career/career_list.php">Cancel</a>
</div>
</form>	
	
<script>
//	$("#page-navbar-con").remove();
$("#status").click(function(){
	
    if ($("#status").prop('checked')==true){ 
	
        $("#status").val(1);
		
    }else{
		
		$("#status").val(0);
		
	}
	
});
</script>    

<?php
echo $OUTPUT->footer();
?>
