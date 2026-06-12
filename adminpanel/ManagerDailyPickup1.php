<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');
//---------------------------------------------------------------------------------------------------------
 if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');
	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` = '".$_REQUEST['delId']."'";
	
	$sqlDelUsers = selectRow(TBL_CUSTOMER," WHERE `id_customer` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One Contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_REQUEST['delId'])."'").' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_REQUEST['delId'])."'");
	}
}

///////////////
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CUSTOMER."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` IN (".addslashes($deleteIds).")";	
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}	






// ----------cate---------
$sql = "SELECT * FROM `daily_pickup`  WHERE id_shop='".addslashes($_SESSION['shop'])."'  ";

if($_REQUEST['search_name'] != ''){
		$sql .= " AND (`bill_no` LIKE '%".addslashes($_REQUEST['search_name'])."%'  )";
	}
if($_REQUEST['id_payment_status'] != ''){
	$sql .= " AND `id_payment_status` = '".addslashes($_REQUEST['id_payment_status'])."'";
}

if($_REQUEST['companyId'] != ''){
	$sql .= " AND `id_company` = '".addslashes($_REQUEST['companyId'])."'";
}

if($_REQUEST['id_executive'] != ''){
	$sql .= " AND `id_executive` = '".addslashes($_REQUEST['id_executive'])."' ";
}

if($_REQUEST['id_mobile'] != ''){
	$sql .= " AND B.`mobile` LIKE '%".addslashes($_REQUEST['id_mobile'])."%' ";
}
//debugData($_REQUEST);
if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==2){	
if($_REQUEST['date_created'] != ''){

		//list($from_book,$to_book) = split(" to ",$_REQUEST['date_created']);
			
$date_created= explode(" to ",$_REQUEST['date_created']);
	$from_book = $date_created['0'];
	$to_book = $date_created['1'];
		

			$sql .= " AND DATE(doc_date) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(doc_date) <= '".date('Y-m-d',strtotime($to_book))."' ";	

		

		
	}
}

if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']==1){	
if($_REQUEST['payment_date'] != ''){

		//list($from_book,$to_book) = split(" to ",$_REQUEST['payment_date']);
			
$payment_date= explode(" to ",$_REQUEST['payment_date']);
	$from_book = $payment_date['0'];
	$to_book = $payment_date['1'];
		

			$sql .= " AND DATE(payment_date) >='".date('Y-m-d',strtotime($from_book))."' AND DATE(payment_date) <= '".date('Y-m-d',strtotime($to_book))."' ";	

		

		
	}
}


if(@$_REQUEST['searchFormSubmit']!='1'){  // Last 24Hours- Record

	/*$date = new DateTime();
	$date->modify('-12 hours -30 minutes');
	$time	=$date->format('H:i:s');
	$UniqueDateFor = date ('Y-m-d h:i:s'); 
	$StartDateListFor	=	strtotime("-1 day", strtotime($UniqueDateFor));
	$UniqueDateFor = date ("Y-m-d h:i:s", $StartDateListFor); 
	$sql .= " AND `last_modified` >= '".$UniqueDateFor."'";*/

	
	$UniqueDateFor = date ('Y-m-d'); 
	$StartDateListFor	=	strtotime("-7 day", strtotime($UniqueDateFor));
	$UniqueDateFor = date ("Y-m-d", $StartDateListFor); 
	$sql .= " AND `doc_date` >= '".$UniqueDateFor."'";

}



if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `doc_date` DESC ";
}else{
	$sql .= " ORDER BY `doc_date` DESC";
}
$sqlDownload=$sql;
//echo $sql;
//exit;
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $numRows;



if($_REQUEST['Download']=='Download'){

	

	


	function cellColor($cells,$color){

	    global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

	        'type' => PHPExcel_Style_Fill::FILL_SOLID,

	        'startcolor' => array(

             'rgb' => $color

	       )

	    ));

	}

		



	
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	

	$shop_id	=	$_SESSION['shop'];
	
	
	$objPHPExcel->getProperties()->setCreator("Shafeer")

									 ->setLastModifiedBy("Shafeer")

									 ->setTitle("Conveyance Report")

									 ->setSubject("Conveyance Report")

									 ->setDescription("Conveyance Report")

									 ->setKeywords("Conveyance Report")

									 ->setCategory("Report");

		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

		$objDrawing->setName('Logo');       //set name to image

		$objDrawing->setDescription('Logo'); //set description to image

		$logo = selectColumn('fs_shop','image'," WHERE  id=".$shop_id." ");

		

		/*if(!isset($cron)){

			$signature = "../uploaded_files/shop/".$logo.""; 

		}

		else{

			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo.""; 

		}*/

		

		if(file_exists($signature)){

		$objDrawing->setPath($signature);

		$objDrawing->setOffsetX(25);                       //setOffsetX works properly

		$objDrawing->setOffsetY(10);                       //setOffsetY works properly

		$objDrawing->setCoordinates('G1');        //set image to cell

		 

		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue('A6', 'Daily Pickup Report   '.$CreationOrBookingDate);

$objPHPExcel->getActiveSheet()->mergeCells('A6:S6');

		$head_hotel_row = 7;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Bill No')
				
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Generation Date')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Payment on')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Payment')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Product Name')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
			
			
						->setCellValue($head_cntr_column++.$head_hotel_row, 'qty')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'rate')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'sales_revenue')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'cost')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'comission')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'discount')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'other_expenses')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'total_cost')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'profit')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'points')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'variable_in_rs');
			
			

			

	$styleArray = array(

	    'font'  => array(

	        'bold'  => true,

	        'color' => array('rgb' => 'FFFFFF'),

	        'size'  => 14,

			'text-transform' => 'uppercase',

	        'name'  => 'Calibri'

	    ));

	$styleArray_1 = array(

	    'font'  => array(

	        'bold'  => true,

	        'color' => array('rgb' => '000'),

	        'size'  => 10,

			'text-transform'=>'uppercase'

	       

	    ));



cellColor('A6:S6','254061');

cellColor('A7:S7','75923c');

	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7:S7')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:S7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	

	$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	

	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);

	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);
	
	 $styleThinBlackBorderOutline = array(

		'borders' => array(

			'allborders' => array(

				'style' => PHPExcel_Style_Border::BORDER_THIN,

				'color' => array('argb' => '000'),

			),

		),

	);	

	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);		

	$objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()

	    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

	    

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(14);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(15);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(22);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(22);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(25);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(30);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(28);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(18);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(18);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(7);	

	

	$head_hotel_row++;

	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)

	);
		$counter = 1;

		

	
	$Serialno=1;

		$connew = 8;

		$head_cntr_column = "A";$head_hotel_column = "A";
	
	
	$DailySql = mysqli_query($conn,$sqlDownload);
	
	
				  while($row = mysqli_fetch_object($DailySql)){
					  
					  
					  
				  $assignUserSql = "SELECT * FROM daily_pickup_details WHERE id_daily_pickup='".$row->id."' ";
$assignUserQuery= mysqli_query($conn,$assignUserSql);

while($assignUserResult=mysqli_fetch_object($assignUserQuery)){
	
	if($row->id_payment_status== '1'){
							$Pendingselected1 = 'Pending';
					}elseif($row->id_payment_status == '2'){
							$Pendingselected1 = 'Received';
					}
				  
				  
				  
									$head_order_data1 = "A";
									
									$head_order_data = "A"; 
									
									$objPHPExcel->getActiveSheet()->getStyle('A')->getFont()->setBold(false);
									
									$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':S'.$connew)->applyFromArray($styleThinBlackBorderOutline);
									
									$objPHPExcel->setActiveSheetIndex(0)
									
							->setCellValue($head_order_data++ . $connew, $Serialno++)
							->setCellValue($head_order_data++ . $connew,$row->bill_no)
							->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->doc_date)))
							
							->setCellValue($head_order_data++ . $connew, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_executive."'"))
							
							->setCellValue($head_order_data++ . $connew, date('d M Y',strtotime($row->payment_date)))
							
							->setCellValue($head_order_data++ . $connew, $Pendingselected1)
							
							->setCellValue($head_order_data++ . $connew, ($assignUserResult->id_product!=0?selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$assignUserResult->id_product."'"):"Direct"))
							
							->setCellValue($head_order_data++ . $connew,($row->id_company!=0?selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"):"Direct").$contactNameMobile)
							->setCellValue($head_order_data++ . $connew,$assignUserResult->qty)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->rate)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->sales_revenue)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->cost)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->comission)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->discount)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->other_expenses)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->total_cost)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->profit)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->points)
						->setCellValue($head_order_data++ . $connew,	$assignUserResult->variable_in_rs);
									
									$connew++;	
									
							
						
						
						
						
							
}
				  }
				  
				  
				  $objPHPExcel->getActiveSheet()->setTitle('Daily Pickup Report');

		

		$objPHPExcel->setActiveSheetIndex(0);

		

		

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);

$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);	

		

	*/		

$objPHPExcel->getActiveSheet()

    ->getPageMargins()->setTop(0.50);

$objPHPExcel->getActiveSheet()

    ->getPageMargins()->setRight(0.10);

$objPHPExcel->getActiveSheet()

    ->getPageMargins()->setLeft(0.15);

$objPHPExcel->getActiveSheet()

    ->getPageMargins()->setBottom(1);	

$objPHPExcel->getActiveSheet()

    ->getPageSetup()

    ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

$objPHPExcel->getActiveSheet()

    ->getPageSetup()

    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

	

	$fileName = 'Daily_Pickup_Report '.date('d_M_Y');

		ob_end_clean();

		if(!isset($cron)){

			// Redirect output to a client's web browser (Excel2007)

			header('Content-Type: application/vnd.ms-excel');

			header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');

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
		


}




?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Daily Pickup Manager <small>Manage Daily Pickup</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Daily Pickup</li>
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
        <h3 class="box-title">Search <small>Total Records: (
          <?=$numRows;?>
          ) &nbsp;</small> </h3>
        <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="edit-daily-pickup1.php">Add Daily Pickup</a>
          <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
          <ul class="dropdown-menu" role="menu">
            <?php ?>
            <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_CUSTOMER;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
            <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_CUSTOMER;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li>
            <?php ?>
          </ul>
        </div>
      </div>
      <!-- /.box-header -->
      <form name="searchForm" action="" method="get">
        <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label> Bill No</label>
                <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
            </div>
            <?php /*?><div class="col-md-6">
              <div class="form-group">
                <label>Contact Name</label>	
                <input type="text" name="search_name" class="form-control searchContact">			
				 <?php $categoryDropDown = '<select class="form-control select2" name="searchContactName" id="searchContactName">
											    <option value="">Select Contact Name</option>';
											  $resCat = selectSql(TBL_CUSTOMER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND type='2' ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_company'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.ucfirst($resultCat->first_name.' '.$resultCat->last_name).'">'.ucfirst($resultCat->first_name.' '.$resultCat->last_name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div><?php */?>
            <div class="col-md-4">
              <div class="form-group">
                <label>Company - City</label>
                <select class="form-control select2 itemName" name="companyId" id="companyId"   >
                </select>
                <!--<?php $categoryDropDown = '<select class="form-control select2" name="id_company">
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ".$_SESSION['Ids_user_access_Company']."  ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_company'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?> --> 
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>User/Executive</label>
                <?php 
				 $executiveDropDown = '<select class="form-control select2" name="id_executive">
											    <option value="" >Select Executive</option>';
											  $resCat = selectSql(TBL_USERS," where id_shop='".addslashes($_SESSION['shop'])."' AND status=1 ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_executive'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$executiveDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' '.ucfirst($resultCat->last_name).'</option>';
												}
										  }
											 	echo $executiveDropDown .= '</select>';
											  ?>
              </div>
            </div>
            <div class="form-group col-sm-4">
              <label for="date_created">
                <input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>
                &nbsp;Bill Date : From - To</label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeReport" placeholder="Enter booking date" id="date_created" name="date_created" value="<?php if($_REQUEST) echo $_REQUEST['date_created'];?>">
              </div>
              <!-- /.input group --> 
            </div>
            <div class="form-group col-sm-4">
              <label for="payment_date">
                <input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>
                &nbsp;Payment Date : From - To </label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeReport" placeholder="Enter Checkin date" name="payment_date" id="payment_date" data-parsley-required value="<?php if(isset($_REQUEST['payment_date'])) echo $_REQUEST['payment_date'];?>" data-parsley-errors-container="#payment_dateError"  automcomplete="off">
              </div>
              <!-- /.input group --> 
              <span id="payment_dateError"></span> </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Payment Status</label>
                <?php 
					if($_REQUEST['id_payment_status'] == '1'){
							$selected1 = 'selected="selected"';
					}elseif($_REQUEST['id_payment_status'] == '2'){
							$selected0 = 'selected="selected"';
					}
				  echo $statusDropDown = '<select class="form-control select2" name="id_payment_status" id="id_payment_status"> 
				  <option value="">Both</option>
				  <option '.$selected1.' value="1">Pending</option>
				  <option '.$selected0.' value="2">Received</option>
				  </select>';?>
              </div>
              <!-- /.form-group --> 
            </div>
            
            <!-- /.row --> 
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <input name="Search" type="submit" class="btn btn-primary" value="Search" />
          <?php  $checkMasterMenuAccess	=$_SESSION['userLevel'];
		
		 if($checkMasterMenuAccess==1){
			 ?>
          <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" style="margin-left:10px;" />
          <?php } ?>
        </div>
      </form>
    </div>
    <div class="row">
      <div class="col-xs-12"> 
        <!-- /.box -->
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Daily Pickup List</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />-->S.No.&nbsp;</th>
                    <th>Bill No</th>
                    <th>Executive</th>
                    <th>Company - City </th>
                    <th>Bill Date </th>
                    <th>Payment Date </th>
                    <th>Payment Status </th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                  <tr>
                    <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    <td><?=$row->bill_no;?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_executive."'");   ?></td>
                    <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'").'-'.selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$row->id_company."'");   ?></td>
                    <td><?php echo  date('d M Y',strtotime($row->doc_date));?></td>
                    <td><?php echo  $row->payment_date=='0000-00-00'?'-':date('d M Y',strtotime($row->payment_date));?></td>
                    <td><?php 
					 if($row->id_payment_status==1)
                		$PendingStatus = 'Pending';
                	else
                		$PendingStatus = 'Received';
					 
					 
					 echo  $PendingStatus;?></td>
                    <?php /*?> <td><?=$row->status=='1'?'<span onclick="location.href=\'ManagerDailyPickup.php?inactiveId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'ManagerDailyPickup.php?activeId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	<?php */?>
                    <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='edit-daily-pickup1.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp; <a  href="javascript:void(0);" id="viewbtn" onclick="OpenViewPopup('<?php echo encryptor('encrypt',$row->id); ?>');"><i class="fa fa-paper-plane"></i></a> 
                      
                      <!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id_customer;?>" onClick="deleteMe(this.id,this.name);"/>--></td>
                  </tr>
                  <?php }?>
                  <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
                  <tr>
                    <td align="right" colspan="5"><?php  echo $pagging->getLinks();?></td>
                  </tr>
                  <?php }else {?>
                  <tr>
                    <td height="200" align="center" colspan="5">---- No Record Found ---- </td>
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
<div id="viewincPopUp" class="well" style="display:none; background-color:#fff;">
  <div id="EditClaimIncentiveForm"></div>
  <div class="box-header with-border">
    <h3 class="box-title">Send Email</h3>
    <div class="box-tools pull-right">
      <button type="button" class="viewincPopUp_close btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
  </div>
  <input type="hidden" name="id_daily_pickup" id="id_daily_pickup" value="" />
 
  <div class="box-body table-responsive">
    <div id="MyTableGroupID">
     
        <div id="UrlLink">ssss</div>
        
          
        
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="viewincPopUp_close btn btn-secondary" data-bs-dismiss="modal">Close</button>
  </div>
</div>
<?php  ?>
<script type="text/javascript">
  
  document.getElementById("payment_date").value = "";
  
  	function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='ManagerDailyPickup.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_customer="+id, true);
  		  xhttp.send();
  	}


</script>
<?php include_once("includes/footer.php")?>
<script>

  	//search company names
  	     $('.itemName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
	  
	  
	  	//search company names
  	     $('.contactEmail').select2({
        placeholder: 'Select Email',
        ajax: {
          url: "ajax/ajaxSearchContactEmail.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
	  
	  
	  
	function OpenViewPopup(id_daily_pickup){
	 var cols1 = ""; 
	$('#viewincPopUp').popup('show');
$('#id_daily_pickup').val(id_daily_pickup);

cols1 = ' <table id="myTableTableList" class="table table-fixedTableGroup table-striped table-bordered dataTable no-footer" cellspacing="0"><tbody><tr>';
            cols1 += '<td><a href="mail-template/SendPaymentReminder.php?id='+id_daily_pickup+'" target="_blank" class="btn n-btn btn-block" style="width: 100% !important;">Payment Reminder</a></td>';
            cols1 += '</tr>';
           cols1 += ' <tr>';
              cols1 += '<td><a href="mail-template/SendRenewalReminder.php?id='+id_daily_pickup+'" target="_blank" class="btn n-btn btn-block" style="width: 100% !important;">Renewal Reminder</a></td>';
            cols1 += '</tr>'; 
			
			cols1 += ' <tr>';
              cols1 += '<td><a href="mail-template/SendPaymentReceipt.php?id='+id_daily_pickup+'" target="_blank" class="btn n-btn btn-block" style="width: 100% !important;">Payment Receipt</a></td>';
            cols1 += '</tr></tbody></table>';
$('#UrlLink').html(cols1);	

	/*var enquiryDate		=	$("#enquiryDate").val();
	var id_hotel_md	=	$("#id_hotel_md").val();
	
			
		*/
		/*	$.ajax({
				type: "POST",
				url: 'ajax/ajaxIncentiveViewForm.php',
				data: 'selectType=view&id_hotel_md='+id_hotel_md+'&enquiryDate='+enquiryDate+'&id_incentive='+id_incentive, 
				success: function (result) {
					$('#viewincPopUp').popup('show');
					$('#EditClaimIncentiveForm').html(result);	
					
				}
		});*/	
					
			
		
	
	}  
	  
	  
	  
  </script> 
