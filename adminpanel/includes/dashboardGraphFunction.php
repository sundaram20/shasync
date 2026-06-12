<?php

	/*
	# MTD YTD Graph
	# Return Array 
	*/
	function performanceGraphData($dateFy,$id_team=0 ,$teamMembers='', $id_shop=0){
		global $connNew;
		$from = date('Y-m-d',strtotime($dateFy));
		//print_r($_SESSION);

		$mtdLastValues = array();
		$mtdThisValues = array();

		$mtdVisits = array();
		$mtdRateLetters = array();
		$mtdTotalExpense = array();

		$budgetValues = array();


		$ytdLastValues = array();
		$ytdThisValues = array();

		$ytdVisits = array();
		$ytdRateLetters = array();
		$ytdTotalExpense = array();

		$exeNameArr = array();
		$returnData = array();

		$stackedArr = array();
		$stackedDataSet = array();

		$days=0;
		$weekends=0;

		$totalDaysGoneMtd=0;
		$totalDaysGoneYtd=0;
		$cond='';
		


		  $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE FIND_IN_SET('".$id_team."',ids_team) AND id IN (".$teamMembers.") ".$cond." order by name";

		//$sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." where id_shop=6 ".$cond." order by name";

		$resExe = mysqli_query($connNew,$sqlExe);

		while($rowExe = mysqli_fetch_object($resExe)){

			$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
				LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowExe->id.'" ');

			
			
			if($assignedCompany >0){


				if($rowExe->user_type!=2){
					$rateTable = TBL_RATE;
					$budgetTable = TBL_AGENT_BUDGET;
					$achievedTable = TBL_AGENT_ACHIEVED;
				}
				else{
					$rateTable = TBL_RATE_UNIT;
					$budgetTable = TBL_UNIT_AGENT_BUDGET;
					$achievedTable = TBL_UNIT_AGENT_ACHIEVED;
				}

				$achieved =selectColumn($achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime($from))."'  and id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

				$prevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

				$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

				$totalExpenseMtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');
				

				

				$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

				
				if(date('m',strtotime($from))<=3){

					$reportPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($from));

					$datePeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));

					$budget = selectColumn($budgetTable,'sum(room_nights)'," WHERE `id_user` = '".$rowExe->id."' AND `from`='".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' AND `to`='".date('Y-03-31',strtotime($from))."'   ");

					$ytdPrevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-2 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-2 years',strtotime($from)))."'  and id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

					$ytdAchieved =selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

					$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

					$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

					$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

					

				}
				else{

					$reportPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($from));

					$datePeriod = date('01-04-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($from));

					$budget = selectColumn($budgetTable,'sum(room_nights)'," WHERE `id_user` = '".$rowExe->id."' AND `from`='".date('Y-04-01',strtotime($from))."' AND `to`='".date('Y-03-31',strtotime('+1 years',strtotime($from)))."'   ");

					$ytdPrevYear = selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

					$ytdAchieved =selectColumn($achievedTable,'sum(qty)'," WHERE month between '".date('Y-04-01',strtotime($from))."'  and '".date('Y-m-01',strtotime($from))."' AND id_shop='".$id_shop."' and id_user='".$rowExe->id."'  ");

					$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

					$totalExpenseYtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');

					$rateLetterYtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');
				}

				

				 $stackedDataSet['label']=$rowExe->name;
				
				// $stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
				//$stackedDataSet['borderColor']='rgba('.rand(0,255).', '.rand(0,255).', '.rand(0,255).',1)';
				$stackedDataSet['data'][0]=($budget==''?0:$budget);

				// array_push($stackedArr,$stackedDataSet);


				array_push($exeNameArr,ucwords(strtolower(substr($rowExe->name, 0, strrpos($rowExe->name, ' ')))));
				array_push($mtdLastValues, ($prevYear==''?0:$prevYear));
				array_push($mtdThisValues, ($achieved==''?0:$achieved));

				array_push($budgetValues, ($budget==''?0:$budget));
				

				array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
				array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

				array_push($mtdVisits,$visitMtd);
				array_push($mtdRateLetters,$rateLetterMtd);

				array_push($ytdVisits,$visitYtd);
				array_push($ytdRateLetters,$rateLetterYtd);

				array_push($mtdTotalExpense, $totalExpenseMtd);
				array_push($ytdTotalExpense, $totalExpenseYtd);
					
			}	
		}

		/***** Total Gone Days Calculatiing Days ****/
		$days=1;
		$weekends=1;

		$totalDaysGoneMtd=1;
		$totalDaysGoneYtd=1;

		//YTD
		if(date('m',strtotime($from))<=3){
			$startDate = date('Y-04-01',strtotime('-1 years',strtotime($from)));
			$lastDate = date('Y-m-d',strtotime($from));
		}
		else{
			$startDate =date('Y-04-01',strtotime($from));
			$lastDate = date('Y-m-d',strtotime($from));
		}

		while($startDate <= $lastDate){

			$day = date("N",strtotime($startDate));
			if($day == 6 || $day == 7) {
			  $weekends++;
			} 

			$days++;
			$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
		}
		$totalDaysGoneYtd = $days-$weekends;
		$startDate=date('Y-m-01',strtotime($from));

		$days=1;
		$weekends=1;
		// MTD
		while($startDate <= $from){

			$day = date("N",strtotime($startDate));
			if($day == 6 || $day == 7) {
			  $weekends++;
			} 

			$days++;
			$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
		}
		$totalDaysGoneMtd = $days-$weekends;

		/**************** END ***********************/ 

		$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;
		$returnData['totalDaysGoneYtd']=$totalDaysGoneYtd;

		$returnData['stacked']=$stackedArr;
		$returnData['mtdThisVal']=$mtdThisValues;
		$returnData['mtdLastVal']=$mtdLastValues;

		$returnData['budgetVal']=$budgetValues;

		$returnData['executives']=$exeNameArr;

		$returnData['ytdLastVal']=$ytdLastValues;
		$returnData['ytdThisVal']=$ytdThisValues;

		$returnData['mtdVisits']=$mtdVisits;
		$returnData['mtdRateLetters']=$mtdRateLetters;
		$returnData['mtdTotalExpense']=$mtdTotalExpense;

		$returnData['ytdVisits']=$ytdVisits;
		$returnData['ytdRateLetters']=$ytdRateLetters;
		$returnData['ytdTotalExpense']=$ytdTotalExpense;
		$returnData['reportPeriod']=$reportPeriod;
		$returnData['datePeriod']=$datePeriod;
		
		return $returnData;
	}



	/*
	# Lead Generated And Received Graph
	# Return Array 
	*/
	function leadGraphData($dateFy, $id_team=0, $teamMembers='', $id_shop=0, $unitUser=false ,$hotelAccess=''){
		global $connNew;

		$period = $dateFy;
		$from = '';
		$to='';
		if(date('m',strtotime($period))<=3){
			$from = date('Y-04-01',strtotime('-1 years',strtotime($period)));
			$to = date('Y-m-d',strtotime($period));
		}
		else{
			$from = date('Y-04-01',strtotime($period));
			$to = date('Y-m-d',strtotime($period));
		}

		$returnData = array();
		$membersArr = array();
		$reasonsArr = array();
		$reasonsValArr = array();
		$reasonValRec =  array();
		$revenueGen = 0;
		$bgColor = array();
		$enquiryIdArr = array();
		$enquiryIdRecArr = array();
		$cond='';


		$sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE FIND_IN_SET('".$id_team."',ids_team) AND id IN (".$teamMembers.") ".$cond." order by name";

		$resExe = mysqli_query($connNew,$sqlExe);

		while($rowMembers = mysqli_fetch_object($resExe)){
			
			$assignedCompany = selectColumn(TBL_COMPANY,'COUNT('.TBL_COMPANY.'.id_company)','LEFT JOIN  '.TBL_AREAS.' ON '.TBL_COMPANY.'.area='.TBL_AREAS.'.id
				LEFT JOIN '.TBL_USERS.' ON '.TBL_AREAS.'.user_id='.TBL_USERS.'.id WHERE '.TBL_USERS.'.id="'.$rowMembers->id.'" ');
			
			if($assignedCompany>0)
				array_push($membersArr,$rowMembers->id);
		}


		$sqlMain = "SELECT  DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A WHERE A.`id_shop` = '".$id_shop."' AND A.id_user IN (".implode(',',$membersArr).") AND A.created_date BETWEEN '".$from."' AND '".$to."' ";
		$resMain = mysqli_query($connNew,$sqlMain);

		while($rowMain = mysqli_fetch_object($resMain)){
			array_push($enquiryIdArr,$rowMain->id);
		}


		$totalLeadsGen = mysqli_num_rows($resMain);

		if($unitUser){
			$unitCond = " AND A.hotel_id IN (".$hotelAccess.") ";
		}
		else{
			$unitCond = "AND A.assign_user_id IN (".implode(',',$membersArr).")";
		}

		$sqlMainRec = "SELECT  DISTINCT A.* FROM `".TBL_DAILY_ENQUERY."` A WHERE A.`id_shop` = '".$id_shop."' ".$unitCond." AND A.created_date BETWEEN '".$from."' AND '".$to."' ";

		$resMainRec = mysqli_query($connNew,$sqlMainRec);

		while($rowMainRec = mysqli_fetch_object($resMainRec)){
			array_push($enquiryIdRecArr,$rowMainRec->id);
		}

		$totalLeadsRec = mysqli_num_rows($resMainRec);


		$sqlOpen = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE FIND_IN_SET(enquiry_id,'".implode(',',$enquiryIdArr)."') AND lead_status=1 GROUP BY enquiry_id";

		$totalOpenLeads = 	mysqli_num_rows(mysqli_query($connNew,$sqlOpen));

		$sqlOpenRec = "SELECT * FROM ".TBL_DAILY_ENQUERY_DETAILS." WHERE FIND_IN_SET(enquiry_id,'".implode(',',$enquiryIdRecArr)."') AND lead_status=1  GROUP BY enquiry_id";

		$totalOpenRecLeads = mysqli_num_rows(mysqli_query($connNew,$sqlOpenRec));

		$revenueGen = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'sum(revenue)','WHERE FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdArr).'")  ');

		$revenueRec = selectColumn(TBL_DAILY_ENQUERY_DETAILS,'sum(revenue)','WHERE FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdRecArr).'")  ');

		$sqlReasons = "SELECT id,name FROM ".TBL_CLOSING_MASTER." WHERE id_shop='".$id_shop."' ";

		$resReasons = mysqli_query($connNew, $sqlReasons);

		array_push($reasonsArr,'Open');
		array_push($reasonsValArr,($totalOpenLeads==''?0:$totalOpenLeads));
		array_push($reasonValRec,($totalOpenRecLeads==''?0:$totalOpenRecLeads));
		while($rowReasons = mysqli_fetch_object($resReasons)){

			$sqlOpen = 'SELECT * FROM '.TBL_DAILY_ENQUERY_DETAILS.' where FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdArr).'")   AND followup_close_type_id="'.$rowReasons->id.'" GROUP BY enquiry_id';

			$val = mysqli_num_rows(mysqli_query($connNew,$sqlOpen));

			$sqlOpenRec = 'SELECT * FROM '.TBL_DAILY_ENQUERY_DETAILS.' where FIND_IN_SET(enquiry_id,"'.implode(',',$enquiryIdRecArr).'")   AND followup_close_type_id="'.$rowReasons->id.'" GROUP BY enquiry_id';
			$valRec =  mysqli_num_rows(mysqli_query($connNew,$sqlOpenRec));

			array_push($reasonsArr, $rowReasons->name);
			array_push($reasonsValArr,($val==''?0:$val));
			array_push($reasonValRec,($valRec==''?0:$valRec));

		}
		// array_push($bgColor,'rgb(255, 205, 86)');
		// array_push($bgColor,'rgb(255, 99, 132)');
		// array_push($bgColor,'rgb(54, 162, 235)');
		// array_push($bgColor,'rgb(64, 192, 235)');
		// array_push($bgColor,'rgb(74, 182, 245)');
		// array_push($bgColor,'rgb(84, 172, 255)');
		// array_push($bgColor,'rgb(94, 152, 265)');
		// array_push($bgColor,'rgb(34, 142, 275)');
		// array_push($bgColor,'rgb(24, 132, 285)');
		// array_push($bgColor,'rgb(14, 122, 295)');


		$returnData['totalLeadsGen'] = $totalLeadsGen;
		$returnData['totalOpenLeads'] = ($totalOpenLeads==''?0:$totalOpenLeads);
		$returnData['reasons'] = $reasonsArr;
		$returnData['reasonval'] = $reasonsValArr;
		// $returnData['bgColor'] = $bgColor;
		$returnData['revenueGen'] = ($revenueGen==''?0:$revenueGen);
		$returnData['revenueRec'] = ($revenueRec==''?0:$revenueRec);
		$returnData['totalLeadsRec']=$totalLeadsRec;
		$returnData['totalOpenRecLeads']=$totalOpenRecLeads;
		$returnData['reasonValRec']=$reasonValRec;
		$returnData['exeIdArr']=$membersArr;
		
		return $returnData;


	}

	/*
	# Generates BAR GRAPH
	# return png file of generated graph
	*/

	function mtdYtdGraph($fileName='', $graphTitle='', $dataSets=array(), $legendTitle=array()){
		
		global 	$attachPath;
		$graph = new PHPGraphLib(480,300,$attachPath.$fileName.'.png');
		
		$graph->addData($dataSets[0], $dataSets[1]);
		$graph->setBarColor('yellow','blue');
		$graph->setTitle($graphTitle);
		$graph->setupYAxis(12, 'blue');
		$graph->setupXAxis(20);
		$graph->setGrid(false);
		$graph->setLegend(true);
		$graph->setTitleLocation('center');
		$graph->setTitleColor('black');
		$graph->setLegendOutlineColor('white');
		$graph->setLegendTitle(implode(',',$legendTitle));
		$graph->setXValuesHorizontal(true);
		$graph->createGraph();

	}

	/*
	# Generates PIE GRAPH
	# return png file of generated graph
	*/

	function leadGraph ($fileName='', $graphTitle='', $dataSet=array()){
		global $attachPath;

		$graph = new PHPGraphLibPie(480, 300, $attachPath.$fileName.'.png');

		$graph->addData($dataSet);
		$graph->setTitle($graphTitle);
		$graph->setLabelTextColor('50, 50, 50');
		$graph->setLegendTextColor('50, 50, 50');
		$graph->createGraph();
	}

	/*
	# Generates pdf file from content
	# return pdf file
	*/

	function pdfGenerator($content='', $fileName=''){
		global $attachPath;
		$dompdf = new DOMPDF();
		$dompdf->set_paper('landscape', 'landscape');
		$dompdf->load_html($content);
		$dompdf->render();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
		$gen = $dompdf->output();
		$dompdf->stream($fileName.'.pdf', array("Attachment" => true));
		file_put_contents($attachPath.$fileName.'.pdf', $gen);
	}	
?>