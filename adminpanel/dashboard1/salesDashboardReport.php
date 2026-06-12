<?php 
include_once("../../config/auto_loader.php");

?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
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
            			//debugData($_SESSION);	
						$sql_team = "SELECT id,name FROM ".TBL_TEAM." WHERE id IN (".$_SESSION['teamId'].") and id!=8 ORDER BY name";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <div class="form-group col-sm-4">
                <label>Team</label>
                <select name="id_team" id="id_team" class="select2 form-control">
                  <!--<option value="">Select Team</option>-->
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
                <a  onclick="fetchLeadPerformance();" class="btn btn-info form-control">Search</a> </div>
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
               <?php /*?> <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a> 
              <a href="#" id="downloadPdf">Download Report Page as PDF</a><?php */?>
              
                </div>
               <div class="col-md-12">
                <div class="form-group">
                <label>
          <input id="rdb2" type="radio"   name="toggler" value="SalesSummary"  checked="check" />
          Sales Summary</label> 
                  <label>
          <input id="rdb1" type="radio"   name="toggler" value="ExecutiveWise"   />
          Executive Wise</label>
             <input id="rdb3" type="radio"   name="toggler" value="Salesleadaward"  />
         Sales Lead Award </label>
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

       <!--start of lead summary award1-->
           <div id="leadAwardSummary"></div>
      <!--end of lead award1-->
      <hr>
      <!--start of lead summary award2-->
           
      <!--end of lead award2-->
       
    <!--------------------------Executive Wise END-------------------------------------------------------->     
    
    <!--------------------------Hotel Wise Wise Start--------------------------------------------------------> 
   
        
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

</script>


  </div>
  <?php include_once("../includes/footer.php")?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script type="text/javascript">


    function leadGraphData(){}

    $('document').ready(function(){
        fetchLeadPerformance();
    });

   

$(function() {
    $("[name=toggler]").click(function(){
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
    });
 });
 
 
  

  

    function fetchLeadPerformance(){
		var per_report_date	=	$("#per_report_date").val();
		var id_team	=	$("#id_team").val();
        $("#loading").show();
			
			
			$.ajax({
				url:'ajax/ajaxLeadAwardSummary.php',
				type:'POST',
				data:'per_report_date='+per_report_date+'&id_team='+id_team,
				success:function(data){
					
					$("#leadAwardSummary").html(data);
					$("#loading").hide();
					}
			})
		
		
        //leadGraphData();
        

	
		
		
		}

    
    

</script>