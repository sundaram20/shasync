<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');



///////unset session for differnt type of bookings /////////////////
if($_SESSION['bookCart']['type'] !=$_GET['type'] ){
	unset($_SESSION['bookCart']);	
}
//---------------------------------------------------------------------------------------------------------

if($_POST['dwn_availabilty'] == 'Generate'){
	
	$hotel_id = $_POST['hotel_id'];
	$room_id = $_POST['room_id'];
	$reservation_date = explode(' to ',$_POST['reservation_date']);
	$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDate = date ("Y-m-d", strtotime($reservation_date['1']));
	//$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));	
	if($room_id == 0){
		
	$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."'");
	}else{
	$resRoom = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."' and ahr.room_id='".addslashes($room_id)."'");
	}

	$totalRoom = GetTotalRoom($hotel_id);
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Availability Report")
								 ->setSubject("Availability Report")
								 ->setDescription("Availability Report")
								 ->setKeywords("Availability Report")
								 ->setCategory("Report");



	// Add some data
	$styleArray = array(

    'font'  => array(

        'bold'  => true,

        'color' => array('rgb' => '1e51bf'),

        'size'  => 15,

        'name'  => 'Verdana'

    ));



$styleArray_1 = array(

    'font'  => array(

        'bold'  => true,

        'color' => array('rgb' => 'FF0000'),

        'size'  => 10,

        'name'  => 'Verdana'

    ));
$totalArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));

$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(28);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(28);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);				 
$objPHPExcel->getActiveSheet()->mergeCells('A2:E2');


$HotelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($hotel_id)."'");

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A2', $HotelName)
				->setCellValue('A4', 'Date')
				->setCellValue('B4', 'Room Type')
				->setCellValue('C4', 'Total Rooms')
				->setCellValue('D4', 'Blocked Rooms')	
				//->setCellValue('D4', 'Blocked Offline(Inv)')	
				->setCellValue('E4', 'Available Rooms');	
				//->setCellValue('F4', 'Blocked Room(Order Details)');
$styleThinBlackBorderOutline = array(

	'borders' => array(

		'outline' => array(

			'style' => PHPExcel_Style_Border::BORDER_THIN,

			'color' => array('argb' => '000'),

		),

	),

);	
				
$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getAlignment()->applyFromArray(
	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->applyFromArray(
	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);


$objPHPExcel->getActiveSheet()->getStyle('A4:D4')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);

	$head_cntr = "B";
	$Rowcount	=4;
	
	$RowcountStart	=	$Rowcount;
	 $startDate = date ("Y-m-d", strtotime($reservation_date['0']));	
	while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
	$totalRooms = 0;




$objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($styleArray_1);

$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getAlignment()->applyFromArray(

array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);



$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->applyFromArray($styleArray_1);

$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getAlignment()->applyFromArray(

array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

);
	
	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
			
				
$Rowcount++;

	if($room_id == 0){
$resRoom1 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."'");
	}else{
	$resRoom1 = executeSql("SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."' and ahr.room_id='".addslashes($room_id)."'");
	}
			$totalAvil=0;
			$totalBlock =0;
			
		while($rowRoom2 = $db->fetch_object2($resRoom1)){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Rowcount, dateformat_date($checkinDate));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B".$Rowcount, $rowRoom2->name);
			$totalRoom = GetAssignTotalRoom($hotel_id,$rowRoom2->room_id);		
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C".$Rowcount, $totalRoom);
			$totalRooms+=$totalRoom;
			
			$blocked_hotel = GetTotalRoomBlocked_Hotel($startDate,$hotel_id,$rowRoom2->room_id);			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".$Rowcount, $blocked_hotel);
			$totalBlock+=	$blocked_hotel;		
			$GetTotalRoomoffline_block_hotel = GetTotalRoomoffline_block_hotel($startDate,$hotel_id,$rowRoom2->room_id);			
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D".$Rowcount, $GetTotalRoomoffline_block_hotel);
						
			$roomAlloted = GetTotalRoomAlloted($startDate,$hotel_id,$rowRoom2->room_id);
			$roomAvailable = $roomAlloted;
			$totalAvil += $roomAvailable; 
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E".$Rowcount, $roomAvailable);
			
			$roomAlloted = GetTotalRoomAllotedOld($startDate,$hotel_id,$rowRoom2->room_id);
			$orderTableAvailableRooms = $roomAlloted;
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F".$Rowcount, isset($orderTableAvailableRooms)?$orderTableAvailableRooms:0);
			
			
		$Rowcount++;	
		} 
		
		$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Rowcount, 'Total Rooms Available');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$Rowcount, $totalRooms);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$Rowcount, $totalBlock);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$Rowcount, $totalAvil);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount.':E'.$Rowcount++)->applyFromArray($totalArray);			
	}
	$totalRooms = 0;	
$objPHPExcel->getActiveSheet()->getStyle('B'.$RowcountStart.':B'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


$objPHPExcel->getActiveSheet()->getStyle('C'.$RowcountStart.':C'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


$objPHPExcel->getActiveSheet()->getStyle('D'.$RowcountStart.':D'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


	$objPHPExcel->getActiveSheet()->setTitle('Availability Report');
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="availability_report.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Booking Manager <small>Book Now</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Book Now</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="box box-default">
      <div class="form-group has-error" align="center">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div class="box-header with-border">
        <h3 class="box-title"> BOOKING AVAILABILITY </h3>
		<a href="manageOrders.php" class="btn btn-success pull-right" >View Bookings</a>
      </div>
      <!-- /.box-header -->
      <div class="panel-body padding-0">
        <div class="row">
          <div class="col-sm-12">
            <div class="row box-border margin-right-10">
              <form method="post" action="" id="availabiltyForm" data-parsley-validate autocomplete="off">
                <div class="container">
                  <div class="form-group col-sm-3">
                    <label for="hotel_id" >Hotel</label>
                    <?php 
				$categoryDropDown = '<select name="hotel_id" id="hotel_id" class="form-control select2" onChange="getRoom(this.value,0); ajaxAddRoommsgUpdate();" data-parsley-required data-parsley-errors-container="#hotelError">
									 					  <option value="">Select Hotel</option>';
											  $resCat = selectSql(TBL_HOTELS,"where `id_shop` = '".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_SESSION['bookCart']['hotel_id'] == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
									
									 ?>
                    <span id="hotelError"></span> </div>
                  <div class="form-group col-sm-2">
                    <label for="room_id">Rooms<br />
                    </label>
                    <select class="form-control select2" name="room_id" id="room_id" data-parsley-required data-parsley-errors-container="#roomError">
                      <?php if($_SESSION['bookCart']['hotel_id']){
					$resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($_SESSION['bookCart']['hotel_id']	)."'");
					$roomType ='<option value="0">All Rooms</option>';
					 while($rowRoom = mysqli_fetch_object($resRoom)){	
						$roomType .= '<option value="'.$rowRoom->room_id.'">'.$rowRoom->name.'</option>';
						}
						echo $roomType;
				}else { ?>
                      <option value="" selected="">Please select hotel</option>
                      <?php } ?>
                    </select>
                    <span id="roomError"></span> </div>
                  <div class="form-group col-sm-3">
                    <label for="reservation_date">Checkin Date - Checkout Date </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                      <input type="text" class="form-control pull-right dateRange" id="reservation_date" name="reservation_date" data-parsley-required value="<?php if($_SESSION['bookCart']['reservation_date'] !='' ){echo $_SESSION['bookCart']['reservation_date'];} ?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                    </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
                  <div class="form-group col-sm-3">
                    <button id="search_availabilty" name="search_availabilty" type="button" class="btn btn-primary" style="margin-top:25px;" onClick="ajaxCheckAvailability();"> <i class="fa fa-search"></i> Search </button>
					<input name="dwn_availabilty" type="submit" class="btn btn-primary" value="Generate" style="margin-top:25px;" />
				
                  </div>
                </div>
              </form>
            </div>
          </div>
          <section class="content">
            <!-- /.row -->
            <div class="row">
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Room Availability</h3>
                    <div class="box-tools">
                      <ul class="pagination pagination-sm no-margin pull-right">
                        <li><a href="#">&laquo;</a></li>
                        <li><a href="#">&raquo;</a></li>
                      </ul>
                    </div>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body table-responsive no-padding text-center loading" >
                    <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request"> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
                  </div>
                  <div class="box-body table-responsive no-padding" id="availabilty">
                    <table class="table table-hover">
                      <tr>
                        <th>Room Type</th>
                        <?php 
				  $checkinDate = date('Y-m-d');
				  echo $availableData = '<th>'.date('d M, Y', strtotime($checkinDate)).'</th>';
				  for($i =0; $i < 6; $i++){
						$checkinDate = date('d M, Y', strtotime('+1 day', strtotime($checkinDate)));
						echo $availableData = '<th>'.$checkinDate.'</th>';
					} ?>
                      </tr>
                      <tr align="center">
                        <td colspan="8" >No Data Available. Please try different Search.</td>
                      </tr>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </section>
  
</div>
<div id="eventsPopup" class="well" style="min-width:20em; display:none;"> <a href="#" class="eventsPopup_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
  <div class="error-content">
    <h4><i class="fa fa-warning text-red"></i> No Events on this Date.</h4>
  </div>
</div>






<div id="planDetail" class="well" style="display:none; min-width:55em;"> <a href="#" class="planDetail_close" style="float:right;padding:0 0.4em;"><i class="fa fa-times text-red"></i></a>
  <div id="ajaxPlanData"></div>
</div>

<script>
<?php if($_SESSION['bookCart']['hotel_id'] != ''){ ?>
//window.onbeforeunload = function() { return "Your work will be lost."; };

window.onload = function() { ajaxCheckAvailability(); 
							getContact(<?php echo $_SESSION['bookCart']['id_company'] ?>,<?php echo $_SESSION['bookCart']['id_contacts'] ?>);
							getRateLetter(<?php echo $_SESSION['bookCart']['id_company'] ?>,<?php echo $_SESSION['bookCart']['rate_id'] ?>); };
							
<?php } ?>
</script>
<?php include_once("includes/footer.php")?>
<script>






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
