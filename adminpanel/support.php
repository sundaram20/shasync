<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');

//---------------------------------------------------------------------------------------------------------

$sql = "SELECT * FROM support WHERE id_shop='".$_SESSION['shop']."'";


if(!empty($_REQUEST['search_serial'])){
$sql .= " AND (`serial` LIKE '%".addslashes($_REQUEST['search_serial'])."%' )";
}

if($_REQUEST['companyId'] != ''){
	$sql .= " AND `id_company` = '".addslashes($_REQUEST['companyId'])."'";
}


if($_REQUEST['id_products'] != ''){
	$sql .= " AND `id_product` = '".addslashes($_REQUEST['id_products'])."'";
}
	

if($_REQUEST['id_executive'] != ''){
	$sql .= " AND `id_mst_user_created_by` = '".addslashes($_REQUEST['id_executive'])."' ";
}

if (!empty($_REQUEST['mob'])) {
    $mob = addslashes($_REQUEST['mob']);
    $sql .= " AND `id_contacts` IN (
        SELECT id_customer FROM fs_customer WHERE mobile LIKE '%$mob%'
    )";
}


if(isset($_REQUEST['check_box']) && $_REQUEST['check_box'] == 1 && !empty($_REQUEST['date_created'])){
    $date_created = explode(" to ", $_REQUEST['date_created']);
    $from_book = $date_created[0];
    $to_book = $date_created[1];
    
    $sql .= " AND DATE(date_created) >= '".date('Y-m-d', strtotime($from_book))."' AND DATE(date_created) <= '".date('Y-m-d', strtotime($to_book))."' ";
}






	$sql .= " ORDER BY id DESC";

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

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Tally Serial No')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Item Name')


			->setCellValue($head_cntr_column++.$head_hotel_row, 'Contact person')
			
			
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Designation')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Mobile No')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Email')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name');
			
			

			

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
	
	$first_name =  selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row->id_contacts."'");
			$last_name = selectColumn(TBL_CUSTOMER,'last_name'," WHERE `id_customer` = '".$row->id_contacts."'");
	$contact_person = $first_name.' '.$last_name;
	
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
							
							->setCellValue($head_order_data++ . $connew, $row->serial_number)
							
							
							->setCellValue($head_order_data++ . $connew, ($assignUserResult->id_product!=0?selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$assignUserResult->id_product."'"):"Direct"))
							
							->setCellValue($head_order_data++ . $connew,$contact_person)
										
							->setCellValue($head_order_data++ . $connew, selectColumn(fs_designation_master,'name'," WHERE `id` = '".$row->id_contacts."'"))
						->setCellValue($head_order_data++ . $connew,	selectColumn(TBL_CUSTOMER,'mobile'," WHERE `id_customer` = '".$row->id_contacts."'"))
						->setCellValue($head_order_data++ . $connew,	selectColumn(TBL_CUSTOMER,'email'," WHERE `id_customer` = '".$row->id_contacts."'"))
						->setCellValue($head_order_data++ . $connew,	selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"));
						
									
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
	
	<style>
	.drawer {
  position: fixed;
  top: 50px; /* Match header height */
  right: 0;
  height: calc(100vh - 50px); /* Full screen minus header */
  width: calc(90vw - 230px); /* 230px = sidebar width */
  background-color: #fff;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.3);
  transform: translateX(100%); /* Fully hidden */
  transition: transform 0.4s ease;
  z-index: 999;
  padding: 20px;
  overflow-y: auto;
}

.drawer.open {
  transform: translateX(0); /* Slide in */
}

.close-btn {
  position: absolute;
  top: 10px;
  left: 10px; /* Top-left corner */
  background: red;
  color: white;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  z-index: 1000;
}




</style>
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Support Manager for Non Exist users<small>Manage Support</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Support for Non Exist Users</li>
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
        <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="addSupport.php">Add Support</a>
          
          
        </div>
      </div>
      <!-- /.box-header -->
      <form name="searchForm" action="" method="get">
        <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
           
			  
			              <div class="col-md-4">
              <div class="form-group">
                <label>Serial No</label>
                <input type="text" name="search_serial" id="search_name" value="<?php echo trim($_REQUEST['search_serial']);?>" class="form-control" />
              </div>
            </div>
			  
            
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
			  
			  <div class="col-md-4">
              <div class="form-group">
                <label>Products</label>
                <?php 
				 $productDropDown = '<select class="form-control select2" name="id_products">
											    <option value="" >Select Product</option>';
											  $resCatt = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."' AND status=1 ");
											  if($db->num_rows2($resCatt)){
											  	while($resultCatt = $db->fetch_object2($resCatt)){
													if($_REQUEST['id_product'] == $resultCatt->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$productDropDown .= '<option '.$selected.' value="'.$resultCatt->id.'">'.ucfirst($resultCatt->name).'</option>';
												}
										  }
											 	echo $productDropDown .= '</select>';
											  ?>
              </div>
            </div>
			  
			  <div class="col-md-4">
              <div class="form-group">
                <label>Mobile No</label>
                <input type="text" name="mob" id="search_name" value="<?php echo trim($_REQUEST['mob']);?>" class="form-control" />
              </div>
            </div>
			  
            <div class="form-group col-sm-4">
              <label for="date_created">
                <input type="checkbox" name="check_box" value="1" <?php ?>/>
                &nbsp;Bill Date : From - To</label>
              <div class="input-group">
                <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                <input type="text" class="form-control pull-right dateRangeReport" placeholder="Enter booking date" id="date_created" name="date_created" value="<?php if($_REQUEST) echo $_REQUEST['date_created'];?>">
              </div>
              <!-- /.input group --> 
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
            <h3 class="box-title">Non Existing Customers</h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />-->S.No.&nbsp;</th>
                    
					  <th>Generation Date</th>
					  <th>Executive</th>
					  <th>Tally Serial no</th>
					  <th>Item Name</th>
					  <th>Contact Person name</th>
                    <th>Designation</th>
					  <th>Mobile No</th>
                    <th>Email</th>
                    <th>Company Name</th>
					  <th>Last Remark</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
					
					 $sqlQuery = "SELECT sum(sales_revenue) as sales_revenueTotal,serial_number,id_product FROM `daily_pickup_details` WHERE id_daily_pickup='".$row->id."'  group by id_daily_pickup";
	
$resw = mysqli_query($connNew,$sqlQuery);


	$roww = mysqli_fetch_object($resw);	
				$Tax	=	($roww->sales_revenueTotal * 18)/100;									
		$TotalValue	=	 round($roww->sales_revenueTotal + $Tax);
					  
					  
					
					  $item_name =  selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_product."'");
			$first_name =  selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$row->id_contacts."'");
			$last_name = selectColumn(TBL_CUSTOMER,'last_name'," WHERE `id_customer` = '".$row->id_contacts."'");  
			$id_designation = selectColumn(TBL_CUSTOMER,'designation'," WHERE `id_customer` = '".$row->id_contacts."'");
					  $mobile = selectColumn(TBL_CUSTOMER,'mobile'," WHERE `id_customer` = '".$row->id_contacts."'");
					  $email = selectColumn(TBL_CUSTOMER,'email'," WHERE `id_customer` = '".$row->id_contacts."'");
					  $designation = selectColumn(fs_designation_master,'name'," WHERE `id` = '".$id_designation."'");
					  
					  $company = selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'");
					  $remark = selectColumn('support_details', 'support_remark', "WHERE `id_support` = '".$row->id."' ORDER BY id desc LIMIT 0,1");
					
					?>
                  <tr>
                    <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    
					  <td><?php echo  date('d M Y',strtotime($row->date_created));?></td>
					  <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_mst_user_created_by."'");   ?></td>
					  <td><?php echo $row->serial; ?></td>
                    <td><?php echo $item_name; ?></td>
					 <td><?php echo $first_name .' '. $last_name ;  ?></td> 
                    
                    <td><?php echo  $designation;?></td>
					  <td><?php echo $mobile;?></td>
					
					  <td><?php echo  $email;?></td>
					  <td><?php echo  $company;?></td>
					  <td><?php echo $remark ?></td>
					  
                    <?php /*?> <td><?=$row->status=='1'?'<span onclick="location.href=\'ManagerDailyPickup.php?inactiveId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'ManagerDailyPickup.php?activeId='.encryptor('encrypt',$row->id_customer).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	<?php */?>
                    <td><!--<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='addSupport.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />-->&nbsp;&nbsp;&nbsp;&nbsp;
						
						<?php /*
					if ($_SESSION['userLevel'] == 1) {
    					echo '<a id="" onclick="viewSupport(\'' . $row->id . '\')"><i class="fa fa-phone"></i></a>';
					}
						*/?>

                      <a id="" onclick="viewSupport('<?php echo $row->id; ?>')"><i class='fa fa-phone'></i></a>
						&nbsp;&nbsp;&nbsp;&nbsp;
					  <a  href="mail-template/supportSummaryMail.php?id='<?php echo encryptor('encrypt',$row->id); ?>'" target="_blank" ><i class="fa fa-paper-plane"></i></a>

                     </td>
                  </tr>
                  <?php }?>
                  <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
                  <tr>
                    <td align="right" colspan="11"><?php  echo $pagging->getLinks();?></td>
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

<!-- Drawer -->
<div id="rightDrawer" class="drawer position-relative">
  <div id="drawerContent"><!-- AJAX content will be loaded here --></div>
</div>

<div id="OpenListPopUpshow" class="well" style="display:none;"> </div>

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
	
	function viewSupport(id){
		console.log('viewsupport called');
	
		
		$.ajax({
			 url: 'ajax/ajaxSupportDrawer1.php',
			type: 'GET',
			data: { id: id },
			timeout: 10000,
			success: function (html) {
            $('#drawerContent').html(html);
            document.getElementById("rightDrawer").classList.add("open");
        },
        error: function () {
            $('#drawerContent').html('<div>Failed to load drawer content.</div>');
            alert("Failed to load drawer content.");
        }
		});
	}
	
	function closeDrawer(){
	document.getElementById("rightDrawer").classList.remove("open");
	}
	
	function addSupportFollowup1(makan){
		
		console.log(makan);
		$.ajax({
		url: 'ajax/ajaxAddSupp.php',
			type:'POST',
			data:{id:makan},
			 success: function (result) {		
					$('#OpenListPopUpshow').html(result);
				$('.datepickertest').datepicker({
            dateFormat: 'dd-mm-yy',
            minDate: 0
        });
  				    $('#OpenListPopUpshow').popup('show');
					
					
			},
			error:function (xhr, status, error) {
            alert('Failed to load assign form.');
            console.error('Assign AJAX error:', status, error);
        }
		});
	}
	
	function closePopupAndRefresh() {
    $("#OpenListPopUpshow").popup("hide");
    }
	
	function saveAddFollowupPopUpform(){
	console.log('save button ok');
		const formDrawer = document.getElementById('support_form');
    const formPopup = document.getElementById('AddFollowPopUpForm');
		
		const formData = new FormData(formDrawer);
    new FormData(formPopup).forEach((value, key) => {
        formData.append(key, value);
    });
		
		$.ajax({
        url: 'ajax/ajaxSaveSupport1.php', 
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            try {
                const res = JSON.parse(response);
                if (res.success) {
                    alert('Support & Followup saved successfully');
                    closeDrawer();
                    $('#OpenListPopUpshow').popup('hide');
					window.location.reload();
                } else {
                    alert('Error: ' + res.message);
                }
            } catch (e) {
                alert('Unexpected error');
                console.error(e);
            }
        },
        error: function (xhr, status, error) {
            alert('Request failed: ' + error);
        }
    });
	}
  
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
              cols1 += '<td><a href="mail-template/SendPaymentReceipt.php?id='+id_daily_pickup+'" target="_blank" class="btn n-btn btn-block" style="width: 100% !important;">Receipt Confirmation</a></td>';
		cols1 += '</tr>'; 
			
			cols1 += ' <tr>';
		     cols1 += '<td><a href="mail-template/SendThankYouMail.php?id='+id_daily_pickup+'" target="_blank" class="btn n-btn btn-block" style="width: 100% !important;">Thank You Mail</a></td>';
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
