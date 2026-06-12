<?php include_once("../config/auto_loader.php"); 
//checkUserLevelPermission($_SESSION['userLevel'],'fs_dashboard','view');
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php");
  include_once("deviceType.php");?>
  
  <style>
  .mystyle {
 color: #fff;
background-color: #f71752;
border-color: rgba(0,0,0,0.2);
}
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
 
 .chart-height1{
     height:200px;
 }
 
 @media screen and (max-width:480px) {
     .mobileSummary-today{
       width:50%;  
     }
	.mobile-today{
		width:25%;}
	 .mobile-responseset{
		 margin-top:10px;
		 width:45%;}
	  .mobile-customrange{
		 margin-top:10px;
		 width:45%;}	 
 }
 .chart-height1{
    /* height:550px !important;*/
 }
 @media screen and (max-width:320px) {
	 .mobile-today{
		width:25%;}
	 	.mobile-responseset{
		 margin-top:10px;
		 width:45%;}
	  .mobile-customrange{
		 margin-top:10px;
		 width:45%;}
		 }
		 .chart-height1{
     /*height:550px !important;*/
 }
 .category, .item, .chzn-container-single .chzn-single {
    font-family: sans-serif !important;}

.category {font-weight: bold !important;}

.chzn-results li.item {padding-left: 25px !important;}
  </style>
  <div class="content-wrapper">
    <section class="content-header">
      <h4>Dashboard</h4>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
      <div class="col-md-12">
          
       <script>
           function generateLabels() {
  var chartLabels = [];
  for (x = 0; x < 100; x++) {
    chartLabels.push("Label" + x);
  }
  return chartLabels;
}

function generateData() {
  var chartData = [];
  for (x = 0; x < 100; x++) {
    chartData.push(Math.floor((Math.random() * 100) + 1));
  }
  return chartData;
}

function addData(numData, chart){
		for (var i = 0; i < numData; i++){
    		chart.data.datasets[0].data.push(Math.random() * 100);
    		chart.data.labels.push("Label" + i);
    		var newwidth = $('.chartAreaWrapper2').width() +60;
    		$('.chartAreaWrapper2').width(newwidth);
    }
}

var chartData = {
  labels: generateLabels(),
  datasets: [{
    label: "Test Data Set",
    data: generateData()
  }]
};

$(function() {
  var canvasFuelSpend = $('#chart-FuelSpend');
  var chartFuelSpend = new Chart(canvasFuelSpend, {
    type: 'bar',
    data: chartData,
    maintainAspectRatio: false,
    responsive: true,
    options: {
      tooltips: {
        titleFontSize: 0,
        titleMarginBottom: 0,
        bodyFontSize: 12
      },
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          ticks: {
            fontSize: 12,
            display: false
          }
        }],
        yAxes: [{
          ticks: {
            fontSize: 12,
            beginAtZero: true
          }
        }]
      },
      animation: {
        onComplete: function() {
          var sourceCanvas = chartFuelSpend.chart.canvas;
          var copyWidth = chartFuelSpend.scales['y-axis-0'].width - 10;
          var copyHeight = chartFuelSpend.scales['y-axis-0'].height + chartFuelSpend.scales['y-axis-0'].top + 10;
          var targetCtx = document.getElementById("axis-FuelSpend").getContext("2d");
          targetCtx.canvas.width = copyWidth;
          targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
        }
      }
    }
  });
  addData(5, chartFuelSpend);
});

       </script>   
        
          
          
          
          
          
          
          
          
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php 
				
$date=date_create(date('Y-m-d'));

date_format($date,"m");
if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
    $financial_year = (date_format($date,"Y")) . 'To' . (date_format($date,"Y")+1);
} else {//On or Before March (FY is previous year - current year)
    $financial_year = (date_format($date,"Y")-1) . 'To' . date_format($date,"Y");
}
$financial_year=explode('To',$financial_year);
$FinanceStarYear=$financial_year[0];
$FinanceEndYear=$financial_year[1];
$Current_financial_year=$FinanceStarYear."-".$FinanceEndYear;

$FinanceStarLastYear=$financial_year[0]-1;
$FinanceEndLastYear=$financial_year[1]-1;
$Last_financial_year=$FinanceStarLastYear."-".$FinanceEndLastYear;


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


$previousmonthStart= date("Y-n-j", strtotime("first day of previous month"));
$previousmonthEnd = date("Y-n-j", strtotime("last day of previous month"));

$LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
$crs_sales_both_active  = selectColumn(TBL_SHOP,'crs_sales_both_active'," WHERE id= '".addslashes($_SESSION['shop'])."'");


//===========================Booking CompleteChar horizontalBar===============================================================
$setFormat = date_create( date('Y-m-d'));
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
?>
<script>
    $(".chzn-select").chosen({
    create_option: true,
    persistent_create_option: true,
    create_option_text: 'add',
});
</script>

 <div class="form-group col-md-3">
               <div style="width:100%;"><label>&nbsp;</label>
             <!--   <label>
          <input type="radio" id="reportType1"   name="reportType" value="1"  onclick="updatereportType(this.value);ReportTypePickupBob();"/>
          Pickup Based </label> 
               <label style="width:110px;">
          <input type="radio" id="reportType2"   name="reportType" value="2" checked="checked"  onclick="updatereportType(this.value);ReportTypePickupBob();" />
          BOB Based </label>-->
          </div>
         
               
                 <button type="button" style="margin-right: 5px;" class="btn btn-foursquare col-md-3" id="rdb21"  title="Chart View"   name="CharSummarytoggler" value="SalesSummary" >
                <i class="fa fa-bar-chart"></i>&nbsp;
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3" id="rdb22"   title="Table View"   name="CharSummarytoggler" value="CustomRangeBookingPeriod2" >
                <i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3" id="rdb23" title="Compare View"    name="CharSummarytoggler" value="CompareRangeBookingPeriod2" >
                <i class="fa fa-exchange"></i>&nbsp; 
                </button>
                
            
          
          
            </div>

<?php
//if($crs_sales_both_active==1){ ?>
      
    

    <div class="form-group col-sm-4 row-fluid">
                
                
              <label>Group</label></br>
              <?php 
            				 $sql_team = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' and id_shop='".$_SESSION['shop']."' ORDER BY display_order";
            				
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <select  class="selectpicker " name="id_group_master" id="id_group_master" Onchange="updateDateQuickSearchHotel();" data-size="7" data-show-subtext="true" data-live-search="true" style="    border: 1px solid #d2d6de;
    border-radius: 0;
    padding: 6px 12px;
    height: 34px;">
                <option class='item' value="0">All Without Unit</option>
                <option class='item' value="10000">All With Unit</option>
                <?php while($objHot=mysqli_fetch_object($res_team)){
						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						$optionlist .= "<option class='category' ".$selected." value='".$objHot->id."_0'>".$objHot->name."</option>";
						
						 $sql_team_group = "SELECT id,name FROM ".TBL_TEAM." WHERE status=1 and id_shop='".$_SESSION['shop']."' and id_group='".$objHot->id."'  ";
            			$res_teamgroup = mysqli_query($connNew,$sql_team_group);
						
        						 while($objHotgroup=mysqli_fetch_object($res_teamgroup)){
        						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
        							$selected="selected";
        						}
        						else{
        							$selected="";
        						}
        						$optionlist .= "<option class='item'  ".$selected." value='".$objHot->id."_".$objHotgroup->id."'>".$objHotgroup->name."</option>";
        						
        						}
					} 
					
					echo $optionlist; ?>
              </select>
            </div>
    
    <?php
	/* }else{ ?>

            <div class="form-group col-sm-4">
                
                
              <label>Team</label>
              <?php 
            				echo $sql_team = "SELECT id,name FROM ".TBL_TEAM." WHERE id IN (".$_SESSION['teamId'].") ORDER BY name";die;
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <select class="select2 form-control" name="id_hotel" id="id_hotel" Onchange="updateDateQuickSearchHotel();">
                <option value="0">All Team</option>
                <?php while($objHot=mysqli_fetch_object($res_team)){
						if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						echo "<option ".$selected." value='".$objHot->id."'>".$objHot->name."</option>";
					} ?>
              </select>
            </div>
            
<?php }*/ ?>            
           <div class="form-group col-sm-2">

                  <label for="seasonId">Financial Year</label>
<select class="form-control select2" name="financialyearselected" id="financialyearselected" 
                   onchange="getfinancialyear(this.value,'<?php echo $Current_financial_year;?>');getCompareYearTwo(this.value,'<?php echo $Current_financial_year;?>');" >
                  <?php 
                  
                  //$seasonDropDown = '								  ';

											  $resCat = selectSql(TBL_BUDGET_YEAR," where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name` desc');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->name == $Current_financial_year){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}	
                                                    $SelectYear =   explode('-',$resultCat->name);
													$seasonDropDown .= '<option '.$selected.' value="'.$SelectYear[0].'-20'.$SelectYear[1].'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown .= '</select>';

											  ?>

                  <span id="seasonError"><?php echo $err_season;?></span> </div>
                  
                  
                   <div class="form-group col-sm-2">

                  <label for="seasonId">Compare Year</label>

                  <?php $seasonDropDown = '<select class="form-control select2" name="CompareYearselected" id="CompareYearselected"  onchange="updateDateQuickSearchCompare(this.value);">';

											  $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name` desc');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->name == $Last_financial_year){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}	

													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->name.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown .= '</select>';

											  ?>

                  <span id="seasonError"><?php echo $err_season;?></span> </div>
                  
                  
                  
                  <div class="form-group col-md-10">
               
               
          
         
          
          
          
              
              <div class="box-bodyw">
                  
                 
               <!--  <button type="button" style="margin-right: 5px;" class="btn bg-default mobile-today" id="dateColor1" type="radio"   name="toggler" value="1" onclick="updateDateQuickSearch('<?php echo date("d-m-Y").' to '.date("d-m-Y");?>','0');">
                Today
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default" id="dateColor2" type="radio"   name="toggler" value="2" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-1 days')).' to '.date('d-m-Y', strtotime('-1 days'));?>','0');">
                Yesterday
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default" id="dateColor3" type="radio"   name="toggler" value="3" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-6 days')).' to '.date("d-m-Y");?>','0');">
                Last 7 Days
                </button>-->
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor4" type="radio"   name="toggler" value="4" onclick="updateDateQuickSearch('<?php echo date("01-m-Y").' to '.date("d-m-Y",strtotime($LastDateCurrentmonth));?>','0');">
                This Month
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor5" type="radio"   name="toggler" value="5" onclick="updateDateQuickSearch('<?php echo date('d-m-Y', strtotime($previousmonthStart)).' to '.date('d-m-Y', strtotime($previousmonthEnd));?>','0');">
                Last Month
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-foursquare  mobile-responseset" id="dateColorFinancialYear" type="radio"   name="toggler" value="FinancialYear" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("01-04-".$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);?>','1');">
                This Year
                </button>
                
              <!-- <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor12" type="radio"   name="toggler" value="12" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("d-m-Y",strtotime($from_month_to_date)).' to '.date("d-m-Y",strtotime($to_month_to_date));?>','0');">
                MTD
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor13" type="radio"   name="toggler" value="13" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("d-m-Y",strtotime($QuarterThisYearstart_date)).' to '.date("d-m-Y",strtotime($to_year_to_date));?>','0');"><?php // $QuarterThisYearlast_date;?>
                QTD
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor14" type="radio"   name="toggler" value="14" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("d-m-Y",strtotime($from_year_to_date)).' to '.date("d-m-Y",strtotime($to_year_to_date));?>','0');">
                YTD
                </button>-->
                
               <button type="button" class="btn btn-default mobile-customrange"  id="dateColorCustomRangeBookingPeriod" type="radio"   name="toggler" data-target="#modal-success"  value="CustomRangeBookingPeriod">
                Custom Range
                </button>
                
              </div>
              
             
          
          
            </div>
             
                  
                  
            <div class="form-group col-md-9">
               
            
               
              <div class="box-bodyw">
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor6" type="radio"   name="toggler" value="6" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                Q1 (APR-JUNE)
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor7" type="radio"   name="toggler" value="7" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                Q2 (JULY-SEP)
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor8" type="radio"   name="toggler" value="8" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                Q3 (OCT-DEC)
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor9" type="radio"   name="toggler" value="9" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                Q4 (JAN-MARCH)
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor10" type="radio"   name="toggler" value="10" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                H1 (APR-SEP)
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColor11" type="radio"   name="toggler" value="11" data-target="#modal-warning" onclick="updateDateQuickSearch(this.value,'0');">
                H2 (OCT-MARCH)
                </button>
                
                
              </div>
             
          
          
            </div> 
             <div id="DivshowdownloadPDF" class="toHidedownloadPDF" style="display:none"> 
         
         <div class="form-group col-sm-2" style="float:right;" >
  <div class="box-header with-border">
    <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
      <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
      <ul class="dropdown-menu" role="menu">
       
        <li><a title="PDF file" onclick="saveAsPDF2();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
      </ul>
    </div>
  </div>
  
  
</div>

  </div> 
                  
                  
             
            <div id="blk-CustomRangeBookingPeriod" class="toHide" style="display:none">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="start_date">Custom Range Booking Period </label>
                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Booking Date From -  To" name="per_report_date" id="per_report_date" data-parsley-required value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('01-04-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear); ?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">
                </div>
              </div>
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
                <a  onclick="SearchButtonType();" class="btn btn-info form-control">Search</a> </div>
            </div>
            
            <div class="col-sm-12">
           
             </div>  
                <input type="hidden" name="SelectedreportType" id="SelectedreportType" value="2">
                <input type="hidden" name="SelectedViewType" id="SelectedViewType" value="1">
                <input type="hidden" name="SelectedSummaryViewType" id="SelectedSummaryViewType" value="7">
                <input type="hidden" name="SelectedCompareType" id="SelectedCompareType" value="1">
                <input type="hidden" name="SelectedMonthView" id="SelectedMonthView" value="1">
                <input type="hidden" name="SelectedComparePeriodDate" id="SelectedComparePeriodDate" value="<?php echo date('01-04-'.($FinanceStarYear-1)).' to '.date("31-03-".($FinanceEndYear-1))?>">
                
               <div id="blk-CustomRangeBookingPeriod2" class="toHideCharSummarytoggler" style="display:none" >
                   <div class="form-group col-sm-10" >
                    
                
                <button type="radio" style="margin-right: 5px;" class="btn btn-foursquare margin mobileSummary-today" id="rdb17"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','7');">
                Team Wise 
                </button>
                <!-- <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb16"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','6');">
                Business Source 
                </button>-->
                
                <button type="radio" style="margin-right: 5px;" class="btn bg-default  margin mobileSummary-today" id="rdb11"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','1');">
                Executivewise 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb12"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','2');">
                Hotelwise 
                </button>
                 <!--<button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb15"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','5');">
                Booking Through 
                </button>
               <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb155"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','55');">
                Booked By 
                </button>-->
                 <!--<button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb19"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','9');">
                Pace
                </button>-->
                
                <!--<button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb120"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','20');">
                Funnel & Forecast
                </button>-->
                 
                <!--<button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb18"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','8');">
                Top 100 Agent
                </button>-->
                <?php 
                $Recpath =explode('/',getcwd());
     if (in_array("crs", $Recpath)) {
    $foldername =    "crs";
}

if (in_array("sales", $Recpath)) {
    $foldername =    "sales";
}

                
                if($foldername=='crs'){
                $salesActiviteDispalay  ="display:none";
                ?>
                 
                
               <?php } ?>
               <button type="radio" style="margin-right: 5px;<?php echo $salesActiviteDispalay; ?>" class="btn bg-foursquare margin mobileSummary-today" id="rdb14"    name="SummaryReportRadio" value="SalesSummary" onclick="SalesSummaryDateQuickForSearch('0','4');">
                Sales Activity 
                </button>
             
              
              
              
       </div>
       <div class="form-group col-sm-2" style="float:right;" >
           
           <div class="box-header with-border">
		                    <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<li><a title="Export to excel file" onclick="downloadSummaryPdf(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
								<li><a title="Export to csv file" onclick="downloadSummaryPdf(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>							  
							  </ul>
							</div>
          
        </div>
           
           
                    
                   <!--  <a  onclick="downloadSummaryPdf();" class="btn btn-warning form-control">Download</a> -->
            </div>
    </div>  
          <div id="blk-CompareRangeBookingPeriod2" class="toHideCompareSummarytoggler" style="display:none" >
                    <div class="box-body table-responsive col-sm-10"> 
                     <div class="box-bodyw">
                <button type="radio" style="margin-right: 5px;" class="btn btn-foursquare margin mobileSummary-today" id="compare1"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','1');">
                Team Wise 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default  margin mobileSummary-today" id="compare2"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','2');">
                Executivewise 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare3"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','3');">
                Hotelwise 
                </button>
                 <!--<button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare5"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','5');">
                MTD | YTD
                </button>-->
                <!-- <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare62"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','62');">
               Hotelwise Summary
                </button>-->
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare4"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','4');">
                Top 100 Agent
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare61"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','61');">
                Drop-Out
                </button>
                
                
                
                
                 
              </div>
             
              
       </div> 
      
      
      
	 
       
        <!-- /.box-header -->
			
     
 
      
          <div  style="display:none" class="toHideloadingDownload" id="showloadingDownload"><img src="../images/ajax-loader1.gif">Loading Please Wait...</div>
       
       
       
       <div class="form-group col-sm-2"  id="hidedownloadCompare" >
            <div class="box-header with-border">
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<li><a title="Export to excel file" onclick="downloadComparePdf(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
								<li><a title="Export to csv file" onclick="downloadComparePdf(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>							  
							  </ul>
							</div>
          
        </div>
                   <!-- <label for="">&nbsp;</label>
                     <a  onclick="downloadComparePdf();" class="btn btn-warning form-control">Download</a> -->
            </div>
    </div> 
           
            
           <style>.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: #222;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 55px;
    height: 55px;
    display: inline-block;
    border-width: 2px;
    border-color: rgba(247,23,82);
    border-top-color: #fff;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}


.chartWrapper {
    position: relative;
}

.chartWrapper > canvas {
    position: absolute;
    left: 0;
    top: 0;
    pointer-events:none;
}

.chartAreaWrapper {
    width: 6000px;
    //overflow-x: scroll;
}

.chartAreaWrapperExecutive {
    width: 10000px;
    //overflow-x: scroll;
}

</style> 
            
            <div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="loading">
                  <div class="overlay">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>
                  
                  <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...--></span> </div>
          </div>
          
          
          
         <?php  if($UserDeviceType=='desktop'){
             $HeightWidth='width="800" height="200"';
         }else{
         $HeightWidth='width="800" height="550"';
         }?>
          <!--col-lg-offset-4 col-sm-offset-3 col-sm-3 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4-->
          
          


          <div id="chart-container">
          <div id="WholeDivshowContent" class="toHideWholeDiv" style="display:none">
          <div id="performanceChart" class="toHideperformanceChar">
         <div id="blk-FinancialYear" class="toHide" >
         
             
            <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span> - Month Wise Report <span class="showPeriodChart"> </span> </strong></h4>
            <div class="row">
              <div class="col-md-12" >
            <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#RoomNightsYTD" aria-controls="home" role="tab" data-toggle="tab">Monthly View</a></li>
            <li role="presentation"><a href="#RoomNightsQTD" aria-controls="profile" role="tab" data-toggle="tab">Quarterly View</a></li>
            <li role="presentation"><a href="#RoomNightsHTD" aria-controls="profile" role="tab" data-toggle="tab">Halfyearly View</a></li>
            </ul>      
                 
                 <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">   
                <p class="text-center"> <strong class="col-lg-offset-2">Room Nights (Month Wise) </strong>
                <!--<div class="row">
                 <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>-->
                </p>
                <div class="chart ">
                  <canvas id="line-chart" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </div>
                
                 <div role="tabpanel" class="tab-pane" id="RoomNightsQTD">
                          <p class="text-center"> <strong class="col-lg-offset-2"> Room Nights (Quarterly Wise) </strong>
                        <!--<div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>-->
                        </p>
                        <div class="chart ">
                          <canvas id="Quarterly-RoomNights-chart" <?php echo $HeightWidth; ?>></canvas>
                        </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="RoomNightsHTD">
                          <p class="text-center"> <strong class="col-lg-offset-2"> Room Nights (Halfyearly Wise) </strong>
                        <!--<div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>-->
                        </p>
                        <div class="chart ">
                          <canvas id="HalfYear-RoomNights-chart" <?php echo $HeightWidth; ?>></canvas>
                        </div>
                </div>
                
                </div>
               
                
                 </div>
              <!-- /.col -->
              <div class="col-md-12" >
                  
                   <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#RevenueYTD" aria-controls="home" role="tab" data-toggle="tab">Monthly View</a></li>
            <li role="presentation"><a href="#RevenueQTD" aria-controls="profile" role="tab" data-toggle="tab">Quarterly View</a></li>
             <li role="presentation"><a href="#RevenueHTD" aria-controls="profile" role="tab" data-toggle="tab">Halfyearly View</a></li></ul>
             <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="RevenueYTD"> 
                        <p class="text-center"> <strong class="col-lg-offset-2">Revenue (Month Wise in Lacs)</strong>
                           <!--<div class="row">
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                            </div>-->
                        </p>
                    <div class="chart ">
                      <canvas id="line-chart-revenue" <?php echo $HeightWidth; ?>></canvas>
                    </div>
                    
                 </div>
                
                 <div role="tabpanel" class="tab-pane" id="RevenueQTD">   
                         <p class="text-center"> <strong class="col-lg-offset-2">Revenue (Quarterly in Lacs) </strong>
                       <!-- <div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>-->
                        </p>
                        <div class="chart ">
                          <canvas id="Quarterly-Revenue-chart" <?php echo $HeightWidth; ?>></canvas>
                        </div>
                 </div>
                 
                 <div role="tabpanel" class="tab-pane" id="RevenueHTD">   
                         <p class="text-center"> <strong class="col-lg-offset-2">Revenue (Halfyearly in Lacs) </strong>
                        <!--<div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>-->
                        </p>
                        <div class="chart ">
                          <canvas id="HalfYear-Revenue-chart" <?php echo $HeightWidth; ?>></canvas>
                        </div>
                 </div>
                    
                    
              </div>    
                    
              </div>
              
               
              
              <!-- /.col --> 
            </div>
            
            
           
            
            
            
            
            <!----Stat PIe Chart-->
            </div>
            <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span><span class="showPeriodChart"> </span></strong></h4>
            <!--<div>
                        <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Budget</strong></h4>
                        <canvas id="budgetChart" width="350" height="50"></canvas>
                    </div>-->
            
            <div class="row" >
              <div class="col-md-6">
                <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Room Nights</strong></h4>
                <canvas id="mtd-per-chart" width="400" height="200"></canvas>
              </div>
              <div class="col-md-6">
                <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Revenue</strong></h4>
                <canvas id="mtdRevenuePerChart" width="400" height="200"></canvas>
              </div>
            </div>
           
           <div  id="blk-showMatChar" class="toHideshowMatChar">
            <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Month Wise Moving Annual Total (MAT) Report</strong></h4>
            <div class="row">
              <div class="col-md-6" >
                <p class="text-center"> <strong class="col-lg-offset-2">Room Nights (MAT) </strong>
               <!-- <div class="row">
                    <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                    <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                    <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>-->
                </p>
                <div class="chart ">
                  <canvas id="line-chart-mat" width="800" height="450"></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
            <div class="col-md-6" >
                <p class="text-center"> <strong class="col-lg-offset-2">Revenue (MAT in Lacs)</strong>
                <!--<div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>-->
                </p>
                <div class="chart ">
                  <canvas id="line-chart-revenue-mat" width="800" height="450"></canvas>
                </div>
              </div>
              <!-- /.col --> 
            </div>
            </div>
            
            
           <div class="row">
               <div  id="blk-AverageRoomRevenueChar" class="toHideAverageRoomRevenueChar">
              <div class="col-md-12" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Average Room Revenue Report<span class="showPeriodChart"> </span></strong></h4>
           
                <p class="text-center"> <strong class="col-lg-offset-2">ARR </strong>
                <!--<div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>-->
                </p>
                <div class="chart ">
                  <canvas id="line-chart-revenue-arr" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
              </div>
               <div class="col-md-6" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>Sales Sync Numbers</strong></h4>
            
                <p class="text-center"> <strong class="col-lg-offset-2"> </strong>
               
                </p>
                <div class="chart ">
                  <canvas id="RoomNightPie" width="800" height="450"></canvas>
                </div>
                </a> </div>
                
                
                <div class="col-md-6" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>Business Source Count</strong></h4>
            
                <p class="text-center"> <strong class="col-lg-offset-2"> </strong>
               
                </p>
                <div class="chart ">
                  <canvas id="BusinessSourceCountPieChart" width="800" height="450"></canvas>
                </div>
                </a> </div>
                
                
              <!--<div  id="blk-BookingThroughChart" class="toHideBookingThroughChart" style="display:none">-->
             
                <!--</div>-->
              <!-- /.col -->
              
              
               <!--<div class="col-md-12" >
                  
                  <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;">
                  <strong><span class="showReportTypeHeadingChart"> </span>Room Nights Hotel Wise <span class="showPeriodChart"> </span></strong></h4>
            
                <p class="text-center"> <strong class="col-lg-offset-2"> </strong>
               
                </p>
                        <div class="chartWrapper" style="overflow: scroll;">
                          <div class="chartAreaWrapper">
                          <div class="chartAreaWrapper2">
                          <canvas id="RoomNightPie"  height="400" width="0" <?php  //echo $HeightWidth; ?>></canvas>
                        </div></div></div>
                </a> </div> -->
                
                
                
                
                <!-- <div class="col-md-12" >
                  
                  <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;">
                  <strong><span class="showReportTypeHeadingChart"> </span>Room Nights Executivewise  <span class="showPeriodChart"> </span></strong></h4>
            
                <p class="text-center"> <strong class="col-lg-offset-2"> </strong>
               
                </p>
                        <div class="chartWrapper" style="overflow: scroll;">
                          <div class="chartAreaWrapper">
                          <div class="chartAreaWrapper2">
                          <canvas id="executiveRoomNightChart"  height="400" width="0" <?php  //echo $HeightWidth; ?>></canvas>
                        </div></div></div>
                </a> </div>
              
            </div> --> 
            
            
           
            
         </div>
          </div> 
          
        </div>    
           
            
         <!--<div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="SummaryDataloading"><img src="../images/ajax-loader1.gif">Loading Please Wait...</span> </div>
          </div>-->
        
       
       <div id="blk-CustomRangeBookingPeriod3" class="toHideCharSummarytogglerContent" style="display:none" >
       <!-- <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showPeriod"> </span></strong></h4>-->
        
            <div class="box-body table-responsive">
              <div id="salesChartWrapper" style="padding:0px 10px 0px 10px;"> </div>
            </div>
        </div>
        
         <div id="blk-CompareRangeBookingPeriod3" class="toHideCharSummarytogglerContent" style="display:none" >
       <!-- <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showPeriod"> </span></strong></h4>-->
        
            <div class="box-body table-responsive">
              <div id="ShowCompareReportData" style="padding:0px 10px 0px 10px;"> </div>
            </div>
        </div>
        
      <!-- <button id="exportButton" type="button">Export as PDF</button>-->
        <!--<div class="form-group col-sm-2" >
          <label for="">&nbsp;</label>
          <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a> </div>-->
      </div>
      </div>
       </div>
      
    </div>
  </div>
  </section>
</div>
<?php include_once("includes/footer.php")?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script type="text/javascript">

$("#exportButton").click(function(){
  html2canvas(document.querySelector("#performanceChart"), { height: 1800, width: window.innerWidth * 2, scale: 1 }).then(canvas => {  	
    var dataURL = canvas.toDataURL();    
    var pdf = new jsPDF();
    pdf.addImage(dataURL, 'JPEG', 20, 20, 170, 120); //addImage(image, format, x-coordinate, y-coordinate, width, height)
    pdf.save("CanvasJS Charts.pdf");
  });
});

  downloadSummaryPdf = (pdf,excel) => {
    
        var id_hotel = $("#id_hotel").val();
        var period = $("#per_report_date").val();
           let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
        var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
        
        var reportType = $("#SelectedreportType").val();
        var summaryReportType = $("#SelectedSummaryViewType").val();
         let CompareFinancialYear = $("#CompareYearselected").val();
        let CurrentFinancialYear = $("#financialyearselected").val();
	if(summaryReportType=='8'){
            let filename1 =  'DashboardagentTop25';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType=8';
            window.open(url1);
        }else if(summaryReportType=='9'){
             let filename1 =  'DashboardpaceReport';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
        }else if(summaryReportType=='20'){
             let filename1 =  'SalesDashboardForecastReport';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
             
        }else{
            let filename = 'SalesDashboardTableView';
            let url = 'ajax/'+filename+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CronSet=0';
            window.open(url);
        }
	
	
	
        
        
        
   }
   downloadComparePdf = (pdf,excel) => {
    
       var id_hotel = $("#id_hotel").val();
        
        var period = $("#per_report_date").val();
        var summaryReportType = $("#SelectedCompareType").val();
        var reportType = $("#SelectedreportType").val();
        var CompareTypedownload = $("#SelectedCompareType").val();
        
        var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
        
        let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
        
        let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
   
    if(CompareTypedownload=='5'){
        let url2 = 'ajax/DashboardMtdYtdReport.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url2);
	}else if(CompareTypedownload=='4'){
        let url1 = 'ajax/SalesDashboardCompareAgentTop.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url1);
	}else if(CompareTypedownload=='61'){
        let url12 = 'ajax/SalesDashboardCompareAgentDropOut.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url12);
	}else if(CompareTypedownload=='62'){
        let url12 = 'ajax/DashboardMtdReport.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url12);
	}else{
	   let url = 'ajax/SalesDashboardCompareView.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear; 
	   window.open(url);
	}
        
   }
   

function updatereportType(value){
    
    $( "#SelectedreportType" ).val(value); 
    if(value==1){
        
       // $("#rdb19").show(); 
    }else{
       //  $("#rdb19").hide(); 
    }
    
    
}

function updateDateQuickSearchYear(year,viewMonthwise){
   
    
    var res = year.split("-");
    var FinanceStarYear = res[0];
    var FinanceEndYear = res[1];
    let quickDate = "01-04-"+FinanceStarYear+" to "+"31-03-"+FinanceEndYear;
     var SelectedComparePeriodDate = $("#SelectedComparePeriodDate").val();
     //alert(SelectedComparePeriodDate);
    updateDateQuickSearch(quickDate,viewMonthwise);
    
}

function updateDateQuickSearchCompare(ComparePeriod){
   
    
    
    
    var res = ComparePeriod.split("-");
    var FinanceComparePeriodStarYear = res[0];
    var FinanceComparePeriodEndYear = res[1];
    let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
    
    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
	    
    let quickDate = $("#per_report_date").val();
    updateDateQuickSearch(quickDate,1);
    
}
function updateDateQuickSearchHotel(){
        var SelectedViewType = $("#SelectedViewType").val();
        var viewMonthwise = $("#SelectedMonthView").val();
        var period = $("#per_report_date").val();
        
        var summaryReportType = $("#SelectedSummaryViewType").val();
        var SelectedViewType = $("#SelectedViewType").val();
        
        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }
        
        
        //alert(SelectedViewType);alert(summaryReportType);
       /* if(summaryReportType=4){
                SalesSummaryDateQuickForSearch('0','4');
        }else{
                if(SelectedViewType==1){
                    fetchPerformanceGraphData();
                }else{	
                	 fetchPerformanceSummaryData(SelectedViewType,summaryReportType);
                }
                	
        }*/
	}
	
function SearchButtonType(){
    
    var SelectedViewType = $("#SelectedViewType").val();
     var viewMonthwise = $("#SelectedMonthView").val();
        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }
    
    
}	
function updateDateQuickSearch(quickDate,viewMonthwise){
    
$( "#per_report_date" ).val(quickDate);
//var per_report_date = $("#per_report_date").val();
//alert(per_report_date);
var SelectedViewType = $("#SelectedViewType").val();
$( "#SelectedMonthView" ).val(viewMonthwise);


        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }


	}
	
function ReportTypePickupBob(){
	
	
var SelectedViewType = $("#SelectedViewType").val();
var viewMonthwise = $("#SelectedMonthView").val();
//$( "#SelectedMonthView" ).val(viewMonthwise);


        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }


	}	

$(function() {
    	$("button").click(function() {
    	    
    	    var Qutare = this.id;
    	  
            
            if(Qutare=='dateColor6' || Qutare=='dateColor7' || Qutare=='dateColor8' || Qutare=='dateColor9'|| Qutare=='dateColor10'|| Qutare=='dateColor11' ){ 
            
            //document.getElementById("dateColor1").className = "btn bg-default mobile-today";  
           // document.getElementById("dateColor2").className = "btn bg-default";  
           // document.getElementById("dateColor3").className = "btn bg-default";  
            document.getElementById("dateColor4").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor5").className = "btn btn-default mobile-responseset";
            
            document.getElementById("dateColor6").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor7").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor8").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor9").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor10").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor11").className = "btn btn-default mobile-responseset";
            
            
            
            //document.getElementById("dateColor12").className = "btn btn-default mobile-responseset";
            //document.getElementById("dateColor13").className = "btn btn-default mobile-responseset";
            //document.getElementById("dateColor14").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColorFinancialYear").className = "btn bg-default  mobile-responseset";
            document.getElementById("dateColorCustomRangeBookingPeriod").className = "btn bg-default mobile-customrange";
            var classValue= ''; 
            document.getElementById(Qutare).className = "btn btn-foursquare "+classValue;
            }
});
    
    
    
    
    
    $("[name=toggler]").click(function(){
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
           
           
          
            if($(this).val()=='FinancialYear'){
                $("#blk-showMatChar").show();
                $("#blk-AverageRoomRevenueChar").show();
                $('.toHideBookingThroughChart').hide();
                
            }else{ 
                $('.toHideshowMatChar').hide();
                $('.toHideAverageRoomRevenueChar').hide();
                $("#blk-BookingThroughChart").show();
            }
            
            //var buttons = document.getElementsByTagName("button");
            
            if($(this).val()=='FinancialYear' || $(this).val()=='CustomRangeBookingPeriod' || $(this).val()=='1' || $(this).val()=='2'|| $(this).val()=='3' || $(this).val()=='4' || $(this).val()=='5'|| $(this).val()=='12'|| $(this).val()=='13' || $(this).val()=='14' ){
             
            
            //dateColorFinancialYear
            //document.getElementById("dateColor1").className = "btn bg-default mobile-today";  
            //document.getElementById("dateColor2").className = "btn bg-default";  
            //document.getElementById("dateColor3").className = "btn bg-default";  
            document.getElementById("dateColor4").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor5").className = "btn btn-default mobile-responseset";
            
            document.getElementById("dateColor6").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor7").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor8").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor9").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor10").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor11").className = "btn btn-default mobile-responseset";
            
            
            //document.getElementById("dateColor12").className = "btn btn-default mobile-responseset";
            //document.getElementById("dateColor13").className = "btn btn-default mobile-responseset";
           // document.getElementById("dateColor14").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColorFinancialYear").className = "btn bg-default  mobile-responseset";
            document.getElementById("dateColorCustomRangeBookingPeriod").className = "btn bg-default mobile-customrange"; 
            if($(this).val()=='FinancialYear' || $(this).val()=='4' || $(this).val()=='5' || $(this).val()=='6'|| $(this).val()=='7' || $(this).val()=='8' || $(this).val()=='9'|| $(this).val()=='10'|| $(this).val()=='11' ){
                var classValue= 'mobile-responseset';
            
            }else if($(this).val()=='1'){
                var classValue= 'mobile-today';
            }else if($(this).val()=='CustomRangeBookingPeriod'){
                var classValue= 'mobile-customrange';
            }else{
                
               var classValue= ''; 
            }
             
            document.getElementById("dateColor"+$(this).val()).className = "btn btn-foursquare "+classValue;
    }    
    });
    
    $("[name=CharSummarytoggler]").click(function(){
            $('.toHideCharSummarytoggler').hide();
            $('.toHideCharSummarytogglerContent').hide();
             $('.toHidedownloadPDF').hide();
            
            if($(this).val()=='CustomRangeBookingPeriod2'){
               
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod3").show();
                $('#performanceChart').hide();
                $("#blk-CompareRangeBookingPeriod2").hide();
                $("#blk-CompareRangeBookingPeriod3").hide(); 
               $( "#SelectedViewType" ).val('0'); //TableView
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
               document.getElementById("rdb23").className = "btn btn-default col-md-3";
               document.getElementById("rdb22").className = "btn btn-foursquare col-md-3";
               
document.getElementById("rdb17").className = "btn bg-default margin btn-foursquare mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";




$( "#SelectedSummaryViewType" ).val('7');
                var viewMonthwise = $("#SelectedMonthView").val();
                var summaryReportType = $("#SelectedSummaryViewType").val();
                fetchPerformanceSummaryData('0','7');
               
               
            }else if($(this).val()=='CompareRangeBookingPeriod2'){
                 
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod2").hide();
                $("#blk-CustomRangeBookingPeriod3").hide(); //Data List Table 
                $("#blk-CompareRangeBookingPeriod3").show(); 
                $('#performanceChart').hide();
                $( "#SelectedViewType" ).val('2'); //TableView
                 var summaryReportType = $("#SelectedCompareType").val();
                fetchCompareReportData('2','1');
                
            document.getElementById("compare1").className = "btn bg-default margin btn-foursquare mobileSummary-today";
            document.getElementById("compare2").className = "btn bg-default margin mobileSummary-today";
            document.getElementById("compare3").className = "btn bg-default margin mobileSummary-today";
            document.getElementById("compare4").className = "btn bg-default margin mobileSummary-today";
           // document.getElementById("compare5").className = "btn bg-default margin mobileSummary-today";
            document.getElementById("compare61").className = "btn bg-default margin mobileSummary-today";
           // document.getElementById("compare62").className = "btn bg-default margin mobileSummary-today";
           
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
               document.getElementById("rdb22").className = "btn btn-default col-md-3";
               document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
            }else{
               
              var SelectedMonthView = $("#SelectedMonthView").val();
              fetchPerformanceGraphData(SelectedMonthView);
              $('#performanceChart').show(); 
              
              $("#blk-CompareRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod3").hide(); 
              $( "#SelectedViewType" ).val('1'); //ChartView'
              
              document.getElementById("rdb22").className = "btn btn-default col-md-3";
              document.getElementById("rdb23").className = "btn btn-default col-md-3";
              document.getElementById("rdb21").className = "btn btn-foursquare col-md-3";
              //alert($(this).val()); 
              
            }
            
    });
     
     $("[name=SummaryReportRadio]").click(function(){
            //$('.toHide').hide();
            //$("#blk-"+$(this).val()).show();
    });
 });
 
 function updateDateQuickForSummarySearch(viewMonthwise,summaryReportType){
//var SelectedViewType = $("#SelectedViewType").val();
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";

//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today"
//document.getElementById("rdb16").className = "btn bg-default margin mobileSummary-today"
document.getElementById("rdb17").className = "btn bg-default margin mobileSummary-today"
document.getElementById("rdb1"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
            $( "#SelectedSummaryViewType" ).val(summaryReportType);
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);

	
	}
 function updateCompareSummarySearch(viewMonthwise,summaryReportType){
//var SelectedViewType = $("#SelectedViewType").val();
document.getElementById("compare1").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare2").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare3").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare4").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("compare5").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare61").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("compare62").className = "btn bg-default margin mobileSummary-today";

document.getElementById("compare"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedCompareType" ).val(summaryReportType);
 
 if(summaryReportType=='4222' || summaryReportType=='5' ){
    //$("#hidedownloadCompare").hide();
}else{
  //$("#hidedownloadCompare").show();  
}
 
            fetchCompareReportData(viewMonthwise,summaryReportType);
	}	
	
function SalesSummaryDateQuickForSearch(viewMonthwise,summaryReportType){

//	$( "#per_report_date" ).val(quickDate);
//	$( "#per_report_date" ).val(quickDate);
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb16").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb17").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb1"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedSummaryViewType" ).val(summaryReportType);
SalesSummaryDateQuickForSearchfunction(viewMonthwise,summaryReportType);
	//fetchPerformanceGraphData(viewMonthwise);
	}
   downloadPdf = () => {
       var hotel_id = $("#id_hotel").val();
	   
	   var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
	   
	var reservation_date = $("#per_report_date").val();
	var reportType = $("#reportType").val();
        let url = 'ajax/DashboardajaxDownloadBookings.php?reservation_bookingDate='+reservation_date+'&reportType='+reportType+'&id_hotel='+hotel_id+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'';
        window.open(url);
   }

    function sumofArray(sum, num) { 
        return Number(sum) + Number(num); 
    } 

   
	var mtdPreValueArr = [];
	var budgetValueArr = [];
    var mtdThisValueArr = [];
	var ytdRoomRevenue=[];
	var ytdPreValueArr = [];
    var ytdThisValueArr = [];

	var exeNameArr = [];
    var graphCount = 0;
    var graphCountLead=0;
  
	var ytdRevenueChart='';
	var mtdRevenueChart='';
var mtdThisAllHotelValues= [];
var ytdAllHotelValues=[];

var MtdRevenueAllHotelValue= [];
var ytdRevenueAllHotelValue=[];

    var exeIdArr =[];
    var graphotelName=[];
    var totalGoneYtd=0;
    var totalGoneMtd=0;
    var datePeriod ='';
var ytdPrevYearAllHotelValue=[];
var ytdAchievedAllHotelValue=[];
var ytdPrevYearRevenuAllHotelLastYearValue=[];
var ytdRevenueAllHotelThisYearValue=[];
var monthNameData=[];
var MonthWiseRoomNightsData=[];
var MonthWiseRoomNightsLastYearData=[];
var MonthWiseRevenueCurrentYearData=[];
var ytdPrevYearRevenueData=[];
var mtdThisAllHotelValuesMat=[];
var ytdAllHotelValuesMat=[];
var MonthWiseRevenueCurrentYearDataMat=[];
var ytdPrevYearRevenueDataMat=[];
var mtdRoomRevenueArr=[];
var companygroupNamearray   =[];
var companygroupDatalist=[];
var CompanyGroupListLastYearArray=[];
var OfferNameArray=[];
var rowOfferListArray=[];
var mtdThisCustomeReportValues=[];
    var stacked = [];
    var CustomeReportValuesNameData=[];
var mtdRoomRevenueLastYearData= [];
var BookingThroughNameArray= [];
var	BookingThroughCurrentYearValue= [];
var rowBookingThroughLastYearValue= [];
var SegmentWiseListLastYearArrayValue =[];
var horizontalBarThisYearArrayValue=[];
var   horizontalBarLastYearArrayValue=[];
var  horizontalBarNameArrayValue    =[];
var horizontalBarThisYearRevenueArrayValue =[];
var horizontalBarLastYearRevenueArrayValue=[];
var MonthWiseRoomNightsCurrentYearQuarterlyArrayValue=[];
var MonthWiseRevenueCurrentYearQuarterlyArrayValue=    [];
var ytdPrevYearRoomNightsQuarterlyArrayValue    =[];
var ytdPrevYearRevenueQuarterlyArrayValue    =[];
var ymonthNameDataQuarterlyArrayValue    =[];

var MonthWiseRoomNightsCurrentYearHalfYearArrayValue=   [];
var MonthWiseRevenueCurrentYearHalfYearArrayValue=   [];
var ytdPrevYearRoomNightsHalfYearArrayValue    =[];
var ytdPrevYearRevenueHalfYearArrayValue    =[];
var monthNameDataHalfYearArrayValue    =[];
var yearToDayPreValueArr    =[];
var mtdThisExecutiveValuesArr=[];
var budgetRoomNightsValuesArr =[];
var SalesSyncLableArr=[];
var SalesSyncNumbersArr=[];
var BusinessSourceLableArr=[];
var BusinessSourceNumbersArr=[];
                   	
                   	function fetchPerformanceGraphData(viewMonthwise){
        $("#loading").show();
        
	
		$('.toHideWholeDiv').hide();
		$('.toHidedownloadPDF').hide();
	
		let period = $("#per_report_date").val();
		let ComparePeriodDate = $("#SelectedComparePeriodDate").val();

		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
		
        if(reportType==1){
            reportTypeFile  ='SalesDashboardGraphData.ajax.php';
        }else{
            reportTypeFile  ='SalesDashboardGraphDataBOB.ajax.php'; //reportType =2 BOB
            //alert('BOB');
        }
         if(reportType==1){
            reportHeading  =' Pickup ';
       }else{
           reportHeading  =' BOB ';
       }
		//console.log($period+'---'+$id_team);	
		//if(id_hotel){	
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_team='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&ComparePeriod='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear,
				success:function(data){
						$('#WholeDivshowContent').show();
						$('#DivshowdownloadPDF').show();
                    	
					data = JSON.parse(data);

                    exeNameArr = data.executives;
                    budgetValueArr =data.budgetVal;
                    mtdPreValueArr =data.mtdLastVal;
                    mtdThisValueArr =data.mtdThisVal;
                    ytdPreValueArr =data.ytdLastVal;
                    ytdThisValueArr =data.ytdThisVal;
                    mtdVisits = data.mtdVisits;
                    ytdVisits = data.ytdVisits;
                    mtdRoomRevenue = data.mtdRoomRevenue;
                    ytdRateletters = data.ytdRateLetters;
                    mtdTotalExpense = data.mtdTotalExpense;
                    ytdTotalExpense = data.ytdTotalExpense;
                    totalGoneYtd = data.totalDaysGoneYtd;
                    totalGoneMtd = data.totalDaysGoneMtd;
                    datePeriod = data.datePeriod;
                    stacked = data.stacked;
					ytdRoomRevenue = data.ytdRoomRevenue
					mtdThisAllHotelValues=data.mtdThisAllHotelValues;
					ytdAllHotelValues=data.ytdAllHotelValues;
                    graphotelName=data.graphotelName;
					ytdPrevYearAllHotelValue=data.ytdPrevYearAllHotelValue;
					ytdAchievedAllHotelValue=data.ytdAchievedAllHotelValue;
					mtdRoomRevenueLastYearData=data.mtdRoomRevenueLastYearArr;
					MtdRevenueAllHotelValue=data.MtdRevenueAllHotelValue;
					ytdRevenueAllHotelValue=data.ytdRevenueAllHotelValue;
					CompanyGroupListLastYearArray=data.CompanyGroupListLastYearArray;
					ytdPrevYearRevenuAllHotelLastYearValue=data.ytdPrevYearRevenuAllHotelLastYearValue;
					ytdRevenueAllHotelThisYearValue=data.ytdRevenueAllHotelThisYearValue;
					monthNameData=data.monthNameData;
					MonthWiseRoomNightsData=data.MonthWiseRoomNightsData;
					MonthWiseRoomNightsLastYearData=data.MonthWiseRoomNightsLastYearData;
					MonthWiseRevenueCurrentYearData=data.MonthWiseRevenueCurrentYearData;
					ytdPrevYearRevenueData=data.ytdPrevYearRevenueData;
				    companygroupNamearray  =data.CompanyGroupNameArray;
                    companygroupDatalist=data.CompanyGroupListArray;
					
					mtdThisAllHotelValuesMat=data.mtdThisAllHotelValuesMat;
					ytdAllHotelValuesMat=data.ytdAllHotelValuesMat;
					MonthWiseRevenueCurrentYearDataMat=data.MonthWiseRevenueCurrentYearDataMat;
					ytdPrevYearRevenueDataMat=data.ytdPrevYearRevenueDataMat;
					mtdRoomRevenueArr=data.mtdRoomRevenueArr;
					OfferNameArray=data.OfferNameArray;
					rowOfferListArray=data.rowOfferListArray;
                    mtdThisCustomeReportValues=data.mtdThisCustomeReportValues;
                    
                    CustomeReportValuesNameData=    data.CustomeReportValuesName
                    mtdRoomCustomeReportRevenue =   data.mtdRoomCustomeReportRevenue;
                    
                    BookingThroughNameArray=    data.BookingThroughNameArray
                    BookingThroughCurrentYearValue=    data.BookingThroughCurrentYearValue
                    rowBookingThroughLastYearValue=    data.rowBookingThroughLastYearValue
                    
                    mtdThisCustomeLastYearReportValues=    data.mtdThisCustomeLastYearReportValues;
                    mtdRoomCustomeLastYearReportRevenue=    data.mtdRoomCustomeLastYearReportRevenue;
                    SegmentWiseListLastYearArrayValue    =data.SegmentWiseListLastYearArray;
                    
                    horizontalBarThisYearArrayValue=    data.horizontalBarThisYear;
                    horizontalBarLastYearArrayValue=    data.horizontalBarLastYear;
                    horizontalBarNameArrayValue    =data.horizontalBarName;
                    
                    horizontalBarThisYearRevenueArrayValue=    data.horizontalBarThisYearRevenue;
                    horizontalBarLastYearRevenueArrayValue=    data.horizontalBarLastYearRevenue;
                    
                    MonthWiseRoomNightsCurrentYearQuarterlyArrayValue=    data.MonthWiseRoomNightsCurrentYearQuarterly;
                    MonthWiseRevenueCurrentYearQuarterlyArrayValue=    data.MonthWiseRevenueCurrentYearQuarterly;
                    ytdPrevYearRoomNightsQuarterlyArrayValue    =data.ytdPrevYearRoomNightsQuarterly;
                    ytdPrevYearRevenueQuarterlyArrayValue    =data.ytdPrevYearRevenueQuarterly;
                    ymonthNameDataQuarterlyArrayValue    =data.monthNameDataQuarterly;
                   
                    MonthWiseRoomNightsCurrentYearHalfYearArrayValue=    data.MonthWiseRoomNightsCurrentYearHalfYear;
                    MonthWiseRevenueCurrentYearHalfYearArrayValue=    data.MonthWiseRevenueCurrentYearHalfYear;
                    ytdPrevYearRoomNightsHalfYearArrayValue    =data.ytdPrevYearRoomNightsHalfYear;
                    ytdPrevYearRevenueHalfYearArrayValue    =data.ytdPrevYearRevenueHalfYear;
                    monthNameDataHalfYearArrayValue    =data.monthNameDataHalfYear;
                   CYLable=data.CYLable;
                   LYLable=data.LYLable;
                   yearToDayPreValueArr =data.yearToDayLastVal;
                   	budgetRoomNightsValuesArr=data.budgetRoomNightsValues;
                   	mtdThisExecutiveValuesArr=data.mtdThisExecutiveValues;
                   	SalesSyncLableArr=data.SalesSyncLable;
                   	SalesSyncNumbersArr=data.SalesSyncNumbers;
                   	
                   	BusinessSourceLableArr=data.BusinessSourceLable;
                   	BusinessSourceNumbersArr=data.BusinessSourceNumbers;
                   	
                   	
                   //alert(mtdThisCustomeReportValues);
                    //alert(CustomeReportValuesNameData);
				    performanceChart(graphCount,viewMonthwise);
				    
				    $(".showReportTypeHeadingChart").html(reportHeading);
				    $(".showPeriodChart").html(' For Period '+data.reportPeriod);
                    //budgetChartFun(graphCount);
					/*$(".showPeriod").html(' For Period '+data.reportPeriod);
                    let salesTable='<table class="table table-striped text-center"><tr style="color:white;"><th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive Name</th><th style="background-color:#3C8DBC;" colspan="3"></th></tr><tr style="background-color:#5cb4e8;"><th >Room Nights</th><th>Revenue</th><th>ARR</th></tr>';

                    for(let i=0;i<exeNameArr.length;i++){
						
						if(mtdRoomRevenue[i]>0){
						var mtrArr =(mtdRoomRevenue[i]/mtdThisValueArr[i]).toFixed(0);
						}else{
							var mtrArr =0;
							}
						if(ytdThisValueArr[i]>0){
							var ytdArr =(ytdRoomRevenue[i]/ytdThisValueArr[i]).toFixed(0);
						}else{
							var ytdArr =0;
							}	
							
							
                        salesTable+='<tr ><td style="text-align:left;">'+exeNameArr[i]+'</td><td>'+mtdThisValueArr[i]+'</td><td>'+(mtdRoomRevenue[i])+'</td><td>'+mtrArr+'</td></tr>';
                    }
				    var SumTotalArray= ((mtdRoomRevenue.reduce(sumofArray)/(mtdThisValueArr.reduce(sumofArray))).toFixed(0));
                    salesTable+='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;"><td style="text-align:left;background-color:#5CB4E8;">Total</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;">'+mtdThisValueArr.reduce(sumofArray)+'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;">'+mtdRoomRevenue.reduce(sumofArray)+'</td><td style="border-left:1px solid #fff;background-color:#5CB4E8;">'+SumTotalArray+'</td></tr>';                                    

                    salesTable+='</table>';

                    $("#salesChartWrapper").html(salesTable);*/
                    
                                       
                    graphCount++;
                    $("#loading").hide();
                    
				}
			})
		//}else{
		//	alert('Please Select Hotel.');
		//}
        //leadGraphData();
        

	}

    
    

    
    function performanceChart(graphCount,viewMonthwise){
        
         
        if(graphCount>0){
            mtdChart.destroy(); 
            mtdRevenueChart.destroy();
            
            //CompanyGroupBarChart.destroy();
		   //BookingThroughBarChart.destroy();
         // monthlyRoomNightPieChart.destroy();//Segment
          
           monthlyBarChart.destroy();
		    monthlyBarRevenueChart.destroy();
		    monthlyBarLineChart.destroy();
		   monthlyBarRevenueLineChart.destroy();
		   monthlyLineARRChart.destroy();
		    //CompleteChartHorizontalBar.destroy();
		    //CompleteChartHorizontalBarRevenue.destroy();
		    QuarterlyRoomNightsBar.destroy();
		    QuarterlyRevenueBar.destroy();
		    
		    HalfYearRoomNightsBar.destroy();
		    HalfYearRevenueBar.destroy();
		   //mtdChart.render();
		  // executiveYearChartBar.destroy();
		  monthlyRoomNightPieChart.destroy();
		  CountBusinessSourcePieChart.destroy();
        }

        let mtdPerChartHeader = document.getElementById('mtd-per-chart').getContext('2d'); 
       let mtdRevenuePerChart = document.getElementById('mtdRevenuePerChart').getContext('2d');
	
       // let monthRoomNightPiePerChart = document.getElementById('RoomNightPie').getContext('2d'); //Segment
         //let CompanyGroupBarPerChart = document.getElementById('bar-chart-company-arr').getContext('2d');
		//let BookingThroughBarPerChart = document.getElementById('BookingThroughChart').getContext('2d');
		
		let monthBarPerChart = document.getElementById('line-chart').getContext('2d'); 
		let monthRevenueBarPerChart = document.getElementById('line-chart-revenue').getContext('2d'); 		
		let monthBarLinePerChart = document.getElementById('line-chart-mat').getContext('2d'); 
		let monthRevenueBarLinePerChart = document.getElementById('line-chart-revenue-mat').getContext('2d');
		let monthRevenueARRLinePerChart = document.getElementById('line-chart-revenue-arr').getContext('2d');
		
	//	let ytdMtdChartHorizontalBar = document.getElementById('horizontalBar-Ytd-Mtd').getContext('2d');
		//let ytdMtdChartHorizontalBarRevenue  = document.getElementById('horizontalBar-Ytd-Mtd-Revenue').getContext('2d');
		
		
		let QuarterlyRoomNightsBarChart = document.getElementById('Quarterly-RoomNights-chart').getContext('2d');
		let QuarterlyRevenueBarChart = document.getElementById('Quarterly-Revenue-chart').getContext('2d');
		
		let HalfYearRoomNightsBarChart = document.getElementById('HalfYear-RoomNights-chart').getContext('2d');
		let HalfYearRevenueBarChart = document.getElementById('HalfYear-Revenue-chart').getContext('2d');
		//let executiveRoomNightBarChart = document.getElementById('executiveRoomNightChart').getContext('2d'); //executive
		let monthRoomNightPiePerChart = document.getElementById('RoomNightPie').getContext('2d'); 
		
		let BusinessSourceCountPie = document.getElementById('BusinessSourceCountPieChart').getContext('2d'); 
		
CountBusinessSourcePieChart = new Chart(BusinessSourceCountPie, {				
 //type: 'doughnut',		 

  type: 'pie',
  data: {
    labels: BusinessSourceLableArr,
    datasets: [ { 
        data: BusinessSourceNumbersArr,
        label: 'Total : '+BusinessSourceNumbersArr.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: [
        "#d495ed",
        "#f1c40f",
        "#2ee83a",
        "#97a3e5",
        "#83c1ea",
        "#00fffa"
      ]
					
      }
    ]
  }, options: {
	 
  
 
	  responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
	  plugins: {
                    labels:{
                      render:'value',  
                    }   
	  },
    
  }
});

monthlyRoomNightPieChart = new Chart(monthRoomNightPiePerChart, {				
 //type: 'doughnut',		 
 
  type: 'pie',
  data: {
    labels: SalesSyncLableArr,
    datasets: [ { 
        data: SalesSyncNumbersArr,
        //label: 'This Year : '+MonthWiseRevenueCurrentYearDataMat.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: [
        "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#eaa6a6",
        "#abeae8",
        "#83c1ea",
        "#00fffa"
      ]
					
      }
    ]
  }, options: {
	 
  
 
	  responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
	  plugins: {
                    labels:{
                      render:'value',  
                    }   
	  },
    
  }
});
 /*executiveYearChartBar = new Chart(executiveRoomNightBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+yearToDayPreValueArr.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: yearToDayPreValueArr
                },
                {
                    label: 'Year ('+CYLable+'): '+budgetRoomNightsValuesArr.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: budgetRoomNightsValuesArr
                },{
                    label: 'Year ('+CYLable+'): '+mtdThisExecutiveValuesArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisExecutiveValuesArr
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }
            }
 
});   */
 
mtdChart = new Chart(mtdPerChartHeader, {
            type: 'bar',
            data: {
                labels: CustomeReportValuesNameData,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+mtdThisCustomeLastYearReportValues+'',
                    
                     backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisCustomeLastYearReportValues
                },
                {
                    label: 'Year ('+CYLable+'): '+mtdThisCustomeReportValues+'',
                     backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisCustomeReportValues
                }
                
                ]
            },
options: {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                  labels: {
                    render: () => {}
                  }
                },"hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 13, bar._model.y + 15);
          });
        });
      }
    }
}
 });//
 
 
 mtdRevenueChart = new Chart(mtdRevenuePerChart, {
            type: 'bar',
            // The data for our dataset
            data: {
                labels: CustomeReportValuesNameData,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+mtdRoomCustomeLastYearReportRevenue.reduce(sumofArray)+'',
                    
                     backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: mtdRoomCustomeLastYearReportRevenue
                },
                {
                    label: 'Year ('+CYLable+'): '+mtdRoomCustomeReportRevenue.reduce(sumofArray)+'',
                     backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: mtdRoomCustomeReportRevenue
                }]
				
            },

            // Configuration options go here
           
options: {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                  labels: {
                    render: () => {}
                  }
                },"hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 13, bar._model.y + 15);
          });
        });
      }
    }
}
        });
        
        
     //Hotel Wise   
/*  monthlyRoomNightPieChart = new Chart(monthRoomNightPiePerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: OfferNameArray,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+SegmentWiseListLastYearArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: SegmentWiseListLastYearArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+rowOfferListArray.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: rowOfferListArray
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }
            }
 
});  */    
   //Segment Wise End     


/* BookingThroughBarChart = new Chart(BookingThroughBarPerChart, {
            type: 'bar',
            data: {
                labels: BookingThroughNameArray,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+rowBookingThroughLastYearValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
                    data: rowBookingThroughLastYearValue
                },
                {
                    label: 'Year ('+CYLable+'): '+BookingThroughCurrentYearValue.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
                    data: BookingThroughCurrentYearValue
                }
                
                ]
            },

            // Configuration options go here
            options: {
				scales: {
        yAxes: [{
            display: true,
            ticks: {
                suggestedMin: 0,    // minimum will be 0, unless there is a lower value.
                // OR //
                beginAtZero: true   // minimum value will be 0.
            }
        }]
    },
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }
            }
        });
        */
 

 if(viewMonthwise==1){      
monthlyBarChart = new Chart(monthBarPerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: monthNameData,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+MonthWiseRoomNightsLastYearData.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRoomNightsLastYearData
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRoomNightsData.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRoomNightsData
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});



 
 monthlyBarRevenueChart = new Chart(monthRevenueBarPerChart, {
  type: 'bar',
  data: {
    labels: monthNameData,
    datasets: [{ 
        data: ytdPrevYearRevenueData,
        label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1
      }, { 
        data: MonthWiseRevenueCurrentYearData,
        label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(60,141,188,0.8)',
        borderColor: 'rgba(54, 162, 235,1)'
					
      }
    ]
  }, options: {
	 
  
 
	  responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
	  plugins: {
                    labels:{
                      render:'value',  
                    }   
	  },
    
  }
});

//Qutaerly Start


QuarterlyRoomNightsBar = new Chart(QuarterlyRoomNightsBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: ymonthNameDataQuarterlyArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRoomNightsQuarterlyArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRoomNightsQuarterlyArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRoomNightsCurrentYearQuarterlyArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRoomNightsCurrentYearQuarterlyArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});


QuarterlyRevenueBar = new Chart(QuarterlyRevenueBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: ymonthNameDataQuarterlyArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueQuarterlyArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRevenueQuarterlyArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearQuarterlyArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRevenueCurrentYearQuarterlyArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});
//HalfYear===============================================================

HalfYearRoomNightsBar = new Chart(HalfYearRoomNightsBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: monthNameDataHalfYearArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRoomNightsHalfYearArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRoomNightsHalfYearArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRoomNightsCurrentYearHalfYearArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRoomNightsCurrentYearHalfYearArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});


HalfYearRevenueBar = new Chart(HalfYearRevenueBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: monthNameDataHalfYearArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueHalfYearArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRevenueHalfYearArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearHalfYearArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRevenueCurrentYearHalfYearArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});
//HalfYear END============================================================

//===================BAR Chart For Moth Wise=EnD==============================	

//===================BAR Chart For Moth Wise=EnD==============================

//LINE CHART ===========
 monthlyBarLineChart = new Chart(monthBarLinePerChart, {
  type: 'line',
  	// The data for our dataset
            data: {
                labels: monthNameData,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+MonthWiseRoomNightsLastYearData.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
					fontColor: "#981C1E",
					margin: 1,
                    data: ytdAllHotelValuesMat
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRoomNightsData.reduce(sumofArray)+'',
                     backgroundColor: 'rgba(60,141,188,0.8)',
                    borderColor: 'rgba(60,141,188,0.8)',
					fontColor: "#981C1E",
					margin: 1,
                    data: mtdThisAllHotelValuesMat
                }]
				
            },

            // Configuration options go here
            options: {showAllTooltips: true,
			  
  /*  "hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 13, bar._model.y + 15);
          });
        });
      }
    },*/
    legend: {
      "display": true,
	  fontColor: '#981C1E'
    },
    tooltips: {
      "enabled": true,
	  fontColor: '#981C1E'
    },maintainAspectRatio: false,
    plugins: {
      datalabels: {
        color: 'red'
      }
    }
  }
 
});
 
monthlyBarRevenueLineChart = new Chart(monthRevenueBarLinePerChart, {
  type: 'line',
  data: {
    labels: monthNameData,
    datasets: [{ 
    //MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)     
        label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1,
		data: ytdPrevYearRevenueDataMat
      }, { 
        
        label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(60,141,188,0.8)',
        borderColor: 'rgba(54, 162, 235,1)',
        margin: 1,
        data: MonthWiseRevenueCurrentYearDataMat,
					
      }
    ]
  },  // Configuration options go here
            options: {showAllTooltips: true,
			  
  /*  "hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 13, bar._model.y + 15);
          });
        });
      }
    },*/
    legend: {
      "display": true,
	  fontColor: '#981C1E'
    },
    tooltips: {
      "enabled": true,
	  fontColor: '#981C1E'
    },maintainAspectRatio: false,
    plugins: {
      datalabels: {
        color: 'red'
      }
    }
  }
});

 //===================LINE Chart For Moth Wise=END==============================	 
  
  
  
  	
monthlyLineARRChart = new Chart(monthRevenueARRLinePerChart, {				
 type: 'bar',		 
  //type: 'line',
  data: {
    labels: monthNameData,
    datasets: [ { 
    //MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)     
      //  label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
      backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1,
		data: mtdRoomRevenueLastYearData
      },{ 
        data: mtdRoomRevenueArr,
        //label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearDataMat.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(60,141,188,0.8)',
        borderColor: 'rgba(54, 162, 235,1)'
					
      }
    ]
  }, options: {
	 
  scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
 
	  responsive: true,
            legend: {
                position: 'bottom',
                display: false,
 
            },
	  plugins: {
                    labels:{
                      render:'value',  
                    }   
	  },
    
  }
});

}

    }

function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 32)];
        }
		 var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        //return color;
    }

   
    $('document').ready(function(){
        //$('.toHideWholeDiv').hide();
        //fetchPerformanceGraphData(1);
    });

   // function drillDown(val){

      //  window.open('manageEnquiry.php?Download=Download&checkin_radio='+val+'&booking_date='+datePeriod+'&reservation_date='+datePeriod+'&lead_status=&id_hotel=&searchFormSubmit=1&drilled_team='+exeIdArr.join());
    //}
	
  //Summary Data
  
  
  function fetchPerformanceSummaryData(viewMonthwise,summaryReportType){
     //alert('2');loading
     $("#loading").show();
        //$("#SummaryDataloading").show();
		let period = $("#per_report_date").val();
		
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	//	alert(id_group_master);
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		 let CompareFinancialYear = $("#CompareYearselected").val();
         let CurrentFinancialYear = $("#financialyearselected").val();
        if(summaryReportType==8){
            reportTypeFile  ='DashboardagentTop25.php';
        }else if(summaryReportType==9){
            reportTypeFile  ='DashboardpaceReport.php'
        }else if(summaryReportType==20){
            reportTypeFile  ='SalesDashboardForecastReport.php'
        }else{
            reportTypeFile  ='SalesDashboardTableView.php';
        }
       
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&CronSet=0'+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
					$("#salesChartWrapper").html(data);
                    
                    $("#loading").hide();
                    
				
				}
			})
	
        

	}
 function fetchCompareReportData(viewMonthwise,summaryReportType){
     //alert('2');loading
     $("#loading").show();
        //$("#SummaryDataloading").show(); 
		let period = $("#per_report_date").val();
		let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
	    
        if(summaryReportType==4){
            reportTypeFile  ='SalesDashboardCompareAgentTop.php';
            
        }else if(summaryReportType==61){
            reportTypeFile  ='SalesDashboardCompareAgentDropOut.php';
        }else if(summaryReportType==62){
            reportTypeFile  ='DashboardMtdReport.php';
        }else if(summaryReportType==5){
            reportTypeFile  ='DashboardMtdYtdReport.php';
        }else{
            reportTypeFile  ='SalesDashboardCompareView.php';
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&ComparePeriodDate='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
					$("#ShowCompareReportData").html(data);
                    
                    $("#loading").hide();
                    
					
				}
			})
	
        

	}	

	
function SalesSummaryDateQuickForSearchfunction(viewMonthwise,summaryReportType){
      $("#loading").show();
        //$("#SummaryDataloading").show();
		let period = $("#per_report_date").val();
		
		//let reportType = $("#reportType").val();
	var reportType = $("#SelectedreportType").val();
	    var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
        
		let id_hotel = $("#id_hotel").val();
        //if(reportType==1){
            reportTypeFile  ='DashboardSalesSummaryData.ajax.php';
       // }
        if(reportType==1){
            reportHeading  =' Pickup ';
       }else{
           reportHeading  =' BOB ';
       }
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_team='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
					
                    
					data = JSON.parse(data);
					if(data.executives!=''){
					//alert(data.executives);
					exeNameCountArr = data.executives;
                    exeNameArr = data.executives;
                    budgetValueArr =data.budgetVal;
                    mtdPreValueArr =data.mtdLastVal;
					mtdThisMonthValueArr=data.mtdThisMonthVal;
					yearToDayPreValueArr =data.yearToDayLastVal;
					budgetRoomNightsThisMonthValueArr =data.budgetRoomNightsThisMonthVal
                    mtdThisValueArr =data.mtdThisVal;
                    ytdPreValueArr =data.ytdLastVal;
                    ytdThisValueArr =data.ytdThisVal;
					hotelNameValueArr =data.hotelNameVal;
                    mtdVisits = data.mtdVisits;
                    ytdVisits = data.ytdVisits;
                    mtdRateletters = data.mtdRateLetters;
                    ytdRateletters = data.ytdRateLetters;
                    mtdTotalExpense = data.mtdTotalExpense;
                    ytdTotalExpense = data.ytdTotalExpense;
                    totalGoneYtd = data.totalDaysGoneYtd;
                    totalGoneMtd = data.totalDaysGoneMtd;
                    datePeriod = data.datePeriod;
                    stacked = data.stacked;
					
					budgetRoomNightsValuesArr=data.budgetRoomNightsValues;
                    
					yearToDayHotelPrevYearValueArr= data.yearToDayHotelPrevYearVal;
					budgetHotelRoomNightsValueArr= data.budgetHotelRoomNightsVal;
					achievedHotelValueArr= data.achievedHotelVal;
					
					mtdHotelPrevYearValueArr= data.mtdHotelPrevYearVal;
					budgetHotelRoomNightsThisMonthValueArr= data.budgetHotelRoomNightsThisMonthVal;
					mtdHotelThisMonthValueArr= data.mtdHotelThisMonthVal;
					
					
					achievedHotelValuePrveYEARValueArr	= data.achievedHotelValuePrveYEARVal;
					achievedHotelValuesValueArr			= data.achievedHotelValueVal;
					achievedHotelValuePrveYEARMonthValueArr= data.achievedHotelValuePrveYEARMonthVal;
					achievedHotelValueThisMonthValueArr	= data.achievedHotelValueThisMonthVal;
					budgetHotelValueCurrentYEARValueArr	= data.budgetHotelValueCurrentYEARVal;
					budgetHotelValueThisMonthValueArr		= data.budgetHotelValueThisMonthVal;

                   
					budgetValueCurrentYEARValueArr	= data.budgetValueCurrentYEARVal
					budgetValueThisMonthValueArr= data.budgetValueThisMonthVal
					achievedValueYEARMonthValueArr= data.achievedValueYEARMonthVal
					achievedValueThisMonthValueArr= data.achievedValueThisMonthVal
					achievedValuePrveYEARValueArr= data.achievedValuePrveYEARVal
					achievedValueCurrentYearValueArr= data.achievedValueCurrentYearVal

							



					
                    //budgetChartFun(graphCount);
                   	//$(".showPeriod").html(data.SummaryHedding+' For Period '+data.reportPeriod);
                    let salesTable='<h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>'+data.SummaryHedding+' For Period '+data.reportPeriod+'</strong></h4><table class="table table-striped text-center"><tr style="color:white;"><th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive</th><th style="background-color:#3C8DBC;" colspan="4">Month To Date</th><th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="5">Year To Date</th></tr><tr style="background-color:#5cb4e8;"><th >Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th style="border-left:1px solid #252525;">Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th>Yearly Budget</th></tr>';

                    for(let i=0;i<exeNameArr.length;i++){
                        salesTable+='<tr ><td style="text-align:left;">'+exeNameArr[i]+'</td><td>'+mtdVisits[i]+'</td><td>'+mtdRateletters[i]+'</td><td>'+mtdTotalExpense[i]+'</td><td>'+(mtdVisits[i]/totalGoneMtd).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits[i]+'</td><td>'+ytdRateletters[i]+'</td><td>'+ytdTotalExpense[i]+'</td><td>'+(ytdVisits[i]/totalGoneYtd).toFixed(2)+'</td><td>'+budgetValueArr[i]+'</td></tr>';
                    }
                    salesTable+='<tr style="font-weight:bold;"><td style="text-align:left;">Total</td><td>'+mtdVisits.reduce(sumofArray)+'</td><td>'+mtdRateletters.reduce(sumofArray)+'</td><td >'+mtdTotalExpense.reduce(sumofArray)+'</td><td>'+(mtdVisits.reduce(sumofArray)/(totalGoneMtd*exeNameArr.length)).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits.reduce(sumofArray)+'</td><td>'+ytdRateletters.reduce(sumofArray)+'</td><td>'+ytdTotalExpense.reduce(sumofArray)+'</td><td>'+(ytdVisits.reduce(sumofArray)/(totalGoneYtd*exeNameArr.length)).toFixed(2)+'</td><td>'+budgetValueArr.reduce(sumofArray)+'</td></tr>';

                    salesTable+='</table>';

                   // $("#salesChartWrapper").html(salesTable);
                   //  $("#loading").hide();
                    //  $(".showPeriod").html('  '+data.reportPeriod);//From Period
					//$(".showPeriodMonth").html('  '+data.reportPeriodMonth);
					 //performanceChart(graphCount); 
					// alert(data.reportPeriod);
                   
                                     
                   $("#salesChartWrapper").html(salesTable);
                    
                                       
                    graphCount++;
                    //$("#SummaryDataloading").hide();
                   $("#loading").hide();
					}else{
					    alert('No Record Found');
					    $("#salesChartWrapper").html('No Record Found');
						$("#loading").hide();
						
						}	
				}
				
				
				
				
				
				
				
			})
	
        

	}
window.onload = function() {
    getfinancialyear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
    getCompareYear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
     $('.toHideWholeDiv').hide();
      $('.toHidedownloadPDF').hide();
     
        fetchPerformanceGraphData(1);
}
	function getfinancialyear(year,Currentfinancialyear){
	    var year = $("#financialyearselected").val();
	    //alert(Currentfinancialyear);
	    //alert(year);
	    
	   
	    if(Currentfinancialyear==year){
	        
	         $("#chartCompleteView").show();
	          $("#dateColor12").show();$("#dateColor13").show();$("#dateColor14").show();
	    }else{
	        $("#chartCompleteView").hide();
	        $("#dateColor12").hide(); $("#dateColor13").hide(); $("#dateColor14").hide();
	    }
	    
	    
	    $("#loading").show();
	    reportTypeFile  ='ajaxfinancialyearsplit.php';
	   	$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'financialyear='+year,
				success:function(data){
				
                   let datas = JSON.parse(data);
                   
                    $("#per_report_date").val(datas.per_report_date);
                    $("#dateColor6").val(datas.Q1_APR_JUNE);
                    $("#dateColor7").val(datas.Q2_JULY_SEP);
                    $("#dateColor8").val(datas.Q3_OCT_DEC);
                    $("#dateColor9").val(datas.Q4_JAN_MARCH);
                    
                    $("#dateColor10").val(datas.H1_APR_SEP);
                    $("#dateColor11").val(datas.H2_OCT_MARCH);
                    
                    
    
                    
                    
                    
                    
                    
                    //$("#per_report_date").val(datas.per_report_date);
                    
                   // $("#loading").hide();
                    
					
				}
			}) 
	}
  function getCompareYear(CompareYear,Currentfinancialyear){	

 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxgetCompareYear.php',
			   data: 'CompareYear='+CompareYear+'&Currentfinancialyear='+Currentfinancialyear, 
			   success: function (result) {				   
			     $('#CompareYearselected').empty();
				 $('#CompareYearselected').html(result);
				 
        let ComparePeriod = $("#CompareYearselected").val();
        
        var res = ComparePeriod.split("-");
        var FinanceComparePeriodStarYear = res[0];
        var FinanceComparePeriodEndYear = res[1];
        let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
	    
	    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
                    
				 
				}
		});
  }
  
   function getCompareYearTwo(CompareYear,Currentfinancialyear){	

 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxgetCompareYear.php',
			   data: 'CompareYear='+CompareYear+'&Currentfinancialyear='+Currentfinancialyear, 
			   success: function (result) {				   
			     $('#CompareYearselected').empty();
				 $('#CompareYearselected').html(result);
				 
        let ComparePeriod = $("#CompareYearselected").val();
        
        var res = ComparePeriod.split("-");
        var FinanceComparePeriodStarYear = res[0];
        var FinanceComparePeriodEndYear = res[1];
        let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
	    
	    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
                    
		updateDateQuickSearchYear(CompareYear,1)		 
				}
		});
		
  }
//  class="" id="showloadingDownload"


function saveAsPDF2() {
 // document.getElementById("#showloadingDownload").style.display = 'block';  

    saveAsPDF();

    // document.getElementById("#showloadingDownload").style.display = 'none'; 
}
  function saveAsPDF() { 
   
   html2canvas(document.getElementById('chart-container'), {
      onrendered: function(canvas) {
         var d = new Date();
         let filename1 =  'DashboardChart_'+d;
         var imgData = canvas.toDataURL('image/png');

          /*
          Here are the numbers (paper width and height) that I found to work. 
          It still creates a little overlap part between the pages, but good enough for me.
          if you can find an official number from jsPDF, use them.
          */
          var imgWidth = 210; 
          var pageHeight = 295;  
          var imgHeight = canvas.height * imgWidth / canvas.width;
          var heightLeft = imgHeight;

          var doc = new jsPDF('p', 'mm');
          var position = 0;

          doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;

          while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
          }
          doc.save(filename1 + '.pdf');
         
          
      }
      
      
      
   });
   //$(".toHideloadingDownload").hide();
    
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

