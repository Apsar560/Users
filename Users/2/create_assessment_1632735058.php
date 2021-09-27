<?php

//  Display the course home page.
require_once('../../config.php');
require_once($CFG->libdir.'/formslib.php');
global $DB,$PAGE,$OUTPUT;
// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('dashboard');
$PAGE->set_title('Schedule Assessment');
$PAGE->set_heading('Schedule Assessments');

$PAGE->requires->js(new \moodle_url('/local/js/datat.js'));

$coursenode = $PAGE->navbar->add('Schedule Assessment', new moodle_url('/local/assessment/create_assessment.php'));
$coursenode->make_active();

echo $OUTPUT->header();

if(!isloggedin()) {
	
	redirect($CFG->wwwroot.'/login/index.php');
	
}

$CompleteCourseList = $DB->get_records_sql("SELECT a.id as cid, b.id as caid, a.fullname as coname, b.name as caname FROM wss_course a JOIN wss_course_categories b on a.category=b.id where b.visible=1");

$CategoriesList = $DB->get_records_sql("SELECT id,name FROM wss_course_categories where visible=1");

$data = " ";

$data .="<table id='table' class='table table-striped table-bordered' >
			<thead>
				<tr>
					<th>S.no</th>
					<th>Program</th>
					<th>Language</th>					
					<th>Action</th>
				</tr>
			</thead>
			<tbody>";
	
	$i = 1;
	
	foreach($CategoriesList as $CL) {
		
		$data .="<tr>
                    <td>$i</td>
                    <td>$CL->name</td>
                    <td>
                        <select class='form-control selectcourse'>";	
	
		foreach($CompleteCourseList as $cc )
		{
			if($cc->caid == $CL->id)
				$data.="	<option value='$cc->cid'> $cc->coname </option>";
		}                
            
                            
        $data .="       </select>
                    </td>				
					<td>
						<a class='clink' id='clinkid_$i' style='cursor:pointer;color:blue' onclick='UpdateAssessment(this)' >
							Add / View Assessments
						</a><input type='hidden' class='cidval' id='cidval_$i' />
					</td>
				</tr>";		
		$i++;
		
	}

$data .="</tbody></table>";

echo $data;
?>

<script>
	$(document).ready(function(){
		$('.cidval').each(function(){
			var cid = $(this).closest('td').prev().find('select').val();
			$(this).val(cid);
		});			
		$('.selectcourse').on('change',function(){
			var cid = $(this).val();
			$(this).closest('td').next().find('.cidval').val(cid);
		});
	});
	function UpdateAssessment(ele)
	{			
		$(ele).prop('disabled',true);
		var courseid = $(ele).closest('td').prev().find('select').val();			
		$.ajax({
			url:'ajax.php',
			data:{'cid':courseid},
			type:'POST',
			success:function(res){					
				$(res).modal();
				$('.clink').prop('disabled',false);					
			},
			error:function(res){
				alert('error');
			}
		});
		
	}
</script>

    
<?php
echo $OUTPUT->footer();
?>
