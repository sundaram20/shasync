<?php include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

					  

					  

$sql = "SELECT  a.id_company,a.id_default_group,a.name,b.name AS area,c.id as id_user,c.name AS executive  FROM `".TBL_COMPANY."` AS a

		LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

		LEFT JOIN `".TBL_USERS."` AS c ON b.user_id=c.id

		WHERE a.`id_shop` = '".addslashes($_SESSION['shop'])."' ";





if($_REQUEST['searchFormSubmit'] =='1'){





	if($_REQUEST['usernameid'] != ''){

		$sql .= " AND c.id = '".addslashes($_REQUEST['usernameid'])."'  ";

	}



	if($_REQUEST['id_area'] != ''){

		$sql .= " AND b.id = '".addslashes($_REQUEST['id_area'])."' ";

	}



	if($_REQUEST['companyId'] != ''){

		$sql .= " AND a.id_company = '".addslashes($_REQUEST['companyId'])."' ORDER BY a.name ";

	}



	





}



?>



<?php

	

	

	if($_REQUEST['Search'] == 'Search'){

		
		

	$res = mysqli_query($conn,$sql);

	$total=mysqli_num_rows($res);

	



	$db->query($sql);

	$numRows= $db->num_rows();

	$pagging = new pagingClass($sql,$setpage);

	$db->query($pagging->getQuery());

	$total = $db->num_rows();







 }?>
	}
	}



<?php

if($_REQUEST['Download'] == 'Download'){

		error_reporting(1);

		$res = mysqli_query($conn,$sql);

		if($res){

			$numRows = mysqli_num_rows($res);

		}

		 $numRows;



		/* setting dates for Achieved values*/

		$date = date('Y-m-d');

		$month = date('m',strtotime($date));

		if($month <= 3){

			$date = strtotime($date);

			$date = strtotime("-1 year",$date);

			$date = date('Y-04-01',$date);

		}

		else{

			$date =date('Y-04-01');

		}

		if(date('m')<=3){
			$period = date('Y',strtotime('-1 years',strtotime(date('Y')))).'-'.date('y');
		}
		else{
			$period = date('Y').'-'.date('y');	
		}
		

		$end = strtotime($date);

		$end = strtotime("+1 year",$end);

		$end = date('Y-03-31',$end);

		

		$prevYear = strtotime($date);

		$prevYear = strtotime("-1 year",$prevYear);

		echo "<br>".$prevYear = date('Y-04-01',$prevYear);

		echo "<br>".$prevYearEnd = date('Y-03-31',strtotime($date));



		$prevYear2 = strtotime($date);

		$prevYear2 = strtotime("-2 year",$prevYear2);

		echo "<br>".$prevYear2 = date('Y-04-01',$prevYear2);

		echo "<br>".$prevYear2End = date('Y-03-31',strtotime($prevYear));

		

		/*date ends*/

		

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

		$logo = selectColumn('fs_shop','image'," WHERE  id=".$_SESSION['shop']." ");

		$signature = "../uploaded_files/shop/".$logo.""; 

		   //Path to signature .jpg file

		

		if(file_exists($signature)){

		$objDrawing->setPath($signature);

		$objDrawing->setOffsetX(20);                       //setOffsetX works properly

		$objDrawing->setOffsetY(10);                       //setOffsetY works properly



		$objDrawing->setCoordinates('G1');        //set image to cell



		//$objDrawing->setWidth(300);                 //set width, height

		//$objDrawing->setHeight(100);  

		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save							 

		

		if($numRows > 0){

		$counter = 1;

		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('A6', 'Executive Portfoilo Report Year :'.$period);

		$objPHPExcel->getActiveSheet()->mergeCells('A6:Q6');



		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('F7', 'Rate Number');

		//$objPHPExcel->getActiveSheet()->mergeCells('F7:G7');



		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('H7', 'Achieved');

		$objPHPExcel->getActiveSheet()->mergeCells('H7:J7');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
		$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
		$objPHPExcel->getActiveSheet()->mergeCells('D7:D8');
		$objPHPExcel->getActiveSheet()->mergeCells('E7:E8');
		$objPHPExcel->getActiveSheet()->mergeCells('F7:F8');
		$objPHPExcel->getActiveSheet()->mergeCells('G7:G8');
		$objPHPExcel->getActiveSheet()->mergeCells('L7:L8');
		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('K7', 'Budget');

		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('M7', 'LAST FIVE VISITS');

		$objPHPExcel->getActiveSheet()->mergeCells('M7:Q7');



		$head_hotel_row = 7;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)

			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Group')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Area')

			

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Sum Rate No.')

			->setCellValue($head_cntr_column++.$head_hotel_row++, 'Win Rate No.')

			->setCellValue($head_cntr_column++.$head_hotel_row, '('.date('Y',strtotime($prevYear2)).'-'.date('Y',strtotime($prevYear2End)).')')

			->setCellValue($head_cntr_column++.$head_hotel_row, '('.date('Y',strtotime($prevYear)).'-'.date('Y',strtotime($prevYearEnd)).')')

			->setCellValue($head_cntr_column++.$head_hotel_row, '('.date('Y',strtotime($date)).'-'.date('Y',strtotime($end)).')')

			->setCellValue($head_cntr_column++.$head_hotel_row, '('.date('Y',strtotime($date)).'-'.date('Y',strtotime($end)).')')

			->setCellValue($head_cntr_column++.'7', 'V2B')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 1')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 2')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 3')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 4')

			->setCellValue($head_cntr_column++.$head_hotel_row, 'Visit 5');



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

	$subHeading = array(

	    'font'  => array(

	        'bold'  => true,

	        'color' => array('rgb' => '000'),

	        'size'  => 10,

	        'name'  => 'Verdana'

	    ));

	  function cellColor($cells,$color){
	    global $objPHPExcel;
	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	        'rgb' => $color
	      )
	    ));
	}

	cellColor('A6:Q6','254061');
	cellColor('A7:Q8','75923c');









	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:Q6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);



	$objPHPExcel->getActiveSheet()->getStyle('A7:Q7')->applyFromArray($styleArray_1);

	$objPHPExcel->getActiveSheet()->getStyle('H8:Q8')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:Q7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);



	$objPHPExcel->getActiveSheet()->getStyle('F7:Q7')->applyFromArray($subHeading);

	 $objPHPExcel->getActiveSheet()->getStyle('F7:Q7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);




	$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('D7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->applyFromArray(

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

	$objPHPExcel->getActiveSheet()->getStyle('A7:G8')->getAlignment()->applyFromArray(

	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('L7')->getAlignment()->applyFromArray(

	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(

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

		

		

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(25);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(15);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(14);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(14);	

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(14);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(14);	

		



	$head_hotel_row++;

						

						

							

		

		

		$Serialno=1;

		$connew = $head_hotel_row;

		while($row = mysqli_fetch_object($res)){

		

		$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$_SESSION['shop']."' and id_company='".$row->id_company."' and id_user='".$row->id_user."' ");

		$budget = selectColumn(TBL_AGENT_BUDGET,'room_nights'," WHERE `id_company` = '".$row->id_company."' and id_user='".$row->id_user."' ");

				

		$head_order_data1 = "A";

		$head_order_data = "A";       

		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue($head_order_data++ . $connew, $Serialno++)
		->setCellValue($head_order_data++ . $connew, $row->executive)

		->setCellValue($head_order_data++ . $connew, $row->name)

		->setCellValue($head_order_data++ . $connew, selectColumn(TBL_GROUP,'name'," WHERE id_group='".$row->id_default_group."' "))

		->setCellValue($head_order_data++ . $connew, $row->area)

		

		->setCellValue($head_order_data++ . $connew, '0')

		->setCellValue($head_order_data++ . $connew, '0')



		->setCellValue($head_order_data++ . $connew, selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear2."' and '".$prevYear2End."' and id_shop='".$_SESSION['shop']."' and id_company='".$row->id_company."' and id_user='".$row->id_user."' "))



		->setCellValue($head_order_data++ . $connew, selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$_SESSION['shop']."' and id_company='".$row->id_company."' and id_user='".$row->id_user."' "))



		->setCellValue($head_order_data++ . $connew,$achieved )

		

		->setCellValue($head_order_data++ . $connew,$budget )



		->setCellValue($head_order_data++ . $connew, $achieved-$budget);

		



		$sqlDate ="SELECT dated FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." Order BY dated desc  LIMIT 5 "; 

        

        $resDate = mysqli_query($conn,$sqlDate);

                               		

        if($resDate){ 

        	$dateCol = 'M';

        	while($resData = mysqli_fetch_object($resDate)){

        		$objPHPExcel->setActiveSheetIndex(0)

        			->setCellValue($dateCol++ . $connew, date('d-M-Y',strtotime($resData->dated)));

        	}

        }		



		

	

		

	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':Q'.$connew)->applyFromArray($styleThinBlackBorderOutline);

	;

	/* $objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':Q'.$connew)->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);*/

	$objPHPExcel->getActiveSheet()->getStyle('A6:Q8')->applyFromArray($styleThinBlackBorderOutline);



	$objPHPExcel->getActiveSheet()->getStyle('H8:Q8')->applyFromArray($styleThinBlackBorderOutline);

	/*$objPHPExcel->getActiveSheet()->getStyle('F8:G8')->applyFromArray($styleThinBlackBorderOutline);

	$objPHPExcel->getActiveSheet()->getStyle('H8:J8')->applyFromArray($styleThinBlackBorderOutline);

	$objPHPExcel->getActiveSheet()->getStyle('K8')->applyFromArray($styleThinBlackBorderOutline);

	$objPHPExcel->getActiveSheet()->getStyle('M8:Q8')->applyFromArray($styleThinBlackBorderOutline);*/







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

		/*$objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':B'.$connew);

		$objPHPExcel->getActiveSheet(0)->setCellValue('H'.$connew,'Grand Total');

		$objPHPExcel->getActiveSheet(0)->setCellValue('I'.$connew,'=SUM(I4:I'.($connew-1).')');

		$objPHPExcel->getActiveSheet(0)->setCellValue('J'.$connew,'=SUM(J4:J'.($connew-1).')');

		$objPHPExcel->getActiveSheet(0)->setCellValue('K'.$connew,'=SUM(K4:K'.($connew-1).')');

		$objPHPExcel->getActiveSheet(0)->setCellValue('L'.$connew,'=SUM(L4:L'.($connew-1).')');

		$objPHPExcel->getActiveSheet()->getStyle('H'.$connew.':L'.$connew)->applyFromArray($totalArray);

		$objPHPExcel->getActiveSheet()->getStyle('H'.$connew.':L'.$connew)->applyFromArray($styleThinBlackBorderOutline);	*/				

	}

		$objPHPExcel->getActiveSheet()->setTitle('Executive Portfolio Yearly');



		

		$objPHPExcel->setActiveSheetIndex(0);

		

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);



$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);



/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);	

		

	*/	

$objPHPExcel->getActiveSheet()->getStyle('B10:B999')

    ->getAlignment()->setWrapText(true);	

//$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$objPHPExcel->getActiveSheet()->getHighestRow())

  //  ->getAlignment()->setWrapText(true); 	

		

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

	

	

		ob_end_clean();

		// Redirect output to a client’s web browser (Excel2007)

		header('Content-Type: application/vnd.ms-excel');



		header('Content-Disposition: attachment;filename="Executive Portfolio Yearly.xls"');

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

    <h1> Executive Portfolio<small>Executive Portfolio Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Executive Portfolio</li>

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

            <!--<div class="form-group col-sm-6">



                <label for="reservation_date">From - To </label>



                <div class="input-group">



                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">



                </div>-->



                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>

              <!--<div class="form-group col-sm-6">

                <label for="seasonId">Date <font color="#FF0000">*</font></label>

                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>

              </div>-->
              <div class="col-md-4">

                <div class="form-group">

                <label>Sales Executive</label>

                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->

               <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
					';
				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  ".$ConditonUserLevel." `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
					  if($db->num_rows2($resUserLevel)){
					  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_REQUEST['usernameid'] == $resultUserLevel->id){

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

              <div class="col-md-4">

                <div class="form-group">

                  <label>Company - City</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>

											    <option value="">Select Company</option>';

											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !='' ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['companyId'] == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

												}

											  }

											 	echo $companyDropDown .= '</select>';

											  ?>

                </div>

              </div>

              



			            <!--Area Executive-->

			            <div class="col-md-4">

			                <div class="form-group">

			                  <label>Area</label>				

			  				 <?php $categoryDropDown = '<select class="form-control select2" name="id_area">

			  											  <option value="">Select Area</option>  ';

			  											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');

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

			  			  			

			            </div>

			            <!--Area Executive End-->



              

              <!-- /.col -->

              

              <!-- /.row -->

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" />

            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />

            </div>

            

        </form>

      

        <div class="box">

          <div class="box-header">

            <h3 class="box-title">Portfolio List</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped ">

                            <thead>

                            <tr>

                              <th>S.No.</th>		

                              <th width="20%">Company Name&nbsp;</th>

            				  <th>Area</th>

            				  <th>Executive</th>

            				  <th>Budget</th>

            				  <th>Budget Achieved</th>

            				  <th>Visit 1</th>

            				  <th>Visit 2</th>

            				  <th>Visit 3</th>

            				  <th>Visit 4</th>

            				  <th>Visit 5</th>				  

                            </tr>

                            </thead>

                            <tbody>

            				<?php 				 				

            				if($total > 0){$counter = 1;

            				  while($row = mysqli_fetch_object($res)){?>

                            <tr>

                               <th><?=$counter++;?></th>     

                               <td width="20%"><?=$row->name;?></td>

                               <td><?=$row->area;?></td>

                               <td><?=$row->executive;?></td>

                               <td><?php echo selectColumn(TBL_AGENT_BUDGET,'value'," WHERE `id_company` = '".$row->id_company."' and id_user='".$row->id_user."' "); ?></td>

                               <td>0</td>

                               

                               <?php 

                               		$sqlDate ="SELECT dated FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." Order BY dated desc  LIMIT 5 "; 

                               		$resDate = mysqli_query($conn,$sqlDate);

                               		

                               		if($resDate){ 

                               			while($resData = mysqli_fetch_object($resDate)){

                               	?>

	           		

			                               <td width="10%"><?=date('d-M-Y',strtotime($resData->dated));?></td>

			                               

                               <?php

           				 				}

           				 			}



                               ?>

                               

                            </tr>

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

  <?php include_once("includes/footer.php")?>

