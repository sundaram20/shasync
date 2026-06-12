<?php 
include_once("../config/auto_loader.php");

include_once("includes/reportFunctions.php");

checkUserLevelPermission($_SESSION['userLevel'],'exe_portfolio','view');

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

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

?>




<?php

if($_REQUEST['Download'] == 'Download'){

	executivePortFolioYearlyReport(NULL,$_SESSION['shop'],$_REQUEST['usernameid'],date('Y-m-d',strtotime($_REQUEST['report_date'])),"",$conn,$objPHPExcel);
}



?>

<?php include_once("includes/header.php")?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Executive Portfolio<small>Executive Portfolio Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Executive Portfolio</li>

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

           <!--  <div class="form-group col-sm-4">



                <label for="reservation_date">From - To </label>



                <div class="input-group">



                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">



                </div>
 -->


                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>

              



              <div class="col-md-4">

                <div class="form-group">

                <label>Sales Executive</label>

                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->

               <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">
				<option value="">Select Sales Executive</option>	';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' ".$teamMembers." ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' AND  user_type!=2 ",' ORDER BY `name`');

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

              <div class="form-group col-sm-4">

                <label for="seasonId">As on <font color="#FF0000">*</font></label>

                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" autocomplete="off" value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date']; else echo date('d-m-Y');?>"  data-parsley-required>

              </div>

              <!--<div class="col-md-4">

                <div class="form-group">

                  <label>Company - City</label>

                  <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>

											    <option value="">Select Company</option>';

											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND name !='' ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['companyId'] == $resultCat->id_company){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';

												}

											  }

											 	echo $companyDropDown .= '</select>';

											  ?>

                </div>

              </div>-->

              



			            <!--Area Executive-->

			            <!--<div class="col-md-4">

			                <div class="form-group">

			                  <label>Area</label>				

			  				 <?php $categoryDropDown = '<select class="form-control select2" name="id_area">

			  											  <option value="">Select Area</option>  ';

			  											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');

			  											  if($db->num_rows2($resCat)){

			  											  	while($resultCat = $db->fetch_object2($resCat)){

			  													if($_REQUEST['id_area'] == $resultCat->id){

			  														$selected = 'selected="selected"';

			  													}else{

			  														$selected = '';

			  													}

			  													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

			  												}

			  											  }

			  											 	echo $categoryDropDown .= '</select>';

			  											  ?>

			                </div>

			  			  			

			            </div>-->

			            <!--Area Executive End-->



              

              <!-- /.col -->

              

              <!-- /.row -->

            </div>

          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <!--<input name="Search" type="submit" class="btn btn-primary" value="Search" />-->

           <?php 
		   
		   
		   
		   /*?> <input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank" /><?php */?>
<input name="Download" type="submit" class="btn btn-primary" value="Download" target="_blank"  />
<?php //echo '<br/><span style="color:red; font-size:12px;">Server is Busy Try Again Later</span>';?>
            </div>

            

        </form>

      

        <div class="box">

          <div class="box-header">

            <h3 class="box-title">Portfolio List</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped ">

                            <!--<thead>

                            <tr>

                              <th>S.No.</th>		

                              <th width="20%">Company Name&nbsp;</th>

            				  <th>Area</th>

            				  <th>Executive</th>

            				  <th>Budget</th>

            				  <th>Budget Achieved</th>

            				  <th>Visit 1</th>

            				  <th>Visit 2</th>

            				  <th>Visit 3</th>

            				  <th>Visit 4</th>

            				  <th>Visit 5</th>				  

                            </tr>

                            </thead>-->

                            <tbody>

            				<?php 				 				

            				if($total > 0){$counter = 1;

            				  while($row = mysqli_fetch_object($res)){?>

                            <tr>

                               <th><?=$counter++;?></th>     

                               <td width="20%"><?=$row->name;?></td>

                               <td><?=$row->area;?></td>

                               <td><?=$row->executive;?></td>

                               <td><?php echo selectColumn(TBL_AGENT_BUDGET,'value'," WHERE `id_company` = '".$row->id_company."' and id_user='".$row->id_user."' "); ?></td>

                               <td>0</td>

                               

                               <?php 

                               		$sqlDate ="SELECT dated FROM `".TBL_DAILYVISIT."` WHERE id_company =".$row->id_company." Order BY dated desc  LIMIT 5 "; 

                               		$resDate = mysqli_query($conn,$sqlDate);

                               		

                               		if($resDate){ 

                               			while($resData = mysqli_fetch_object($resDate)){

                               	?>

	           		

			                               <td width="10%"><?=date('d-M-Y',strtotime($resData->dated));?></td>

			                               

                               <?php

           				 				}

           				 			}



                               ?>

                               

                            </tr>

                           <?php }?> 

            			   

            				<!--<tr>	 

            					  <td align="right" colspan="13"><?php  echo $pagging->getLinks();?> </td>

                             </tr>  -->             

            				<?php }else {?>

            				

            				 <tr>

                                  <td height="200" align="center" colspan="13">---- No Record Found ---- </td>

                             </tr>                 

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

  <?php include_once("includes/footer.php")?>

