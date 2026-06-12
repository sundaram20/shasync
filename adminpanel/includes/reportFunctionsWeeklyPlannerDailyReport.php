<?php
//error_reporting(E_ALL);
error_reporting(0);



	//Color Cell Function

	function cellColor($cells,$color){

	    global $objPHPExcel;

		$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(

	        'type' => PHPExcel_Style_Fill::FILL_SOLID,

	        'startcolor' => array(

	        'rgb' => $color

	    	)	

	    ));

	}

	

	function WeeklyPlannerDailyReport($id_shop,$id_executive ,$report_date,$id_team,$companyId,$cron,$db,$connNew,$objPHPExcel,$fileName){
		
		global $connNew;
		global $objPHPExcel;
		
		
		
		$reservation_date = explode(' to ',$report_date);
		$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
		$checkoutDate =date ("Y-m-d", strtotime($reservation_date['1']));
		
	

		$styleArray = array(
		      'font'  => array(
	          'bold'  => true,
	          'color' => array('rgb' => 'FFFFFF'),
	          'size'  => 14,
		      'text-transform'=>'uppercase',
	          'name'  => 'Calibri'
	    ));

	  	$styleArray_1 = array(
		      'font'  => array(
	          'bold'  => true,
	          'color' => array('rgb' => '000'),
	          'size'  => 12,
		      'text-transform'=>'uppercase'
        ));

        $styleThinBlackBorderOutline = array(
					'borders' => array(
					'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('argb' => '000'),
					),
				),
		);	


		$objPHPExcel->getProperties()
					->setCreator("Shafeer Ahamed")
					->setLastModifiedBy("Shafeer Shafeer")
					->setTitle("Executive Portfoilo Report")
					->setSubject("Executive Portfoilo Report")
					->setDescription("Executive Portfoilo Report")
					->setKeywords("Executive Portfoilo Report")
					->setCategory("Report");				 	



		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		/*$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");



		if(!isset($cron)){
			$signature = '../uploaded_files/shop/'.$logo;
		}
		else{
			$signature = '/var/www/vhosts/roomstatushub.in/httpdocs/sales/uploaded_files/shop/'.$logo;
		}*/


		/*if(file_exists($signature)){
			$objDrawing->setPath($signature);
			$objDrawing->setOffsetX(20);                       
			$objDrawing->setOffsetY(10);                       
			$objDrawing->setCoordinates('F1');      
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
		}*/

		
//while (strtotime($checkinDate) < strtotime($checkoutDate)) {
			
$sqlConn ="And ( allocation_date BETWEEN '".date('Y-m-d',strtotime($checkinDate))."' And '".date('Y-m-d',strtotime($checkoutDate))."')";
 
 if($id_executive!=''){
 
	$sqlConn	 .=	"And `user_id` ='".$id_executive."'";
 }
 
  if($id_team!=''){
 
		$id_teams = $id_team; 
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$id_shop." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		$teamName= selectColumn(TBL_TEAM,'name'," WHERE `id` =".$id_teams." and id_shop=".$id_shop." ");
		$sqlConn .= " AND `user_id` IN (".addslashes($teamMembers).")";
 }

// $id_team
 if($companyId!=''){
 
	$sqlConn	 .=	"And `id_company` ='".$companyId."'";
 }
 
 

 $sqlFollowUpExplode1 = "SELECT * FROM `fs_weeklyplanner`  
 
 WHERE `id` !='0' ".$sqlConn." ORDER BY allocation_date,user_id asc";
//echo $sqlFollowUpExplode1;
$resQue = mysqli_query($connNew,$sqlFollowUpExplode1);

	
	$WeeklyPlanner=array();
		$NumberRows	=	mysqli_num_rows($resQue);
	
if($NumberRows>0){
while($RowFollowUpExplode=mysqli_fetch_object($resQue)){
	
	//debugData($RowFollowUpExplode);
	$id_account	= $RowFollowUpExplode->id_account;
	$type	= $RowFollowUpExplode->type;
	if($cron==''){		
$myownteam_id= selectColumn(TBL_USERS,'myownteam_id'," WHERE `id` =".$RowFollowUpExplode->user_id." and id_shop=".$id_shop." ");		
 $teamName= selectColumn(TBL_TEAM,'name'," WHERE `id` =".$myownteam_id." and id_shop=".$id_shop." ");
}
	
	if($type=='1'){
		
		
		if($id_account=='1'){
		$DataPlanner	=	'Visit';// Existing Customer';
		}else{
			$DataPlanner	=	'Visit'; // 'New Account';
			
			}
		
		}else{
			
			$DataPlanner	=	'Activity';
			
			}
	//$RowFollowUpExplode->allocation_date=$checkinDate;
	
	$ExeUserName	=	selectColumn('fs_users','name'," WHERE `id` = '".addslashes($RowFollowUpExplode->user_id)."'");
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['executive']=$ExeUserName; 
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['TeamName']=$teamName;
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['allocation_date']=$RowFollowUpExplode->allocation_date; 
	//print_r($RowFollowUpExplode);
	if($RowFollowUpExplode->description!=''){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['description']=$RowFollowUpExplode->description;
	}else{
		
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['description']='-';	
		}
		
		
		
		if($RowFollowUpExplode->id_company>0){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_name']=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes($RowFollowUpExplode->id_company)."'"); 
		
		}elseif($RowFollowUpExplode->id_other_activity>0){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_name']=selectColumn('sales_other_activity','name'," WHERE `id` = '".addslashes($RowFollowUpExplode->id_other_activity)."'");  	
		}elseif($id_account=='2'){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_name']='New Account';
		}
		
		
		
		
	if($RowFollowUpExplode->id_contact>0){
		$sqlCus = "SELECT * FROM fs_customer  WHERE  type='2' and id_customer='".addslashes($RowFollowUpExplode->id_contact)."'";

	$resCus = mysqli_query($connNew,$sqlCus);
	$Rowcus=mysqli_fetch_object($resCus);
	$Custome			= $Rowcus->title.''.$Rowcus->first_name.' '.$Rowcus->last_name;
	
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_contact']=$Custome;
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['contact_mobile']=$Rowcus->mobile;

	
	}elseif($RowFollowUpExplode->contact_name!=''){
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_contact']=$RowFollowUpExplode->contact_name;
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['contact_mobile']=$RowFollowUpExplode->contact_mobile;
		
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['company_contact']='-';
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['contact_mobile']='-';
		}
		
		
		
		
		
	   $WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['type']=$DataPlanner;
	
	
	
	if($RowFollowUpExplode->contact_mobile!=''){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['contact_mobile']=$RowFollowUpExplode->contact_mobile;
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['contact_mobile']='-';
		}
	
	if($RowFollowUpExplode->id_other_activity>0){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['activity']=selectColumn('sales_other_activity','name'," WHERE `id` = '".addslashes($RowFollowUpExplode->id_other_activity)."'"); 
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$RowFollowUpExplode->id]['activity']='-'; 
		}
	
	
}


		
		//echo $checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));

		
		
		///}
		//debugData($WeeklyPlanner);die;

		$head_hotel_row = 7;
		$head_cntr_column = "C";
							
	foreach($WeeklyPlanner as $datenew=>$datalist){
			$head_hotel_row++;	
		
		$head_cntr_column = "C";
		
		//cellColor('C6:H6','254061');
		//cellColor('C7:H7','75923c');

		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C'.$head_hotel_row,''.date ("d-m-Y", strtotime($datenew)));
		
		$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->getStyle('C'.($head_hotel_row).':H'.($head_hotel_row))->applyFromArray($styleThinBlackBorderOutline);
		//$objPHPExcel->getActiveSheet()->mergeCells('C'.$head_hotel_row.':H'.$head_hotel_row);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$head_hotel_row.':H'.$head_hotel_row)->getFont()->setBold( true );
		//$objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray($styleArray);
		
		//$objPHPExcel->getActiveSheet()->getStyle('C'.$head_hotel_row.':H'.$head_hotel_row)->getAlignment()->applyFromArray(
								    //    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									 //   );
		
		
$head_hotel_row++;
cellColor('C'.$head_hotel_row.':H'.$head_hotel_row,'75923c');
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Executive Name.')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Team')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Company Name')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Contact Name')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Phone')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Focuse'); 

		
		$objPHPExcel->getActiveSheet()->getStyle('C'.$head_hotel_row.':H'.$head_hotel_row)->getFont()->setBold( true ); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(30); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(25);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(20);  
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );
		$objPHPExcel->getActiveSheet()->getStyle('C'.($head_hotel_row).':H'.($head_hotel_row))->applyFromArray($styleThinBlackBorderOutline);								
										
			foreach($datalist as $datalist2){
				$head_hotel_row++;	
		
		$head_cntr_column = "C";
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['executive'])
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['TeamName'])
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['company_name'])
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['company_contact'])
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['contact_mobile'])
					->setCellValue($head_cntr_column++.$head_hotel_row, $datalist2['description']);
			$objPHPExcel->getActiveSheet()->getStyle('C'.($head_hotel_row).':H'.($head_hotel_row))->applyFromArray($styleThinBlackBorderOutline);
			
			}
				
			
			
			
			
			
		
		$head_hotel_row =$head_hotel_row + 2;
	
	}
		

		/*if($id_company!='')
			$filter = ' AND id_company="'.$id_company.'"';
		else
			$filter = '';
	    
	     if($usernameid!=''){
	
		    $filter .= " AND `id_user` = '".addslashes($usernameid)."'";
	
		   //$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$_REQUEST['usernameid'].'" ');
	
		}*/

		

		//$objPHPExcel->getActiveSheet()->getStyle('C8:H'.($head_hotel_row))->applyFromArray($styleThinBlackBorderOutline);



if($cron==''){
		$objPHPExcel->getActiveSheet()->setTitle('Weekly Planner Detail Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()
				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	    
	    ob_end_clean();

		header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="WeeklyPlanner_Detail_Report_'.date('d-m-Y H:i:s').'.xls"');
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
	
}else{

$objPHPExcel->getActiveSheet()->setTitle('Weekly Planner Detail Report');
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.50);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0.10);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0.15);
		$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(1); 
		$objPHPExcel->getActiveSheet()->getPageSetup()
					->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()
				    ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	    
	    ob_end_clean();

		header('Content-Type: application/vnd.ms-excel');
	    header('Content-Disposition: attachment;filename="WeeklyPlanner_Detail_Report_'.date('d-m-Y H:i:s').'.xls"');
	    header('Cache-Control: max-age=0');
	    // If you're serving to IE 9, then the following may be needed
	    header('Cache-Control: max-age=1');
	    // If you're serving to IE over SSL, then the following may be needed
	    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	    header ('Pragma: public'); // HTTP/1.0

	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	   // $objWriter->save('php://output');	
	$objWriter->save('/var/www/vhosts/roomstatushub.in/httpdocs/sales/adminpanel/mailattach/WeeklyPlanner_Detail_Report_'.$teamName.'.xls');	
	//('/var/www/vhosts/roomstatushub.in/httpdocs/sales/adminpanel/mailattach/'.$Filename.'.pdf'

}
	}	
	}
?>