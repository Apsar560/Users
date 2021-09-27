<?php

//  Display the job opportunities page.
require_once('../../config.php');
global $DB,$PAGE,$OUTPUT;
// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('dashboard');
$PAGE->set_title('Career');
$PAGE->set_heading('Career');

echo $OUTPUT->header();

$careers = $DB->get_records_sql("SELECT * FROM wss_career WHERE status = 1 ORDER BY id DESC LIMIT 5");

$career = '';
$career .="<br/><h2 align='center' style='font-family:times new roman;'>CURRENT JOBS</h2><br/>
<div class='row'>";

/*$career .="
<div class='Box col-md-4' style='border:none;'>
  <div class='Box-row Box-row--unread'>
    <div class='CircleBadge CircleBadge--small bg-green float-left d-block mr-3 text-center'>
      <h1 class='text-white text-center text-shadow-dark d-flex flex-justify-center height-full mt-1'>$firstCharacter</h1>
	</div>
    <h4><$career->caption</h4>
    <span>$career->description</span><br/>
    <span>
    <label class='Label Label--gray mr-1'>IOS</label> <label class='Label Label--gray mr-1'>SENIOR</label> <label class='Label Label--gray mr-1'>ENGINEER</label>
   </span>
  </div>
 <div class='Box-row'>
  <ul class='lead ml-4 mb-0'>
	<li><b>
	$150-$200k</b> salary</li>
	<li>
	Flexible hours</li>
	<li>
	Up to <b>20</b> weeks parental leave</li>
	<li>
	<b>$3,000</b> learning budget per year</li>
	<li>
	New laptop every 2 years
	</li>
  </ul>
 </div>
</div>";*/

$career .="

		  <div id='carouselExampleIndicators' class='carousel slide' data-ride='carousel' style='width:100%;height:400px;'>
			  <div class='carousel-inner'>"; 
		
		$i = 0;
		foreach($careers as $car) {
			
			$firstCharacter = $car->caption;
			
			//~ echo "<img src='$CFG->wwwroot/local/posters/images/$poster->filename' id='imagepreview' style='width: 1400px; height: 500px;' >";
			
			if($i == 0) {
				
				$career .="
						
						  <div class='carousel-item active'>
							<img class='d-block w-100' src='$CFG->wwwroot/local/career/images/$car->filename' style='width:100%;height:400px;' alt='Event'>
						  </div>";
			}else{
				
				$career .="
						
						  <div class='carousel-item'>
							<img class='d-block w-100' src='$CFG->wwwroot/local/career/images/$car->filename' style='width:100%;height:400px;' alt='Event'>
						  </div>";
			}
			
			$i++;
		}
			
$career .="
				</div>
			<a class='carousel-control-prev' href='#carouselExampleIndicators' role='button' data-slide='prev'>
				<span class='carousel-control-prev-icon' aria-hidden='true'></span>
				<span class='sr-only'>Previous</span>
			  </a>
			  <a class='carousel-control-next' href='#carouselExampleIndicators' role='button' data-slide='next'>
				<span class='carousel-control-next-icon' aria-hidden='true'></span>
				<span class='sr-only'>Next</span>
			  </a>
		  </div>";

echo $career;
?>
<script>
	
$(document).ready(function(){
   
   $("#page-navbar-con").remove();
   $("label").hide();
   
   if (document.readyState === 'complete') {
		jQuery(function(){
		   jQuery('#modal_button').click();
		});
	}
	
}	
</script>    

<?php

echo $OUTPUT->footer();

?>
