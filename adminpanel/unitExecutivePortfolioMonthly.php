<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],'exe_portfolio_unit','view');



if($_SESSION['userLevel']!=1){
$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
$resPer = mysqli_query($connNew,$perSql);

if($resPer){
  	$perData	=	mysqli_fetch_object($resPer);
    if($perData->calendar_user_list_approved == 0){
	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
    }

}
}
if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

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
	}
?>
	



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
			$period = date('Y').'-'.date('Y',strtotime('+1 years',strtotime(date('Y'))));	
		}
		

		$end = strtotime($date);

		$end = strtotime("+1 year",$end);

		$end = date('Y-03-31',$end);

		

		$prevYear = strtotime($date);

		$prevYear = strtotime("-1 year",$prevYear);

		$prevYear = date('Y-04-01',$prevYear);

		$prevYearEnd = date('Y-03-31',strtotime($prevYear));



		$prevYear2 = strtotime($date);

		$prevYear2 = strtotime("-2 year",$prevYear2);

		$prevYear2 = date('Y-04-01',$prevYear2);

		$prevYear2End = date('Y-03-31',strtotime($prevYear2));

		

		/*date ends*/

		 $styleThinBlackBorderOutline = array(
			'borders' => array(
				'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000'),
				),
			),
		);

		   function cellColor($cells,$color){
		     global $objPHPExcel;
		     $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
		         'type' => PHPExcel_Style_Fill::FILL_SOLID,
		         'startcolor' => array(
		         'rgb' => $color
		       )
		     ));
		 }

		// Set document properties

		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
									 ->setLastModifiedBy("Hitesh Aloney")
									 ->setTitle("Executive Portfoilo Report")
									 ->setSubject("Executive Portfoilo Report")
									 ->setDescription("Executive Portfoilo Report")
									 ->setKeywords("Executive Portfoilo Report")
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



		$objDrawing->setCoordinates('R1');        //set image to cell



		//$objDrawing->setWidth(300);                 //set width, height

		//$objDrawing->setHeight(100);  

		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

		}  //save							 

		

		if($numRows == 0){

		$counter = 1;

		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('A6', 'Executive Portfoilo - Unit Report Monthly For Year :'.$period);

		$objPHPExcel->getActiveSheet()->mergeCells('A6:AV6');



		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('F7', 'Rate Number');

		//$objPHPExcel->getActiveSheet()->mergeCells('F7:G7');



		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('G7', 'Achieved');
		$objPHPExcel->setActiveSheetIndex(0)

		->setCellValue('H7', 'Budget');

		//$objPHPExcel->getActiveSheet()->mergeCells('H7:J7');
		$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
		$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
		$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
		$objPHPExcel->getActiveSheet()->mergeCells('D7:D8');
		$objPHPExcel->getActiveSheet()->mergeCells('E7:E8');
		$objPHPExcel->getActiveSheet()->mergeCells('F7:F8');
		//$objPHPExcel->getActiveSheet()->mergeCells('G7:G8');
		$objPHPExcel->getActiveSheet()->mergeCells('I7:I8');
		
		//$objPHPExcel->getActiveSheet()->mergeCells('M7:Q7');
		mysqli_query($connNew,"DELETE FROM temp_executive_port_monthly WHERE id_executive='".$_REQUEST['usernameid']."' ");
		$head_hotel_row = 7;
		$head_hotel_row2 = 8;

		$head_cntr_column = "A";$head_hotel_column = "A";

		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Team')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Group')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Area')
			->setCellValue($head_cntr_column++.$head_hotel_row2, ''.date('Y',strtotime($date)).'-'.date('Y',strtotime($end)).'')
			->setCellValue($head_cntr_column++.$head_hotel_row2, ''.date('Y',strtotime($date)).'-'.date('Y',strtotime($end)).'')
			->setCellValue($head_cntr_column++.'7', 'V2B')
			;

			$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('G:AV')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			
			$dateInc=$date;
			$yearCol=$head_cntr_column; 
			//month merging here//
			$objPHPExcel->getActiveSheet()->mergeCells('J7:L7');
			$objPHPExcel->getActiveSheet()->mergeCells('M7:O7');
			$objPHPExcel->getActiveSheet()->mergeCells('P7:R7');
			$objPHPExcel->getActiveSheet()->mergeCells('S7:U7');
			$objPHPExcel->getActiveSheet()->mergeCells('V7:X7');
			$objPHPExcel->getActiveSheet()->mergeCells('Y7:AA7');
			$objPHPExcel->getActiveSheet()->mergeCells('AB7:AD7');
			$objPHPExcel->getActiveSheet()->mergeCells('AE7:AG7');
			$objPHPExcel->getActiveSheet()->mergeCells('AH7:AJ7');
			$objPHPExcel->getActiveSheet()->mergeCells('AK7:AM7');
			$objPHPExcel->getActiveSheet()->mergeCells('AN7:AP7');
			$objPHPExcel->getActiveSheet()->mergeCells('AQ7:AS7');
			$objPHPExcel->getActiveSheet()->mergeCells('AT7:AV7');
			//month printing loop
			$monthCount = 4;
			$thisEnd =	$date;
			$year2back = $prevYear2End;
			$yearBack = $prevYearEnd;
			while($dateInc<=$end){
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row, date('M',strtotime($dateInc)));
					
				
				if($monthCount == 13){
					$year2back =date('Y-m-d',strtotime('+1 year',strtotime($year2back)));
					$yearBack =date('Y-m-d',strtotime('+1 year',strtotime($yearBack)));
					$thisEnd =date('Y-m-d',strtotime('+1 year',strtotime($thisEnd)));
				}	

				$dateInc = date('Y-m-d',strtotime('+1 month',strtotime($dateInc)));
				$head_cntr_column++;
				$head_cntr_column++;

				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($year2back)));
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($yearBack)));
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($thisEnd)));
				$monthCount++;	

			}
			
			//month loop end
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row,'Total');
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($prevYear2End)));
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($prevYearEnd)));
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue($yearCol++.$head_hotel_row2, date('Y',strtotime($date)));	


			$sql="SELECT  A.name AS company_name,A.id_company AS id_company,B.name AS company_group,C.name AS area,D.name AS executive,D.id AS id_exe,TM.name AS team FROM 
				 
				 ".TBL_COMPANY." AS A LEFT JOIN ".TBL_GROUP." B ON 
				  A.id_default_group=B.id_group
				  
				  LEFT JOIN ".TBL_AREAS." C ON A.area=C.id
				  LEFT JOIN ".TBL_USERS." D ON FIND_IN_SET(D.id,C.ids_unit_user)
				  LEFT JOIN ".TBL_TEAM." TM ON D.ids_team=TM.id
				  WHERE A.id_shop='".$_SESSION['shop']."' AND D.id=".$_REQUEST['usernameid']."  ";
				  
			/*if($_SESSION['userLevel']!=1 && $_SESSION['teamMemberLevel']==''){
				$sql .=	" AND D.id=".$_REQUEST['usernameid']." ";
			}*/

				  
					  	
			$res = mysqli_query($connNew,$sql);
			$head_hotel_row2++;
			$tempTableArr = array();
			while($row=mysqli_fetch_object($res)){
					

				$sqlBud = "SELECT room_nights AS budgetRN FROM ".TBL_UNIT_AGENT_BUDGET." WHERE id_user='".$row->id_exe."' AND id_company='".$row->id_company."' AND `from` = '".date('Y-m-d',strtotime($date))."' AND `to` ='".date('Y-m-d',strtotime($end))."' ";

				

				$sqlAch =  "SELECT SUM(qty) AS achRN FROM ".TBL_UNIT_AGENT_ACHIEVED." WHERE id_user='".$row->id_exe."' AND id_company='".$row->id_company."' AND month BETWEEN '".date('Y-m-d',strtotime($date))."' AND '".date('Y-m-d',strtotime($end))."' 
					GROUP BY id_user,id_company
					";

				$resBud = mysqli_query($connNew,$sqlBud);
				$rowBud = mysqli_fetch_object($resBud);
				$resAch = mysqli_query($connNew,$sqlAch);
				$rowAch = mysqli_fetch_object($resAch);
								
				$dateInc=$date;
				$monthCount=4;	
				$totalPrveYEAR2=0;
				$totalPrveYEAR=0;
				$totalThis=0;
				$monthWiseValue = array();
				while($dateInc<=$end){
		

					$sqlMon="SELECT 
						SUM(CASE WHEN month='".date('Y-m-d',strtotime('-2 years',strtotime($dateInc)))."' THEN qty ELSE 0 END)  AS rnPrveYEAR2,
						SUM(CASE WHEN month='".date('Y-m-d',strtotime('-1 years',strtotime($dateInc)))."' THEN qty  ELSE 0 END) AS rnPrveYEAR,
						SUM(CASE WHEN month='".$dateInc."' THEN qty  ELSE 0 END) AS rnThis
						FROM ".TBL_UNIT_AGENT_ACHIEVED."
						WHERE 
						id_user='".$row->id_exe."' AND id_company='".$row->id_company."' 
						GROUP BY id_user,id_company order by qty desc
						";
						
					$resMon = mysqli_query($connNew,$sqlMon);
					$rowMon = mysqli_fetch_object($resMon);
			
					
					$totalPrveYEAR2+=$rowMon->rnPrveYEAR2;
					$totalPrveYEAR+=$rowMon->rnPrveYEAR;
					$totalThis+=$rowMon->rnThis;			
					
					/**** PUSHING MONTH VALUES ****/			
					array_push($monthWiseValue, ($rowMon->rnPrveYEAR2!=''?$rowMon->rnPrveYEAR2:0));
					array_push($monthWiseValue, ($rowMon->rnPrveYEAR!=''?$rowMon->rnPrveYEAR:0));
					array_push($monthWiseValue, ($rowMon->rnThis!=''?$rowMon->rnThis:0));
					
					/**** PUSHING MONTH VALUES END****/

					$dateInc = date('Y-m-d',strtotime('+1 month',strtotime($dateInc)));
					$monthCount++;

					
					
				}

				array_push($monthWiseValue, $totalPrveYEAR2);
				array_push($monthWiseValue, $totalPrveYEAR);
				array_push($monthWiseValue, $totalThis);
				$v2b =($rowAch->achRN-$rowBud->budgetRN);

				array_push($tempTableArr,"INSERT INTO temp_executive_port_monthly VALUES (".$row->id_exe.",'".$row->executive."','".$row->team."','".str_replace('&amp;','&', $row->company_name)."','".$row->company_group."','".$row->area."','".($rowAch->achRN!=''?$rowAch->achRN:0)."','".($rowBud->budgetRN!=''?$rowBud->budgetRN:0)."','".$v2b."',".implode(",",$monthWiseValue).") ");
			}
			
			foreach ($tempTableArr as $key => $query) {
				mysqli_query($connNew,$query);
			}
			$fetchTempData="SELECT * FROM temp_executive_port_monthly WHERE id_executive='".$_REQUEST['usernameid']."' AND budget!=0 ORDER BY achieved desc";

			$resFetchData = mysqli_query($connNew,$fetchTempData);
			$sno=1;
			while($rowFetchData = mysqli_fetch_object($resFetchData)){
				$head_cntr_column='A';

				$objPHPExcel->setActiveSheetIndex(0)

				->setCellValue('A6', ''.$rowFetchData->executive.' Report Monthly For Year :'.$period);

				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$sno++)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->executive)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->team)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->company_name)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->company_group)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->area)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->achieved)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->budget);
					
				if($rowFetchData->v2b<0)
					cellColor($head_cntr_column.$head_hotel_row2,'f72e2e');
				else
					cellColor($head_cntr_column.$head_hotel_row2,'65ed5e');

				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->v2b);	

				$objPHPExcel->setActiveSheetIndex(0)		
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->apr_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->apr_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->apr_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->may_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->may_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->may_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->june_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->june_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->june_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->july_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->july_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->july_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->aug_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->aug_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->aug_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->sep_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->sep_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->sep_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->oct_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->oct_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->oct_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->nov_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->nov_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->nov_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->dec_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->dec_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->dec_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->jan_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->jan_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->jan_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->feb_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->feb_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->feb_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->mar_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->mar_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->mar_this)

						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->total_pre_2)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->total_pre)
						->setCellValue($head_cntr_column++.$head_hotel_row2,$rowFetchData->total_this);
				
				$head_hotel_row2++;		
			}
			// Providing Grand Total Below

			$head_cntr_column='A';
			$objPHPExcel->getActiveSheet()->getStyle('A'.$head_hotel_row2.':AV'.$head_hotel_row2)->getFont()->setBold( true );
			cellColor('A'.$head_hotel_row2.':AV'.$head_hotel_row2,'b5d8ab');
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$head_hotel_row2.':F'.$head_hotel_row2);
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row2,'TOTAL');
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('G'.($head_hotel_row2),'=SUM(G9:G'.($head_hotel_row2-1).')')	
				->setCellValue('H'.($head_hotel_row2),'=SUM(H9:H'.($head_hotel_row2-1).')')
				->setCellValue('I'.($head_hotel_row2),'=SUM(I9:I'.($head_hotel_row2-1).')')
				->setCellValue('J'.($head_hotel_row2),'=SUM(J9:J'.($head_hotel_row2-1).')')
				->setCellValue('K'.($head_hotel_row2),'=SUM(K9:K'.($head_hotel_row2-1).')')
				->setCellValue('L'.($head_hotel_row2),'=SUM(L9:L'.($head_hotel_row2-1).')')
				->setCellValue('M'.($head_hotel_row2),'=SUM(M9:M'.($head_hotel_row2-1).')')
				->setCellValue('N'.($head_hotel_row2),'=SUM(N9:N'.($head_hotel_row2-1).')')
				->setCellValue('O'.($head_hotel_row2),'=SUM(O9:O'.($head_hotel_row2-1).')')
				->setCellValue('P'.($head_hotel_row2),'=SUM(P9:P'.($head_hotel_row2-1).')')
				->setCellValue('Q'.($head_hotel_row2),'=SUM(Q9:Q'.($head_hotel_row2-1).')')
				->setCellValue('R'.($head_hotel_row2),'=SUM(R9:R'.($head_hotel_row2-1).')')
				->setCellValue('S'.($head_hotel_row2),'=SUM(S9:S'.($head_hotel_row2-1).')')
				->setCellValue('T'.($head_hotel_row2),'=SUM(T9:T'.($head_hotel_row2-1).')')
				->setCellValue('U'.($head_hotel_row2),'=SUM(U9:U'.($head_hotel_row2-1).')')
				->setCellValue('V'.($head_hotel_row2),'=SUM(V9:V'.($head_hotel_row2-1).')')
				->setCellValue('W'.($head_hotel_row2),'=SUM(W9:W'.($head_hotel_row2-1).')')
				->setCellValue('X'.($head_hotel_row2),'=SUM(X9:X'.($head_hotel_row2-1).')')
				->setCellValue('Y'.($head_hotel_row2),'=SUM(Y9:Y'.($head_hotel_row2-1).')')
				->setCellValue('Z'.($head_hotel_row2),'=SUM(Z9:Z'.($head_hotel_row2-1).')')
				->setCellValue('AA'.($head_hotel_row2),'=SUM(AA9:AA'.($head_hotel_row2-1).')')
				->setCellValue('AB'.($head_hotel_row2),'=SUM(AB9:AB'.($head_hotel_row2-1).')')
				->setCellValue('AC'.($head_hotel_row2),'=SUM(AC9:AC'.($head_hotel_row2-1).')')
				->setCellValue('AD'.($head_hotel_row2),'=SUM(AD9:AD'.($head_hotel_row2-1).')')
				->setCellValue('AE'.($head_hotel_row2),'=SUM(AE9:AE'.($head_hotel_row2-1).')')
				->setCellValue('AF'.($head_hotel_row2),'=SUM(AF9:AF'.($head_hotel_row2-1).')')

				->setCellValue('AG'.($head_hotel_row2),'=SUM(AG9:AG'.($head_hotel_row2-1).')')	
				->setCellValue('AH'.($head_hotel_row2),'=SUM(AH9:AH'.($head_hotel_row2-1).')')
				->setCellValue('AI'.($head_hotel_row2),'=SUM(AI9:AI'.($head_hotel_row2-1).')')
				->setCellValue('AJ'.($head_hotel_row2),'=SUM(AJ9:AJ'.($head_hotel_row2-1).')')
				->setCellValue('AK'.($head_hotel_row2),'=SUM(AK9:AK'.($head_hotel_row2-1).')')
				->setCellValue('AL'.($head_hotel_row2),'=SUM(AL9:AL'.($head_hotel_row2-1).')')
				->setCellValue('AM'.($head_hotel_row2),'=SUM(AM9:AM'.($head_hotel_row2-1).')')
				->setCellValue('AN'.($head_hotel_row2),'=SUM(AN9:AN'.($head_hotel_row2-1).')')
				->setCellValue('AO'.($head_hotel_row2),'=SUM(AO9:AO'.($head_hotel_row2-1).')')
				->setCellValue('AP'.($head_hotel_row2),'=SUM(AP9:AP'.($head_hotel_row2-1).')')
				->setCellValue('AQ'.($head_hotel_row2),'=SUM(AQ9:AQ'.($head_hotel_row2-1).')')
				->setCellValue('AR'.($head_hotel_row2),'=SUM(AR9:AR'.($head_hotel_row2-1).')')
				->setCellValue('AS'.($head_hotel_row2),'=SUM(AS9:AS'.($head_hotel_row2-1).')')
				->setCellValue('AT'.($head_hotel_row2),'=SUM(AT9:AT'.($head_hotel_row2-1).')')
				->setCellValue('AU'.($head_hotel_row2),'=SUM(AU9:AU'.($head_hotel_row2-1).')')
				->setCellValue('AV'.($head_hotel_row2),'=SUM(AV9:AV'.($head_hotel_row2-1).')');		



			$objPHPExcel->getActiveSheet()->getStyle('A9:AV'.$head_hotel_row2)->applyFromArray($styleThinBlackBorderOutline);
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

	  

	cellColor('A6:AV6','254061');
	cellColor('A7:AV8','75923c');



	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);

	 $objPHPExcel->getActiveSheet()->getStyle('A6:Q6')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);



	$objPHPExcel->getActiveSheet()->getStyle('A7:AV7')->applyFromArray($styleArray_1);

	$objPHPExcel->getActiveSheet()->getStyle('G8:AV8')->applyFromArray($styleArray_1);

	 $objPHPExcel->getActiveSheet()->getStyle('A7:AV8')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);



	$objPHPExcel->getActiveSheet()->getStyle('F7:Q7')->applyFromArray($subHeading);

	 $objPHPExcel->getActiveSheet()->getStyle('F7:Q7')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	 $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(

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

	$objPHPExcel->getActiveSheet()->getStyle('I7')->getAlignment()->applyFromArray(

	    array('vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->applyFromArray(

	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

			

	 	

	 $objPHPExcel->getActiveSheet()->getStyle('A6:AV8')->applyFromArray($styleThinBlackBorderOutline);
	

	//$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()->setWrapText(true);

		$objPHPExcel->getActiveSheet()->getStyle('B9')->getAlignment()

	    ->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		

		

	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(20);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(10);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(15);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(12);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(12);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('O')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('P')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('Q')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('R')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('S')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('T')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('U')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('V')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('W')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('X')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('Y')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('Z')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AA')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AB')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AC')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AD')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AE')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AF')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AG')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AH')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AI')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AJ')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AK')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AL')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AM')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AN')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AO')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AP')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AQ')->setWidth(7);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AR')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AS')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AT')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AU')->setWidth(7);
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('AV')->setWidth(7);
		



	$head_hotel_row++;


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

		$objPHPExcel->getActiveSheet()->setTitle('Executive Portfolio Monthly');



		

		$objPHPExcel->setActiveSheetIndex(0);

		

$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);



$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);



/*$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);	

		

	*/	

//$objPHPExcel->getActiveSheet()->getStyle('B10:B999')

    //->getAlignment()->setWrapText(true);	

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



		header('Content-Disposition: attachment;filename="Executive Portfolio Monthly.xls"');

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

    <h1> Unit Executive Portfolio<small>Unit Executive Portfolio Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Unit Executive Portfolio</li>

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
				
<option value="">Select Sales Executive</option>	';
				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' ".$teamMembers." ".$UserRestriction." AND user_type=2 AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
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

              <!--<div class="col-md-4">

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

              </div>-->

              



			            <!--Area Executive-->

			            <!--<div class="col-md-4">

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

			  			  			

			            </div>-->

			            <!--Area Executive End-->



              

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

            <h3 class="box-title">Portfolio List</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped ">

                            <!--<thead>

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

                            </thead>-->

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

