<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');


//////For Updating Payment Date
	if(isset($_REQUEST['extended']) AND $_REQUEST['extended']!= ""){
		$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
		$updateSql = "UPDATE `fs_orders` SET payment_extend = payment_extend + 1 ,payment_date ='".date("Y-m-d",strtotime($_REQUEST['paymentExtendDate']))."' WHERE id_order = '".$_REQUEST['sentId']."' AND id_shop = '".$_SESSION['shop']."' ";
		$res = mysqli_query($conn,$updateSql);
		if($res){
			echo "<script>alert('Payment date extended Successfully.');</script>";
		}
		else{
			echo "<script>alert('Error! .');</script>";
		}
		mysqli_close($conn);
	}

//---------------------------------------------------------------------------------------------------------

// ----------cate---------
if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
	 $cond = " AND `".TBL_ORDERS."`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).") ";
	 $hotel_cond3 = " AND id IN  (".addslashes($_SESSION['HotelUserPermission']).")";
}	
$sql = " SELECT * FROM `".TBL_ORDERS."` where `".TBL_ORDERS."`.booking_status='2' and `id_shop` = '".addslashes($_SESSION['shop'])."'";

if($_REQUEST['id_hotel']!="" AND $_REQUEST['id_hotel']!=0 ){
	$sql .=" AND id_hotel = ".$_REQUEST['id_hotel']."";

}

if($_REQUEST['reminder_date'] !=''){
$reminderDate = explode(' to ',$_REQUEST['reminder_date'] );

	$sql .= " AND `".TBL_ORDERS."`.`payment_date` between '".date('Y-m-d',strtotime($reminderDate[0]) )."' and '".date('Y-m-d',strtotime($reminderDate[1]) )."' ".$cond." ORDER BY `".TBL_ORDERS."`.`checkin` ASC";

}else{
	$sql .= " AND `".TBL_ORDERS."`.`payment_date` between '".date('Y-m-d')."' and '".date('Y-m-d',(strtotime ( '+6 day' ,strtotime(date('d-m-Y')) ) ))."' ".$cond." ORDER BY `".TBL_ORDERS."`.`checkin` ASC";
}





if($_REQUEST['reminder_date'] !=''){
$reminderDate = explode(' to ',$_REQUEST['reminder_date'] );

	$link = " AND `".TBL_ORDERS."`.`payment_date` between '".date('Y-m-d',strtotime($reminderDate[0]) )."' and '".date('Y-m-d',strtotime($reminderDate[1]) )."' ".$cond." ORDER BY `".TBL_ORDERS."`.`checkin` ASC";

}else{
	$link = " AND `".TBL_ORDERS."`.`payment_date` between '".date('Y-m-d')."' and '".date('Y-m-d',(strtotime ( '+6 day' ,strtotime(date('d-m-Y')) ) ))."' ".$cond." ORDER BY `".TBL_ORDERS."`.`checkin`  ASC";
}


$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
	

if($_REQUEST['Download'] == 'Generate'){
	
$sql;

	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	

	$datawisearrayFinal = array();			
	if($total > 0){
		
		$cntr_order= 0;
		
		
		$head_hotel_row = 2;


			$head_cntr_column = "A";$head_hotel_column = "A";
			//$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue($head_cntr_column.$head_hotel_row, 'Date');
			$objPHPExcel->getActiveSheet()->getStyle(A1.":".J1)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($head_cntr_column++.$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);

			
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue($head_cntr_column.$head_hotel_row++, dateformat_date($dateCheckin))
				->setCellValue(A1, 'Follow Up Report ( FROM '.$_REQUEST['reminder_date'].' )')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Hotel Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Reservation Id')				
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Guest Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Source')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Created / Updated By')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Payment Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Date')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Checkin-Checkout')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Follow Up Date')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Reminders Sent');
				
				
				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_hotel_column.$head_hotel_row)->getFont()->setBold(true);
				$head_hotel_row++;
				
				
			$counter = 3;	
		while($rows = $db->fetch_object()){
		
							
	  $head_order_data = "A";

	$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rows->id_hotel."'"))
											
									
->setCellValue($head_order_data++ . $counter,  $rows->reference)
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$rows->id_customer."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rows->id_company."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rows->last_modified_by."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$rows->payment_status."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$rows->booking_status."'"))
->setCellValue($head_order_data++ . $counter,dateformat_date($rows->invoice_date))
->setCellValue($head_order_data++ . $counter, dateformat_date($rows->checkin)." - ".dateformat_date($rows->checkout))->setCellValue($head_order_data++ . $counter, dateformat_date($rows->payment_date))
->setCellValue($head_order_data++ . $counter, $rows->reminder);		
		
		
			
		$counter++;		
		}//die;
	}
	
	$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$head_order_data.'1');

	$styleArray = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => '1e51bf'),
		        'size'  => 12,
		        'name'  => 'Verdana'
		    ));
	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
		 $objPHPExcel->getActiveSheet()->getStyle('A1:'.$head_order_data.'1')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		// Add some data

	
	
	$objPHPExcel->getActiveSheet()->setTitle('Advance Reminder Report');

								
	//$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="advance_reminder_report.xls"');
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
<style type="text/css">
	#example2{border-collapse:collapse; table-layout:fixed; width:100%;}
    #example2 td { width:150px; word-wrap:break-word;}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hotel Manager
        <small>Manage Hotels</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Hotels</li>
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
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /* ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>&que=<?php echo $link; ?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>&que=<?php echo $link; ?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */ ?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->

        <!-- Trigger the modal with a button -->
        

        <!-- Modal -->
        <div id="extend" class="modal fade" role="dialog">
          <div class="modal-dialog" style="width: 300px;">

            <!-- Modal content-->
            <div class="modal-content" >
              <div class="modal-header" >
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Select Payment Date:</h4>
              </div>
              <div class="modal-body">
                <form class="text-center" action="" method="POST">
                	<input type="hidden" name="sentId" id="sentId" value="">
                	<input type="text" class="form-control pickerdate" placeholder="Enter payment date" id="paymentExtendDate" name="paymentExtendDate" value=""  data-parsley-required><br>
                	<input type="submit" name="extended" class="btn btn-primary" value="Update">
                </form>
              </div>
            </div>

          </div>
        </div>


		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
			
        <div class="box-body">
          <div class="row">	
          	
                      
       
		  <div class="form-group col-sm-4">
                <div class="input-group">
				  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
					<input type="text" class="form-control" name="reminder_date" value="<?php if($_REQUEST['reminder_date']) {echo $_REQUEST['reminder_date'];} else {echo date('d-m-Y',strtotime(date('Y-m-d')) ).' to '.date('d-m-Y',(strtotime ( '+6 day' ,strtotime(date('Y-m-d')) ) ));}  ?>" id="daterange-btn" readonly/> 
				                  
                </div>
              </div>	             
            <!-- /.col --> 
            	<div class="form-group col-sm-4 ">
                      <?php 
                          $hotelDropDown = '<select class="form-control select2 " name="id_hotel">
                                               <option value="0">All Hotels</option>';
                                              $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' ".$hotel_cond3." ",' ORDER BY `name`');
                                        if($db->num_rows2($resCat)){
                                          while($resultCat = $db->fetch_object2($resCat)){
                                          if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']!="" && trim($_REQUEST['id_hotel']) == $resultCat->id_hotel){
                                            $selected = 'selected="selected"';
                                         }else{
                                            $selected = '';
                                          }
                                          $hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' '.ucfirst($resultCat->last_name).'</option>';
                                       }
                                        }
                                        echo $hotelDropDown .= '</select>';
                         ?>
              </div> 
			
          <!-- /.row -->
        </div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" /> <input name="Download" type="submit" class="btn btn-primary" value="Generate" />
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Hotel List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped " >
                <thead>
                <tr>
				  <th >Reservation Id</th>
                  <th >Hotel Name</th>
				  <th >Guest Name</th>
				  <th >Source</th>
				  <th >Created / Updated By</td>  
				  <th >Payment Status</th>
				  <th >Checkin Date</th>     
				  <th >Payment Date</th>
				  <th >Extended</th>
				  <th >Reminder</th>                 
				  <th >Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
					  
				$EmailFormatFileExit  =	selectColumn(TBL_SHOP,'email_format_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
				
				if($EmailFormatFileExit!=''){
				
				$email_format_url  =	selectColumn(TBL_SHOP,'email_format_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
				}else{
				$email_format_url  =	'sendReminderMail.php';
				} 
					  
					  ?>
                <tr>
				  <td ><a href="editOrders.php?eId=<?=encryptor('encrypt',$row->id_order)?>&action=edit&page=<?=$_REQUEST['page']?>" target="_blank"><?=$row->reference;?></a></td>
                  <td ><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_hotel."'");?></td>
				  <td ><?=selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row->id_customer."'")."<br>".selectColumn(TBL_CUSTOMER,'email'," WHERE `id_customer` = '".$row->id_customer."'")."<br>".selectColumn(TBL_CUSTOMER,'phone'," WHERE `id_customer` = '".$row->id_customer."'")
				  ."<br>".selectColumn(TBL_CUSTOMER,'mobile'," WHERE `id_customer` = '".$row->id_customer."'");?></td>
				  <td style=" word-wrap: break-word !important;"><?=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'")."<br>".selectColumn(TBL_COMPANY,'email'," WHERE `id_company` = '".$row->id_company."'")."<br>".selectColumn(TBL_COMPANY,'mobile'," WHERE `id_company` = '".$row->id_company."'");?></td>
				  <td ><?=selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->last_modified_by."'");?></td>
				  <td ><?=selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$row->payment_status."'");?></td>
				  <th ><?=date("d-M-Y",strtotime($row->checkin));?></th> 
				  <td ><?=dateformat_date($row->payment_date);?></td>
				  <td ><?php echo '<span class="label label-success">'.$row->payment_extend.'</span>';?></td>	
				  <td ><?php echo '<span class="label label-success">'.$row->reminder.'</span>';?></td>			 
				  <td >				 
				  <a target="_blank" href="mail-template/<?php echo $email_format_url; ?>?mailId=<?php echo encryptor('encrypt',$row->id_order); ?>"  ><i class="fa fa-paper-plane"></i></a>&nbsp;&nbsp;
				  <a href="#" id="<?php echo $row->id_order?>,<?php echo date('d-m-Y',strtotime($row->payment_date))?>" name="extendHerf" data-toggle="modal" data-target="#extend" onClick="setExtend(this.id);"><i class="fa fa-pencil-square-o" ></i></a>
				  </td>
                </tr>
               <?php }?> 
			    
				<tr>	 
					  <td align="right" colspan="9"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="9">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?> 
<script type="text/javascript">
	function setExtend(id){
		var require = [];
		require = id.split(',');
		$('#sentId').val(require[0]);
		$('#paymentExtendDate').val(require[1]);
	}
</script> 