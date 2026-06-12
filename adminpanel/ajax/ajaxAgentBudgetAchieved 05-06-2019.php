<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_ACHIEVED,'update');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//print_r($_REQUEST);

 $hotelId = $_REQUEST['hotelId'];

//$room_id = implode(',',$_REQUEST['roomId'])	;

$season = $_REQUEST['seasonId'];

//$id = encryptor('decrypt',$_REQUEST['hotelId']);



$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		

$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");

if($_REQUEST['hotelId']!=''){
	$editRowvalue = executeSql("SELECT * FROM `".TBL_AGENT_ACHIEVED."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`month`='".$start_date."' and a.`id_user`='".$_REQUEST['hotelId']."'");
}

//////////////////////////////getting rate data on edit//////////////////////////////////////////////////////

$CountNumber_row	=	num_rows($editRowvalue); 

if($_REQUEST['hotelId']!='' && $CountNumber_row > 0){

	 //EDIT
////////////////////////////show grid data////////////////////////////////////////////////////////

$rowRepeat ='<tr> 
		<th>Agent</th>
		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		
		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  
		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		</tr>';

$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<!--<tr> 

		<th>Agent</th>

		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total</th>
		

		</tr>-->';
//jump
 	$resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  B.user_id=".$_REQUEST['hotelId']." AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";
 	$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
$gradTot=0;
$totalId=1;
$prevValue=1;
while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){
	$availableData.=$rowRepeat;
	$availableData .= '<tr>';
    $editstart_date =	$editrow->start_date;
	$editend_date 	=	$editrow->end_date;

	$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	
	//$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id.'" >';
	$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 

	$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
	$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 
	$sqlqu = "SELECT * FROM `".TBL_AGENT_ACHIEVED."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and a.`id_company`='".$rowHotelResult->id_company."' ORDER BY a.month";
	

	$editRowvalue = executeSql($sqlqu);

	if(num_rows($editRowvalue) > 0){
		$totalqtyHori=0;
		$totalIdVer = 1;
		while($resultCat = $db->fetch_object2($editRowvalue)){
		 $availableData .= '<td>

		  <input type="hidden" disabled="disabled" id="hiddenBox'.$prevValue.'" value="'.$resultCat->qty.'">

		  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$resultCat->qty.'"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="getTotal(this.value,'.$totalId.','.$prevValue.');getTotalVer(this.value,'.$totalIdVer.','.$prevValue.');">
		  </td>';
		  $totalIdVer ++;
		  $totalqtyHori+=$resultCat->qty;
		  $prevValue++;
		}
		 $availableData .="<td>
		 					<input id='totalRow".$totalId."' class='form-control' type='text'  disabled='disabled' value='".$totalqtyHori."' style='width:60px;'>	
		 				   </td>";
	}else{

		$start = $month = strtotime($start_date);
		$end = strtotime($end_date);
		while($month < $end){
	     $DateValue	=	date('Y-m-d', $month);
	     $month = strtotime("+1 month", $month);
		 $availableData .= '<td>



					  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="'.$editsingle_pax_price.'"  automcomplete="off" data-parsley-type="number" style="width:60px;">

				  </td>';
	}



}//end of while
$availableData .='</tr>';
	$totalId++;

	$gradTot+=$totalqtyHori;

}
	$fromVer = date('Y-m-01',strtotime($start_date));
	$tillVer = date('Y-m-01',strtotime($end_date));
	$monthQtyVer = '';
	$totalIdVer=1;
	while($fromVer <= $tillVer){
		$sqlTotalVer = "SELECT sum(qty)AS qty FROM `".TBL_AGENT_ACHIEVED."` as a  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' and  a.`id_user`='".$_REQUEST['hotelId']."' and a.`seasonId`='".$_REQUEST['seasonId']."' and month='".date('Y-m-d',strtotime($fromVer))."'  ";


		$resVer= mysqli_query($connNew,$sqlTotalVer);
		$objTot = mysqli_fetch_object($resVer);

		$fromVer = date('Y-m-d',strtotime('+1 months',strtotime($fromVer)));
		$monthQtyVer.="<td>
		 				<input id='totalCol".$totalIdVer."' class='form-control' type='text'  disabled='disabled' value='".$objTot->qty."' style='width:60px;'>	
		 				</td>";
		$totalIdVer++;
	}


$availableData .='</tr>
					<td style="font-weight:bold;">Total</td>
					'.$monthQtyVer.'
					<td>
		 				<input id="grandTotal" class="form-control" type="text"  disabled="disabled" value="'.$gradTot.'" style="width:60px;">	
		 			</td>
					
				   <tr>';
			
// -------------------Bottom Total END----------------------------------------------------
}else{ //EDIT
	$rowRepeat='<tr>

		<th>Agent</th>

		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total<th>
		</tr>';
////////////////////////////show grid data////////////////////////////////////////////////////////
$availableData .= '<div class="box box-success  table-responsive no-padding">

				  <table class="table table-hover" style="margin-bottom:none !important;">

		

		<!--<tr>

		<th>Agents</th>

		<th>Apr - '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>		

		<th>May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>				  

		<th>Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	

		<th>Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'"))).'</th>	
		<th>Total<th>
		</tr>-->
		
		';

 $resCat_rooms2 ="SELECT A.name,A.id_company FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
 				  WHERE  B.user_id=".$_REQUEST['hotelId']." AND A.id_shop=".$_SESSION['shop']." AND A.name !='' ORDER BY A.name";
 	$resCat_rooms1=mysqli_query($conn,$resCat_rooms2);	
 	$totalId=1;
 	$prevValue=1;		  
	while($rowHotelResult = mysqli_fetch_object($resCat_rooms1)){
		$availableData .=$rowRepeat;
		$availableData .= '<tr id="rateMaster|'.$roomId.'|'.$rate_plan_id.'|'.$planType.'">';
	    $editstart_date 		=	$editrow->start_date;
		$editend_date 			=	$editrow->end_date;

$availableData .= '<input type="hidden" id="data_id" name="data_id[]" value="'.$rowHotelResult->id_company.'" >';	



//$availableData .= '<input type="hidden" id="bugetHotel" name="bugetHotel[]" value="'.$rowHotelResult->id_company.'" >';
$availableData .= '<td>'.$rowHotelResult->name.'</td>'; 
$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");		
$end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");								 

$start = $month = strtotime($start_date);

$end = strtotime($end_date);
$totalIdVer=1;
while($month < $end){
     $DateValue	=	date('Y-m-d', $month);
     $month = strtotime("+1 month", $month);
     //$availableData .= '<input type="hidden" id="MonthDate" name="MonthDate|'.$rowHotelResult->id_company.'[]" value="'.$DateValue.'" >';
	 $availableData .= '<td>
	 				<input type="hidden" disabled="disabled" id="hiddenBox'.$prevValue.'" value="0">

				  <input type="text" class="form-control  buget_qty" id="buget_qty|'.$DateValue.'|'.$hotelId.'|'.$rowHotelResult->id_company.'" name="buget_qty|'.$rowHotelResult->id_company.'[]" value="0"  automcomplete="off" data-parsley-type="number" style="width:60px;" onchange="getTotal(this.value,'.$totalId.','.$prevValue.');getTotalVer(this.value,'.$totalIdVer.','.$prevValue.');">

				  </td>';
				  $totalIdVer++;
				  $prevValue++;
}

$availableData .="<td>
		 					<input id='totalRow".$totalId."' class='form-control' type='text'  disabled='disabled' value='0' style='width:60px;'>	
		 				   </td>";
$totalId++;
$availableData .='</tr>';

	}
	$fromVer = date('Y-m-01',strtotime($start_date));
	$tillVer = date('Y-m-01',strtotime($end_date));
	$totalIdVer=1;
	while($fromVer <= $tillVer){
		$fromVer = date('Y-m-d',strtotime('+1 months',strtotime($fromVer)));
		$monthQtyVer.="<td>
		 				<input id='totalCol".$totalIdVer."' class='form-control' type='text'  disabled='disabled' value='0' style='width:60px;'>	
		 				</td>";
		 				$totalIdVer++;
	}


$availableData .='</tr>
					<td style="font-weight:bold;">Total</td>
					'.$monthQtyVer.'
					<td>
		 				<input id="grandTotal" class="form-control" type="text"  disabled="disabled" value="0" style="width:60px;">	
		 			</td>
					
				   <tr>';	
}

				 

											 

$availableData .='</table>	';
//}
$availableData .= '  
            </div>';
echo $availableData;

?>
<script type="text/javascript">

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


</script>

