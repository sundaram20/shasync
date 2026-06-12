<?php
if(!isset($cron)){
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'performanceAnalysis_report','view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
if($_REQUEST['report_date'] != ''){
	//list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
	$report_date= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date['0'];
	$checkout = $report_date['1'];
}	
					
}
	
if($_SESSION['userLevel'] !=1){
  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
  $resPer = mysqli_query($conn,$perSql);

  if($resPer){
      $perData  = mysqli_fetch_object($resPer);
      if($perData->calendar_user_list_approved == 0){
       $UserRestriction =" AND id='".$_SESSION['userId']."'"; 
      }
  }
}

?>
<?php
//echo $sql;
	if($_REQUEST['Search'] == 'Search'){
	$db->query($sql);
	$numRows= $db->num_rows();
	$pagging = new pagingClass($sql,$setpage);
	$db->query($pagging->getQuery());
	$total = $db->num_rows();
 }?>
<?php
if($_REQUEST['Download'] == 'Download'){
	


	  function cellColor($cells,$color){
	    global $objPHPExcel;
	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
             'rgb' => $color
	       )
	    ));
	}
$shop_id	= $_SESSION['shop'];
		error_reporting(1);
$date = date('Y-m-d',strtotime($checkin));
		$month = date('m',strtotime($date));




 $StartDayOfMonth	=	date('Y-'.$month.'-01');
 $LastDateOfMonth	=	date("Y-m-t", strtotime($StartDayOfMonth));
 
   $PreMonth			   =	date('Y-m-d', strtotime(date('Y-'.$month.'-01').' -1 MONTH')); 
   $LastDateOfMonthPrev	=	date('Y-m-t', strtotime(date('Y-'.$month.'-01').' -1 MONTH'));

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
			$period = date('Y').'-'.date('y',strtotime('+1 years',strtotime(date('Y'))));	
		}
				
		$end = strtotime($date);
		$end = strtotime("+1 year",$end);
		$end = date('Y-03-31',$end);

		$prevYear = strtotime($date);
		$prevYear = strtotime("-1 year",$prevYear);
		$prevYear = date('Y-04-01',$prevYear);
		$prevYearEnd = date('Y-03-31',strtotime('+1 years',strtotime($prevYear)));
		
		$prevYear2 = strtotime($date);
		$prevYear2 = strtotime("-2 year",$prevYear2);
		$prevYear2 = date('Y-04-01',$prevYear2);
		$prevYear2End = date('Y-03-31',strtotime('+1 years',strtotime($prevYear2)));
		
		
				
	if($_REQUEST['executive_user_id'] != ''){
				$sqlUser = " AND B.`user_id` = '".addslashes($_REQUEST['executive_user_id'])."'";
			}
	if($_REQUEST['teamId'] != ''){
		$teamId = implode(',',$_REQUEST['teamId']);
		$cond 	   .=	" AND FIND_IN_SET(D.`ids_team`,'".$teamId."')";
		
	}
		
		
 $sql.= "SELECT A.name,A.id_company,B.user_id,A.id_shop,D.id as id_user,D.name AS executiveName FROM `".TBL_COMPANY."` AS A 
 				  LEFT JOIN `".TBL_AREAS."` AS B ON A.area=B.id
				  LEFT JOIN `".TBL_USERS."` AS D ON B.user_id=D.id
				  
 				  WHERE  A.id_shop=".$_SESSION['shop']." AND B.id IN (".$_SESSION['teamMyAreas'].") $sqlUser AND A.name !='' $cond 
				  group by B.user_id  ORDER BY D.ids_team";
			
			
	
		 $res = mysqli_query($conn,$sql);
		if($res){
			 $numRows = mysqli_num_rows($res);
		}
				
		
		
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Hitesh Aloney")
									 ->setLastModifiedBy("Hitesh Aloney")
									 ->setTitle("Conveyance Report")
									 ->setSubject("Conveyance Report")
									 ->setDescription("Conveyance Report")
									 ->setKeywords("Conveyance Report")
									 ->setCategory("Report");
		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		$logo = selectColumn('fs_shop','image'," WHERE  id=".$shop_id." ");
		
		if(!isset($cron)){
			$signature = "../uploaded_files/shop/".$logo."";
		}
		else{
		   //Path to signature .jpg file
			//local
			//$signature = "../../uploaded_files/shop/".$logo."";
			//server cron
			$signature = "/home/admingcs/public_html/sync/uploaded_files/shop/".$logo."";
		}
		
		if(file_exists($signature)){
		$objDrawing->setPath($signature);
		$objDrawing->setOffsetX(25);                       //setOffsetX works properly
		$objDrawing->setOffsetY(10);                       //setOffsetY works properly
		$objDrawing->setCoordinates('D1');        //set image to cell
		//$objDrawing->setWidth(200);                 //set width, height
		//$objDrawing->setHeight(120);
		$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}  //save
		if($numRows > 0){
		$counter = 1;
		if(!isset($cron)){
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Performance Analysis Report  From '.$_REQUEST['report_date']);
		}
		else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A6', 'Performance Analysis Report From '.$_REQUEST['report_date']);
		}	
		$objPHPExcel->getActiveSheet()->mergeCells('A6:M6');
		$head_hotel_row = 7;
		$head_cntr_column = "A";$head_hotel_column = "A";
$head_hotel_row = 8;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Team')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'This Year')
			
			->setCellValue($head_cntr_column++.$head_hotel_row, 'GOLY')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'V2B')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Last Year')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'This Year')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'GOLY')
			->setCellValue($head_cntr_column++.$head_hotel_row, 'V2B');
			
			$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->getFont()->setBold( true );	
			$objPHPExcel->getActiveSheet()->getStyle('A8:M8')->getFont()->setBold( true );						
$objPHPExcel->getActiveSheet()->mergeCells('D7:H7');
$objPHPExcel->getActiveSheet()->mergeCells('I7:M7');
$objPHPExcel->getActiveSheet()->mergeCells('A7:A8');
$objPHPExcel->getActiveSheet()->mergeCells('B7:B8');
$objPHPExcel->getActiveSheet()->mergeCells('C7:C8');
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A7', 'S.No.')
			->setCellValue('B7', 'Team')
		->setCellValue('C7', 'Executive Name')
		->setCellValue('D7', 'Month To Date')
		->setCellValue('I7', 'Year To Date ');
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
		$styleArray_grand = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '000'),
	        'size'  => 14,
			'text-transform'=>'uppercase'
	 
	    ));
cellColor('A6:M6','254061');
cellColor('A8:M8','75923c');
cellColor('A7:M7','75923c');
	$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
	 $objPHPExcel->getActiveSheet()->getStyle('A6:F6')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('A7:N7')->applyFromArray($styleArray_1);
	 $objPHPExcel->getActiveSheet()->getStyle('A7:N7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	 $objPHPExcel->getActiveSheet()->getStyle('A8:N8')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
$objPHPExcel->getActiveSheet()->getStyle('B7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('C7')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	
	$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->applyFromArray(
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
			
	$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setWrapText(true);
	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(6);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(35);	
	$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	
	
	if($numRows>0){
	$head_hotel_row++;
						
				
		$Serialno=1;
		$connew = 9;
		while($row = mysqli_fetch_object($res)){
		
		if($row->user_id!=''){
		
		$ids_team	=selectColumn(TBL_USERS,'ids_team'," WHERE `id` =".$row->user_id." and id_shop=".$row->id_shop." ");
		
		$teamname='';
		$teamname=array();
		$teamnameID	=array();
		$perSql	=	"SELECT name,id FROM `mst_team` WHERE id IN (".$ids_team.") AND id_shop=".$_SESSION['shop'];
  		$resPer = mysqli_query($connNew,$perSql);
		
		while($perData  = mysqli_fetch_object($resPer)){
		$teamname[]= $perData->name;	
		$teamnameID[]= $perData->id;	
		}
		if($Serialno == 1){
			$assianTeamId	=	$teamnameID[0];
			$AssignSumRow	= 9;
			}
		if($teamnameID[0]!=$assianTeamId){
		$assianTeamId	=	'';
		
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':C'.$connew);
			$CellColor	=	($connew);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$CellColor.':M'.($CellColor))->getFont()->setBold( true );
	$objPHPExcel->getActiveSheet()->getStyle('A'.$CellColor.':M'.($CellColor))->getFont()->setBold( true );
	
			cellColor('A'.$CellColor.':M'.$CellColor,'b5d8ab');					
			/* $objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':C'.$connew)->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
	);*/
			

	
	
			$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->setActiveSheetIndex(0)
			   			->setCellValue('A'.$connew++, 'TOTAL');
			$AssignSumRow	= $connew;
			}else{
				
	
			}
		$assianTeamId	=	$teamnameID[0];
			$Monthachieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$StartDayOfMonth."' and '".$LastDateOfMonth."' and id_shop='".$_SESSION['shop']."' and id_user='".$row->user_id."'  ");

				$Monthbudget = selectColumn(TBL_AGENT_BUDGET,'sum(room_nights)'," WHERE `id_user` = '".$row->user_id."'  AND (( `".TBL_AGENT_BUDGET."`.from <=  '".date('Y-m-d',strtotime($StartDayOfMonth))."' and  `".TBL_AGENT_BUDGET."`.to >= '".date('Y-m-d',strtotime($LastDateOfMonth))."') OR (  `".TBL_AGENT_BUDGET."`.from between '".date('Y-m-d',strtotime($StartDayOfMonth))."' and '".date('Y-m-d',strtotime($LastDateOfMonth))."') OR (  `".TBL_AGENT_BUDGET."`.to between '".date('Y-m-d',strtotime($StartDayOfMonth))."' and '".date('Y-m-d',strtotime($LastDateOfMonth))."'))  ");

				$prevMonthAch = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$PreMonth."' and '".$LastDateOfMonthPrev."' and id_shop='".$_SESSION['shop']."' and id_user='".$row->user_id."'  ");

			
			
//Year Start
				$achieved =selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$date."' and '".$end."' and id_shop='".$_SESSION['shop']."' and id_user='".$row->user_id."'  ");

				$budget = selectColumn(TBL_AGENT_BUDGET,'sum(room_nights)'," WHERE `id_user` = '".$row->user_id."' AND (( `".TBL_AGENT_BUDGET."`.from <=  '".date('Y-m-d',strtotime($date))."' and  `".TBL_AGENT_BUDGET."`.to >= '".date('Y-m-d',strtotime($end))."') OR (  `".TBL_AGENT_BUDGET."`.from between '".date('Y-m-d',strtotime($date))."' and '".date('Y-m-d',strtotime($end))."') OR (  `".TBL_AGENT_BUDGET."`.to between '".date('Y-m-d',strtotime($date))."' and '".date('Y-m-d',strtotime($end))."'))   ");

				$prevAch = selectColumn(TBL_AGENT_ACHIEVED,'sum(qty)'," WHERE month between '".$prevYear."' and '".$prevYearEnd."' and id_shop='".$_SESSION['shop']."' and id_user='".$row->user_id."'  ");
//Year Start
				
			$GOLYM	=	(($Monthachieved-$prevMonthAch)/$prevMonthAch)*100;
			$GOLY	=	(($achieved-$prevAch)/$prevAch)*100;
		
		$v2bM	=	$Monthbudget-$Monthachieved;
		$v2bY	=	$budget-$achieved;
		
		$teamname=implode(',',$teamname);
		$head_order_data1 = "A";
		$head_order_data = "A"; 
		
		
		$GrandTotalprevMonthAch +=$prevMonthAch;
		$GrandTotalMonthbudget +=$Monthbudget;
		$GrandTotalMonthachieved+=$Monthachieved;
		$GrandTotalGOLYM +=$GOLYM;
		$GrandTotalv2bM +=$v2bM;
		
		$GrandTotalprevAch +=$prevAch;
		$GrandTotalbudget +=$budget;
		$GrandTotalachieved+=$achieved;
		$GrandTotalGOLY +=$GOLY;
		$GrandTotalv2bY +=$v2bY;

		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$connew)->getAlignment()->applyFromArray(
	    	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
		);


		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($head_order_data++ . $connew, $Serialno++)
			->setCellValue($head_order_data++ . $connew, $teamname)
			->setCellValue($head_order_data++ . $connew, $row->executiveName)
			->setCellValue($head_order_data++ . $connew, isset($prevMonthAch)?$prevMonthAch:0)
			->setCellValue($head_order_data++ . $connew, isset($Monthbudget)?$Monthbudget:0)
			->setCellValue($head_order_data++ . $connew, $Monthachieved==''?0:$Monthachieved)
			->setCellValue($head_order_data++ . $connew, round($GOLYM,2))
			->setCellValue($head_order_data++ . $connew, $v2bM)
			->setCellValue($head_order_data++ . $connew, isset($prevAch)?$prevAch:0)
			->setCellValue($head_order_data++ . $connew, isset($budget)?$budget:0)
			->setCellValue($head_order_data++ . $connew, $achieved==''?0:$achieved)
			->setCellValue($head_order_data++ . $connew, round($GOLY,2))
			->setCellValue($head_order_data++ . $connew, $v2bY)
			;
		//->setCellValue($head_order_data++ . $connew, date('d-M-Y',strtotime($NextFollowUPBackRow['dated'])))
	
		//->setCellValue($head_order_data . $connew, ($row->lead_status==1?'Open':'Close'));
		
		//$objPHPExcel->getActiveSheet()->getStyle('A8:G'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A6:M6')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A8:M8')->applyFromArray($styleThinBlackBorderOutline);
	
		$connew++;	
		
		
		//$Serialno =$EndSerialno++;
		}
		//Grand total start here
		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);			
			$objPHPExcel->setActiveSheetIndex(0)
			   			->setCellValue('A'.$connew, 'TOTAL')
						->setCellValue('D'.($connew),'=SUM(D'.($AssignSumRow).':D'.($connew-1).')')
						->setCellValue('E'.($connew),'=SUM(E'.($AssignSumRow).':E'.($connew-1).')')
						->setCellValue('F'.($connew),'=SUM(F'.($AssignSumRow).':F'.($connew-1).')')
						->setCellValue('G'.($connew),'=((SUM(F'.($AssignSumRow).':F'.($connew-1).')-SUM(D'.($AssignSumRow).':D'.($connew-1).'))/SUM(D'.($AssignSumRow).':D'.($connew-1).'))*100')
						->setCellValue('H'.($connew),'=SUM(H'.($AssignSumRow).':H'.($connew-1).')')
			   			->setCellValue('I'.($connew),'=SUM(I'.($AssignSumRow).':I'.($connew-1).')')
						->setCellValue('J'.($connew),'=SUM(J'.($AssignSumRow).':J'.($connew-1).')')
						->setCellValue('K'.($connew),'=SUM(K'.($AssignSumRow).':K'.($connew-1).')')
						->setCellValue('L'.($connew),'=((SUM(K'.($AssignSumRow).':K'.($connew-1).')-SUM(I'.($AssignSumRow).':I'.($connew-1).'))/SUM(I'.($AssignSumRow).':I'.($connew-1).'))*100')
						->setCellValue('M'.($connew),'=SUM(M'.($AssignSumRow).':M'.($connew-1).')');

						$objPHPExcel->getActiveSheet()->getStyle('D'.$connew.':M'.$connew)->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	
	$forTotal = 'C';
		$totalArray = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '1e51bf'),
	        'size'  => 12,
	        'name'  => 'Verdana'
	    ));
		$assianTeamId	=	$teamnameID[0];
		//}
}//User id Not null
$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->getFont()->setBold( true );
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->mergeCells('A'.$connew.':C'.$connew);
					cellColor('A'.$connew.':M'.$connew,'b5d8ab');
$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':C'.$connew)->applyFromArray($styleArray_1);
	 

$connew++;
$objPHPExcel->setActiveSheetIndex(0)
			   			->setCellValue('B'.$connew, 'Grand Total')
						->setCellValue('D'.($connew),$GrandTotalprevMonthAch)
						->setCellValue('E'.($connew),$GrandTotalMonthbudget)
						->setCellValue('F'.($connew),$GrandTotalMonthachieved)
						->setCellValue('G'.($connew),(($GrandTotalMonthachieved-$GrandTotalprevMonthAch)/$GrandTotalprevMonthAch)*100)
						->setCellValue('H'.($connew),$GrandTotalv2bM)
			   			->setCellValue('I'.($connew),$GrandTotalprevAch)
						->setCellValue('J'.($connew),$GrandTotalbudget)
						->setCellValue('K'.($connew),$GrandTotalachieved)
						->setCellValue('L'.($connew),(($GrandTotalachieved-$GrandTotalprevAch)/$GrandTotalprevAch)*100)
						->setCellValue('M'.($connew),$GrandTotalv2bY);
	
	}//exit;
		
	$totalArrayGrandTotal = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '1e51bf'),
	        'size'  => 11,
	        'name'  => 'Verdana'
	    ));	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->getFont()->setBold( true );
	$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->mergeCells('B'.$connew.':C'.$connew);
					cellColor('A'.$connew.':M'.$connew,'b5d8ab');

		$objPHPExcel->getActiveSheet()->getStyle('D'.$connew.':M'.$connew)->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);			

$objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':M'.$connew)->applyFromArray($totalArrayGrandTotal);
	 $objPHPExcel->getActiveSheet()->getStyle('A'.$connew.':C'.$connew)->getAlignment()->applyFromArray(
	    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,)
	);

}
		$objPHPExcel->getActiveSheet()->setTitle('Performance Analysis Report');
		
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
	
		$fileName =  'Performance_Analysis_Report'.date('d_M_Y');       
		ob_end_clean();
		if(!isset($cron)){
			// Redirect output to a client’s web browser (Excel2007)
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
			if($numRows>0){
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				
				//local
				//$objWriter->save('../mailattach/'.$fileName.'.xls');
				
				//cron server
				$objWriter->save('/home/admingcs/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls');
			}	
		}	
}
?>
<?php
if(!isset($cron)){
	if($_REQUEST['Download']=='Download'){
		feedbackReport($conn,$_SESSION['shop'],$checkin,$checkout,$_REQUEST['executive_user_id'],$_REQUEST['feedType'],$_REQUEST['lead_status'],$objPHPExcel,$_SESSION['teamMembers'],$cron,$fileName);
	}
include_once("includes/header.php");?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Sales Report<small>Performance Analysis Report</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Performance Analysis Report</li>
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
          <h3 class="box-title">Performance Analysis Report</h3>
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
              <div class="form-group col-sm-4">
                <label for="reservation_date">From - To </label>
                <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">
                </div>
              </div>
              
              <div class="col-md-4">
                            <div class="form-group">
                            <label>Team</label>
                          
                           <?php $categoryDropDown = '<select class="form-control " name="teamId[]" onclick="reportSelection(this.value,this.id);" id="teamId" multiple="multiple">
                        <option value="0">All Team</option>';
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
              <?php
			 /* if($_SESSION['userLevel']==1){
				 	
				  $ConditonUserLevel = "";
				  }else{
					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";
					  }*/
					  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
					  $resPer = mysqli_query($connNew,$perSql);
					  if($resPer){
					    	$perData	=	mysqli_fetch_object($resPer);
					      if($perData->calendar_user_list_approved == 0){
					  	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
					      }
					  }
					  if($_SESSION['teamMembers'] !=""){
					    $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
					  }
					  else{
					    $teamMembers ="";
					  }
			  ?>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Sales Executive</label>
                  
                  <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                  
                  <?php
                   
    
                         $categoryDropDown = '<select class="form-control select2 " name="executive_user_id" id="executive_user_id" >
                                                        <option value="">All user Name</option>';
                                  
                                        $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ".$UserRestriction.$teamMembers." ",' ORDER BY `name`');
                                   
                                        if($db->num_rows2($resUserLevel)){
                                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                          if($_SESSION['userId'] == $resultUserLevel->id){
                                            $selected = 'selected="selected"';
                                          }else{
                                            $selected = '';
                                          }
                                          $categoryDropDown .= '<option  value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
                                        }
                                        }
                                        echo $categoryDropDown .= '</select>';
                                        ?>
                </div>
              </div>
              
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
            
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped text-center">
                
                <!--<thead>
                            <tr>
                              <th>S.No.&nbsp;</th>
                              <th>Hotel</th>
                              <th>Assign By</th>
                           
                              <th>Assigned On</th>
                              <th>Assign To</th>
                           
            				  <th>Feed Back  Date</th>                         
            				            				
                              <th>Feed Back Summary</th>
                              <th>Status</th>
                         
                              <th>Action</th>
                          
            				
                            </tr>
                            </thead>-->
                
                <tbody>
                  <?php 				 				
            				if($total > 0){$counter = 1;
            				  while($row = $db->fetch_object()){?>
                  <tr>
                    <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                    <td style="text-align:left;"><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'");  ?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$_SESSION['shop']." ");
                               ?></td>
                    <td><?php echo date('d M Y',strtotime($row->date_created));?></td>
                    <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->assign_user_id." and id_shop=".$_SESSION['shop']." ");
                               ?></td>
                    <td><?=date('d M Y',strtotime($row->dated));?></td>
                    <td><?php echo selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$row->id."' AND `visit_id` = '".$row->visit_id."'"); ?></td>
                    <td ><!--<button  class="btn <?php echo $row->lead_status==1?'btn-success':'btn-danger';?>" type="button"   ></button>--> 
                      <?php echo $row->lead_status==1?'Open':'Close';?></td>
                    
                    <!--<td>&nbsp;&nbsp;<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='addreport.php?eId=<?=encryptor('encrypt',$row->visit_id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>--> 
                    
                  </tr>
                  <?php }?>
                  <tr>
                    <td align="right" colspan="13"><?php  echo $pagging->getLinks();?></td>
                  </tr>
                  <?php }else {?>
                  <tr> 
                    
                    <!--<td height="200" align="center" colspan="13">---- No Record Found ---- </td>--> 
                    
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
<div id="duplicate" class="well" style="display:none;"> </div>
<?php
	
  include_once("includes/footer.php");
}?>

<script type="text/javascript">
	
	function reportSelection(op,id){
	     		if(document.getElementById(id).options[0].selected == true){
	    			console.log("selected");
	    			selectAll(id,true);
	    		}
	    	}
	function selectAll(selectBox,selectAll) { 

	    		 if (typeof selectBox == "string") { 
	    		    selectBox = document.getElementById(selectBox);
	    		   }
	    		
	    		   for (var i = 0; i < selectBox.options.length; i++) { 
	    		       selectBox.options[i].selected = selectAll; 
	    		    }		  		    
	    	}
</script>