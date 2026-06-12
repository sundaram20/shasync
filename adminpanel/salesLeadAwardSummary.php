<?php 
include_once("../config/auto_loader.php");

?>
<?php include_once("includes/header.php")?>
  <?php include_once("includes/left.php")?>
  <div class="content-wrapper">
    <section class="content-header">
      <h4>Dashboard</h4>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <section class="content" style="min-height:0px; padding-bottom:0px;">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <?php 
            			if (date('m') <= 6) {//Upto June 2014-2015
    $financial_year =(date('Y')-1) . '-' .date('Y');
} else {//After June 2015-2016
    $financial_year = (date('Y')) . '-' . (date('Y') + 1);
}
	

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
	
	
	
	//debugData($Values);
	$Values['per_report_date'] =date('01-04-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);
	
	
			$sql_team = "SELECT id,name FROM ".TBL_TEAM." WHERE id IN (".$_SESSION['teamId'].") and id!=8 ORDER BY name";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              
              <div class="col-md-3">
                <div class="form-group">
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="per_report_date" name="per_report_date" value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
                </div>
              </div>
              
              <input type="hidden" name="date_period" id="date_period" value="" />
              <input type="hidden" name="ptype" id="ptype" value="" />
              
              
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
                <a  onclick="fetchLeadPerformance();" class="btn btn-info form-control">Search</a> </div>
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
               <?php /*?> <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a> 
              <a href="#" id="downloadPdf">Download Report Page as PDF</a><?php */?>
              
                </div>
                
              
            </div>  
                
              <div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="loading">
              <div class="overlay">
                <div class="overlay__inner">
                  <div class="overlay__content"><span class="spinner"></span></div>
                </div>
              </div>
              
              <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...--></span> 
                 
                    
                    
                    
        </div>
            </div>
            
            
            
          </div>
          
        
        <!-- /.box-body -->
        
       
        
      </div>
    </section>
    <section class="content" style="padding: 0px 15px;">
    	   <!--------------------------Lead Star--------------------------------------------------------> 
       
         <div id="blk-SalesSummary" class="toHide" >
      	<div class="row">
        	<div class="col-md-12">
            
                    <div id="salesChartWrapper" style="padding:0px 10px 0px 10px;">
                        
                    </div>
                    
                    
                   

            <div  id="LeadSummaryCon" style="display:none;" > 
             
              <div class="row">
              
              <div class="form-group col-sm-2" style="float:right;" >
                <div class="box-header with-border">
                  <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a title="Export to excel file" onclick="downloadSummaryPdf(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
                      <li><a title="Export to PDF file" onclick="downloadSummaryPdf(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
                        <li><a title="Export to excel file" onclick="downloadSummaryPdfDetails(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel With Detail</a></li>
                      <li><a title="Export to PDF file" onclick="downloadSummaryPdfDetails(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf With Detail</a></li>
                    </ul>
                  </div>
                </div>
                
                <!--  <a  onclick="downloadSummaryPdf();" class="btn btn-warning form-control">Download</a> --> 
              </div>
              
                <div class="col-md-12" >
                <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#leadAwardSummary" aria-controls="home" role="tab" data-toggle="tab"  onclick="fetchLeadPerformance();">Overview </a></li>
                      <li role="presentation"><a href="#H1_APR_SEP" aria-controls="profile" role="tab" data-toggle="tab"   onclick="loadleadAward('<?php echo $Values['H1_APR_SEP'];?>','1');">
                H1 (APR-SEP)
                </a></li>
                      <li role="presentation"><a href="#H2_OCT_MARCH" aria-controls="profile" role="tab" data-toggle="tab" data-target="#modal-warning" onclick="loadleadAward('<?php echo $Values['H2_OCT_MARCH']?>','2');">  H2 (OCT-MARCH) </a></li>
                      
                    </ul>
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="leadAwardSummary"></div>
                      
                      <div role="tabpanel" class="tab-pane " id="H1_APR_SEP">
                        
                        
                      </div>
                      
                      
                      
                      </div>
                      
                  </div>
                   </div>         
             <!--start of lead summary award1-->
           
      <!--end of lead award1-->       
                    <hr>
                    
             </div></div>
             
             
             
             
                   
       
                     
                    </div>
                    
                 
       </div> 	
       
    <!--------------------------Executive Wise END-------------------------------------------------------->     
    
    <!--------------------------Hotel Wise Wise Start--------------------------------------------------------> 
          
      <!--------------------------Hotel Wise Wise End--------------------------------------------------------> 
        
    </section>
    
<style>
.overlay {
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

  

   </script>


  </div>
  <?php include_once("includes/footer.php")?>
<script type="text/javascript">



  

$(function() {
    $("[name=toggler]").click(function(){
		
		
		alert($("#blk-"+$(this).val()));
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
    });
 });
 
 

      
function fetchLeadPerformance(){
		var per_report_date	=	$("#per_report_date").val();
		var id_team	=	$("#id_team").val();
		var date_period='';
		var ptype='';
		$("#date_period").val(date_period);
		$("#ptype").val(ptype);
		
        $("#loading").show();
			
			
			$.ajax({
				url:'ajax/ajaxLeadAwardSummaryPeriod.php',
				type:'POST',
				data:'per_report_date='+per_report_date+'&id_team='+id_team+'&date_period='+date_period+'&ptype='+ptype+'&leadSummaryType=2',
				success:function(data){
					$("#LeadSummaryCon").show();
					$("#leadAwardSummary").html(data);
					$("#loading").hide();
					}
			})
		
		
        //leadGraphData();
        

	
		
		
		}

    
function loadleadAward(date_period,ptype){
	
	
	$("#date_period").val(date_period);
			$("#ptype").val(ptype);
	
	
		var per_report_date	=	$("#per_report_date").val();
		var id_team	=	$("#id_team").val();
        $("#loading").show();
			
			
			$.ajax({
				url:'ajax/ajaxLeadAwardSummaryPeriod.php',
				type:'POST',
				data:'per_report_date='+per_report_date+'&id_team='+id_team+'&date_period='+date_period+'&ptype='+ptype+'&leadSummaryType=2',
				success:function(data){
					$("#LeadSummaryCon").show();
					//alert(ptype);
					$("#H1_APR_SEP").html(data);
					$("#loading").hide();
					}
			})
		

	
		
		
		}

   downloadSummaryPdf = (pdf,excel) => {
    		var date_period	=	$("#date_period").val();
			var ptype	=	$("#ptype").val();
      
			var per_report_date	=	$("#per_report_date").val();
			var id_team	=	$("#id_team").val();
            let filename1 =  'ajaxLeadAwardSummaryPeriod';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&per_report_date='+per_report_date+'&id_team='+id_team+'&date_period='+date_period+'&ptype='+ptype+'&leadSummaryType=2&status=download';
            window.open(url1);
        
	
	
	
        
        
        
   }
   
    downloadSummaryPdfDetails = (pdf,excel) => {
    		var date_period	=	$("#date_period").val();
			var ptype	=	$("#ptype").val();
      
			var per_report_date	=	$("#per_report_date").val();
			var id_team	=	$("#id_team").val();
            let filename1 =  'ajaxLeadAwardSummaryPeriod';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&per_report_date='+per_report_date+'&id_team='+id_team+'&date_period='+date_period+'&ptype='+ptype+'&leadSummaryType=2';
            window.open(url1);
        
	
	
	
        
        
        
   }
</script>