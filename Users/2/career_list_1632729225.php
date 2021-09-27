<?php

//  Display the course home page.
require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
global $DB,$PAGE,$OUTPUT,$USER;
// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('dashboard');
$PAGE->set_title('Career List');
$PAGE->set_heading('Career List');

$PAGE->requires->js(new \moodle_url('/local/js/datat.js'));

$coursenode = $PAGE->navbar->add('Career List', new moodle_url('/local/career/career_list.php'));
$coursenode->make_active();

require_login();

$chk_managerrole = $DB->get_record_sql("SELECT * FROM wss_role_assignments WHERE userid = $USER->id AND roleid IN(1,10)");

if (empty($chk_managerrole) && !is_siteadmin()) {
    redirect($CFG->wwwroot.'/my', "Access Denied");
}

echo $OUTPUT->header();
?>

<head>
	<style>

.mytooltip {
    display: inline;
    position: relative;
    z-index: 999
}

.mytooltip .tooltip-item {
    background: rgba(0, 0, 0, 0.1);
    cursor: pointer;
    display: inline-block;
    font-weight: 500;
    padding: 0 10px
}

.mytooltip .tooltip-content {
    position: absolute;
    z-index: 9999;
    width: 360px;
    left: 50%;
    margin: 0 0 20px -180px;
    bottom: 100%;
    text-align: left;
    font-size: 14px;
    line-height: 30px;
    -webkit-box-shadow: -5px -5px 15px rgba(48, 54, 61, 0.2);
    box-shadow: -5px -5px 15px rgba(48, 54, 61, 0.2);
    background: #2b2b2b;
    opacity: 0;
    cursor: default;
    pointer-events: none
}

.mytooltip .tooltip-content::after {
    content: '';
    top: 100%;
    left: 50%;
    border: solid transparent;
    height: 0;
    width: 0;
    position: absolute;
    pointer-events: none;
    border-color: #2a3035 transparent transparent;
    border-width: 10px;
    margin-left: -10px
}

.mytooltip .tooltip-content img {
    position: relative;
    height: 140px;
    display: block;
    float: left;
    margin-right: 1em
}

.mytooltip .tooltip-item::after {
    content: '';
    position: absolute;
    width: 360px;
    height: 20px;
    bottom: 100%;
    left: 50%;
    pointer-events: none;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%)
}

.mytooltip:hover .tooltip-item::after {
    pointer-events: auto
}

.mytooltip:hover .tooltip-content {
    pointer-events: auto;
    opacity: 1;
    -webkit-transform: translate3d(0, 0, 0) rotate3d(0, 0, 0, 0deg);
    transform: translate3d(0, 0, 0) rotate3d(0, 0, 0, 0deg)
}

.mytooltip:hover .tooltip-content2 {
    opacity: 1;
    font-size: 18px
}

.mytooltip .tooltip-text {
    font-size: 14px;
    line-height: 24px;
    display: block;
    padding: 1.31em 1.21em 1.21em 0;
    color: #fff
}
	</style>
</head>

<?php

$career_list = $DB->get_records_sql("SELECT * FROM `wss_career` ORDER BY id DESC");

$data = '';

$data .="<a class='btn btn-primary pull-right' style='color:white;' href='$CFG->wwwroot/local/career/form.php'>Add New</a>";                  

$data .="<table id='table' class='table table-striped table-bordered' style='width:100%'>
			<thead>
				<tr>
					<th>S.No</th>
					<th>Caption</th>
					<th>Description</th>
					<th>Image</th>
					<th>Status</th>
					<th>Updated On</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>";
	
	$i = 1;
	
	foreach($career_list as $list) {
				
		$id = $list->id;
		
		$caption = $list->caption;
		
		$filename = $list->filename;
		
		$description = $list->description;
		
		if($list->status == 1) {
			$status = "Active";
		}else{
			$status = "Inactive";
		}
		
		if(empty($list->updatedon) || $list->updatedon == '') {
			$updateon = date('d-M-Y H:i',$list->timecreated);
		}else{
			$updateon = date('d-M-Y H:i',$list->updatedon);
		}
		
		$data .="<tr>
					<td>$i</td>
					<td>$caption</td>
					<td>$description</td>
					<td><img src='images/$filename' width='150px' height='50px'></td>
					<td>$status</td>
					<td>$updateon</td>
					<td><a onClick=\"javascript: return confirm('Do you want to Edit');\" href='form.php?id=$id'><span class='glyphicon glyphicon-edit'></span></a> | <a onClick=\"javascript: return confirm('Do you want to Delete');\" href='form.php?did=$id'><span class='glyphicon glyphicon-trash'></span></a></td>
				</tr>";
		
		$i++;
		
	}

$data .="</tbody></table>";

echo $data;
?>
<script>
	$('span[data-toggle="tooltip"]').tooltip({
		animated: 'fade',
		placement: 'bottom',
		html: true
	});
</script>
<?php
echo $OUTPUT->footer();
?>
