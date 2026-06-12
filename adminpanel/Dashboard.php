<?php include_once("../config/auto_loader.php"); 

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
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php 
						$a_date = date("d-m-Y");
	// $year = ( date('m') > 6) ? date('Y') + 1 : date('Y');

if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceStarYear=date('Y');
	if(date('Y')==$FinanceStarYear){
	$FinanceEndYear=date('Y');
	}else{
		$FinanceEndYear=(date('Y') +1);
	}
}elseif(date('m') <=3){$FinanceStarYear=(date('Y')-1);$FinanceEndYear=(date('Y'));}
else {
    $year = (date('Y')-1)."-".date('Y');
	$FinanceStarYear=(date('Y')-1);
	if(date('Y')==$FinanceStarYear){
	$FinanceEndYear=date('Y');
	}
}
if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceEndYear=(date('Y') +1);
}
else {
    $year = (date('Y')-1)."-".date('Y');
}
 // 2015-2016
//echo date("t-m-Y", strtotime($a_date));

$previousmonthStart= date("Y-n-j", strtotime("first day of previous month"));
$previousmonthEnd = date("Y-n-j", strtotime("last day of previous month"));

$LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
$crs_sales_both_active  = selectColumn(TBL_SHOP,'crs_sales_both_active'," WHERE id= '".addslashes($_SESSION['shop'])."'");


if($crs_sales_both_active==1){ ?>
    
    <div class="form-group col-sm-4">
                
                
              <label>Group</label>
              <?php 
            				$sql_team = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status=1 and id_shop='".$_SESSION['shop']."' ORDER BY display_order";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <select class="select2 form-control" name="id_group_master" id="id_group_master" Onchange="updateDateQuickSearchHotel();">
                <option value="0">All Group Without Unit</option>
                <option value="10000">All Group With Unit</option>
                <?php while($objHot=mysqli_fetch_object($res_team)){
						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						echo "<option ".$selected." value='".$objHot->id."'>".$objHot->name."</option>";
					} ?>
              </select>
            </div>
    
    <?php }else{ ?>

            <div class="form-group col-sm-4">
                
                
              <label>Team</label>
              <?php 
            				$sql_team = "SELECT id,name FROM ".TBL_TEAM." WHERE id IN (".$_SESSION['teamId'].") ORDER BY name";
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
            
<?php } ?>            
            <div class="form-group col-md-8">
               
                <label>
          <input type="radio" id="reportType1"   name="reportType" value="1" checked="checked" onclick="updatereportType(this.value);ReportTypePickupBob();"/>
          Pickup Based </label> 
               <label>
          <input type="radio" id="reportType2"   name="reportType" value="2"  onclick="updatereportType(this.value);ReportTypePickupBob();" />
          BOB Based </label>
          
         
          
          
          
              
              <div class="box-bodyw">
                <button type="button" style="margin-right: 5px;" class="btn bg-default mobile-today" id="dateColor1" type="radio"   name="toggler" value="1" onclick="updateDateQuickSearch('<?php echo date("d-m-Y").' to '.date("d-m-Y");?>','0');">
                Today
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default" id="dateColor2" type="radio"   name="toggler" value="2" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-1 days')).' to '.date('d-m-Y', strtotime('-1 days'));?>','0');">
                Yesterday
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default" id="dateColor3" type="radio"   name="toggler" value="3" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-6 days')).' to '.date("d-m-Y");?>','0');">
                Last 7 Days
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor4" type="radio"   name="toggler" value="4" onclick="updateDateQuickSearch('<?php echo date("01-m-Y").' to '.date("d-m-Y",strtotime($LastDateCurrentmonth));?>','0');">
                This Month
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor5" type="radio"   name="toggler" value="5" onclick="updateDateQuickSearch('<?php echo date('d-m-Y', strtotime($previousmonthStart)).' to '.date('d-m-Y', strtotime($previousmonthEnd));?>','0');">
                Last Month
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-foursquare  mobile-responseset" id="dateColorFinancialYear" type="radio"   name="toggler" value="FinancialYear" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("01-04-".$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);?>','1');">
                This Year
                </button>
                <button type="button" class="btn btn-default mobile-customrange"  id="dateColorCustomRangeBookingPeriod" type="radio"   name="toggler" data-target="#modal-success"  value="CustomRangeBookingPeriod">
                Custom Range
                </button>
              </div>
              <?php /*?>
	      <div class="form-group">
                <label>
          <input id="rdb2" type="radio"   name="toggler" value="SalesSummary" checked="checked" />
          Booking Report</label> 
                  <label>
          <input id="rdb1" type="radio"   name="toggler" value="ExecutiveWise"   />
          Month Wise</label>
          <label>
          <input id="rdb2" type="radio"   name="toggler" value="HotelWise" />
          Hotelwise Summary</label>
         
          
          
          </div><?php */?>
          
          
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
            <div class="box-bodyw">
                <button type="button" style="margin-right: 5px;" class="btn btn-foursquare  col-md-3" id="rdb21"    name="CharSummarytoggler" value="SalesSummary" >
                <i class="fa fa-bar-chart"></i>&nbsp;Chart View
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-4" id="rdb22"    name="CharSummarytoggler" value="CustomRangeBookingPeriod2" >
                <i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;Table View 
                </button>
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-4" id="rdb23"    name="CharSummarytoggler" value="CompareRangeBookingPeriod2" >
                <i class="fa fa-exchange"></i>&nbsp;Compare 
                </button>
                
                
                </div>
             </div>  
                <input type="hidden" name="SelectedreportType" id="SelectedreportType" value="1">
                <input type="hidden" name="SelectedViewType" id="SelectedViewType" value="1">
                <input type="hidden" name="SelectedSummaryViewType" id="SelectedSummaryViewType" value="7">
                <input type="hidden" name="SelectedCompareType" id="SelectedCompareType" value="1">
                <input type="hidden" name="SelectedMonthView" id="SelectedMonthView" value="1">
                
               <div id="blk-CustomRangeBookingPeriod2" class="toHideCharSummarytoggler" style="display:none" >
                    <div class="box-body table-responsive"> 
                     <div class="box-bodyw">
                
                <button type="radio" style="margin-right: 5px;" class="btn btn-foursquare margin mobileSummary-today" id="rdb17"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','7');">
                Team Wise 
                </button>
                <!-- <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb16"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','6');">
                Business Source 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb15"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','5');">
                Booking Source 
                </button>-->
                <button type="radio" style="margin-right: 5px;" class="btn bg-default  margin mobileSummary-today" id="rdb11"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','1');">
                Executivewise 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb12"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','2');">
                Hotelwise 
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb19"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','9');">
                Pace
                </button>
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="rdb18"    name="SummaryReportRadio" value="SalesSummary" onclick="updateDateQuickForSummarySearch('0','8');">
                Top 100 Agent
                </button>
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
              
              <div class="form-group col-sm-2" >
                    <label for="">&nbsp;</label>
                     <a  onclick="downloadSummaryPdf();" class="btn btn-warning form-control">Download</a> 
            </div>
       </div>
    </div>     
            
          <div id="blk-CompareRangeBookingPeriod2" class="toHideCompareSummarytoggler" style="display:none" >
                    <div class="box-body table-responsive"> 
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
                <button type="radio" style="margin-right: 5px;" class="btn bg-default margin mobileSummary-today" id="compare4"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','4');">
                Top 100 Agent
                </button>
                
                
                
                
              </div>
              <div class="form-group col-sm-2"  id="hidedownloadCompare" >
                    <label for="">&nbsp;</label>
                     <a  onclick="downloadComparePdf();" class="btn btn-warning form-control">Download</a> 
            </div>
              
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
          <div id="WholeDivshowContent" class="toHideWholeDiv" style="display:none">
          <div id="performanceChart" class="toHideperformanceChar">
         <div id="blk-FinancialYear" class="toHide" >
            <h4 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span> - Month Wise Report <span class="showPeriodChart"> </span> </strong></h4>
            <div class="row">
              <div class="col-md-12" >
                <p class="text-center"> <strong class="col-lg-offset-2">Room Nights (Month Wise) </strong>
                <div class="row">
                 <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>
                </p>
                <div class="chart ">
                  <canvas id="line-chart" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
              <div class="col-md-12" >
                <p class="text-center"> <strong class="col-lg-offset-2">Revenue (Month Wise in Lacs)</strong>
                <div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>
                </p>
                <div class="chart ">
                  <canvas id="line-chart-revenue" <?php echo $HeightWidth; ?>></canvas>
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
                <div class="row">
                    <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                    <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                    <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>
                </p>
                <div class="chart ">
                  <canvas id="line-chart-mat" width="800" height="450"></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
            <div class="col-md-6" >
                <p class="text-center"> <strong class="col-lg-offset-2">Revenue (MAT in Lacs)</strong>
                <div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>
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
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Average Room Revenue Report</strong></h4>
           
                <p class="text-center"> <strong class="col-lg-offset-2">ARR </strong>
                <div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>
                </p>
                <div class="chart ">
                  <canvas id="line-chart-revenue-arr" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
              </div>
              
              <!--<div  id="blk-BookingSourceChart" class="toHideBookingSourceChart" style="display:none">
              <div class="col-md-12" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Booking Source Report</strong></h4>
           
                
                <div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  </div>
                </p>
                <div class="chart ">
                  <canvas id="BookingSourceChart" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
                </div>-->
              <!-- /.col -->
              
              
              <div class="col-md-12" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Room Nights Segment Wise</strong></h4>
            
                <p class="text-center"> <strong class="col-lg-offset-2"> </strong>
               
                </p>
                <div class="chart ">
                  <canvas id="RoomNightPie" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
              <!-- /.col --> 
            </div>
            
            
            <div class="row">
              <div class="col-md-12" >
              <h4 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Business Source Wise <span class="showPeriodChart"> </span></strong></h4>
           
                <!--<p class="text-center"> <strong class="col-lg-offset-2">ARR </strong>-->
                <div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <!--<div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>-->
                </div>
                </p>
                <div class="chart ">
                  <canvas id="bar-chart-company-arr" <?php echo $HeightWidth; ?>></canvas>
                </div>
                </a> </div>
              <!-- /.col -->
              
              <!-- /.col --> 
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

  downloadSummaryPdf = () => {
    
       var id_hotel = $("#id_hotel").val();
	   var period = $("#per_report_date").val();
	var id_group_master = $("#id_group_master").val();
	var reportType = $("#SelectedreportType").val();
	var summaryReportType = $("#SelectedSummaryViewType").val();
	if(summaryReportType=='8'){
            let filename1 =  'DashboardagentTop25';
             let url1 = 'ajax/'+filename1+'.php?pdf=1&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&summaryReportType=8';
            window.open(url1);
        }else if(summaryReportType=='9'){
             let filename1 =  'DashboardpaceReport';
             let url1 = 'ajax/'+filename1+'.php?pdf=1&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&summaryReportType=8';
            window.open(url1);
            
        }else{
            let filename = 'DashboardExecutiveSummaryData-1';
            let url = 'ajax/'+filename+'.php?pdf=1&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&summaryReportType=15&CronSet=0';
            window.open(url);
        }
	
	
	
        
        
        
   }
   downloadComparePdf = () => {
    
       var id_hotel = $("#id_hotel").val();
	   
	var period = $("#per_report_date").val();
	var summaryReportType = $("#SelectedCompareType").val();
	var reportType = $("#SelectedreportType").val();
var CompareTypedownload = $("#SelectedCompareType").val();
var id_group_master = $("#id_group_master").val();
	if(CompareTypedownload=='4'){
        let url1 = 'ajax/DashboardCompareagentTop25.php?pdf=1&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType;
         window.open(url1);
	}else{
	   let url = 'ajax/DashboardCompareSummaryData-1.php?pdf=1&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType; 
	   window.open(url);
	}
        
   }
   

function updatereportType(value){
    
    $( "#SelectedreportType" ).val(value); 
    if(value==1){
        
        $("#rdb19").show(); 
    }else{
         $("#rdb19").hide(); 
    }
    
    
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
    $("[name=toggler]").click(function(){
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
           
            if($(this).val()=='FinancialYear'){
                $("#blk-showMatChar").show();
                $("#blk-AverageRoomRevenueChar").show();
                $('.toHideBookingSourceChart').hide();
                
            }else{ 
                $('.toHideshowMatChar').hide();
                $('.toHideAverageRoomRevenueChar').hide();
                $("#blk-BookingSourceChart").show();
            }
            
            //dateColorFinancialYear
            document.getElementById("dateColor1").className = "btn bg-default mobile-today";  
            document.getElementById("dateColor2").className = "btn bg-default";  
            document.getElementById("dateColor3").className = "btn bg-default";  
            document.getElementById("dateColor4").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor5").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColorFinancialYear").className = "btn bg-default  mobile-responseset";
            document.getElementById("dateColorCustomRangeBookingPeriod").className = "btn bg-default mobile-customrange"; 
            if($(this).val()=='FinancialYear' || $(this).val()=='4' ){
                var classValue= 'mobile-responseset';
            
            }else if($(this).val()=='1'){
                var classValue= 'mobile-today';
            }else if($(this).val()=='CustomRangeBookingPeriod'){
                var classValue= 'mobile-customrange';
            }else{
                
               var classValue= ''; 
            }
            
            document.getElementById("dateColor"+$(this).val()).className = "btn btn-foursquare "+classValue;
            
    });
    
    $("[name=CharSummarytoggler]").click(function(){
            $('.toHideCharSummarytoggler').hide();
            $('.toHideCharSummarytogglerContent').hide();
            
            if($(this).val()=='CustomRangeBookingPeriod2'){
               
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod3").show();
                $('#performanceChart').hide();
                $("#blk-CompareRangeBookingPeriod2").hide();
                $("#blk-CompareRangeBookingPeriod3").hide(); 
               $( "#SelectedViewType" ).val('0'); //TableView
               document.getElementById("rdb21").className = "btn btn-default col-md-4";
               document.getElementById("rdb23").className = "btn btn-default col-md-4";
               document.getElementById("rdb22").className = "btn btn-foursquare col-md-3";
               
document.getElementById("rdb17").className = "btn bg-default margin btn-foursquare mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";



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
                     
               document.getElementById("rdb21").className = "btn btn-default col-md-4";
               document.getElementById("rdb22").className = "btn btn-default col-md-4";
               document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
            }else{
               
              var SelectedMonthView = $("#SelectedMonthView").val();
              fetchPerformanceGraphData(SelectedMonthView);
              $('#performanceChart').show(); 
              
              $("#blk-CompareRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod3").hide(); 
              $( "#SelectedViewType" ).val('1'); //ChartView'
              
              document.getElementById("rdb22").className = "btn btn-default col-md-4";
              document.getElementById("rdb23").className = "btn btn-default col-md-4";
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
document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today"
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

document.getElementById("compare"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedCompareType" ).val(summaryReportType);
 
 if(summaryReportType=='2'){
//$("#hidedownloadCompare").hide();
}else{
  $("#hidedownloadCompare").show();  
}
 
            fetchCompareReportData(viewMonthwise,summaryReportType);
	}	
	
function SalesSummaryDateQuickForSearch(viewMonthwise,summaryReportType){

//	$( "#per_report_date" ).val(quickDate);
//	$( "#per_report_date" ).val(quickDate);
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb16").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb17").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb1"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedSummaryViewType" ).val(summaryReportType);
SalesSummaryDateQuickForSearchfunction(viewMonthwise,summaryReportType);
	//fetchPerformanceGraphData(viewMonthwise);
	}
   downloadPdf = () => {
       var hotel_id = $("#id_hotel").val();
	   var id_group_master = $("#id_group_master").val();
	var reservation_date = $("#per_report_date").val();
	var reportType = $("#reportType").val();
        let url = 'ajax/DashboardajaxDownloadBookings.php?reservation_bookingDate='+reservation_date+'&reportType='+reportType+'&id_hotel='+hotel_id+'&id_group_master='+id_group_master+'';
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
var BookingSourceNameArray= [];
var	BookingSourceCurrentYearValue= [];
var rowBookingSourceLastYearValue= [];
var SegmentWiseListLastYearArrayValue =[];
    function fetchPerformanceGraphData(viewMonthwise){
        $("#loading").show();
        
	
		$('.toHideWholeDiv').hide();
		let period = $("#per_report_date").val();
		
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
			var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		var id_group_master = $("#id_group_master").val();
	
        if(reportType==1){
            reportTypeFile  ='DashboardfetchPerformanceGraphData.ajax-test.php';
        }else{
            reportTypeFile  ='DashboardfetchPerformanceGraphDataBOB.ajax.php'; //reportType =2 BOB
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
				data:'period='+period+'&id_hotel='+id_hotel+'&id_team='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise,
				success:function(data){
						$('#WholeDivshowContent').show();
                    
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
                   
BookingSourceNameArray=    data.BookingSourceNameArray
BookingSourceCurrentYearValue=    data.BookingSourceCurrentYearValue
rowBookingSourceLastYearValue=    data.rowBookingSourceLastYearValue
                   
                   mtdThisCustomeLastYearReportValues=    data.mtdThisCustomeLastYearReportValues;
                   mtdRoomCustomeLastYearReportRevenue=    data.mtdRoomCustomeLastYearReportRevenue;
                   SegmentWiseListLastYearArrayValue    =data.SegmentWiseListLastYearArray;
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
            
            CompanyGroupBarChart.destroy();
		   //BookingSourceBarChart.destroy();
          monthlyRoomNightPieChart.destroy();//Segment
          
           monthlyBarChart.destroy();
		    monthlyBarRevenueChart.destroy();
		    monthlyBarLineChart.destroy();
		   monthlyBarRevenueLineChart.destroy();
		   monthlyLineARRChart.destroy();
		   //mtdChart.render();
        }

        let mtdPerChartHeader = document.getElementById('mtd-per-chart').getContext('2d'); 
       let mtdRevenuePerChart = document.getElementById('mtdRevenuePerChart').getContext('2d');
	
        let monthRoomNightPiePerChart = document.getElementById('RoomNightPie').getContext('2d'); //Segment
         let CompanyGroupBarPerChart = document.getElementById('bar-chart-company-arr').getContext('2d');
		//let BookingSourceBarPerChart = document.getElementById('BookingSourceChart').getContext('2d');
		
		let monthBarPerChart = document.getElementById('line-chart').getContext('2d'); 
		let monthRevenueBarPerChart = document.getElementById('line-chart-revenue').getContext('2d'); 		
		let monthBarLinePerChart = document.getElementById('line-chart-mat').getContext('2d'); 
		let monthRevenueBarLinePerChart = document.getElementById('line-chart-revenue-mat').getContext('2d');
		let monthRevenueARRLinePerChart = document.getElementById('line-chart-revenue-arr').getContext('2d');

mtdChart = new Chart(mtdPerChartHeader, {
            type: 'bar',
            data: {
                labels: CustomeReportValuesNameData,
                datasets: [{
                    label: 'Last Year : '+mtdThisCustomeLastYearReportValues+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: mtdThisCustomeLastYearReportValues
                },
                {
                    label: 'This Year : '+mtdThisCustomeReportValues+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
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
                    label: 'Last Year : '+mtdRoomCustomeLastYearReportRevenue.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
					margin: 1,
                    data: mtdRoomCustomeLastYearReportRevenue
                },
                {
                    label: 'This Year : '+mtdRoomCustomeReportRevenue.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(60,141,188,0.8)',
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
        
        
     //Segment Wise   
  monthlyRoomNightPieChart = new Chart(monthRoomNightPiePerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: OfferNameArray,
                datasets: [{
                    label: 'Last Year : '+SegmentWiseListLastYearArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: SegmentWiseListLastYearArrayValue
                },
                {
                    label: 'This Year : '+rowOfferListArray.reduce(sumofArray)+'',
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
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});      
   //Segment Wise End     


 /*BookingSourceBarChart = new Chart(BookingSourceBarPerChart, {
            type: 'bar',
            data: {
                labels: BookingSourceNameArray,
                datasets: [{
                    label: 'Last Year : '+rowBookingSourceLastYearValue.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: rowBookingSourceLastYearValue
                },
                {
                    label: 'This Year : '+BookingSourceCurrentYearValue.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: BookingSourceCurrentYearValue
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
        });*/
        
  CompanyGroupBarChart = new Chart(CompanyGroupBarPerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: companygroupNamearray,
                datasets: [{
                    label: 'Last Year : '+CompanyGroupListLastYearArray.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: CompanyGroupListLastYearArray
                },
                {
                    label: 'This Year : '+companygroupDatalist.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: companygroupDatalist
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

 if(viewMonthwise==1){      
monthlyBarChart = new Chart(monthBarPerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: monthNameData,
                datasets: [{
                    label: 'Last Year : '+MonthWiseRoomNightsLastYearData.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRoomNightsLastYearData
                },
                {
                    label: 'This Year : '+MonthWiseRoomNightsData.reduce(sumofArray)+'',
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
        label: 'Last Year : '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1
      }, { 
        data: MonthWiseRevenueCurrentYearData,
        label: 'This Year : '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
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

//===================BAR Chart For Moth Wise=EnD==============================	

//===================BAR Chart For Moth Wise=EnD==============================

//LINE CHART ===========
 monthlyBarLineChart = new Chart(monthBarLinePerChart, {
  type: 'line',
  	// The data for our dataset
            data: {
                labels: monthNameData,
                datasets: [{
                    label: 'Last Year : '+MonthWiseRoomNightsLastYearData.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
					fontColor: "#981C1E",
					margin: 1,
                    data: ytdAllHotelValuesMat
                },
                {
                    label: 'This Year : '+MonthWiseRoomNightsData.reduce(sumofArray)+'',
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
        label: 'Last Year : '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1,
		data: ytdPrevYearRevenueDataMat
      }, { 
        
        label: 'This Year : '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
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
 type: 'line',		 
  //type: 'line',
  data: {
    labels: monthNameData,
    datasets: [ { 
    //MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)     
      //  label: 'Last Year : '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
      backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1,
		data: mtdRoomRevenueLastYearData
      },{ 
        data: mtdRoomRevenueArr,
        //label: 'This Year : '+MonthWiseRevenueCurrentYearDataMat.reduce(sumofArray).toFixed(2)+'',
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
        $('.toHideWholeDiv').hide();
        fetchPerformanceGraphData(1);
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
		var id_group_master = $("#id_group_master").val();
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		
        if(summaryReportType==8){
            reportTypeFile  ='DashboardagentTop25.php';
        }else if(summaryReportType==9){
            reportTypeFile  ='DashboardpaceReport.php'
        }else{
            
            reportTypeFile  ='DashboardExecutiveSummaryData-1.php';
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&CronSet=0',
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
		
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		let id_group_master = $("#id_group_master").val();
		
        if(summaryReportType==4){
            reportTypeFile  ='DashboardCompareagentTop25.php';
            
        }else{
            reportTypeFile  ='DashboardCompareSummaryData-1.php';
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType,
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
	let id_group_master = $("#id_group_master").val();
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
				data:'period='+period+'&id_team='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType,
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
	

  
</script>