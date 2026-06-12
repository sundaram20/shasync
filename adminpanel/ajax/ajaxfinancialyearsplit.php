<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$financial_year = $_POST['financialyear'];


$financial_year=explode('-',$financial_year);
$FinanceStarYear=$financial_year[0];
$FinanceEndYear=$financial_year[1];
// print_r($financial_year);

// get month name from number
function month_name($month_number){
	return date('F', mktime(0, 0, 0, $month_number, 10));
}


// get get last date of given month (of year)
function month_end_date($year, $month_number){
	return date("t", strtotime("$year-$month_number-0"));
}

// return two digit month or day, e.g. 04 - April
function zero_pad($number){
	if($number < 10)
		return "0$number";
	
	return "$number";
}

// Return quarters between tow dates. Array of objects
//Calculation Q1,q2,q3,q4===========================================================
function get_quarters($start_date, $end_date){
	
	$quarters = array();
	
	$start_month = date( 'm', strtotime($start_date) );
	$start_year = date( 'Y', strtotime($start_date) );
	
	$end_month = date( 'm', strtotime($end_date) );
	$end_year = date( 'Y', strtotime($end_date) );
	
	$start_quarter = ceil($start_month/3);
	$end_quarter = ceil($end_month/3);

	$quarter = $start_quarter; // variable to track current quarter
	
	// Loop over years and quarters to create array
	for( $y = $start_year; $y <= $end_year; $y++ ){
		if($y == $end_year)
			$max_qtr = $end_quarter;
		else
			$max_qtr = 4;
		
		for($q=$quarter; $q<=$max_qtr; $q++){
			
			$current_quarter = new stdClass();
			
			$end_month_num = zero_pad($q * 3);
			$start_month_num = ($end_month_num - 2);

			$q_start_month = month_name($start_month_num);
			$q_end_month = month_name($end_month_num);
			
			$current_quarter->period = "Qtr $q ($q_start_month - $q_end_month) $y";
			$current_quarter->period_start = "$y-$start_month_num-01";      // yyyy-mm-dd    
			$current_quarter->period_end = "$y-$end_month_num-" . month_end_date($y, $end_month_num);
			
			$quarters[] = $current_quarter;
			unset($current_quarter);
		}

		$quarter = 1; // reset to 1 for next year
	}
	
	return $quarters;
	
}

$quarters = get_quarters(date($FinanceStarYear.'-04-01'), date($FinanceEndYear.'-03-31'));
//Calculation Q1,q2,q3,q4===========================================================

//===========================Booking CompleteChar horizontalBar===============================================================
if (date('m') <= 6) {//Upto June 2014-2015
    $financial_yearchcek = (date('Y')-1) . '-' . date('Y');
} else {//After June 2015-2016
    $financial_yearcheck = date('Y') . '-' . (date('Y') + 1);
}

if($financial_yearcheck==$financial_year){
 $twelveMonthsAgo = date('Y-m-d');//date("Y-m-d", strtotime("-12 months"));
}else{
 $twelveMonthsAgo = date("Y-m-d", strtotime("-12 months"));    
    
}

//Also resulted in 2018-08-22.
//var_dump($twelveMonthsAgo);

$setFormat = date_create( $twelveMonthsAgo);
$current_date = $setFormat->format('Y-m-d');
$last_year_current_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));

//MTD
$from = date_create($current_date);
$from_month_to_date = date_create($from->format('Y-m-01'));
$from_month_to_date = $from_month_to_date->format('Y-m-d');
$to_month_to_date = $current_date;
$last_year_to_month_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_month_date);

$last_year_from_month_date = date_create($from->format('Y-m-01'));
$last_year_from_month_date = $last_year_from_month_date->format('Y-m-d');

//YTD
$to_year_to_date = $current_date;
$from = date_create($current_date);

if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
	$from_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($from_year_to_date)));
}
else{
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
}

$last_year_to_year_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_year_date);
$last_year_from_year_date = date_create($from->format('Y-04-01'));
if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
    $last_year_from_year_date = date('Y-m-d',strtotime('-1 year',strtotime($last_year_from_year_date)));
  }
  else{
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
  }
$current_quarter = ceil(date('n') / 3);
$QuarterThisYearstart_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
$QuarterThisYearlast_date = date('Y-m-t', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));


//===========================Booking CompleteChar horizontalBar===============================================================

$Values = array();
	$Values['Q1_APR_JUNE']  = date("d-m-Y",strtotime($quarters[0]->period_start)).' to '.date("30-06-Y",strtotime($quarters[0]->period_end));
	$Values['Q2_JULY_SEP']  = date("d-m-Y",strtotime($quarters[1]->period_start)).' to '.date("30-09-Y",strtotime($quarters[1]->period_end));
	$Values['Q3_OCT_DEC']   = date("d-m-Y",strtotime($quarters[2]->period_start)).' to '.date("31-12-Y",strtotime($quarters[2]->period_end));
	$Values['Q4_JAN_MARCH'] = date("d-m-Y",strtotime($quarters[3]->period_start)).' to '.date("31-03-Y",strtotime($quarters[3]->period_end));
	
	$Values['H1_APR_SEP']   = date('01-04-'.$FinanceStarYear).' to '.date("30-09-".$FinanceStarYear);
	$Values['H2_OCT_MARCH'] = date('01-10-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);
	
	$Values['MTD'] = date($from_month_to_date).' to '.date($to_month_to_date);//date('01-04-'.$FinanceStarYear).' to '.date("30-09-".$FinanceStarYear);
	$Values['QTD'] = date($QuarterThisYearstart_date).' to '.date($to_year_to_date);//date('01-10-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);
	$Values['YTD'] = date($from_year_to_date).' to '.date($to_year_to_date);//date('01-04-'.$FinanceStarYear).' to '.date("30-09-".$FinanceStarYear);
	
	
	
	
	$Values['per_report_date'] =date('01-04-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);

	 
/*echo '<pre>';
print_r($Values);
echo '</pre>';              
	
echo '<pre>';
print_r($quarters);
echo '</pre>';*/
echo json_encode($Values);
?>