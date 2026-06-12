<?php

//error_reporting(E_ALL);



	//WARNING : If you don't know how to indent a code kindly learn that first : 



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

	
	

	function reportAgentAchieved($id_shop,$id_user,$seasonId,$id_hotel=''){
		$seasonId	= (encryptor('decrypt',$seasonId));
		$id_user	= (encryptor('decrypt',$id_user));
		$_REQUEST['id_hotel']=$id_hotel;
		
	$unitUser = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" ');	
		if($unitUser=='2'){
	$userTypeTable	= TBL_UNIT_AGENT_ACHIEVED;	
	$SqlConn		  = "and a.id_hotel = '".$_REQUEST['id_hotel']."' and FIND_IN_SET('".$id_user."',C.ids_unit_user)";
	$SqlunitConn		  = "and ach.id_hotel = '".$_REQUEST['id_hotel']."' ";
	//echo 'UNIT USER';
	}else{
			$userTypeTable	= TBL_AGENT_ACHIEVED;			
			$SqlConn		  = "and C.`user_id`='".$_REQUEST['hotelId']."'";
			$SqlunitConn  = "and ach.id_user = '".$id_user."' ";
			//echo 'RSO USER ';
		}
		
		
		global $connNew;
		global $objPHPExcel;

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
					->setCreator("shafeer")
					->setLastModifiedBy("shafeer")
					->setTitle("Executive Portfoilo Report")
					->setSubject("Executive Portfoilo Report")
					->setDescription("Executive Portfoilo Report")
					->setKeywords("Executive Portfoilo Report")
					->setCategory("Report");				 	



		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");



		if(!isset($cron)){
			$signature = '../uploaded_files/shop/'.$logo;
		}
		else{
			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;
		}


		
		cellColor('A6:N6','254061');
		cellColor('A7:N7','75923c');
$reportstartDate	=' April '.date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'")));
$reportendDate	=' To March '.date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'")));
$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ');
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A6','Agent Achieved Report  For '.$exeName.$reportstartDate.$reportendDate);
		
		$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
		
		$objPHPExcel->getActiveSheet()->mergeCells('A6:N6');
		$objPHPExcel->getActiveSheet()->getStyle("A6:N7")->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getStyle('A6:N7')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );




		$head_hotel_row = 7;

		$head_cntr_column = "A";


		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Agent')
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Apr -'.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row, 'Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'"))))
					->setCellValue($head_cntr_column++.$head_hotel_row++, 'Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'")))); 

		
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(45); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(8); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(8);

		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
									    );

		
		
		  $sql = "select  
budget.name,
budget.id_company,
budget.ids_unit_user,
sum(budget.Apr) as Apr,sum(budget.May) as May,sum(budget.Jun) as Jun,
sum(budget.Jul) as Jul,sum(budget.Aug) as Aug,sum(budget.Sep) as 'Sep',
sum(budget.Oct) as Oct,sum(budget.Nov) as Nov,sum(budget.Dec) as 'Dec',
sum(budget.Jan) as Jan,sum(budget.Feb) as Feb,sum(budget.Mar) as Mar,
sum(budget.Total) as Total
from 
(
select distinct
com.name, com.id_company, ar.ids_unit_user,
case when month(ach.month)=4 then ach.qty end as 'Apr',case when month(ach.month)=5 then ach.qty end as 'May',
case when month(ach.month)=6 then ach.qty end as 'Jun',case when month(ach.month)=7 then ach.qty end as 'Jul',
case when month(ach.month)=8 then ach.qty end as 'Aug',case when month(ach.month)=9 then ach.qty end as 'Sep',
case when month(ach.month)=10 then ach.qty end as 'Oct',case when month(ach.month)=11 then ach.qty end as 'Nov',
case when month(ach.month)=12 then ach.qty end as 'Dec',case when month(ach.month)=1 then ach.qty end as 'Jan',
case when month(ach.month)=2 then ach.qty end as 'Feb',case when month(ach.month)=3 then ach.qty end as 'Mar',
ach.qty as Total
from fs_areas_assign ar
inner join
fs_company com
on
com.area = ar.id
inner join
`".$userTypeTable."` ach
on
ach.id_company=com.id_company
where com.id_shop='".addslashes($_SESSION['shop'])."' $SqlunitConn and ach.seasonId='".$seasonId."'  and com.name<>'' and FIND_IN_SET('".$id_user."',ar.ids_unit_user)
) as budget
group by budget.name,
budget.id_company,
budget.ids_unit_user
having sum(budget.Total)>0
order by budget.name";
 //echo $sql;die;
		$res = mysqli_query($connNew,$sql);
		$sno = 1; 
		while($row = mysqli_fetch_object($res)){
			$head_cntr_column = "A";
			
			$date = date('d-M-Y',strtotime($row->dated));
			$company = $row->name;
			$idCompanyArea = selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$row->id_company.'" ');
			//$companyLocation = selectColumn(TBL_AREAS,'name','WHERE id="'.$idCompanyArea.'" ');
			$companyLocation =selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$row->id_company.'" ');
			$title = selectColumn(TBL_CUSTOMER,'title','WHERE id_customer="'.$row->id_contacts.'" ');
			$firstName = selectColumn(TBL_CUSTOMER,'first_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$lastName = selectColumn(TBL_CUSTOMER,'last_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$id_designation = selectColumn(TBL_CUSTOMER,'designation','WHERE id_customer="'.$row->id_contacts.'" ');
			$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');
			$companyEmail = selectColumn(TBL_COMPANY,'email','WHERE id_company="'.$row->id_company.'" '); 
			$email = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contacts.'" ');
			$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$row->id_contacts.'" ');
			$idGroup = selectColumn(TBL_COMPANY,'id_default_group','WHERE id_company="'.$row->id_company.'" ');
			$companyGroup = selectColumn(TBL_GROUP,'name','WHERE id_group="'.$idGroup.'" ');
			$description = $row->discussion_summary;
			$details = selectColumn(TBL_COMPANY,'details','WHERE id_company="'.$row->id_company.'" ');
			$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ');

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($head_cntr_column++.$head_hotel_row, $sno++)
						->setCellValue($head_cntr_column++.$head_hotel_row, $company)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Apr)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->May)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jun)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jul)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Aug)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Sep)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Oct)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Nov)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Dec)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jan)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Feb)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Mar)
						->setCellValue($head_cntr_column++.$head_hotel_row++, $row->Total); 

		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A6:N'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);




		$objPHPExcel->getActiveSheet()->setTitle('Agent Achieved Report');
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
	    header('Content-Disposition: attachment;filename="Agent_Achieved_Report_'.$exeName.$reportstartDate.$reportendDate.date('d-m-Y H:i:s').'.xls"');
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

	}
	
	function reportExecutiveBudget($id_shop,$id_user,$seasonId,$type){
	if($type=='1'){
$file	='Hotel Wise Monthly Budget';
}else{
$file	='Hotel Wise Monthly Achieved';
}	
		$seasonId	= (encryptor('decrypt',$seasonId));
		$id_user	= (encryptor('decrypt',$id_user));
		$_REQUEST['id_hotel']=$id_hotel;
		
	$unitUser = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" ');	
		if($unitUser=='2'){
	$userTypeTable	= TBL_UNIT_AGENT_ACHIEVED;	
	$SqlConn		  = "and a.id_hotel = '".$_REQUEST['id_hotel']."' and FIND_IN_SET('".$id_user."',C.ids_unit_user)";
	$SqlunitConn		  = "and ach.id_hotel = '".$_REQUEST['id_hotel']."' ";
	//echo 'UNIT USER';
	}else{
			$userTypeTable	= TBL_AGENT_ACHIEVED;			
			$SqlConn		  = "and C.`user_id`='".$_REQUEST['hotelId']."'";
			$SqlunitConn  = "and ach.id_user = '".$id_user."' ";
			//echo 'RSO USER ';
		}
		
		
		global $connNew;
		global $objPHPExcel;

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
					->setCreator("shafeer")
					->setLastModifiedBy("shafeer")
					->setTitle("Executive Portfoilo Report")
					->setSubject("Executive Portfoilo Report")
					->setDescription("Executive Portfoilo Report")
					->setKeywords("Executive Portfoilo Report")
					->setCategory("Report");				 	



		$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing
		$objDrawing->setName('Logo');       //set name to image
		$objDrawing->setDescription('Logo'); //set description to image
		$logo = selectColumn('fs_shop','image'," WHERE  id=".$id_shop." ");



		if(!isset($cron)){
			$signature = '../uploaded_files/shop/'.$logo;
		}
		else{
			$signature = '/home/inroomhub/public_html/sync/uploaded_files/shop/'.$logo;
		}


		
		cellColor('A6:P6','254061');
		cellColor('A7:P7','75923c');
$reportstartDate	=' April '.date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'")));
$reportendDate	=' To March '.date('Y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'")));
$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$id_user.'" ');
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A6',$file.' Report  For '.$exeName.' From '.$reportstartDate.$reportendDate);
		
		$objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setWrapText(true);
		
		$objPHPExcel->getActiveSheet()->mergeCells('A6:P6');
		$objPHPExcel->getActiveSheet()->getStyle("A6:P7")->getFont()->setBold( true );
		$objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleArray);
		
		$objPHPExcel->getActiveSheet()->getStyle('A6:P7')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );




		$head_hotel_row = 7;

		$head_cntr_column = "A";


		$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_cntr_column++.$head_hotel_row, 'S.No.')
->setCellValue($head_cntr_column++.$head_hotel_row, 'Agent')
->setCellValue($head_cntr_column++.$head_hotel_row, '')
->setCellValue($head_cntr_column++.$head_hotel_row, 'Apr -'.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'May- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Jun- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Jul- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Aug- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Sep- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Oct- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Nov- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Dec- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Jan- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Feb- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row, 'Mar- '.date('y',strtotime(selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'"))))
->setCellValue($head_cntr_column++.$head_hotel_row++, 'Total');

		
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(10);  
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(45); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20); 
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('I')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('J')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('K')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('L')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('M')->setWidth(8);
		$objPHPExcel->getActiveSheet(1)->getColumnDimension('N')->setWidth(8);

		$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
									    );
		$objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->applyFromArray(
								        array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
									    );

		
		$BudgetType	=" AND a.type=".$type." ";
		$start_date	=	selectColumn(TBL_BUDGET_YEAR,'start_date'," WHERE `id` = '".$seasonId."'");		

 $end_date	=selectColumn(TBL_BUDGET_YEAR,'end_date'," WHERE `id` = '".$seasonId."'");
		// $sql ="SELECT * FROM `".TBL_BUDGET_MASTER."` as a  
		//  where  a.`id_shop` = '".addslashes($_SESSION['shop'])."' $BudgetType and  a.`month`='".$start_date."' and a.`id_user`='".$id_user."'";
		//die; // $id_user,$seasonId
		  
		  $sql=  "select  
budget.name,
budget.id_hotel,

sum(budget.Apr) as Apr,sum(budget.May) as May,sum(budget.Jun) as Jun,
sum(budget.Jul) as Jul,sum(budget.Aug) as Aug,sum(budget.Sep) as 'Sep',
sum(budget.Oct) as Oct,sum(budget.Nov) as Nov,sum(budget.Dec) as 'Dec',
sum(budget.Jan) as Jan,sum(budget.Feb) as Feb,sum(budget.Mar) as Mar,
sum(budget.Total) as Total,

sum(budget.Apr_month_value) as Apr_month_value,sum(budget.May_month_value) as May_month_value,sum(budget.Jun_month_value) as Jun_month_value,
sum(budget.Jul_month_value) as Jul_month_value,sum(budget.Aug_month_value) as Aug_month_value,sum(budget.Sep_month_value) as 'Sep_month_value',
sum(budget.Oct_month_value) as Oct_month_value,sum(budget.Nov_month_value) as Nov_month_value,sum(budget.Dec_month_value) as 'Dec_month_value',
sum(budget.Jan_month_value) as Jan_month_value,sum(budget.Feb_month_value) as Feb_month_value,sum(budget.Mar_month_value) as Mar_month_value,
sum(budget.Total_month_value) as Total_month_value

from 
(
    select distinct
ach.id_hotel,com.name,com.id,
case when month(ach.month)=4 then ach.qty end as 'Apr',case when month(ach.month)=5 then ach.qty end as 'May',
case when month(ach.month)=6 then ach.qty end as 'Jun',case when month(ach.month)=7 then ach.qty end as 'Jul',
case when month(ach.month)=8 then ach.qty end as 'Aug',case when month(ach.month)=9 then ach.qty end as 'Sep',
case when month(ach.month)=10 then ach.qty end as 'Oct',case when month(ach.month)=11 then ach.qty end as 'Nov',
case when month(ach.month)=12 then ach.qty end as 'Dec',case when month(ach.month)=1 then ach.qty end as 'Jan',
case when month(ach.month)=2 then ach.qty end as 'Feb',case when month(ach.month)=3 then ach.qty end as 'Mar',
ach.qty as Total,

case when month(ach.month)=4 then ach.month_value end as 'Apr_month_value',
case when month(ach.month)=5 then ach.month_value end as 'May_month_value',
case when month(ach.month)=6 then ach.month_value end as 'Jun_month_value',
case when month(ach.month)=7 then ach.month_value end as 'Jul_month_value',
case when month(ach.month)=8 then ach.month_value end as 'Aug_month_value',
case when month(ach.month)=9 then ach.month_value end as 'Sep_month_value',
case when month(ach.month)=10 then ach.month_value end as 'Oct_month_value',
case when month(ach.month)=11 then ach.month_value end as 'Nov_month_value',
case when month(ach.month)=12 then ach.month_value end as 'Dec_month_value',
case when month(ach.month)=1 then ach.month_value end as 'Jan_month_value',
case when month(ach.month)=2 then ach.month_value end as 'Feb_month_value',
case when month(ach.month)=3 then ach.month_value end as 'Mar_month_value',
ach.month_value as Total_month_value

from 
fs_hotels com
INNER JOIN
fs_budget_master ach
ON ach.id_hotel=com.id

where  ach.id_shop='".addslashes($_SESSION['shop'])."' AND ach.type=".$type." and ach.seasonId='".$seasonId."'   and ach.id_user='".$id_user."' 
) as budget
group by budget.id,
budget.id_hotel


order by budget.name";

		$res = mysqli_query($connNew,$sql);
		$sno = 1; 
		while($row = mysqli_fetch_object($res)){
			$head_cntr_column = "A";
			
			$date = date('d-M-Y',strtotime($row->dated));
			$company = selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->id_hotel.'" ');
			$idCompanyArea = selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$row->id_company.'" ');
			//$companyLocation = selectColumn(TBL_AREAS,'name','WHERE id="'.$idCompanyArea.'" ');
			$city =selectColumn(TBL_HOTELS,'city','WHERE id="'.$row->id_hotel.'" ');
			$title = selectColumn(TBL_CUSTOMER,'title','WHERE id_customer="'.$row->id_contacts.'" ');
			$firstName = selectColumn(TBL_CUSTOMER,'first_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$lastName = selectColumn(TBL_CUSTOMER,'last_name','WHERE id_customer="'.$row->id_contacts.'" ');
			$id_designation = selectColumn(TBL_CUSTOMER,'designation','WHERE id_customer="'.$row->id_contacts.'" ');
			$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');
			$companyEmail = selectColumn(TBL_COMPANY,'email','WHERE id_company="'.$row->id_company.'" '); 
			$email = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contacts.'" ');
			$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$row->id_contacts.'" ');
			$idGroup = selectColumn(TBL_COMPANY,'id_default_group','WHERE id_company="'.$row->id_company.'" ');
			$companyGroup = selectColumn(TBL_GROUP,'name','WHERE id_group="'.$idGroup.'" ');
			$description = $row->discussion_summary;
			$details = selectColumn(TBL_COMPANY,'details','WHERE id_company="'.$row->id_company.'" ');
			$exeName = selectColumn(TBL_USERS,'name','WHERE id="'.$row->id_user.'" ');

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($head_cntr_column++.$head_hotel_row, $sno++)
						->setCellValue($head_cntr_column++.$head_hotel_row, $company.'-'.$city)
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Room Nights')
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Apr)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->May)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jun)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jul)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Aug)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Sep)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Oct)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Nov)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Dec)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jan)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Feb)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Mar)
						->setCellValue($head_cntr_column++.$head_hotel_row++, $row->Total);
						 
						 $head_cntr_column = "A";
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($head_cntr_column++.$head_hotel_row, '')
						->setCellValue($head_cntr_column++.$head_hotel_row, '')
						->setCellValue($head_cntr_column++.$head_hotel_row, 'Budget Value (lacs)')
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Apr_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->May_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jun_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jul_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Aug_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Sep_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Oct_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Nov_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Dec_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Jan_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Feb_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row, $row->Mar_month_value)
						->setCellValue($head_cntr_column++.$head_hotel_row++, $row->Total_month_value);

		}
		
		
		$objPHPExcel->getActiveSheet()->getStyle('A6:P'.($head_hotel_row-1))->applyFromArray($styleThinBlackBorderOutline);

if($type=='1'){
$file	='Hotel Wise Monthly Budget';
}else{
$file	='Hotel Wise Monthly Achieved';
}

		$objPHPExcel->getActiveSheet()->setTitle($file);
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
	    header('Content-Disposition: attachment;filename="'.$file.'_'.$exeName.date('d-m-Y H:i:s').'.xls"');
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

	}				
?>