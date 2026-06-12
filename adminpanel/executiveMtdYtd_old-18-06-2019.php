<?php 



if(!isset($cron)){
	include_once("../config/auto_loader.php");
	//checkUserLevelPermission($_SESSION['userLevel'],'exe_portfolio','view');
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');
	$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	$session_shop=$shop_id=$_SESSION['shop'];
	$user_id = $_SESSION['userId'];
	$teamMember = $_SESSION['teamMembers'];
	$fileName = 'salesSummaryReport_'.date('d_M_Y');
	$teamMemberLevel =$_SESSION['teamMemberLevel'];
	$teamId = $_REQUEST['teamId'] ;
	$user_id = $_SESSION['userId'];
	if($_REQUEST['Download']=='Download')
		salesReport($conn,$objPHPExcel,$shop_id,$fileName,$cron,$teamId,$teamMemberLevel,$teamMember,$user_id);
	
}
else{
	$session_shop=$shop_id;
	$user_id = $setUser;
	$teamMember = $idsTeamMember;
	$_REQUEST['report_date'] = date('Y-m-d');
	$_REQUEST['Download']='Download';
	$teamMemberLevel =1;
}	

/*echo "<pre>";
print_r($_REQUEST);
echo "<pre>";
exit;*/


//debugData($_SESSION);
?>



<?php
function cellColor($cells,$color){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
      )
    ));
}
function salesReport($conn,$objPHPExcel,$session_shop,$fileName,$cron,$teamId,$teamMemberLevel='',$teamMember,$user_id){
if($_REQUEST['Download'] == 'Download'){
		error_reporting(1);

		if($_REQUEST['report_date'] !=''){
			$fromDate = date('Y-m-d',strtotime($_REQUEST['report_date']));
			$toDate =  date('Y-m-d',strtotime($_REQUEST['report_date']));
		}
		else{
			$fromDate = date('Y-m-d');
			$toDate = date('Y-m-d');
		}

		$mtdfrom = date('Y-m-01',strtotime($fromDate));

		if(date('m',strtotime($fromDate)) <= 3){
			$ytdfrom = date('Y-04-01',strtotime('-1 years',strtotime($fromDate)));
		}
		else{
			$ytdfrom = date('Y-04-01');
		}

		if($_SESSION['userLevel'] !=1){
			if($teamMemberLevel!=1){
				$cond = "AND `".TBL_DAILYVISIT."`.id_user='".$user_id."' " ;
			}
			else{
				$cond = "AND `".TBL_DAILYVISIT."`.id_user IN (".$teamMember.") " ;
			}
		}


		if($teamId!=''){
			$cond.=" AND  FIND_IN_SET ('".$teamId."',".TBL_USERS.".ids_team)";
		}

		$sql = "SELECT `".TBL_DAILYVISIT."`.id_user,".TBL_USERS.".ids_team,".TBL_TEAM.".name as teamname,
					COUNT(IF(dated BETWEEN '".$fromDate."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL) ) AS	visit,
					SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyance,
					SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainment,
					SUM(CASE WHEN (dated BETWEEN '".$fromDate."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunch,
					COUNT(IF(dated BETWEEN '".$mtdfrom."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL)) AS	visitMtd,
					SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceMtd,
					SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainmentMtd,
					SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunchMtd,
					COUNT(IF(dated BETWEEN '".$ytdfrom."' AND '".$toDate."',`".TBL_DAILYVISIT."`.id, NULL)) 			AS	visitYtd,
					SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceYtd,
					SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(entertainment,0) else 0 end) AS	entertainmentYtd,
					SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(lunch,0) else 0 end) AS	lunchYtd

					FROM `".TBL_DAILYVISIT."`
					LEFT JOIN ".TBL_USERS." 
					
					ON `".TBL_DAILYVISIT."`.id_user=".TBL_USERS.".id
					LEFT JOIN ".TBL_TEAM." ON `".TBL_TEAM."`.id=".TBL_USERS.".ids_team
					WHERE `".TBL_DAILYVISIT."`.id_shop=".$session_shop." ".$cond." GROUP BY `".TBL_DAILYVISIT."`.id_user ORDER BY ".TBL_TEAM.".name";		
					
			



			$res = mysqli_query($conn,$sql);
				if($res){
					$numRows = mysqli_num_rows($res);
				}
				
							
		
		$numRows;
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
									 ->setLastModifiedBy("Hitesh Aloney")
									 ->setTitle("Company Portfoilo Report")
									 ->setSubject("Company Portfoilo Report")
									 ->setDescription("Company Portfoilo Report")
									 ->setKeywords("Company Portfoilo Report")
									 ->setCategory("Report");

		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		$logo = selectColumn('fs_shop','image'," WHERE  id=".$session_shop." ");
		if($cron!=''){
			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo.""; 
		}
		else{
			$signature = "../uploaded_files/shop/".$logo.""; 
		}

	   //Path to signature .jpg file
		if(file_exists($signature)){
			$objDrawing->setPath($signature);
			$objDrawing->setOffsetX(20);                       //setOffsetX works properly
			$objDrawing->setOffsetY(10);                       //setOffsetY works properly
			$objDrawing->setCoordinates('G1');        //set image to cell
			/*$objDrawing->setWidth(300);                 //set width, height
			$objDrawing->setHeight(130);*/  
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet(0));
		}  //save										 
		
		if($numRows > 0){
		$counter = 1;
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A6', 'Sales Summary Report As On '.date('d-M-Y',strtotime($_REQUEST['report_date'])));
		$objPHPExcel->getActiveSheet()->mergeCells('A6:M6');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
		$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
		$objPHPExcel->getActiveSheet()->mergeCells('D7:H7');
		$objPHPExcel->getActiveSheet()->mergeCells('I7:M7');
		$head_hotel_row = 7;
		$head_cntr_column = "A";$head_hotel_column = "A";
		

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$head_hotel_row, 'S.No.')
			->setCellValue('B'.$head_hotel_row, 'Team')
			->setCellValue('C'.$head_hotel_row, 'Executive')
			->setCellValue('D'.$head_hotel_row, 'Month to date')
			->setCellValue('I'.$head_hotel_row++, 'Year to date');

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D8', 'Visit')
			->setCellValue('E8', 'Conveyance')
			->setCellValue('F8', 'Entertainment')
			->setCellValue('G8', 'Lunch')
			->setCellValue('H8', 'Total Expense')
			->setCellValue('I8', 'Visit')
			->setCellValue('J8', 'Conveyance')
			->setCellValue('K8', 'Entertainment')
			->setCellValue('L8', 'Lunch')
			->setCellValue('M8', 'Total Expense');
				

	$styleArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => 'ffffff'),
	        'size'  => 14,
	        'name'  => 'Verdana'
	    ));

	$styleArray_1 = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '000000'),
	        'size'  => 10,
	        'name'  => 'Verdana'
	    ));
	

	cellColor('A6:M6','254061');

	cellColor('A7:M8','75923c');




	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
	 $objPHPExcel->getActiveSheet()->getStyle('A6:M6')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

	$objPHPExcel->getActiveSheet()->getStyle('A7:M8')->applyFromArray($styleArray_1);
	 $objPHPExcel->getActiveSheet()->getStyle('A7:M8')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);


	$objPHPExcel->getActiveSheet()->getStyle('A7:A7')->getAlignment()->applyFromArray(
	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
	);
	  $objPHPExcel->getActiveSheet()->getStyle('B7:B8')->getAlignment()->applyFromArray(
	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
	);
	  $objPHPExcel->getActiveSheet()->getStyle('C7:C8')->getAlignment()->applyFromArray(
	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
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
	$objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

	$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('M')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	

	/*$objPHPExcel->getActiveSheet()->getStyle('C11:K11')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);*/
		
	 $styleThinBlackBorderOutline = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000'),
			),
		),
	);	



		
	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(22);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(10);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(10);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(15);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(18);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(10);		
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(20);
	$head_hotel_row++;
						
						
							
		$visit =0;
		$conveyance =0;
		$entertainment =0;
		$lunch =0;

		$visitMtd =0;
		$conveyanceMtd =0;
		$entertainmentMtd =0;
		$lunchMtd =0;

		$visitYtd =0;
		$conveyanceYtd =0;
		$entertainmentYTd =0;
		$lunchYtd =0;
		
		$Serialno=1;
		$connew = 9;
		while($row = mysqli_fetch_object($res)){
		
		$teamName = selectColumn(TBL_TEAM,'name','where id="'.$row->ids_team.'" ');
//////////////////////////Other Activites

if($_SESSION['userLevel'] !=1){
			if($teamMemberLevel!=1){
				$cond_other_activites = "AND `".TBL_OTHER."`.id_user='".$user_id."' " ;
			}
			else{
				$cond_other_activites = "AND `".TBL_OTHER."`.id_user IN (".$teamMember.") " ;
			}
		}

		if($teamId!=''){
			$cond_other_activites.=" AND  FIND_IN_SET ('".$teamId."',".TBL_USERS.".ids_team)";
		}

					
		
$sqlother_activites = "SELECT `".TBL_OTHER."`.id_user,".TBL_USERS.".ids_team,
					SUM(CASE WHEN (dated BETWEEN '".$mtdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceMtd,
					SUM(CASE WHEN (dated BETWEEN '".$ytdfrom."' AND '".$toDate."') THEN ROUND(Total,0) else 0 end) AS	conveyanceYtd

					FROM `".TBL_OTHER."`
					LEFT JOIN ".TBL_USERS." 
					ON `".TBL_OTHER."`.id_user=".TBL_USERS.".id
					WHERE `".TBL_OTHER."`.id_shop=".$session_shop." ".$cond_other_activites." AND  ".TBL_USERS.".id = ".$row->id_user." GROUP BY `".TBL_OTHER."`.id_user ORDER BY ".TBL_USERS.".ids_team,".TBL_USERS.".name";		
					
			

			$resother_activites = mysqli_query($conn,$sqlother_activites);
				if($resother_activites){
					$numRowsother_activites = mysqli_num_rows($resother_activites);
				}
	$row_other_activites = mysqli_fetch_object($resother_activites);
///////////////////////////////Othere activites END	








		$head_order_data1 = "A";
		$head_order_data = "A";       
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue($head_order_data++ . $connew, $Serialno++)
		->setCellValue($head_order_data++ . $connew, ucwords($teamName))
		->setCellValue($head_order_data++ . $connew, ucwords(selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_user."' ")))
		->setCellValue($head_order_data++ . $connew, $row->visitMtd)
		->setCellValue($head_order_data++ . $connew, ($row->conveyanceMtd+$row_other_activites->conveyanceMtd))
		->setCellValue($head_order_data++ . $connew, $row->entertainmentMtd)
		->setCellValue($head_order_data++ . $connew, $row->lunchMtd)
		->setCellValue($head_order_data++ . $connew, ($row->entertainmentMtd+$row->conveyanceMtd+$row->lunchMtd+$row_other_activites->conveyanceMtd))
		->setCellValue($head_order_data++ . $connew, $row->visitYtd)
		->setCellValue($head_order_data++ . $connew, $row->conveyanceYtd+$row_other_activites->conveyanceYtd)
		->setCellValue($head_order_data++ . $connew, $row->entertainmentYtd)
		->setCellValue($head_order_data++ . $connew, $row->lunchYtd)
		->setCellValue($head_order_data++ . $connew, ($row->lunchYtd+$row->entertainmentYtd+$row->conveyanceYtd+$row_other_activites->conveyanceYtd));

		$objPHPExcel->getActiveSheet()->getStyle('A6:M'.$connew)->applyFromArray($styleThinBlackBorderOutline);

		
		$visitMtd +=$row->visitMtd;
		$conveyanceMtd +=$row->conveyanceMtd;
		$entertainmentMtd +=$row->entertainmentMtd;
		$lunchMtd +=$row->lunchMtd;
		$visitYtd +=$row->visitYtd;
		$conveyanceYtd +=$row->conveyanceYtd;
		$entertainmentYTd +=$row->entertainmentYtd;
		$lunchYtd +=$row->lunchYtd;
	
		$conveyanceOtherMtd +=$row_other_activites->conveyanceMtd;
		$conveyanceOtherYtd +=$row_other_activites->conveyanceYtd;	
	
		$connew++;	
	}
		$forTotal = 'D';
		$totalArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '1e51bf'),
	        'size'  => 12,
	        'name'  => 'Verdana'
	    ));
		cellColor('A'.$connew.':M'.$connew,'cbf9b8');
	    $objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':C'.$connew);
		$objPHPExcel->getActiveSheet(0)->setCellValue('A'.$connew,'Grand Total');
		$objPHPExcel->getActiveSheet(0)->setCellValue('D'.$connew,$visitMtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('E'.$connew,$conveyanceMtd+$conveyanceOtherMtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('F'.$connew,$entertainmentMtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('G'.$connew,$lunchMtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('H'.$connew,($conveyanceMtd+$conveyanceOtherMtd+$entertainmentMtd+$lunchMtd));
		$objPHPExcel->getActiveSheet(0)->setCellValue('I'.$connew,$visitYtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('J'.$connew,$conveyanceYtd+$conveyanceOtherYtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('K'.$connew,$entertainmentYTd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('L'.$connew,$lunchYtd);
		$objPHPExcel->getActiveSheet(0)->setCellValue('M'.$connew,($conveyanceYtd+$conveyanceOtherYtd+$entertainmentYTd+$lunchYtd));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($totalArray);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);
		
						
	}
		$objPHPExcel->getActiveSheet()->setTitle('Sales Summary Report');

		
		$objPHPExcel->setActiveSheetIndex(0);

		ob_end_clean();
		// Redirect output to a client’s web browser (Excel2007)
		if(!isset($cron)){
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
		else{
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			//local
			//$objWriter->save('../mailattach/'.$fileName.'.xls');
			
			//server
			$objWriter->save('/home/admingcs/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');	
		}
	
}
 
} 
?>

<?php 
if(!isset($cron)){
include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Sales Summary<small>Sales Summary Report</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Sales Summary</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="nav-tabs-custom">
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
          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->
            <ul class="dropdown-menu" role="menu">
              <?php /*?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_RATE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_RATE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
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
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" autocomplete="off" id="pace_date" name="report_date" value="<?php if($_POST) echo $_POST['report_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>

            <div class="col-md-4">
                            <div class="form-group">
                            <label>Team</label>
                            <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                           <?php $categoryDropDown = '<select class="form-control select2" name="teamId" id="teamId">
                        <option value="">All</option>';
                          $resUserLevel = selectSql(TBL_TEAM," WHERE `status` = '1'  AND  id IN (".$_SESSION['teamId'].") AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
                                    if($db->num_rows2($resUserLevel)){
                                      while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                      if($_REQUEST['teamId'] == $resultUserLevel->id){
                                        $selected = 'selected="selected"';
                                      }else{
                                        $selected = '';
                                      }
                                      $categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
                                    }
                                    }
                                    echo $categoryDropDown .= '</select>';
                                    ?>
                                                          
                                                          </div>
                          </div>

                <!-- /.input group --> 

                <span id="reservation_dateError"></span> </div>
              <!--<div class="form-group col-sm-6">
                <label for="seasonId">Date <font color="#FF0000">*</font></label>
                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
              </div>-->
              <!--<div class="col-md-4">
                <div class="form-group">
                  <label>Company</label>
                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !='' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['companyId'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
                </div>
              </div>-->
              

			            <!--Area Executive-->
			            <!--<div class="col-md-4">
			                <div class="form-group">
			                  <label>Area</label>				
			  				 <?php $categoryDropDown = '<select class="form-control select2" name="id_area">
			  											  <option value="">Select Area</option>  ';
			  											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
			  											  if($db->num_rows2($resCat)){
			  											  	while($resultCat = $db->fetch_object2($resCat)){
			  													if($_REQUEST['id_area'] == $resultCat->id){
			  														$selected = 'selected="selected"';
			  													}else{
			  														$selected = '';
			  													}
			  													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
			  												}
			  											  }
			  											 	echo $categoryDropDown .= '</select>';
			  											  ?>
			                </div>
			  			  			
			            </div>-->
			            <!--Area Executive End-->

              <!--<div class="col-md-4">
                <div class="form-group">
                <label>Sales Executive</label>-->
                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
               <?/*php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
											  								<option value="">All</option>';
											  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  ".$ConditonUserLevel." `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['usernameid'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->username).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  */?>
                                              
                                             <!-- </div>
              </div>-->
              <!-- /.col -->
              
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />
            </div>
            
        </form>
      
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"></h3>
          </div>
          <form name="listingForm" action="" method="post">
            <input type="hidden" value="" name="act" />
            <div id="listingDiv"></div>
            <!-- /.box-header -->
            
             
               

                        <!--<div class="box-body table-responsive">
                          <table id="example2" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                              <th rowspan="2">S.No. </th>		
                              <th width="20%" rowspan="2">Executive Name&nbsp;</th>
            				  <th colspan="3">Today</th>
            				  <th colspan="3">MTD</th>
            				  <th colspan="3">YTD</th>				  
                            </tr>
                            <tr>
                              <th >Visit</th>		
                              <th >Conveyance</th>
            				  <th>Entertainment</th>
            				  <th >Visit</th>		
                              <th >Conveyance</th>
            				  <th>Entertainment</th>
            				  <th >Visit</th>		
                              <th >Conveyance</th>
            				  <th>Entertainment</th>
            							  
                            </tr>
                            </thead>
                            <tbody>
            				<?php 

            				//$res = mysqli_query($conn,$sql);
            							 				
            				if($total > 0){$counter = 1;
            				  while($row = mysqli_fetch_object($res)){?>
                            <tr>
                               <th><?=$counter++;?></th> 
                               <td><?php echo selectColumn(TBL_USERS,'name'," WHERE id_user=".$row->id_user." "); ?></td>    
                               <td ><?=$row->visit;?></td>
                               <td><?=$row->conveyance;?></td>
                               <td><?=$row->entertainment;?></td>
                               <td ><?=$row->visitMtd;?></td>
                               <td><?=$row->conveyanceMtd;?></td>
                               <td><?=$row->entertainmentMtd;?></td>
                               <td ><?=$row->visitYtd;?></td>
                               <td><?=$row->conveyanceYtd;?></td>
                               <td><?=$row->entertainmentYtd;?></td>
                            </tr>-->

                           <?php }?> 
            			   
            				<!--<tr>	 
            					  <td align="right" colspan="13"><?php  echo $pagging->getLinks();?> </td>
                             </tr>  -->             
            				<?php }else {?>
            				
            				 <tr>
                                  <td height="200" align="center" colspan="13">---- No Record Found ---- </td>
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
  
  <div id="duplicate" class="well" style="display:none;">
    
  </div>
  <?php include_once("includes/footer.php")	;
	}
  ?>