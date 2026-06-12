<?php 

if(!isset($cron)){
include_once("../config/auto_loader.php");
include_once("includes/reportFunctions.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

if($_REQUEST['report_date'] != ''){
	//list($checkin,$checkout) = split(" to ",$_REQUEST['report_date']);
	$report_date1= explode(" to ",$_REQUEST['report_date']);
	$checkin = $report_date1['0'];
	$checkout = $report_date1['1'];
}	
					  
}
					  
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/


//echo $HOST_NAME = $_SERVER['SERVER_NAME'];
//echo '===='.$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];

?>



<?php

//echo $sql;
  
  if($_REQUEST['action']=='delete'){
    $id_del = encryptor('decrypt',$_REQUEST['delid']);
    $delSql = "DELETE FROM ".TBL_FEEDBACK_DETAILS." WHERE id='".$id_del."'";

    if(mysqli_query($connNew,$delSql)){
      
      $delSql="DELETE FROM ".TBL_FEEDBACK_DETAILS_EXPLOAD." WHERE details_id='".$id_del."'";

      if(mysqli_query($connNew,$delSql))
        $_SESSION['successMsg']="Deleted Successfully";
      else
        $_SESSION['errorMsg']="Error While Deleting From Details";
    }
    else
      $_SESSION['errorMsg']="Error While Deleting";
  
  }

	if($_REQUEST['Search'] == 'Search'){



	$db->query($sql);
	$numRows= $db->num_rows();
	$pagging = new pagingClass($sql,$setpage);
	$db->query($pagging->getQuery());
	$total = $db->num_rows();







 }?>








<?php 
if(!isset($cron)){
	if($_REQUEST['Download']=='Download'){
		
		feedbackReport($connNew,$_SESSION['shop'],$checkin,$checkout,$_REQUEST['usernameid'],$_REQUEST['feedType'],$_REQUEST['lead_status'],$objPHPExcel,$_SESSION['teamMembers'],$cron,$fileName,$RsoUserChecked,$UserHotelAccesid);
	}
include_once("includes/header.php");?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Feedback Manager<small>Feedback Report</small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Feedback Report</li>

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



                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Select From -  To" name="report_date" id="report_date" data-parsley-required value="<?php if(isset($_REQUEST['report_date'])) echo $_REQUEST['report_date'];?>" data-parsley-errors-container="#report_dateError"  automcomplete="off">



                </div>



                <!-- /.input group --> 



                <span id="reservation_dateError"></span> </div>

              <!--<div class="form-group col-sm-6">

                <label for="seasonId">Date <font color="#FF0000">*</font></label>

                <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>

              </div>-->

              <!--<div class="col-md-6">

              <div class="form-group">

                <label>Status</label>				

				<?php 

					if($_REQUEST['status'] == '1'){

							$selected1 = 'selected="selected"';

					}elseif($_REQUEST['status'] == '0'){

							$selected0 = 'selected="selected"';

					}

				  echo $statusDropDown = '<select class="form-control select2" name="lead_status"> <option value="">Both</option>

				  <option '.$selected1.' value="1">Open</option>

				  <option '.$selected0.' value="0">Close</option>

				  </select>';?>

              </div>

              

            </div>-->

              <?php 

			 /* if($_SESSION['userLevel']==1){

				 	

				  $ConditonUserLevel = "";

				  }else{

					  $ConditonUserLevel= "  `".TBL_USERS."`.`id` = '".addslashes($_SESSION['userId'])."' AND ";

					  }*/
					  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
					  $resPer = mysqli_query($connNew,$perSql);

					  if($resPer){
					    	$perData	=	mysqli_fetch_object($resPer);
					      if($perData->calendar_user_list_approved == 0){
					  	   $UserRestriction	=" AND id='".$_SESSION['userId']."'";	
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

                <label>Sales Executive</label>

                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->

               <?php $categoryDropDown = '<select class="form-control select2" name="usernameid" id="usernameid">

											 <option value="">All</option>';

											  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' ".$teamMembers."  ".$UserRestriction." AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

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

              <div class="col-md-4">

                <div class="form-group ">
                	<label>Feedback Type</label>
                	<select class="form-control select2" name="feedType">
                		<option value="">Select Feedback Type</option>
                		<option value="1">Positive</option>
                		<option value="2">Negative</option>
                	</select>
                </div>
              </div>  	

              <!-- /.col -->

              

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

          <div class="box-header">

            <h3 class="box-title"></h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            

             

               



                        <div class="box-body table-responsive">

                          <table id="example2" class="table table-bordered table-striped text-center">

                            <!--<thead>

                            <tr>

                              <th>S.No.&nbsp;</th>

                              <th>Hotel</th>
                              <th>Assign By</th> 
                               
                              <th>Assigned On</th> 
                              <th>Assign To</th>

                               

            				  <th>Feed Back  Date</th>                             

            				            				  

                              <th>Feed Back Summary</th>

                              <th>Status</th>

                             

                              <th>Action</th>

                              

            				  

                            </tr>

                            </thead>-->

                            <tbody>

            				<?php 				 				

            				if($total > 0){$counter = 1;

            				  while($row = $db->fetch_object()){?>

                            <tr>

                              <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>

							<td style="text-align:left;"><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->hotel_id."'");  ?></td>

                            <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->id_user." and id_shop=".$_SESSION['shop']." ");  

                               ?></td>
                               <td><?php echo date('d M Y',strtotime($row->date_created));?></td>
                               <td><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` =".$row->assign_user_id." and id_shop=".$_SESSION['shop']." ");  

                               ?></td>

                              <td><?php echo date('d M Y',strtotime($row->dated));?></td>

                              

                              

                               



                              

                               	

                               



                               

                               <td><?php echo selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$row->id."' AND `visit_id` = '".$row->visit_id."'"); ?></td>

                               

                               <td ><!--<button  class="btn <?php echo $row->lead_status==1?'btn-success':'btn-danger';?>" type="button"   ></button>-->
                               		<?php echo $row->lead_status==1?'Open':'Close';?>


								</td>

                               

                               <!--<td>&nbsp;&nbsp;<img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='addreport.php?eId=<?=encryptor('encrypt',$row->visit_id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>-->

                              

            				  

                            </tr>

                           <?php }?> 

            			   

            				<tr>	 

            					  <td align="right" colspan="13"><?php  echo $pagging->getLinks();?> </td>

                             </tr>               

            				<?php }else {?>

            				

            				 <tr>

                                  <!--<td height="200" align="center" colspan="13">---- No Record Found ---- </td>-->

                             </tr>                 

            				<?php }?>

                            </tbody>                

                          </table>			  

                        </div>



              

            

          </form>

          <!-- /.box-body -->
          <?php
            if($_SESSION['userLevel']!=1){
              $cond = "AND A.user_id='".$_SESSION['userId']."' ";
            }
           $sqlFoll = "SELECT A.*,B.summary FROM ".TBL_FEEDBACK_DETAILS." A LEFT JOIN ".TBL_FEEDBACK_DETAILS_EXPLOAD." B ON A.id=B.details_id WHERE A.id_shop='".$_SESSION['shop']."' ".$cond." AND A.hotel_id!='' AND B.summary!='' ORDER BY A.dated DESC  "; 
            $resFoll= mysqli_query($connNew,$sqlFoll);

          ?>
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>S.no.</th>
                <th>Hotel Name</th>
                <th>Discription</th>
                <th>Date</th>
                <th>Feedback Type</th>
                <?php if($_SESSION['userLevel']==1){ ?>
                <th>Action</th>
                <?php }?>
              </tr>
            </thead>
            <tbody>
              <?php
                $sno=1; 
                while($rowFoll = mysqli_fetch_object($resFoll)){ ?>
              <tr>
                <td><?php echo $sno++; ?>.</td>
                <td><?php echo selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)',"WHERE id='".$rowFoll->hotel_id."'");?></td>
                <td><?php echo $rowFoll->summary; ?></td>
                <td><?php echo date('d-M-Y',strtotime($rowFoll->dated)); ?></td>
                <td><?php echo ($rowFoll->conclusion_type==2?'Negative':'Positive'); ?></td>
                <?php if($_SESSION['userLevel']==1){ ?>
                <td><a href="#" class=""><img onclick="deleteMe('ManagerFeedBackReport.php?action=delete&delid=<?php echo encryptor('encrypt',$rowFoll->id);?>');" src="images/delete.gif" style="cursor:pointer;" title="Delete"></a></td>
                <?php }?>
              </tr>
              <?php } ?>
            </tbody>
          </table>

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


  <?php 
	
  include_once("includes/footer.php");
}?>

<script type="text/javascript">
  function deleteMe(link){
    if(confirm('Are you Sure ? ')){
      window.location.href=link;
    }else{
      return;
    }
  }
</script>