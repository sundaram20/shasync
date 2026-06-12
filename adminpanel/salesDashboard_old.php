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

 	
    <section class="content">
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
							<a  onclick="fetchPerformanceGraphData();" class="btn btn-info form-control">Search</a>   
            			</div>

                        <div class="form-group col-sm-2">
                            <label for="">&nbsp;</label>
                            <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a>   
                        </div>

                        <div class="col-sm-2">
                            <label for="">&nbsp;</label><br>
                            <span style="color:red;display:none;" id="loading">Please Wait...</span>
                        </div>

            		</div>

                    <h4 class="text-center"><strong>Performance Analysis <span class="showPeriod"> </span></strong></h4>
                    <!--<div>
                        <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Budget</strong></h4>
                        <canvas id="budgetChart" width="350" height="50"></canvas>
                    </div>-->

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Month To Date</strong></h4>
                            <canvas id="mtdPerChart" width="400" height="200"></canvas>
                        </div>
                               
                        <div class="col-md-6">
                            <h4 class="text-center" ><strong style="padding: 3px 10px 3px 10px;background-color:#e3e3e3; border-radius:5px;">Year To Date</strong></h4>
                            <canvas id="ytdPerChart" width="400" height="200"></canvas>
                        </div>
                    </div>
            		

                     <hr>           
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

            	</div>		
        	</div>
        </div>
    </section>             
</div>         

<?php include_once("includes/footer.php")?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

<script type="text/javascript">

   downloadPdf = () => {
        let id_team = $("#id_team").val();
        let date = $("#per_report_date").val();
        let url = 'cronjobs/dashboardReportPdfAutomailer.php?id_team='+id_team+'&report_date='+date+'';
        window.open(url);
   }

    function sumofArray(sum, num) { 
        return Number(sum) + Number(num); 
    } 

   
	var mtdPreValueArr = [];
	var budgetValueArr = [];
    var mtdThisValueArr = [];

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

    var stacked = [];

    function fetchPerformanceGraphData(){
        $("#loading").show();
		let period = $("#per_report_date").val();
		let id_team = $("#id_team").val();
		//console.log($period+'---'+$id_team);	
		if(id_team){	
			$.ajax({
				url:'ajax/fetchPerformanceGraphData.ajax_old.php',
				type:'POST',
				data:'period='+period+'&id_team='+id_team,
				success:function(data){
					
                    
					data = JSON.parse(data);

                    exeNameArr = data.executives;
                    budgetValueArr =data.budgetVal;
                    mtdPreValueArr =data.mtdLastVal;
                    mtdThisValueArr =data.mtdThisVal;
                    ytdPreValueArr =data.ytdLastVal;
                    ytdThisValueArr =data.ytdThisVal;
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
                    

                    
					performanceChart(graphCount);
                    //budgetChartFun(graphCount);

                    let salesTable='<table class="table table-striped text-center"><tr style="color:white;"><th rowspan="2" style="background-color:#3C8DBC;vertical-align: middle;">Executive</th><th style="background-color:#3C8DBC;" colspan="4">Month To Date</th><th style="background-color:#3C8DBC;border-left:1px solid #252525;" colspan="5">Year To Date</th></tr><tr style="background-color:#5cb4e8;"><th >Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th style="border-left:1px solid #252525;">Visits</th><th>Rate Letters</th><th>Total Expense</th><th>Avg. Daily Call</th><th>Yearly Budget</th></tr>';

                    for(let i=0;i<exeNameArr.length;i++){
                        salesTable+='<tr ><td style="text-align:left;">'+exeNameArr[i]+'</td><td>'+mtdVisits[i]+'</td><td>'+mtdRateletters[i]+'</td><td>'+mtdTotalExpense[i]+'</td><td>'+(mtdVisits[i]/totalGoneMtd).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits[i]+'</td><td>'+ytdRateletters[i]+'</td><td>'+ytdTotalExpense[i]+'</td><td>'+(ytdVisits[i]/totalGoneYtd).toFixed(2)+'</td><td>'+budgetValueArr[i]+'</td></tr>';
                    }
                    salesTable+='<tr style="font-weight:bold;"><td style="text-align:left;">Total</td><td>'+mtdVisits.reduce(sumofArray)+'</td><td>'+mtdRateletters.reduce(sumofArray)+'</td><td >'+mtdTotalExpense.reduce(sumofArray)+'</td><td>'+(mtdVisits.reduce(sumofArray)/(totalGoneMtd*exeNameArr.length)).toFixed(2)+'</td><td style="border-left:1px solid #252525;">'+ytdVisits.reduce(sumofArray)+'</td><td>'+ytdRateletters.reduce(sumofArray)+'</td><td>'+ytdTotalExpense.reduce(sumofArray)+'</td><td>'+(ytdVisits.reduce(sumofArray)/(totalGoneYtd*exeNameArr.length)).toFixed(2)+'</td><td>'+budgetValueArr.reduce(sumofArray)+'</td></tr>';

                    salesTable+='</table>';

                    $("#salesChartWrapper").html(salesTable);
                    $(".showPeriod").html(' For Period '+data.reportPeriod);
                                       
                    graphCount++;
                    $("#loading").hide();
				}
			})
		}
		else{
			alert('Please Select Team.');
		}
        leadGraphData();
        

	}

    
    
    

    /*function salesSummaryChart(graphCount){
        if(graphCount>0){
            mtdSalesSumChart .destroy();
            ytdSalesSumChart .destroy();
        }

        let mtdSalesChart = document.getElementById('mtdSalesChart').getContext('2d');
        let ytdSalesChart = document.getElementById('ytdSalesChart').getContext('2d');

         mtdSalesSumChart = new Chart(mtdSalesChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Visits',
                    backgroundColor: 'rgb(101, 237, 126)',
                    borderColor: 'rgb(101, 237, 126)',
                    data: mtdVisits
                },
                {
                    label: 'Rate Letters',
                    backgroundColor: 'rgb(36, 214, 69)',
                    borderColor: 'rgb(36, 214, 69)',
                    data: mtdRateletters
                }
                ]
            },

            // Configuration options go here
            options: {}
        });

        ytdSalesSumChart = new Chart(ytdSalesChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Visits',
                    backgroundColor: 'rgb(97, 186, 93)',
                    borderColor: 'rgb(97, 186, 93)',
                    data: ytdVisits
                },
                {
                    label: 'Rate Letters',
                    backgroundColor: 'rgb(23, 178, 51)',
                    borderColor: 'rgb(23, 178, 51)',
                    data: ytdRateletters
                }]
            },

            // Configuration options go here
            options: {}
        });



    }*/

    /*function budgetChartFun(graphCount){
        if(graphCount>0){
           budgetChart.destroy();
        }

        let budChart = document.getElementById('budgetChart').getContext('2d');

        budgetChart = new Chart(budChart, {
            // The type of chart we want to create
            type: 'horizontalBar',
             // The data for our dataset
            data: {
                labels:['Budget'],
                datasets:stacked,
            },

            // Configuration options go here
            options: {
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                },
                scales: {
                    xAxes: [{ stacked: true }],
                    yAxes: [{ stacked: true }]
                },
                title:{
                    display:true,
                    text:'Total Budget : '+budgetValueArr.reduce(sumofArray)+'',
                }
            }
        });
    }*/

    

    function performanceChart(graphCount){
        
        if(graphCount>0){
           mtdChart.destroy();
           ytdChart.destroy();
        }


        let mtdPerChart = document.getElementById('mtdPerChart').getContext('2d');
        let ytdPerChart = document.getElementById('ytdPerChart').getContext('2d');

        mtdChart = new Chart(mtdPerChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year : '+mtdPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(153, 102, 255,0.5)',
                    borderColor: 'rgba(153, 102, 255,1)',
                    data: mtdPreValueArr
                },
                {
                    label: 'This Year : '+mtdThisValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(54, 162, 235,0.5)',
                    borderColor: 'rgba(54, 162, 235,1)',
                    data: mtdThisValueArr
                }]
            },

            // Configuration options go here
            options: {
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                },
                title:{
                    display:true,
                    text:'Total Budget : '+budgetValueArr.reduce(sumofArray)+' '
                }
            }
        });

         ytdChart = new Chart(ytdPerChart, {
            // The type of chart we want to create
            type: 'bar',

            // The data for our dataset
            data: {
                labels: exeNameArr,
                datasets: [{
                    label: 'Last Year : '+ytdPreValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgba(255, 205, 86,0.5)',
                    borderColor: 'rgb(255, 205, 86,1)',
                    data: ytdPreValueArr
                },
                {
                    label: 'This Year : '+ytdThisValueArr.reduce(sumofArray)+'',
                    backgroundColor: 'rgb(54, 162, 235,0.5)',
                    borderColor: 'rgb(54, 162, 235,1)',
                    data: ytdThisValueArr
                }]
            },

            // Configuration options go here
            options: {
                plugins: {
                    labels:{
                      render:'value',  
                    }
                    
                },
                title:{
                    text:'Total Budget : '+budgetValueArr.reduce(sumofArray)+'',
                    display:true
                }
            }
        });

    }   



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
</script>