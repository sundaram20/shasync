<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');

//---------------------------------------------------------------------------------------------------------
//$id_customer_array = array();	


 $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_CUSTOMER."` WHERE type='1'  and `id_shop` = '".addslashes($_SESSION['shop'])."' and  (user_type= 'VIP' || user_type= 'CIP') "); 
		 while($rowGuestDetail = $db->fetch_object2($sqlGuestDetail)){

			$id_customer_array[] = $rowGuestDetail->id_customer;

		}

$str = implode (", ", $id_customer_array);
// ----------cate---------
 $sql = " SELECT * FROM `".TBL_ORDERS."` where  id_customer IN ($str) AND `id_shop` = '".addslashes($_SESSION['shop'])."'";



if($_REQUEST['reminder_date'] !=''){
$reminderDate = explode(' to ',$_REQUEST['reminder_date'] );

	$sql .= " AND `".TBL_ORDERS."`.`checkin` between '".date('Y-m-d',strtotime($reminderDate[0]) )."' and '".date('Y-m-d',strtotime($reminderDate[1]) )."'";

}else{
	$sql .= " AND `".TBL_ORDERS."`.`checkin` between '".date('Y-m-d')."' and '".date('Y-m-d',(strtotime ( '+6 day' ,strtotime(date('d-m-Y')) ) ))."'";
}

if($_REQUEST['reminder_date'] !=''){
$reminderDate = explode(' to ',$_REQUEST['reminder_date'] );

	$link = " AND `".TBL_ORDERS."`.`checkin` between '".date('Y-m-d',strtotime($reminderDate[0]) )."' and '".date('Y-m-d',strtotime($reminderDate[1]) )."'";

}else{
	$link = " AND `".TBL_ORDERS."`.`checkin` between '".date('Y-m-d')."' and '".date('Y-m-d',(strtotime ( '+6 day' ,strtotime(date('d-m-Y')) ) ))."'";
}
	
//echo $sql;
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
	

if($_REQUEST['Download'] == 'Download'){
	
$sql;

	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	

	$datawisearrayFinal = array();			
	if($total > 0){
		
		$cntr_order= 0;
		
		
		$head_hotel_row = 1;


			$head_cntr_column = "A";$head_hotel_column = "A";
			//$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue($head_cntr_column.$head_hotel_row, 'Date');
			$objPHPExcel->getActiveSheet()->getStyle($head_cntr_column++.$head_hotel_row.":".$head_cntr_column.$head_hotel_row)->getFont()->setBold(true);
			
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue($head_cntr_column.$head_hotel_row++, dateformat_date($dateCheckin))
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Hotel Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Reservation Id')				
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Guest Name')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Source')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Payment Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Status')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Booking Date')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'Checkin-Checkout')
				->setCellValue($head_hotel_column++.$head_hotel_row, 'User Type');
				
				
				$objPHPExcel->getActiveSheet()->getStyle("A".$head_hotel_row.":".$head_hotel_column.$head_hotel_row)->getFont()->setBold(true);
				$head_hotel_row++;
				
				
			$counter = 2;	
		while($rows = $db->fetch_object()){
		
							
	  $head_order_data = "A";

	$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$rows->id_hotel."'"))
											
									
->setCellValue($head_order_data++ . $counter,  $rows->reference)
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$rows->id_customer."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$rows->id_company."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$rows->payment_status."'"))
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_HTL_BOOKING_STATUS,'name'," WHERE `id` = '".$rows->booking_status."'"))
->setCellValue($head_order_data++ . $counter,dateformat_date($rows->invoice_date))
->setCellValue($head_order_data++ . $counter, dateformat_date($rows->checkin)." - ".dateformat_date($rows->checkout))	
->setCellValue($head_order_data++ . $counter, selectColumn(TBL_CUSTOMER,'user_type'," WHERE `id_customer` = '".$rows->id_customer."'"));		
		
		
			
		$counter++;		
		}//die;
	}
	


	
		// Add some data

	
	
	$objPHPExcel->getActiveSheet()->setTitle('Vip and Cip Report');

								
	//$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="vip_cip_report.xls"');
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
      <h1>
        VIP & CIP Manager
        <small>Manage  VIP & CIP</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  VIP & CIP</li>
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
							<!--  <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>-->
							  <ul class="dropdown-menu" role="menu">
								<?php /* ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>&que=<?php echo $link; ?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>&que=<?php echo $link; ?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */ ?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
			
        <div class="box-body">
          <div class="row">	
		  <div class="form-group col-sm-4">
                <label>Date	:</label>
                <div class="input-group">
				  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
					<input type="text" class="form-control" name="reminder_date" value="<?php if($_REQUEST['reminder_date']) {echo $_REQUEST['reminder_date'];} else {echo date('d-m-Y',strtotime(date('Y-m-d')) ).' to '.date('d-m-Y',(strtotime ( '+6 day' ,strtotime(date('Y-m-d')) ) ));}  ?>" id="daterange-btn" readonly/> 
				                  
                </div>
              </div>	             
            <!-- /.col -->  
			
          <!-- /.row -->
        </div>
		</div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" /> <input name="Download" type="submit" class="btn btn-primary" value="Download" />
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">VIP / CIP  List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>
				  <th>Reservation Id</th>
                  <th>Hotel Name</th>
				  <th>Guest Name</th>
				  <th>Source</th>
				  <th>Payment Status</th>    
                   <th>User Type</th>     
			<!--	  <th>Payment Date</th>
				  <th>Reminder</th>                 
				  <th>Action</th>-->
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
                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_order;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
				  <td><a href="editOrders.php?eId=<?=encryptor('encrypt',$row->id_order)?>&action=edit&page=<?=$_REQUEST['page']?>" ><?=$row->reference;?></a></td>
                  <td><?=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_hotel."'");?></td>
				  <td><?=selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row->id_customer."'");?></td>
				  <td><?=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'");?></td>
				  <td><?=selectColumn(TBL_ORDER_STATE,'name'," WHERE `id_order_state` = '".$row->payment_status."'");?></td>
                  <td><?=selectColumn(TBL_CUSTOMER,'user_type'," WHERE `id_customer` = '".$row->id_customer."'");?></td>
				<!--  <td><?=dateformat_date($row->payment_date);?></td>	
				  <td><?php echo '<span class="label label-success">'.$row->reminder.'</span>';?></td>			 
				  <td>				 
				  <a href="mail-template/<?php echo $email_format_url; ?>?mailId=<?php echo encryptor('encrypt',$row->id_order); ?>"  ><i class="fa fa-paper-plane"></i></a>&nbsp;&nbsp;
				  </td>-->
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