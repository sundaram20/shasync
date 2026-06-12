<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

//include_once("../includes/header.php");


$followup_id	= $_REQUEST['followup_id'];




?>
<script>
  $( function() {	 
    $( ".datepickertest").datepicker();
  } );
  
  

  </script>
  <style>
/* Desktops and laptops ----------- */
@media only screen  and (min-width : 1224px) {
.tablenew1
{
width:100% !important;
	
}
}/* Large screens ----------- */
@media only screen  and (min-width : 1824px) {
.tablenew1
{
width:100% !important;
	
}

/* Smartphones (portrait and landscape) ----------- */
@media only screen and (min-device-width : 320px) and (max-device-width : 480px) {
.tablenewmobile1
{
width:100% !important;
	
}
}
}


</style>
 <script>
		function Toggle(id) {
			
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		
		function  SelectHotelsList(HotelDuplicateInsert){
			alert(HotelDuplicateInsert);
			var test = HotelDuplicateInsert;
		        $("div.desc").hide();
		        $("#cars" + test).show();
			}
		
</script>
<style>
#test01 { width:500px; padding:15px;  display:none; }
</style>
<?php
$OtherChargesuniqueCode = 'NEXTFOLLOWUPS'.rand(0000,9999);
$availableData .= '<div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom"><div class="btn btn-default tablenew1 tablenewmobile1">';


$availableData .='<div class="col-md-9">
                <div class="form-group" style="text-align:left;">
                  <label>Choose Follow Up Status </label><br>
                 						  
			  <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList(1);"   value="1"  checked="checked" />  1) Open

<input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert"onClick="SelectHotelsList(2);" class="flat-red" value="2" /> 2) Close</div></div>';


$availableData .='<div id="cars1" class="desc"  >
<form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

<input type="hidden" name="followup_id" id="followup_id" value="'.$_REQUEST['followup_id'].'">';






 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="followup_description" id="followup_description" value=""  placeholder="Follow Up Summary." data-parsley-required></div>';
												 
		
			 
$availableData .='<div class="form-group"><input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="'.date('d-m-Y').'"  data-parsley-required></div>';


$availableData .='<div class="form-group" style="float:left;"><button class="btn btn-primary" onclick="savefollowupDate();" type="button">Save</button>
      </div>';
	 


		/*$availableData .='<div class="form-group" style="float:right;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></div>  '; */  
				  
				           
                $availableData .='</form></div>';
				
				
$availableData .='<div id="cars2" class="desc" style="display: none;" >';

$availableData .='<form id="ColseSummaryPopUpForm" class="ColseSummaryPopUpForm" data-parsley-validate autocomplete="off">
<div class="form-group">
 <input type="hidden" name="fs_daily_visit_followup_new" id="fs_daily_visit_followup_new" value="'.$_REQUEST['followup_id'].'">
       ';
		$availableData .= '<select name="close_type"  id="close_type" class="form-control input-sm" data-parsley-required >
									 	 <option value="">Select Close Type</option>';
											  $resCat2 = selectSql(TBL_SALES_FOLLOWUPCLOSE_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
											  if(num_rows($resCat2)){
											  	while($resultCat2 = $db->fetch_object2($resCat2)){
												
													if($row->amendment_remarks_id	== $resultCat2->id){
														$selected2 = 'selected="selected"';
													}else{
														$selected2 = '';
													}
													$availableData .= '<option   '.$selected2.' value="'.$resultCat2->id.'">'.ucfirst($resultCat2->name).'</option>';
												}
											  }
											 	 $availableData .= '</select>';
		
		$availableData .='</div>';
		
			 
$availableData .='<textarea   name="followup_close_summary" id="followup_close_summary" class="form-control" placeholder="Close Summary"  data-parsley-required automcomplete="off"></textarea>';


$availableData .='
		 
		 <br/><input  type="button" class="btn btn-primary" onClick="saveColseSummaryPopUpform();" value="Save" style="float:left;">
    
  
</div></form>';				
				
				
				
				
				
				$availableData .=' <a class="btn btn-default" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");" style="float:right;">
				  Close</button></a>
				  
				  </div>';
				
				
				
				$availableData .='
				
				
				</div>';
                




echo $availableData.'|||'.$followup_id;



	









?>