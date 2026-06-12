<?php 
if(!isset($cron)){
include_once("../config/auto_loader.php");
include_once("includes/reportFunctions.php");
//error_reporting(E_ALL);
checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


}

?>

<?php

if($_REQUEST['Download'] == 'Download'){
	
	if(!isset($cron)){
		conveyanceReport(NULL,$_SESSION['shop'],$_REQUEST['usernameid'],$_REQUEST['report_date'],$_REQUEST['conveyance_status']);
		
	}

}



 

?>

<?php if(!isset($cron)){
	include_once("includes/header.php"); ?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Conveyance Manager<small>Conveyance Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Conveyance Report</li>

    </ol>

  </section>

  <!-- Main content -->

  <section class="content">

  <div class="row">

    <div class="col-xs-12">

      <div class="nav-tabs-custom">

        <div class="form-group has-error" align="center">

          <?php if($_SESSION['errorMsg']){?>

          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

          <?php unset($_SESSION['successMsg']);}?>

        </div>

        <div class="box-header with-border">

          <h3 class="box-title">Search <small>Total Records: (

            <?=$numRows;?>

            ) &nbsp;</small> </h3>

          <div class="btn-group  pull-right"><!--<a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>

            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>-->

            <ul class="dropdown-menu" role="menu">

              <?php /*?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_RATE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>

								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_RATE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>

            </ul>

          </div>

        </div>

        <!-- /.box-header -->

        <form name="searchForm" action="" method="get">

          <input type="hidden" value="1" name="searchFormSubmit" />

          <div class="box-body">

            <div class="row">

            <div class="form-group col-sm-4">



                <label for="reservation_date">From - To </label>



                <div class="input-group">



                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>



                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">


                </div>



                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>

              <!--<div class="form-group col-sm-6">

                <label for="seasonId">Date <font color="#FF0000">*</font></label>

                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>

              </div>-->

             <!-- <div class="col-md-4">

                <div class="form-group">

                  <label>Company</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>

											    <option value="">Select Company</option>';

											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !='' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['companyId'] == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $companyDropDown .= '</select>';

											  ?>

                </div>

              </div>-->

              <?php 

			  /*if($_SESSION['userLevel']==1){

				 	

				  $ConditonUserLevel = "";

				  }else{

					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";

					  }*/
				if($_SESSION['userLevel']!=1){	  
					$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
					$resPer = mysqli_query($connNew,$perSql);

					if($resPer){
					  	$perData	=	mysqli_fetch_object($resPer);
					    if($perData->calendar_user_list_approved == 0){
						   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
					    }
					}
				}
				if($_SESSION['teamMembers'] !=""){
				  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
				}
				else{
				  $teamMembers ="";
				}	  

			  ?>

              <div class="col-md-4">

                <div class="form-group">

                <label>Sales Executive <font style="color: red;">*</font></label>

                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->

               <?php $categoryDropDown = '<select class="form-control select2" required="required" name="usernameid" id="usernameid">
							<option value="">Select Executive</option>';

											  //$resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' ".$teamMembers."  ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`'); COMMENTED ON 13-02-2021
											  
$resUserLevel = selectSql(TBL_USERS," WHERE  `sales_status_active` = '1' ".$teamMembers."  ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_REQUEST['usernameid'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';

												}

											  }

											 	echo $categoryDropDown .= '</select>';

											  ?>

                                              

                                              </div>

              </div>

              <!-- /.col -->

              <div class="form-group col-md-3">
              		<label>Approval Status</label>
              		<select name="conveyance_status" class="form-control select2">
              			<option value="">Select Status</option>
              			<option value="0">Pending</option>
              			<option value="1">Approved</option>
              			<option value="2">Not Approved</option>
              		</select>
              </div>

              <!-- /.row -->

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->

            <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" />

            </div>

            

        </form>

      

        <div class="box">

          <!--<div class="box-header">

            <h3 class="box-title">Conveyance List</h3>

          </div>-->

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

           

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped text-center">

                            <thead>

                            <!--<tr>

                              <th >S.No.&nbsp;</th>

            				  <th>Date</th>

            				  <th>Executive</th>

            				  <th>From</th>

                              <th>To</th>

                              <th>Company Visited</th>

                              <th>Kms Run</th>

                              <th>Rate/Km</th>

                              <th>Parking</th>

                              <th>Sub Total</th>

                              <th>Entertainment</th>

                              <th>Total</th>

                              <th>Status</th>
                            </tr>-->
                            </thead>

                            <tbody>

            				<?php 				 				

            				if($total > 0){$counter = 1;

            				  while($row = $db->fetch_object()){?>

                            <tr>

                              <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>



                              <td><?=date('d-M-Y',strtotime($row->dated));?></td>

                              <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$_SESSION['shop']." ");  

                               ?>

                               <td><?=$row->StatFrom;?></td>



                               <td><?=$row->StatTo;?></td>

                               <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` =".$row->id_company." and id_shop=".$_SESSION['shop']." ");  

                               ?>

                               	

                               </td>



                               <td><?=$row->KmsRun;?></td>

                               <td><?=$row->RateKm;?></td>

                               <td><?=$row->Parking;?></td>

                               <td><?=$row->Total;?></td>

                               <td><?=$row->entertainment;?></td>

                               <td><?=$row->Total+$row->entertainment;?></td>



                               <?php

                               if($row->conveyance_approved==1)

                               		$approval ='Approved';

                               else

                               		$approval ='Not Approved';

                               ?>



                               <td><?=$approval;?></td>

            				  

                            </tr>

                           <?php }?> 

            			   

            				<tr>	 

            					  <td align="right" colspan="13"><?php  echo $pagging->getLinks();?> </td>

                             </tr>               

            				<?php }else {?>

            				

            				 <!--<tr>

                                  <td height="200" align="center" colspan="13">---- No Record Found ---- </td>

                             </tr>   -->              

            				<?php }?>

                            </tbody>                

                          </table>			  

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

  

  <div id="duplicate" class="well" style="display:none;">

    

  </div>

  <?php include_once("includes/footer.php"); } ?>

