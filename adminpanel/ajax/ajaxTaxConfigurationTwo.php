<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 $hotelId = $_REQUEST['hotelId'];
//$room_id = implode(',',$_REQUEST['roomId'])	;
$season = $_REQUEST['seasonId'];
$id = encryptor('decrypt',$_REQUEST['id']);

if(addslashes(encryptor('decrypt',$_REQUEST['id']))!=''){
	$sql1 = "SELECT * FROM `".TBL_RATE."` as a INNER JOIN `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";
$editRowvalue = executeSql($sql1);


}
//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////
$CountNumber_row	=	num_rows($editRowvalue); 

if(addslashes(encryptor('decrypt',$_REQUEST['id']))!='' && $CountNumber_row >0){
	
$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
//	$editsql = executeSql("SELECT * FROM `".TBL_RATE."`
									//WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
		

				
			//$disabled = 'disabled="disabled"';

	
			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

//if(num_rows($resInclusion)==0){
	/*$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($hotelId)."'").' will be added to Tax Configuration '.$row->rate_name.'-'.$row->sub_code.'.</p>
					 </div>';*/
					 $availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($hotelId)."'").' will be added to Tax Configuration .</p>
					 </div>';
//}

/*$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th>Room</th>
		<th width="8%">Rate Plan</th>		
		<th>Single</th>	
		<th>Double</th>	
		<th>Package</th>				  
		
		</tr>';*/
		$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th width="8%">Room</th>
		
		<th width="8%">Tax</th>
		
		</tr>';
					
		$SqlRoomType	=	executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));


			while($rowRoomType = $db->fetch_object2($SqlRoomType)){
				$SqlRoomType2	=	executeSql("SELECT * from `".TBL_TAX_CONFIGURATION_TWO."`  where `id_shop` = '".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($rowRoomType->hotel_id)."' and  `room_id` = '".addslashes($rowRoomType->room_id)."' and  `seasonId` = '".addslashes($_REQUEST['seasonId'])."' ");

				$rowRoomType2 = $db->fetch_object2($SqlRoomType2);

				$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';

				$editstart_date 		=	$editrow->start_date;
				$editend_date 			=	$editrow->end_date;

				$availableData .= '<input type="hidden" id="id" name="id[]" value="'.$rowRoomType2->id.'" >';		
				$availableData .= '<input type="hidden" id="room_id" name="room_id[]" value="'.$rowRoomType->room_id.'" >';		
				$availableData .= '<td>'.ucfirst($rowRoomType->name).'</td>';
				$availableData .='<td><input type="text" class="form-control  tax" id="tax_room" name="tax_room[]" value="'.$rowRoomType2->tax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:20%;"></td>';	
							// if(addslashes(encryptor('decrypt',$_REQUEST['id']))==''){ 
							  
							  
							// }
				$availableData .='</tr>';	
						
						     	
			}//end of while
			
								
$rowRoom = $db->fetch_object2($resRoom);

//print_r($rowRoom);
$counter = 0;	
$resRatePlan = executeSql("SELECT * from `".TBL_RATE_PLAN."`  where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' order by display_order");

$rack_rate = $rowRoom->double_pax_price;
$rowRatePlan = $db->fetch_object2($resRatePlan);

$resRate = executeSql("SELECT `".TBL_RATE_DETAILS."`.* from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id= `".TBL_RATE_DETAILS."`.rate_id  where `".TBL_RATE."`.id='".$id ."' and `".TBL_RATE_DETAILS."`.room_id='".$rowRoom->room_id."' and `".TBL_RATE_DETAILS."`.rate_plan_id='".$rowRatePlan->id."'  and `".TBL_RATE_DETAILS."`.rate_assign_id='".$rowInclusion->id."'");

$rate_plan_id = $rowRatePlan->id;
$roomId = $rowRoom->room_id;

/////planType is pkgCounter to check how many pkg are created ////////

$planType = '0';


$resCat_rooms22 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));											  
while($rowInclusion22 = $db->fetch_object2($resCat_rooms22))
	{
	$y[] = $rowInclusion22;
	}
foreach($y as $rest){
											  
	$outputasd .= '<option value="'.$rest->room_id.'">'.ucfirst($rest->name).'</option>';
			}
			
			
			
$resCat_2 = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
											 
			while($resultCat_2 = $db->fetch_object2($resCat_2)){
			$Add_rateplan[] = $resultCat_2;
			}
				 foreach($Add_rateplan as $Add_rateplan_result){
		  
					$Add_rateplan_results .= '<option '.$selected3.' value="'.$Add_rateplan_result->id.'">'.ucfirst($Add_rateplan_result->name).'</option>';
				}
				
$CountNumber	=	1;			
				
while($editrow = $db->fetch_object2($editsql)){
		
		//echo "<pre>";print_r($editrow);echo "</pre>";
			$editid_id				=	$editrow->id;
			$editroom_id			=	$editrow->room_id;
			$editrate_id			=	$editrow->rate_id;
			$editrate_plan_id		=	$editrow->rate_plan_id;
			$editsingle_pax_price 	=	$editrow->single_pax_price;
			$editdouble_pax_price 	=	$editrow->double_pax_price;
			$editextra_bed		 	=	$editrow->extra_bed_price;
			$editbreakfast_price 	=	$editrow->breakfast_price;
			$editlunch_price 		=	$editrow->lunch_price;
			$editdinner_price 		=	$editrow->dinner_price;
			$editdpkg_price 		=	$editrow->pkg_price;
			$editstart_date 		=	$editrow->start_date;
			$editend_date 			=	$editrow->end_date;
			$edittax_room 			=	$editrow->tax_room;
 		    $statusCheck			=	$editrow->detail_status;
			 
			 
if($statusCheck==1){
	$statusCheck = 'checked="checked"';
}else{
	$statusCheck = '';
}


$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);	
$inclusion = explode(',',$rowRatePlan->inclusion);
$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';

	

		  $editstart_date 		=	$editrow->start_date;
			$editend_date 			=	$editrow->end_date;

	
$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$editid_id.'" >';	
$availableData .= '<input type="hidden" id="rate_id" name="rate_id[]" value="'.$editrate_id.'" >';	
//$availableData .= '<input type="hidden" id="data_id|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="data_id[]" value="|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" >';	


					
					
			

						
												

							 
				  
				 $availableData .= '
				  
				  
				  
				    <td>
				  
				  <input type="hidden" class="form-control  pkg_price" id="pkg_price|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'" name="pkg_price[]" value="'.$editdpkg_price.'" data-parsley-required automcomplete="off" data-parsley-type="number"  style="width:60px;">
</td>
				 
				  <td>
				   <input class="form-control  " type="hidden" name="extra_bed[]" id="extra_bed" value="'.$editextra_bed.'" style="width:60px;" />
				  </td>
				  
				  
				  
					
				
				  
				  ';
				  
				  
				  
				  
				  
				  
				  
				  /*$availableData .='<td><input type="text" class="form-control  tax" id="tax_room" name="tax_room[]" value="'.$edittax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:60px;"></td>
				  
				  <td><label class="switchCheck"><input type="checkbox" '.$statusCheck.' name="status[]" id="status"><span class="slider round"></span></label></td>';	
				 if($CountNumber=='1'){ 
				  
				  $availableData .='
				  
				 <td><button type="button" name="add" class="btn btn-success btn-sm add" onClick="AddTextBox();"><span class="glyphicon glyphicon-plus"></span></button></td>';
				 }*/
				$availableData .='</tr>';	
				
				
				$CountNumber++;
				
					}
			
			}else{ //EDIT
			
			
			
	
$editsql = executeSql("SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where  a.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' AND hotel_id='".addslashes($hotelId)."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ");
//	$editsql = executeSql("SELECT * FROM `".TBL_RATE."`
									//WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
		

				
			//$disabled = 'disabled="disabled"';

	
			
////////////////////////////show grid data////////////////////////////////////////////////////////
$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");


$resInclusion = executeSql("SELECT * from `".TBL_RATE_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$rowInclusion = $db->fetch_object2($resInclusion);

if(num_rows($resInclusion)==0){
	$availableData .= '<div class="alert alert-success alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						<p><i class="icon fa fa-check"></i> 
						'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($hotelId)."'").' will be added to Tax Configuration </p>
					 </div>';
}


$availableData .= '<div class="box box-success  table-responsive no-padding">
				  <table class="table table-hover" style="margin-bottom:none !important;">
		
		<tr>
		<th width="8%">Room</th>
		
		<th width="8%">Tax</th>
		
		</tr>';
					

			
								
$rowRoom = $db->fetch_object2($resRoom);

//print_r($rowRoom);
$counter = 0;	
$resRatePlan = executeSql("SELECT * from `".TBL_RATE_PLAN."`  where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' order by display_order");

$rack_rate = $rowRoom->double_pax_price;
$rowRatePlan = $db->fetch_object2($resRatePlan);



$rate_plan_id = $rowRatePlan->id;
$roomId = $rowRoom->room_id;

/////planType is pkgCounter to check how many pkg are created ////////

$planType = '0';


$SqlRoomType	=	executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.double_pax_price from `fs_assign_hotel_room` ahr left join `fs_room_type` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id=".addslashes($hotelId));


	while($rowRoomType = $db->fetch_object2($SqlRoomType)){
		$SqlRoomType2	=	executeSql("SELECT * from `".TBL_TAX_CONFIGURATION_TWO."`  where `id_shop` = '".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($rowRoomType->hotel_id)."' and  `room_id` = '".addslashes($rowRoomType->room_id)."' and  `seasonId` = '".addslashes($_REQUEST['seasonId'])."' ");

		$rowRoomType2 = $db->fetch_object2($SqlRoomType2);

		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';

		$editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

		$availableData .= '<input type="hidden" id="id" name="id[]" value="'.$rowRoomType2->id.'" >';		
		$availableData .= '<input type="hidden" id="room_id" name="room_id[]" value="'.$rowRoomType->room_id.'" >';		
		$availableData .= '<td>'.ucfirst($rowRoomType->name).'</td>';
		$availableData .='<td><input type="text" class="form-control  tax" id="tax_room" name="tax_room[]" value="'.$rowRoomType2->tax_room.'" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:20%;"></td>';	
					// if(addslashes(encryptor('decrypt',$_REQUEST['id']))==''){ 
					  
					  
					// }
		$availableData .='</tr>';	
				
				     	
	}//end of while
}//end of else				 
											 
 $availableData .='</table>
				';
				  
				
			
	
	//}
 $availableData .= '  
            </div>';
		


					  
					



			  
echo $availableData;
?>




<script type="text/javascript">

function AddTextBox() {
    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicTextBox("");
    document.getElementById("TextBoxContainer").appendChild(div);
}
 
function RemoveTextBox(div) {
	
	document.getElementById("TextBoxContainer").removeChild(table.parentNode);
  //document.getElementById("TextBoxContainer").removeChild(div.parentNode);
   
	//y.remove();
}
 
function RecreateDynamicTextboxes() {
    var values = eval('<%=Values%>');
    if (values != null) {
        var html = "";
        for (var i = 0; i < values.length; i++) {            		
			html += "<div>" + room_type_id(values[i]) + "</div>";
			html += "<div>" + rate_plan_id(values[i]) + "</div>";	
        }
        document.getElementById("TextBoxContainer").innerHTML = html;
    }
}
window.onload = RecreateDynamicTextboxes;
</script>

<script type="text/javascript">
        function addTextArea(){}
		
		 $(document).on('click', '.remove', function(){
  		$(this).closest('div').remove();
 });
   
   
   
   function roomtypevalue(k){
alert(k);

var room_type_id = $('#room_type_id').val();
var rate_plan_id = $('#rate_plan_id').val();
alert(room_type_id);
alert(rate_plan_id)/*
 var ratePointId = $('#rate_points option:selected').val();
 var ratePointDetail = $('#ratePointDetail').val();*/
	$.ajax({
	   type: "GET",
	   url: 'ajax/ajaxRoomTypeValue.php',
	   data: 'room_type_id='+room_type_id+'&rate_plan_id='+rate_plan_id, 
	   success: function (result) {	
					$( "#ratePoinData" ).html(result);
					$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
		}
	})
}

   
   
   
   
   
   
   
   
    </script>
   <script>





function Creditallow(id_company,rate_id){
 var form1=$("#availabiltyForm");	
 var dataString = $("#availabiltyForm").serialize();	
	if(form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxCreditallow.php',
		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 
		   success: function (result) {					
				$( "#Creditallow_value" ).html(result);								
			}
		})
	}
}


//////////////////////check availabilty -book-now.php///////////////////////////////////////////////// 

function ajaxCheckAvailability() {
          //alert('test');
  		  var form=$("#availabiltyForm");		  
		  form.parsley().validate();		  
  		  $('.loading').show(); 
		  $.ajax({
			   type: "POST",
			   url: 'ajax/ajaxcheckAvailability.php',
			   data: form.serialize(), 
			   success: function (result) {
					$('#availabilty').html(result)
				},
			  complete: function(){
				$('.loading').hide();
			  }
		})
	return false;
 }
/////////////////////////////////show events on date -book-now.php/////////////////////////////////////////////
function getEvents(dated){
//$('#eventsPopup').popup('show');
 $('#eventsPopup').popup({
            //pagecontainer: '.container',
        	transition: 'all 0.3s',
            autoopen: true,            
        });
}



////////////////////////////////////////


function getRateLetter(id_company,rate_id){
 var form1=$("#availabiltyForm");	
 var dataString = $("#availabiltyForm").serialize();	
	if(form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: dataString+'&id_company='+id_company+'&rate_id='+rate_id, 
		   success: function (result) {					
				$( "#rate_id" ).html(result);								
			}
		})
	}
}

/////////////////////////////////show plan Details on date -book-now.php/////////////////////////////////////////////


$("#view").click(function (){
 var form1=$("#availabiltyForm");	
 var form2=$("#addRoomForm");
 var dataString = $("#availabiltyForm, #addRoomForm").serialize();	
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetPlanDetails.php',
		   data: dataString, 
		   success: function (result) {					
				$( "#ajaxPlanData" ).html(result);
				$('#planDetail').popup({
        			 transition: 'all 0.3s',
           			 autoopen: true,            
        		});
				 //$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
})


////////////////////////////////////////////////////////////////////////////////


function ajaxAddRoom(rate_id,rate_assign_id,room_id,rate_plan_id,type){
   var form1=$("#availabiltyForm");	
   var form2=$("#addRoomForm");
   var dataString = $("#availabiltyForm, #addRoomForm").serialize();
	if(form1.parsley().validate() && form2.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxaddRoom.php',
		   data: dataString+'rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 
		   success: function (result) {					
				resultArray = result.split('|||');
					if(resultArray['0']!=''){
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
					}
					$('#showRoom').append(resultArray['1']);
					$('#addRoommsg').css('display', 'none');
					$('#createBooking').css('visibility', 'visible');					
			}
		})
	}


}

//////////////////////////////save price popup form common//////////////////////////////////////////////////////////


function pricePopUp(id){
	var Id = id.split('_');
	var uniqueId= Id[1];
	$('#uniqueCode').val(uniqueId);
	
}
function savepricePopUpform(){
	var uniqueCode = $("#uniqueCode").val();
	var dataValue = $('#dataValue'+'\\|'+uniqueCode).val();		
	//alert(dataValue);
	var form=$("#pricePopUpform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSavePrice.php',
	   data: form.serialize()+'&dataValue='+dataValue, 
	   success: function (result) {
	    $('#pricePopUp').popup('hide');
		$("#pricePopUpform")[0].reset();
		 alert('Price has been updated.');
		 $('#price_'+uniqueCode).html(result);		
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}





</script>
