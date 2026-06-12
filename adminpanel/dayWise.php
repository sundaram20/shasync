<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	
	$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ";
		
	if($_SESSION['hotel_access']!=''){
		$condhotelAccess = "  AND A.id_hotel IN (".$_SESSION['hotel_access'].")";
		$hotel_cond = "  AND id IN (".$_SESSION['hotel_access'].")";
	}

	if($_REQUEST['id_hotel_md']!='' && $_REQUEST['id_hotel_md']!=0){
		$condhotelAccess = "  AND A.id_hotel IN (".$_REQUEST['id_hotel_md'].")";
	}
		
	if($_REQUEST['autoDate'] !="")
		$flash_date	=	date('Y-m-d',strtotime($_REQUEST['autoDate']));
	else
		$flash_date	=	date('Y-m-d',strtotime($_REQUEST['flash_date']));
	
	$sql= "SELECT B.name AS Hotel,
					SUM(CASE WHEN A.booking_status = '3' THEN A.total_products*A.no_of_days ELSE 0 END) AS Waitlisted,
					SUM(CASE WHEN A.booking_status = '4' THEN A.total_products*A.no_of_days ELSE 0 END) AS Cancelled,
					SUM(CASE WHEN (A.booking_status = '1' || A.booking_status = '2') THEN A.total_products*A.no_of_days ELSE 0 END ) AS Confirmed 
			FROM fs_orders A
			LEFT JOIN fs_hotels B ON A.id_hotel = B.id
			WHERE A.invoice_date ='".$flash_date."' ".$condhotelAccess." AND A.id_shop = '".$_SESSION['shop']."'GROUP BY A.id_hotel ORDER BY B.name ASC";
	
if($_REQUEST['Download'] == 'Generate'){
	error_reporting(1);
	$res = mysqli_query($conn,$sql);
	if($res){
		$numRows = mysqli_num_rows($res);
	}
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
								 ->setLastModifiedBy("Hitesh Aloney")
								 ->setTitle("Flash Report")
								 ->setSubject("Flash Report")
								 ->setDescription("Flash Report")
								 ->setKeywords("Flash Report")
								 ->setCategory("Report");
	
	if($numRows > 0){
	$counter = 1;
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A2', 'DAILY BOOKING REPORT ON  '.date("d-m-Y",strtotime($flash_date)));
	$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
	$head_hotel_row = 3;
	$head_cntr_column = "A";$head_hotel_column = "A";
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Hotel Name')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Waitlisted')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Cancelled')
		->setCellValue($head_cntr_column++.$head_hotel_row, 'Booked')
		->setCellValue($head_cntr_column.$head_hotel_row, 'Net Booked (Booked - Cancelled)');

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





$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleArray_1);
 $objPHPExcel->getActiveSheet()->getStyle('A3:F3')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);




$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('C11:G11')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);
	
 $styleThinBlackBorderOutline = array(
	'borders' => array(
		'allborders' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);	



	
$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()
    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(33);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(18);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(18);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(18);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(35);	

$head_hotel_row++;
					
					
						
	
	
	$Serialno=1;
	$connew = 4;
	while($row = mysqli_fetch_object($res)){
	
	echo "<br>";
	print_r($row);
	
	$head_order_data1 = "A";
	$head_order_data = "A";       
	
	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_order_data++ . $connew, $Serialno++)
->setCellValue($head_order_data++ . $connew, $row->Hotel)
->setCellValue($head_order_data++ . $connew, $row->Waitlisted)
->setCellValue($head_order_data++ . $connew, $row->Cancelled)
->setCellValue($head_order_data++ . $connew, $row->Confirmed)
->setCellValue($head_order_data . $connew, $row->Confirmed-$row->Cancelled);
	
$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($styleThinBlackBorderOutline);


$connew++;	
	}
	$forTotal = 'C';
	$totalArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':B'.$connew);
	$objPHPExcel->getActiveSheet(0)->setCellValue('A'.$connew,'Total');
	$objPHPExcel->getActiveSheet(0)->setCellValue('C'.$connew,'=SUM(C4:C'.($connew-1).')');
	$objPHPExcel->getActiveSheet(0)->setCellValue('D'.$connew,'=SUM(D4:D'.($connew-1).')');
	$objPHPExcel->getActiveSheet(0)->setCellValue('E'.$connew,'=SUM(E4:E'.($connew-1).')');
	$objPHPExcel->getActiveSheet(0)->setCellValue('F'.$connew,'=SUM(F4:F'.($connew-1).')');
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':F'.$connew)->applyFromArray($totalArray);
	$objPHPExcel->getActiveSheet()->getStyle($head_order_data1.$head_hotel_row.':'.$head_order_data .$connew)->applyFromArray($styleThinBlackBorderOutline);					
}//exit;
	$objPHPExcel->getActiveSheet()->setTitle('Flash Report');

	
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="daily_booking_report.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	
	if($_REQUEST['filePath'] =="SET"){
		//$path = getcwd()."/public_html/crs/adminpanel/autoMailerExport/daily_booking_report.xls";
		$path = getcwd()."/public_html/crs/adminpanel/autoMailerExport/booking.xls";
		//$path =getcwd();
		//echo $path;
		//$path = "/home/admingcs/public_html/crs/adminpanel/autoMailerExport/daily_booking_report.xls";
		$objWriter->save($path);
		mail("support@roomstatushub.com","download","file");
	}
	else
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
       Day Wise Booking Report 
        <small>Day Wise Booking Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Day Wise Booking Report</li>
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
          <h3 class="box-title">Day Wise Booking Report &nbsp;</small> </h3>          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
		  <!--<div class="form-group col-sm-4">
                   
                    <div class="form-group">
                  <label for="start_date">Checkin Date from</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
                    
                    <span id="reservation_dateError"></span> </div>
					
            <div class="col-md-4">
              <div class="form-group">
               
					
				
                  <label for="end_date">Checkin Date To</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end_date" name="end_date" value="<?php if($_POST) echo $_POST['end_date'];elseif($row->end_date) echo stripslashes(date('d-m-Y',strtotime($row->end_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>
              
              
            </div>-->
          
			
		  
		  
			<div class="col-md-4">
              <div class="form-group">
                  <label for="start_date">On Day</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="flash_date" value="<?php if($_POST) echo $_POST['flash_date'];elseif($row->flash_date) echo stripslashes(date('d-m-Y',strtotime($row->flash_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>

            <!--Hotel select list-->
			<div class="col-md-3" >
			   <div class="form-group">  
			   	<label for="start_date">Select Hotel</label>    
			       <?php 
			         $hotelDropDown = '<select class="form-control select2" name="id_hotel_md" >
			                              <option value="0">All Hotels</option>';
			                             $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' ".$hotel_cond." ",' ORDER BY `name`');
			                       if($db->num_rows2($resCat)){
			                         while($resultCat = $db->fetch_object2($resCat)){
			                         if($_REQUEST['id_hotel_md']!="" && trim($_REQUEST['id_hotel_md']) == $resultCat->id){
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
			       
			   </div>
			   <!--hotel select list end-->
         <!-- /.row -->
        </div>
			
		</div>
       
        <div class="box-footer">
       <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
        <input name="Download" type="submit" class="btn btn-primary" value="Generate" />
        </div>
		</form>		
      </div>
	  <!--<style>
	  #example2 tbody tr td, #example2 tbody tr th{padding:2px;}
	  </style>
      <div class="row">
        <div class="col-xs-12">	-->	     
          
          <!--<div class="box">
            <div class="box-header">-->
              <!--<h3 class="box-title">Pace Report List</h3>-->
            <!--</div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
             
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                
                </thead>
                <tbody>
				<?php 		
				


				if($total > 0){$counter = 1;
				
				?>
				<tr><th colspan=5>Pace Report On : <?php echo dateformat_date($pace_date)?></th><td colspan=1></td></tr>
				
				
				<tr>
                  <th>Hotel Name</th>
				  <th>Last Year</th>
				  <th>Confirm</th>
				  
                  
				  <th>Waitlisted</th>
				  <th>Cancelled</th>
				  <th>Variance Vs LY</th> 
				    



                </tr>
				<?php
				while($row = $db->fetch_object()){
						
						
						
						
						?>

							<tr>
								<td><?php echo $row->HotelName;?></td>
                                <td><?php echo $row->LY_ConfirmandTend; ?></td>
								<td><?php echo $row->ConfirmandTend;?></td>
								<td><?php echo $row->Waitlisted;?></td>								
								<td><?php echo $row->Cancelled;?></td>
								<td><?php echo $row->ConfirmandTend-$row->LY_ConfirmandTend;?></td>
								
								
							
							</tr/>

						<?php
				}
				?>
				
				
					
				<?php
				
				?>
			   
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
                 </tr>              
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form>
           
          </div>
          
        </div>
        
      </div>
      
    </section>
 -->
  </div>                                 
<?php include_once("includes/footer.php")?>  