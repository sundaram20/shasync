<?php 
include_once("../config/auto_loader.php");
include_once("includes/reportFunctions.php");

checkUserLevelPermission($_SESSION['userLevel'],'company_addition_report','view');

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
	//list($startDate,$endDate) = split(" to ",$_REQUEST['report_date']);
	$report_date1= explode(" to ",$_REQUEST['report_date']);
	$startDate = $report_date1['0'];
	$endDate = $report_date1['1'];
	
}	
	companyAdditionReport($_SESSION['shop'],$startDate,$endDate,$connNew,$_SESSION['teamMyAreas']);
}

$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sales Report
        <small>Company Log Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sales Report/Company Log Report</li>
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
              <h3 class="box-title">Company Log Report</h3>
            </div>
                    <form name="searchForm" action="" method="get">
                      <input type="hidden" value="1" name="searchFormSubmit" />
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="form-group col-sm-3">



                <label for="reservation_date">From - To </label>



                <div class="input-group">



                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">



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
