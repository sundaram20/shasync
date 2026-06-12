<?php include_once("../config/auto_loader.php");
error_reporting(1);
checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

if(isset($_REQUEST['reservation_date']) || $_REQUEST['reservation_date'] !=""){
	$date = array();
	$date = explode(" to ", $_REQUEST['reservation_date']);
	$start = date('Y-m-d',strtotime($date[0]));
	$end = date('Y-m-d',strtotime($date[1]));
	$lastYearStart = date('Y-m-d',strtotime('-1 year',strtotime($start)));
	$lastYearEnd = date('Y-m-d',strtotime('-1 year',strtotime($end)));	
	$lastYrText = date('Y',strtotime($lastYearStart))." - ".date('Y',strtotime($lastYearEnd));
	$thisYrText = date('Y',strtotime($start))." - ".date('Y',strtotime($end));
}
else{
	$_REQUEST['reservation_date'] = "01-04-2018 to 31-03-2019";
}
if(isset($_REQUEST['id_executive']) AND $_REQUEST['id_executive'] !=0){
	$cond = " AND exe_id = ".$_REQUEST['id_executive']."";
}


if($_REQUEST['Generate'] == 'Generate'){
	
	$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
								 ->setLastModifiedBy("Hitesh Aloney")
								 ->setTitle("Productivity  Report")
								 ->setSubject("Productivity  Report")
								 ->setDescription("Productivity  Report")
								 ->setKeywords("Productivity  Report")
								 ->setCategory("Report");

//print_r($_REQUEST['reservation_date']);

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

	$styleArray_2 = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => '996515'),
		        'size'  => 10,
		        'name'  => 'Verdana'
	));	

	$styleArray_3 = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => 'FF0000'),
		        'size'  => 10,
		        'name'  => 'Verdana'
	));	
	$executiveStyle = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => '252525'),
		        'size'  => 10,
		        'name'  => 'Verdana'
	));	

	$totalStyle = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '1e51bf'),
	        'size'  => 14,
	        'name'  => 'Verdana'
	));

	 $styleThinBlackBorderOutline = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000'),
			),
		),
	);	
	//Title of the report
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', 'EXECUTIVEWISE PRODUCTIVITY REPORT');	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A2:V2");
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->applyFromArray($styleArray);
	$objPHPExcel->getActiveSheet()->getStyle('A2:V2')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	//fincial year date
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'From '.$_REQUEST['reservation_date']);	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A3:V3");
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A3')->applyFromArray($styleArray_1);
	$objPHPExcel->getActiveSheet()->getStyle('A3:V3')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

	//Format of report
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5','S.NO');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5','UNIT NAME');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5','ROOM NIGHTS');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5','ARR');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L5','ROOM REVENUE');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P5','OTHER REVENUE');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S5','TOTAL REVENUE');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A5:W5')->applyFromArray($styleArray_2);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(40);	
	$objPHPExcel->getActiveSheet()->getStyle('C5:V5')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("C5:G5");			
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("H5:K5");	
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("L5:O5");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("P5:R5");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("S5:V5");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A5:A7");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("B5:B7");		
		//Sub Headings
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C6','LAST YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D6','BUDGET');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E6','THIS YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F6','V2B');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G6','GOLY %');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H6','LAST YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I6','BUDGET');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J6','THIS YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K6','GOLY %');
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L6','LAST YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M6','BUDGET');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N6','THIS YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O6','GOLY %');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P6','LAST YEAR');
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q6','BUDGET');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q6','THIS YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R6','GOLY %');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S6','LAST YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T6','BUDGET');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U6','THIS YEAR');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V6','GOLY %');					
								
								
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C7',$lastYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("F6:F7");
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("G6:G7");

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H7',$lastYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("K6:K7");

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L7',$lastYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("O6:O7");

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P7',$lastYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("R6:R7");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S7",$lastYrText);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T7',$lastYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V7',$thisYrText);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells("V6:V7");

			$objPHPExcel->getActiveSheet()->getStyle('C6:W7')->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));

			$objPHPExcel->getActiveSheet()->getStyle('F6:G6')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

		    $objPHPExcel->getActiveSheet()->getStyle('K6')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

		    $objPHPExcel->getActiveSheet()->getStyle('O6')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

		    $objPHPExcel->getActiveSheet()->getStyle('R6')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

		    $objPHPExcel->getActiveSheet()->getStyle('V6')->getAlignment()->applyFromArray(
		    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,));

		    $objPHPExcel->getActiveSheet()->getStyle("C6:V7")->applyFromArray($styleArray_3);
		    $objPHPExcel->getActiveSheet()->getStyle("A5:V7")->applyFromArray($styleThinBlackBorderOutline);



//Adding Data from data base into sheet
	$sqlEx ="SELECT exe_id,executive FROM budget_acheived WHERE shop_id = ".$_SESSION['shop']." ".$cond." AND executive !='' GROUP BY exe_id";
	$resEx = mysqli_query($conn,$sqlEx);
	$rowCounter = 9;
//////Grand Total Variables below
	$grandTotalRnLast = 0;
	$grandTotalRnBudget = 0;
	$grandTotalRnThis = 0;
	$grandTotalV2B = 0;
	$grandTotalRnGoly = 0;
	
	$grandTotalArrLast = 0;
	$grandTotalArrBudget = 0;
	$grandTotalArrThis = 0;
	$grandTotalArrGoly = 0;

	$grandTotalValueLast = 0;
	$grandTotalValueBudget = 0;
	$grandTotalValueThis = 0;
	$grandTotalValueGoly = 0;
	
	$grandTotalOtherLast = 0;
	$grandTotalOtherThis = 0;
	$grandTotalOtherGoly = 0;

	$grandTotalRevenueLast = 0;
	$grandTotalRevenueBudget = 0;
	$grandTotalRevenueThis = 0;
	$grandTotalRevenueGoly = 0;

	while($rowEx = mysqli_fetch_object($resEx)){
		echo $rowEx->executive."<br>";
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowCounter,$rowEx->executive);
		//colouring executive row
		$objPHPExcel->getActiveSheet()->getStyle('B' . $rowCounter . ':V' . $rowCounter)->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCounter . ':V' . $rowCounter)->getFill()
        ->getStartColor()->setRGB('ca0ee1');

        //
		$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCounter)->applyFromArray($executiveStyle);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$rowCounter++)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
		$sno = 1;
		$columnCounter = "B";

		//for calculating sub total and grand total

		$totalRnLast = 0;
		$totalRnThis = 0;
		$totalRnBudget = 0;

		$totalValueLast = 0;
		$totalValueThis = 0;
		$totalValueBudget = 0;

		$totalArrLast = 0;
		$totalArrThis = 0;
		$totalArrBudget = 0;

		$totalV2B = 0;
		$totalGolyRn =0;
		$totalGolyArr =0;
		$totalGolyValue =0;

		$totalOtherLast = 0;
		$totalOtherThis = 0;
		$totalGolyOther =0;

		$totalRevenueLast = 0;
		$totalRevenueThis = 0;
		$totalRevenueBudget = 0;
		$totalRevenueGoly = 0;
		$totalGolyOtherPlusValue = 0;

		
		//////////////////////
		if($rowEx->exe_id != " "){
		$sqlMain = "SELECT hotel,
					SUM(case when date BETWEEN '".$lastYearStart."' AND '".$lastYearEnd."' AND DOC_TYPE = 'ACHEIVED' THEN RN ELSE 0 END) AS RNLAST
					,SUM(case when date BETWEEN '".$start."' AND '".$end."' AND DOC_TYPE = 'ACHEIVED' THEN RN ELSE 0 END) AS RNTHIS
					,SUM(case when date BETWEEN '".$start."' AND '".$end."' AND DOC_TYPE = 'BUDGET' THEN RN ELSE 0 END) AS RNBUDGET
					
					,SUM(case when date BETWEEN '".$lastYearStart."' AND '".$lastYearEnd."' AND DOC_TYPE = 'ACHEIVED' THEN ROUND(VALUE,2) ELSE 0 END) AS VALUELAST
					,SUM(case when date BETWEEN '".$start."' AND '".$end."' AND DOC_TYPE = 'ACHEIVED' THEN ROUND(VALUE,2) ELSE 0 END) AS VALUETHIS
					,SUM(case when date BETWEEN '".$start."' AND '".$end."' AND DOC_TYPE = 'BUDGET' THEN ROUND(VALUE,2) ELSE 0 END) AS VALUEBUDGET

					,SUM(case when date BETWEEN '".$lastYearStart."' AND '".$lastYearEnd."' AND DOC_TYPE = 'OTHER_CHARGES' THEN ROUND(otherCharges,2) ELSE 0 END) AS OTHERLAST
					,SUM(case when date BETWEEN '".$start."' AND '".$end."' AND DOC_TYPE = 'OTHER_CHARGES' THEN ROUND(otherCharges,2) ELSE 0 END) AS OTHERTHIS

					FROM budget_acheived  WHERE shop_id = '".$_SESSION['shop']."'  AND exe_id= '".$rowEx->exe_id."' GROUP BY hotel";
		
		$resMain = mysqli_query($conn,$sqlMain);

		while($rowMain = mysqli_fetch_object($resMain)){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$rowCounter,$sno);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$rowCounter,$rowMain->hotel);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$rowCounter,$rowMain->RNLAST);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$rowCounter,$rowMain->RNBUDGET);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$rowCounter,$rowMain->RNTHIS);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$rowCounter,$rowMain->RNTHIS-$rowMain->RNBUDGET);
			
			if($rowMain->RNLAST != 0 ){
				$goly = 0;
				$goly = (($rowMain->RNTHIS-$rowMain->RNLAST)*100)/$rowMain->RNLAST;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$rowCounter,round($goly,2));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$rowCounter,round(($rowMain->VALUELAST/$rowMain->RNLAST),0));
				//$totalArrLast += ($rowMain->VALUELAST/$rowMain->RNLAST) ;
				//$totalGolyRn +=$goly;
			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$rowCounter,0);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$rowCounter,0);
				//$totalArrLast +=0;
				//$totalGolyRn +=0;
			}

			if($rowMain->RNBUDGET !=0){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$rowCounter,round(($rowMain->VALUEBUDGET/$rowMain->RNBUDGET),0));
				//$totalArrBudget += ($rowMain->VALUEBUDGET/$rowMain->RNBUDGET);
			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$rowCounter,0);
				//$totalArrBudget +=0;
			}
			
			if($rowMain->RNTHIS !=0){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$rowCounter,round(($rowMain->VALUETHIS/$rowMain->RNTHIS),0));
				//$totalArrThis += ($rowMain->VALUETHIS/$rowMain->RNTHIS);


			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$rowCounter,0);
				//$totalArrThis +=0;
			}

			if($rowMain->RNTHIS !=0 AND $rowMain->RNLAST !=0){
				$goly =0;
				$goly = ($rowMain->VALUETHIS/$rowMain->RNTHIS)-($rowMain->VALUELAST/$rowMain->RNLAST);
				$divGoly = $rowMain->VALUELAST/$rowMain->RNLAST;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$rowCounter,round(($goly*100)/$divGoly),2);
				//$totalGolyArr +=(($goly*100)/$divGoly);
			}
			else{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$rowCounter,0);
				//$totalGolyArr +=0;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$rowCounter,round($rowMain->VALUELAST/100000,2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$rowCounter,round($rowMain->VALUEBUDGET/100000,2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$rowCounter,round($rowMain->VALUETHIS/100000,2));
			$goly = 0;
			$goly = ((($rowMain->VALUETHIS-$rowMain->VALUELAST)*100)/$rowMain->VALUELAST);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$rowCounter,round($goly,2));

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$rowCounter,isset($rowMain->OTHERLAST)?round($rowMain->OTHERLAST/100000,2):0);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$rowCounter,round($rowMain->OTHERTHIS/100000,2));
			if($rowMain->OTHERLAST != 0){
				$golyOther =0;
				$golyOther = (($rowMain->OTHERTHIS-$rowMain->OTHERLAST)*100)/$rowMain->OTHERLAST;
				//$totalGolyOther+=(($rowMain->OTHERTHIS-$rowMain->OTHERLAST)*100)/$rowMain->OTHERLAST;
			}
			else{
				$golyOther = 0;
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$rowCounter,round($golyOther,2));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$rowCounter,round(($rowMain->VALUELAST+$rowMain->OTHERLAST)/100000,2));
			$totalRevenueLast += $rowMain->VALUELAST+$rowMain->OTHERLAST;



			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$rowCounter,round($rowMain->VALUEBUDGET)/100000,2);
			$totalRevenueThis += $rowMain->VALUETHIS+$rowMain->OTHERTHIS;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$rowCounter,round(($rowMain->VALUETHIS+$rowMain->OTHERTHIS)/100000,2));
			
			if( ($rowMain->VALUELAST+$rowMain->OTHERLAST) !=0){
				//$totalRevenueGoly = 0;
				//$totalRevenueGoly = ((($rowMain->VALUETHIS+$rowMain->OTHERTHIS)-($rowMain->VALUELAST+$rowMain->OTHERLAST)*100)/($rowMain->VALUELAST+$rowMain->OTHERLAST));
				//$totalGolyOtherPlusValue +=$totalRevenueGoly; 
			}
			else{
				//$totalRevenueGoly = 0;
				//$totalGolyOtherPlusValue += 0;
			}
			//$totalRevenueGoly += $totalRevenueGoly;
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$rowCounter,round($totalRevenueGoly,2));

			//$totalGolyValue +=$goly;	

			//Summing Sub total
			$totalRnLast += $rowMain->RNLAST;
			$totalRnThis += $rowMain->RNTHIS;
			$totalRnBudget += $rowMain->RNBUDGET;
			$totalValueLast += $rowMain->VALUELAST;
			$totalValueThis += $rowMain->VALUETHIS;
			$totalValueBudget += $rowMain->VALUEBUDGET;
			$totalV2B += $rowMain->RNTHIS-$rowMain->RNBUDGET;
			$totalOtherLast+=$rowMain->OTHERLAST;
			$totalOtherThis+=$rowMain->OTHERTHIS;
			///////////////////

			$sno++;
			$rowCounter++;
		}
		if($totalRnLast!=0){
			$totalArrLast = $totalValueLast/$totalRnLast;
		}
		else{
			$totalArrLast = 0;	
		}
		if($totalRnBudget!=0){
			$totalArrBudget = $totalValueBudget/$totalRnBudget;
		}
		else{
			$totalArrBudget = 0;	
		}
		if($totalRnThis!=0){
			$totalArrThis = $totalValueThis/$totalRnThis;
		}
		else{
			$totalArrThis = 0;	
		}
		if($totalArrLast !=0){
			$totalGolyArr = (($totalArrThis-$totalArrLast)/$totalArrLast)*100;
		}
		else{
			$totalGolyArr =  0;	
		}
		//Filling Sub Total in sheet
			//Colouring Sub Total
				$objPHPExcel->getActiveSheet()->getStyle('A' . $rowCounter . ':V' . $rowCounter)->getFill()
				    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('A' . $rowCounter . ':V' . $rowCounter)->getFill()
				    ->getStartColor()->setRGB('FFFF00');
			//
		$totalGolyRn = (($totalRnThis-$totalRnLast)/$totalRnLast)*100;	
		$totalGolyArr = (($totalArrThis-$totalArrLast)/$totalArrLast)*100;	
		$totalGolyValue = (($totalValueThis-$totalValueLast)/$totalValueLast)*100; 
		$totalGolyOther = (($totalOtherThis-$totalOtherLast)/$totalOtherLast)*100; 
		$totalRevenueGoly = (($totalRevenueThis-$totalRevenueLast)/$totalRevenueLast)*100;  
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,"Sub Total");			
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalRnLast);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalRnBudget);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalRnThis);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalV2B);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalGolyRn,2));

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalArrLast,0));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalArrBudget,0));	
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalArrThis,0));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalGolyArr,0));

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalValueLast/100000,2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalValueBudget/100000,2));	
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalValueThis/100000,2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalGolyValue,2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalOtherLast);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,$totalOtherThis);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalGolyOther,2));
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalRevenueLast/100000,2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalRevenueBudget/100000,2));
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalRevenueThis/100000,2));

		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($columnCounter++.$rowCounter,round($totalRevenueGoly,2));
		$objPHPExcel->getActiveSheet()->getStyle("A9:V".$rowCounter++)->applyFromArray($styleThinBlackBorderOutline);
		///////////////////////////////
		//echo $totalRnThis;
	}
		
		$grandTotalRnLast += $totalRnLast;
		$grandTotalRnBudget += $totalRnBudget;
		$grandTotalRnThis += $totalRnThis;
		$grandTotalV2B += $totalV2B;
		//$grandTotalRnGoly += $totalGolyRn;
		
		

		$grandTotalValueLast += $totalValueLast;
		$grandTotalValueBudget += $totalValueBudget;
		$grandTotalValueThis += $totalValueThis;
		$grandTotalValueGoly += $totalGolyValue;
		
		$grandTotalOtherLast += $totalOtherLast;
		$grandTotalOtherThis += $totalOtherThis;
		//$grandTotalOtherGoly += $totalGolyOther;

		$grandTotalRevenueLast += $totalRevenueLast;
		$grandTotalRevenueBudget += $totalRevenueBudget;
		$grandTotalRevenueThis += $totalRevenueThis;
		//$grandTotalRevenueGoly += $totalGolyOtherPlusValue;
		//$grandTotalArrGoly += $totalGolyArr;
	}
	//Providing Grand Total Below
	$rowCounter++;
	$grandColumn = 'C';
	
	if($grandTotalRnLast !=0){
		$grandTotalArrLast = $grandTotalValueLast/$grandTotalRnLast;
	}
	if($grandTotalRnBudget !=0){
		$grandTotalArrBudget = $garndTotalValueBudget/$grandTotalRnBudget;
	}
	if($grandTotalRnThis !=0){
		$grandTotalArrThis = $grandTotalValueThis/$grandTotalRnThis;
	}
	
	//jump
	$grandTotalRnGoly = (($grandTotalRnThis-$grandTotalRnLast)/$grandTotalRnLast)*100;	 
	$grandTotalArrGoly = (($grandTotalArrThis-$grandTotalArrLast)/$grandTotalArrLast)*100;  
	$grandTotalValueGoly = (($grandTotalValueThis-$grandTotalValueLast)/$grandTotalValueLast)*100;
	$grandTotalOtherGoly = (($grandTotalOtherThis-$grandTotalOtherLast)/$grandTotalOtherLast)*100;
	$grandTotalRevenueGoly = (($grandTotalRevenueThis-$grandTotalRevenueLast)/$grandTotalRevenueLast)*100;
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A'.$rowCounter.':V'.$rowCounter)->applyFromArray($totalStyle);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$rowCounter.':V'.$rowCounter)->getAlignment()->applyFromArray(
		    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
	$objPHPExcel->getActiveSheet()->getStyle("A".$rowCounter.":V".$rowCounter)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells("A".$rowCounter.":B".$rowCounter);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$rowCounter,'Grand Total');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,$grandTotalRnLast);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,$grandTotalRnBudget);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,$grandTotalRnThis);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,$grandTotalV2B);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalRnGoly,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalArrLast,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalArrBudget,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalArrThis,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalArrGoly,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalValueLast/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,$grandTotalValueBudget);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalValueThis/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalValueGoly,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalOtherLast/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalOtherThis/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalOtherGoly,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalRevenueLast/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalRevenueBudget/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalRevenueThis/100000,2));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($grandColumn++.$rowCounter,round($grandTotalRevenueGoly,2));


//Generating Report
	$objPHPExcel->getActiveSheet()->setTitle('Executive Productivity');

								
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');

	header('Content-Disposition: attachment;filename="ExecutivewiseProductivity.xls"');
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
	mysqli_close($conn);
	exit;
}
	
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Productivity Report Manager
        <small>Executive Wise Productivity Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Productivity Report Manager</li>
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
          <h3 class="box-title">Executive wise productivity report &nbsp;</small> </h3>
		  <!--<div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="booknow.php?type=N" >Book Now</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php /*?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ORDERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ORDERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
							  
							  </ul>
							</div>-->
          
        
        <!-- /.box-header -->
		<form method="post" accept="" style="text-align: center;">
              <!--date input-->
              <div class="form-group col-md-2 col-md-offset-1 col-sm-12 col-xs-12 " style="margin-top:15px;">
                <div class="input-group">
                  <select id="preDate" class="form-control">
                    <option value="01-04-2017 to 31-03-2018" > FY 2017-2018</option>
                    <option value="01-04-2018 to 31-03-2019" selected="selected" > FY 2018-2019</option>
                    <option value="01-04-2019 to 31-03-2020" >FY 2019-2020</option>
                    <option value="01-04-2018 to 30-09-2018" >Apr-2018 to Sep-2018</option>
                    <option value="01-10-2018 to 31-03-2019" >Oct-2018 to Mar-2019</option>
                    <option value="01-04-2019 to 30-09-2020" >Apr-2019 to Sep-2020</option>                    
                    <option value="01-10-2019 to 31-03-2020" >Oct-2019 to Mar-2020</option>
                  </select>  
                </div>
               </div>


              <div class="form-group col-md-3 col-sm-12" style="margin-top:15px;">
                <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control pull-right dateRangeEdit"  placeholder="Enter Checkin date" name="reservation_date" id="reservation_date" data-parsley-required value="<?php if(isset($_REQUEST['reservation_date'])) echo $_REQUEST['reservation_date'];?>" data-parsley-errors-container="#reservation_dateError"  automcomplete="off">
                  </div>
               </div>
               <!--date input end-->
               <!--Hotel select list-->
               <div class="col-md-3  col-sm-12" style="margin-top:15px;">
                  <div class="form-group ">
                    <?php 
                        $hotelDropDown = '<select class="form-control select2 " name="id_executive">
                                             <option value="0">All Executives</option>';
                                            $resCat = selectSql(TBL_USERS," where id_shop='".addslashes($_SESSION['shop'])."' AND STATUS = 1 AND name !='' ",' ORDER BY `name`');
                                      if($db->num_rows2($resCat)){
                                        while($resultCat = $db->fetch_object2($resCat)){
                                        if(isset($_REQUEST['id_executive']) && $_REQUEST['id_executive']!="" && trim($_REQUEST['id_executive']) == $resultCat->id){
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
                 <!--search button-->
                  <div class="form-group col-md-3 col-sm-12" style="margin-top:15px;" >
                    <div class="input-group">
                        <input name="Generate" type="submit" class="btn btn-primary" value="Generate" />
                      </div>
                   </div>
                  
                 <!--search button end-->
               </form>	
               </div>	
      </div>
	  <style>
	  #example2 tbody tr td, #example2 tbody tr th{padding:2px;}
	  </style>
      </section>
      <?php/*<div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <!--<h3 class="box-title">Pace Report List</h3>-->
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
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
				
				 <!--<tr>
                      <td height="200" align="center" colspan="8">---- No Record Found ---- </td>
                 </tr>  -->               
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
      </div>*/?>
      <!-- /.row -->
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?>  
<script type="text/javascript">
  $("#preDate").change(function(){
    var date = $(this).val();
    $("#reservation_date").val(date);
  });
</script>