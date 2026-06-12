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
            				$sql_team = "SELECT id,name FROM ".TBL_TEAM." WHERE id IN (".$_SESSION['teamId'].") ORDER BY name";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <div class="form-group col-sm-4">
                <label>Team</label>
                <select name="id_team" id="id_team" class="select2 form-control">
                  <option value="">Select Team</option>
                  <option value="0">All Team</option>
                  <?php
	            					while($row_team = mysqli_fetch_object($res_team)){

                                        if(explode(',',$_SESSION['teamId'])[0]==$row_team->id){
                                            $selected="selected='selected'"; 
                                        }
                                        else{
                                            $selected=""; 
                                        }

	            						echo '<option '.$selected.' value="'.$row_team->id.'">'.$row_team->name.'</option>';
	            					}
	            				?>
                </select>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="start_date">AS On</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="per_report_date" name="per_report_date" value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
                </div>
              </div>
              
              
              
              
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
                <a  onclick="fetchPerformanceGraphData();" class="btn btn-info form-control">Search</a> </div>
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
               <?php /*?> <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a> 
              <a href="#" id="downloadPdf">Download Report Page as PDF</a><?php */?>
              
                </div>
               <div class="col-md-12">
                <div class="form-group">
                <label>
          <input id="rdb2" type="radio"   name="toggler" value="SalesSummary" checked="checked" />
          Sales Summary</label> 
                  <label>
          <input id="rdb1" type="radio"   name="toggler" value="ExecutiveWise"   />
          Executive Wise</label>
        <!--<label>
          <input id="rdb2" type="radio"   name="toggler" value="HotelWise" />
          Hotel Wise</label>-->
         
          
          
          </div>
              </div> 
                
                
              <div class="col-sm-2">
                <label for="">&nbsp;</label>
                <br>
                <span style="color:red;display:none;" id="loading">Please Wait...</span> </div>
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
            <h4 class="text-center"><strong>Sales Summary<span class="showPeriod"> </span></strong></h4>
                    <div id="salesChartWrapper" style="padding:0px 10px 0px 10px;">
                        
                    </div>
                    
                    
                   

                    
                    
                    <hr>
                    
            <h4 class="text-center"><strong>Lead Summary<span class="showPeriod"> </span></strong></strong></h4>
                    <div class="row" style="padding-bottom:20px;">
                        <div class="col-md-6">
                            <h4 class="text-center"><u>Lead Generated</u></h4>
                            <canvas onclick="drillDown(2);" id="leadChartGen" width="130" height="80"></canvas>
                        </div>
                        <div class="col-md-6" style="border-left: 1px solid #252525;">
                            <h4 class="text-center"><u>Lead Received</u></h4>
                            <canvas onclick="drillDown(1);" id="leadChartAss" width="130" height="80"></canvas>
                        </div>
                    </div>
                    
                    
            
            <!--<div class="box-body">
              <div class="chart">
                <canvas id="barChart" style="height: 230px; width: 510px;" height="230" width="510"></canvas>
              </div>
            </div>-->
            
               <table class="table table-striped text-center" style=" border: 1px solid :#3C8DBC;">
  <tbody>
      <tr style="color:white;">
      <th colspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Sales Sync Numbers</th>
           
    </tr>
    <tr style="color:white;">
      <th style="background-color:#3C8DBC;vertical-align: middle;">Name</th>
      <th style="background-color:#3C8DBC;">Count</th>     
    </tr>
    <?php 
            				$sql_team_user = "SELECT count(user_level) as count, fs_user_levels.name,fs_user_levels.id FROM `fs_users` left join `fs_user_levels` ON  fs_user_levels.id=fs_users.user_level where fs_users.id_shop='".$_SESSION['shop']."' AND fs_users.status='1' and fs_users.user_level!=1   GROUP by fs_users.user_level
							";
            				$res_teamuser = mysqli_query($connNew,$sql_team_user);
            	while($resUserActionsTeam = mysqli_fetch_object($res_teamuser)){		?>
    <tr style=" border: 1px solid :#3C8DBC;">
      <td style="text-align:left;"><?php echo $resUserActionsTeam->name; ?></td>
      <td><?php echo $resUserActionsTeam->count; ?></td>
      
    </tr>
    <?php } ?>
    <tr>
      <td style="text-align:left;">Total Company</td>
      <td><?php echo $count 	= selectColumn(TBL_COMPANY,'count(id_company)'," WHERE   status='1' and id_shop='".$_SESSION['shop']."'   "); ?></td>
      
    </tr>
   <tr>
      <td style="text-align:left;">Total Contacts</td>
      <td><?php $sql_Customeruser = "SELECT count(id_customer) as customer  FROM `fs_company` left join fs_customer On fs_company.id_company=fs_customer.id_company WHERE fs_company.`id_shop` = '".$_SESSION['shop']."' AND fs_company.`status` = 1 and fs_customer.type=2
      
							";
            				$res_Customeruser = mysqli_query($connNew,$sql_Customeruser);
            	$resUserCustomeruser = mysqli_fetch_object($res_Customeruser);
            	echo $resUserCustomeruser->customer;
            	 ?></td>
      
    </tr>  
    
  
  </tbody>
</table>              
                    </div>
                    
                    
                    
                    
                    
        </div>
       </div> 	
        <div id="reportPage">  
          
       <!--------------------------Executive Wise Start-------------------------------------------------------->
      <div id="blk-ExecutiveWise" class="toHide" style="display:none">
      <div class="row">
      <div class="box-body box-primary" style="padding:0px 15px 7px 15px;">
      <div class="alert alert-success alert-dismissible" style="height: 40px;margin-bottom:0px;padding: 9px;">

						<p> 

						Performance Analysis Executive Wise</p>

					 </div></div>
      
      
      <div class="col-md-6">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;">Room Nights On 
              <span class="showPeriodMonth"> </span></h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="chart">
              <canvas id="mtdPerChart"></canvas>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        
      
          
      </div>
      
      
      
      
      
      <div class="col-md-6">       
        
          <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;">Room Nights From
                      <span class="showPeriod"> </span></h3>
                      <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                      </div>
                    </div>
                    <div class="box-body">
                      <div class="chart">
                        <canvas id="yearToDayPerChart"></canvas>
                      </div>
                    </div>
            
          </div>
        
        
          
         
          
          
      </div>
    </div>
    </div>
    </div>
    <!--------------------------Executive Wise END-------------------------------------------------------->     
    
    <!--------------------------Hotel Wise Wise Start--------------------------------------------------------> 
    <div id="blk-HotelWise" class="toHide" style="display:none">
      <div class="row">
      <div class="box-body box-primary" style="padding:0px 15px 7px 15px;">
      <div class="alert alert-success alert-dismissible" style="height: 40px;margin-bottom:0px;padding: 9px;">

						<p>

						Performance Analysis Hotel Wise</p>

					 </div></div>
        <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;">Room Nights On
                <span class="showPeriodMonth"> </span></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="mtdPerHotelChart"></canvas>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;"> Room Nights From
                <span class="showPeriod"> </span> </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="yearToDayPerHotelChart"  ></canvas>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          
          
        </div>
        <!-- /.col (LEFT) --> 
        
        <!------Col 2----------------------------->
        
        <div class="col-md-6"> 
          <!-- LINE CHART --> 
          
          <!-- /.box --> 
          
          <!-- BAR CHART --> 
          
          <!-- /.box -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;">Revenue On <span class="showPeriodMonth"> </span></h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="mtdPerHotelRevenueChart" ></canvas>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title" style="font-size:14px; line-height:1.5;font-weight: bold;">Revenue From <span class="showPeriod"> </span> </h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="chart">
                <canvas id="yearToDayPerHotelRevenueChart"></canvas>
              </div>
            </div>
            <!-- /.box-body --> 
          </div>
          
        </div>
        <!-- /.col (RIGHT) --> 
      </div>
      <!-- /.row --> 
     </div>      
      <!--------------------------Hotel Wise Wise End--------------------------------------------------------> 
        
    </section>
    


  

    <script>
    /*Downloaded from https://www.codeseek.co/jordanwillis/chartjs-download-multiple-charts-as-pdf-peqVOM */
var chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  yellow: 'rgb(255, 205, 86)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(231,233,237)'
};

var randomScalingFactor = function() {
  return (Math.random() > 0.5 ? 1.0 : 1.0) * Math.round(Math.random() * 100);
};

var data =  {
  labels: ["Car", "Bike", "Walking"],
  datasets: [{
    label: 'Fuel',
    backgroundColor: [
      chartColors.red,
      chartColors.blue,
      chartColors.yellow],
    data: [
      randomScalingFactor(), 
      randomScalingFactor(), 
      randomScalingFactor(), 
    ]
  }]
};

var myBar = new Chart(document.getElementById("myChart"), {
  type: 'horizontalBar', 
  data: data, 
  options: {
    responsive: true,
    title: {
      display: true,
      text: "Chart.js - Base Example"
    },
    tooltips: {
      mode: 'index',
      intersect: false
    },
    legend: {
      display: false,
    },
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});

var myBar2 = new Chart(document.getElementById("myChart2"), {
  type: 'horizontalBar', 
  data: data, 
  options: {
    responsive: true,
    title: {
      display: true,
      text: "Chart.js - Changing X Axis Step Size"
    },
    tooltips: {
      mode: 'index',
      intersect: false
    },
    legend: {
      display: false,
    },
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: true,
          stepSize: 2
        }
      }]
    }
  }
});

var myBar3 = new Chart(document.getElementById("myChart3"), {
  type: 'horizontalBar', 
  data: data, 
  options: {
    responsive: true,
    maintainAspectRatio: false,
    title: {
      display: true,
      text: "Chart.js - Setting maintainAspectRatio = false and Setting Parent Width/Height"
    },
    tooltips: {
      mode: 'index',
      intersect: false
    },
    legend: {
      display: false,
    },
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
});

$('#downloadPdf').click(function(event) {
  // get size of report page
  var reportPageHeight = $('#reportPage').innerHeight();
  var reportPageWidth = $('#reportPage').innerWidth();
  
  // create a new canvas object that we will populate with all other canvas objects
  var pdfCanvas = $('<canvas />').attr({
    id: "canvaspdf",
    width: reportPageWidth,
    height: reportPageHeight
  });
  
  // keep track canvas position
  var pdfctx = $(pdfCanvas)[0].getContext('2d');
  var pdfctxX = 0;
  var pdfctxY = 0;
  var buffer = 100;
  
  // for each chart.js chart
  $("canvas").each(function(index) {
    // get the chart height/width
    var canvasHeight = $(this).innerHeight();
    var canvasWidth = $(this).innerWidth();
    
    // draw the chart into the new canvas
    pdfctx.drawImage($(this)[0], pdfctxX, pdfctxY, canvasWidth, canvasHeight);
    pdfctxX += canvasWidth + buffer;
    
    // our report page is in a grid pattern so replicate that in the new canvas
    if (index % 2 === 1) {
      pdfctxX = 0;
      pdfctxY += canvasHeight + buffer;
    }
  });
  
  // create new pdf and add our new canvas as an image
  var pdf = new jsPDF('l', 'pt', [reportPageWidth, reportPageHeight]);
  pdf.addImage($(pdfCanvas)[0], 'PNG', 0, 0);
  
  // download the pdf
  pdf.save('filename.pdf');
});</script>


  </div>
  <?php include_once("includes/footer.php")?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script type="text/javascript">

 function leadChart(graphCountLead){
        
        if(graphCountLead>0){
            leadChartGen.destroy();
            leadChartAss.destroy();
        }

        leadChartGenVar = document.getElementById('leadChartGen').getContext('2d');
        leadChartRecVar = document.getElementById('leadChartAss').getContext('2d');

        leadChartGen = new Chart(leadChartGenVar , {
            // The data for our dataset
            type: 'pie',

            // The data for our dataset
            data: {
                labels:labelArr,
                datasets: [{
                    data: labelVal,
                    label:labelArr,
                    backgroundColor:bgColor,
                    borderColor:bgColor,
                    }]
            },

            // Configuration options go here
            options: {
                plugins: {
                      labels: [{
                        // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
                        render: 'label',
                        position:'outside',
                        fontSize: 8,
                        fontStyle: 'bold',
                        fontColor: '#000'
                       },
                       {
                        render:'value',
                        fontSize: 12,
                        fontStyle: 'bold',
                        fontColor: '#000'
                       }
                       ]
                },
                legend:{
                    display:false,
                },
                title:{
                    display:true,
                    text:['Total Leads: '+labelVal.reduce(sumofArray)+' ','Revenue : '+revenue],
                    position:'bottom',
                    fontSize: 15,
                    fontStyle: 'bold',
                }      
            }
        });

        leadChartAss = new Chart(leadChartRecVar, {
             // The type of chart we want to create
            type: 'pie',

            // The data for our dataset
            data: {
                labels:labelArr,
                datasets: [{
                    label:labelArr,
                    backgroundColor: bgColor,
                    borderColor: bgColor,
                    data: reasonValRec
                    }]
            },

            // Configuration options go here
            options: {
                plugins: {
                      labels: [{
                        // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
                        render: 'label',
                        position:'outside',
                        fontSize: 8,
                        fontStyle: 'bold',
                        fontColor: '#000'
                       },
                       {
                        render:'value',
                        fontSize: 12,
                        fontStyle: 'bold',
                        fontColor: '#000'
                       }
                       ]
                },
                legend:{
                    display:false,
                    
                },
                title:{
                    display:true,
                    text:['Total Leads: '+reasonValRec.reduce(sumofArray)+' ','Revenue : '+revenueRec],
                    position:'bottom',
                    fontSize: 15,
                    fontStyle: 'bold',
                }      
            }
        });

    }

    function leadGraphData(){
        let period = $("#per_report_date").val();
        let id_team = $("#id_team").val();

        if(id_team!=''){
            $.ajax({
                url:'ajax/fetchLeadGraphData.ajax.php',
                type:'POST',
                data:'id_team='+id_team+'&period='+period,
                success:function(data){
                    // console.log(data);
                    data = JSON.parse(data);
                    labelArr = data.reasons;
                    labelVal = data.reasonval;
                    bgColor = data.bgColor;
                    totalLeads = data.totalLeadsGen;
                    revenue = data.revenueGen;
                    totalLeadsRec = data.totalLeadsRec;
                    reasonValRec = data.reasonValRec;
                    exeIdArr = data.exeIdArr;

                    totalOpenRecLeads = data.totalOpenRecLeads;
                    
                    revenueRec = data.revenueRec;
                    
                    
                    leadChart(graphCountLead);
                    graphCountLead++;
                }
            });
        }
        else{
            alert('Please Select Team.');
        }
    }

    $('document').ready(function(){
        fetchPerformanceGraphData();
    });

    function drillDown(val){

        window.open('manageEnquiry.php?Download=Download&checkin_radio='+val+'&booking_date='+datePeriod+'&reservation_date='+datePeriod+'&lead_status=&id_hotel=&searchFormSubmit=1&drilled_team='+exeIdArr.join());
    }

$(function() {
    $("[name=toggler]").click(function(){
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
    });
 });
 
 
  /* downloadPdf = () => {
        let id_team = $("#id_team").val();
        let date = $("#per_report_date").val();
        let url = 'cronjobs/dashboardReportPdfAutomailer.php?id_team='+id_team+'&report_date='+date+'';
        window.open(url);
   }*/

    function sumofArray(sum, num) { 
        return Number(sum) + Number(num); 
    } 
	var exeNameCountArr = [];
	var yearToDayHotelPrevYearValueArr	= [];
	var budgetHotelRoomNightsValueArr	= [];
	var achievedHotelValueArr	= [];
	var hotelNameValueArr	=[];
	var mtdHotelPrevYearValueArr	= [];
	var budgetHotelRoomNightsThisMonthValueArr	= [];
	var mtdHotelThisMonthValueArr	= [];
	
	var achievedHotelValuePrveYEARValueArr	= [];
	var achievedHotelValuesValueArr			= [];
	var achievedHotelValuePrveYEARMonthValueArr= [];
	var achievedHotelValueThisMonthValueArr	= [];
	var budgetHotelValueCurrentYEARValueArr	= [];
	var budgetHotelValueThisMonthValueArr=[];
	
	var budgetValueCurrentYEARValueArr = [];
	var budgetValueThisMonthValueArr = [];
	var achievedValueYEARMonthValueArr = [];
	var achievedValueThisMonthValueArr = [];
	var achievedValuePrveYEARVal = [];
	var achievedValueCurrentYearValueArr = [];
	
	var budgetRoomNightsThisMonthValueArr= [];
   var yearToDayPreValueArr = [];
	var mtdPreValueArr = [];
	var mtdThisMonthValueArr= [];
	var budgetValueArr = [];
    var mtdThisValueArr = [];
	var budgetRoomNightsValuesArr=[];

	var ytdPreValueArr = [];
    var ytdThisValueArr = [];

	var exeNameArr = [];
    var graphCount = 0;
    var graphCountLead=0;
    var mtdChart='';
    var ytdChart='';

    var exeIdArr =[];
    
    var totalGoneYtd=0;
    var totalGoneMtd=0;
    var datePeriod ='';
var CustomeReportValuesName='';
    var stacked = [];

    function fetchPerformanceGraphData(){
        $("#loading").show();
		let period = $("#per_report_date").val();
		let id_team = $("#id_team").val();
		//console.log($period+'---'+$id_team);	
		if(id_team){	
			$.ajax({
				url:'ajax/fetchPerformanceGraphData.ajax.php',
				type:'POST',
				data:'period='+period+'&id_team='+id_team,
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
                   
                    let salesTable='<table class="table table-striped text-center"><tr style="color:white;"><th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive</th><th style="background-color:#3C8DBC;" colspan="4">Month To Date</th><th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="5">Year To Date</th></tr><tr style="background-color:#5cb4e8;"><th >Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th style="border-left:1px solid #252525;">Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th>Yearly Budget</th></tr>';

                    for(let i=0;i<exeNameArr.length;i++){
                        salesTable+='<tr ><td style="text-align:left;">'+exeNameArr[i]+'</td><td>'+mtdVisits[i]+'</td><td>'+mtdRateletters[i]+'</td><td>'+mtdTotalExpense[i]+'</td><td>'+(mtdVisits[i]/totalGoneMtd).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits[i]+'</td><td>'+ytdRateletters[i]+'</td><td>'+ytdTotalExpense[i]+'</td><td>'+(ytdVisits[i]/totalGoneYtd).toFixed(2)+'</td><td>'+budgetValueArr[i]+'</td></tr>';
                    }
                    salesTable+='<tr style="font-weight:bold;"><td style="text-align:left;">Total</td><td>'+mtdVisits.reduce(sumofArray)+'</td><td>'+mtdRateletters.reduce(sumofArray)+'</td><td >'+mtdTotalExpense.reduce(sumofArray)+'</td><td>'+(mtdVisits.reduce(sumofArray)/(totalGoneMtd*exeNameArr.length)).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits.reduce(sumofArray)+'</td><td>'+ytdRateletters.reduce(sumofArray)+'</td><td>'+ytdTotalExpense.reduce(sumofArray)+'</td><td>'+(ytdVisits.reduce(sumofArray)/(totalGoneYtd*exeNameArr.length)).toFixed(2)+'</td><td>'+budgetValueArr.reduce(sumofArray)+'</td></tr>';

                    salesTable+='</table>';

                    $("#salesChartWrapper").html(salesTable);
                     $("#loading").hide();
                      $(".showPeriod").html('  '+data.reportPeriod);//From Period
					$(".showPeriodMonth").html('  '+data.reportPeriodMonth);
					 performanceChart(graphCount); 
					// alert(data.reportPeriod);
                   
                                     
                    graphCount++;
                   
					}else{
					    alert('No Record Found');
					    $("#salesChartWrapper").html('No Record Found');
						$("#loading").hide();
						
						}	
				}
			})
		}
		else{
			alert('Please Select Team.');
		}
        //leadGraphData();
        

	}

    
    
    
   


    function performanceChart(graphCount){
        
        if(graphCount>0){
           mtdChart.destroy();
		   yearToDayPerChartHeader.destroy();
          //	mtdExecutiveRevenueChartHeader.destroy();
     	//	 yearToDayPerExecutiveRevenueChartHeader.destroy();
			 
		    mtdHotelChartHeader.destroy();
		    yearToDayPerChartHotelHeader.destroy();
			mtdPerHotelRevenueChartHeader.destroy();
		    yearToDayPerHotelRevenueChartHeader.destroy();
        }

		let mtdPerChart = document.getElementById('mtdPerChart').getContext('2d');
        let yearToDayPerChart = document.getElementById('yearToDayPerChart').getContext('2d');
		
	//	let mtdExecutiveRevenueChart = document.getElementById('mtdExecutiveRevenueChart').getContext('2d');
       // let yearToDayPerExecutiveRevenueChart = document.getElementById('yearToDayPerExecutiveRevenueChart').getContext('2d');
		
		
        
		let mtdPerHotelChart = document.getElementById('mtdPerHotelChart').getContext('2d');
        let yearToDayPerHotelChart = document.getElementById('yearToDayPerHotelChart').getContext('2d');
		
		let mtdPerHotelRevenueChart = document.getElementById('mtdPerHotelRevenueChart').getContext('2d');
        let yearToDayPerHotelRevenueChart = document.getElementById('yearToDayPerHotelRevenueChart').getContext('2d');
	
	
	  	


		

/*var chart = ev.target;
  var categoryAxis = chart.yAxes.getIndex(0);

  // Calculate how we need to adjust chart height
  var adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

  // get current chart height
  var targetHeight = chart.pixelHeight + adjustHeight;

  // Set it on chart's container
  chart.svgContainer.htmlElement.style.height = targetHeight + "px";
  
  */
  
  if(achievedHotelValuePrveYEARMonthValueArr!=''){
		mtdPerHotelRevenueChartHeader = new Chart(mtdPerHotelRevenueChart, {
            // The type of chart we want to create
            
  
  type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: hotelNameValueArr,
                datasets: [{
                    label: 'Last Year Achieved: '+achievedHotelValuePrveYEARMonthValueArr.reduce(sumofArray).toFixed(2)+'',
                    backgroundColor: 'rgba(247, 113, 113,0.5)',
                    //borderColor: 'rgba(247, 0, 113,0)',
                    data: achievedHotelValuePrveYEARMonthValueArr
                },
                {
                    label: 'This Year Budget: '+budgetHotelValueThisMonthValueArr.reduce(sumofArray).toFixed(2)+'',
                    backgroundColor: 'rgba(0, 255, 195,0.5)',
                  //  borderColor: 'rgba(0, 255, 195,0)',
                    data: budgetHotelValueThisMonthValueArr
                },{
                    label: 'This Year: '+achievedHotelValueThisMonthValueArr.reduce(sumofArray).toFixed(2)+'',
                    backgroundColor: 'rgba(255, 0, 255,0.5)',
                   // borderColor: 'rgba(255, 0, 255,0)',
                    data: achievedHotelValueThisMonthValueArr
                }]
            },

            				
			   options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: false
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
		yearToDayPerHotelRevenueChartHeader = new Chart(yearToDayPerHotelRevenueChart, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: hotelNameValueArr,
                datasets: [{
                    label: 'Last Year Achieved: '+achievedHotelValuePrveYEARValueArr.reduce(sumofArray).toFixed(2)+'',
                   backgroundColor: 'rgba(247, 113, 113,0.5)',
                    borderColor: 'rgba(247, 113, 113,1)',
                    data: achievedHotelValuePrveYEARValueArr
                },
                {
                    label: 'This Year Budget: '+budgetHotelValueCurrentYEARValueArr.reduce(sumofArray).toFixed(2)+'',
                   backgroundColor: 'rgba(0, 255, 195,0.5)',
                    borderColor: 'rgba(0, 255, 195,1)',
                    data: budgetHotelValueCurrentYEARValueArr
                },{
                    label: 'This Year: '+achievedHotelValuesValueArr.reduce(sumofArray).toFixed(2)+'',
                    backgroundColor: 'rgba(255, 0, 255,0.5)',
                    borderColor: 'rgba(255, 0, 255,1)',
                    data: achievedHotelValuesValueArr
                }]
            },
					

               options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: false
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
		//==================================================================================================

		mtdHotelChartHeader = new Chart(mtdPerHotelChart, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: hotelNameValueArr,
                datasets: [{
                    label: 'Last Year Achieved: '+mtdHotelPrevYearValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: mtdHotelPrevYearValueArr
                },
                {
                    label: 'This Year Budget: '+budgetHotelRoomNightsThisMonthValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(541, 132, 275,0.5)',
                    borderColor: 'rgba(54, 112, 235,1)',
                    data: budgetHotelRoomNightsThisMonthValueArr
                },{
                    label: 'This Year: '+mtdHotelThisMonthValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdHotelThisMonthValueArr
                }]
            },

               options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: true
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
		yearToDayPerChartHotelHeader = new Chart(yearToDayPerHotelChart, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: hotelNameValueArr,
                datasets: [{
                    label: 'Last Year Achieved: '+yearToDayHotelPrevYearValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: yearToDayHotelPrevYearValueArr
                },
                {
                    label: 'This Year Budget: '+budgetHotelRoomNightsValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(541, 132, 275,0.5)',
                    borderColor: 'rgba(54, 112, 235,1)',
                    data: budgetHotelRoomNightsValueArr
                },{
                    label: 'This Year: '+achievedHotelValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: achievedHotelValueArr
                }]
            },

               options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: true
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
		//Hotel Chart End=====================================================================
		mtdChart = new Chart(mtdPerChart, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year Achieved: '+mtdPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',					
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: mtdPreValueArr
                },
                {
                    label: 'This Year Budget: '+budgetRoomNightsThisMonthValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(541, 132, 275,0.5)',					
                    borderColor: 'rgba(54, 112, 235,1)',
                    data: budgetRoomNightsThisMonthValueArr
                },{
                    label: 'This Year: '+mtdThisMonthValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',					
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisMonthValueArr
                }]
            },

              options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: true
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
		
        yearToDayPerChartHeader = new Chart(yearToDayPerChart, {
            // The type of chart we want to create
            type: 'horizontalBar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year Achieved: '+yearToDayPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: yearToDayPreValueArr
                },
                {
                    label: 'This Year Budget: '+budgetRoomNightsValuesArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(541, 132, 275,0.5)',
                    borderColor: 'rgba(54, 112, 235,1)',
                    data: budgetRoomNightsValuesArr
                },{
                    label: 'This Year: '+mtdThisValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisValueArr
                }]
            },

              options: {
    "hover": {
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

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
			if(data>0)
            ctx.fillText(data, bar._model.x - 15, bar._model.y + 6);
          });
        });
      }
    },
    legend: {
      "display": true
    },
    tooltips: {
      "enabled": true
    },
    scales: {
      yAxes: [{
        display: true,
        gridLines: {
          display: true
        },
        ticks: {
          max: Math.max(...data.datasets[0].data) + 10,
          display: true,
          beginAtZero: true
        }
      }],
      xAxes: [{
        gridLines: {
          display: true
        },
        ticks: {
          beginAtZero: true
        }
      }]
    }
  }
        });
		
//Executive Revenue Chart START=================================================================

						
					
					
					
					
					
					
					
					
					

		
		
       
		
		
	}
       

    }   



   
</script>