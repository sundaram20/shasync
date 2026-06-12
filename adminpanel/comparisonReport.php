<?php 
include_once("../config/auto_loader.php");
include_once("includes/reportFunctions.php");

checkUserLevelPermission($_SESSION['userLevel'],'comparison_report','view');

if($_SESSION['userLevel']!=1){
$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
$resPer = mysqli_query($connNew,$perSql);

if($resPer){
    $perData  = mysqli_fetch_object($resPer);
    if($perData->calendar_user_list_approved == 0){
     $UserRestriction =" AND id='".$_SESSION['userId']."'"; 
    }
}
}
if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}


if($_REQUEST['Download']=='Download'){
	if($_REQUEST['report_date'] != ''){
		camparisonReport($_SESSION['shop'],$_REQUEST['report_date'],$_REQUEST['search_name']);
	}	
}

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales Report
        <small>Comparison Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Comparison Report</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        
        
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Comparison Report</h3>
            </div>
                    <form name="searchForm" action="" method="get">
                      <input type="hidden" value="1" name="searchFormSubmit" />
                      <div class="box-body">
                        <div class="row">
                        
                    <div class="col-md-3">
              <div class="form-group">
                  <label for="start_date">Select Date</label>
                  <input type="text" autocomplete="off" class="form-control pickerdate" placeholder="Enter start date" id="pace_date" name="report_date" value="<?php if($_POST) echo $_POST['report_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
              <!-- /.form-group -->
            </div>

            <div class="form-group col-sm-5">
                     
       <label for="id_company">Company Name - City<font style="color: red;">*</font> </label>

                  <select class="form-control select2 itemName" name="search_name" id="search_name"  required="required" >

                  </select>
              </div>  



                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>
                          <!-- /.row -->
                        </div>
                      </div>
                      <!-- /.box-body -->
                      <div class="box-footer">
                       <!-- <input name="Search" type="submit" class="btn btn-primary" value="Search" />-->
                        <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />
                        </div>
                        
                    </form>

			
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
                                      
<?php include_once("includes/footer.php")?> 

<script>
  //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.roomstatushub.in/sync/adminpanel/index.php';
	}
     $('.itemName').select2({
        placeholder: 'Select Company',
        ajax: {
          url: "ajax/ajaxSearchCompanyName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
 </script>