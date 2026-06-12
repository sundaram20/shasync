<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_VISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

//findSalesHead($connNew);
//echo $_SESSION['teamMyAreas'];


//---------------------------------------------------------------------------------------------------------

$perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";

$resPer = mysqli_query($conn,$perSql);



if($resPer){

  $perData=mysqli_fetch_object($resPer);


}

  $dsrNoDays	=selectColumn(TBL_USERS,'dsr_num_days'," WHERE `id` = '".$_SESSION['userId']."'");




unset($_SESSION['editCart']);

unset($_SESSION['editCart']['charges_total']);

unset($_SESSION['editCart']['charges_price']);

unset($_SESSION['editCart']['charges_description']);

unset($_SESSION['editCart']['charges_total']);



unset($_SESSION['followup_hotel_id']); 

unset($_SESSION['followup_description']); 

unset($_SESSION['followup_date']); 

unset($_SESSION['followupCode']); 

unset($_SESSION['followupstatus']); 

unset($_SESSION['feedbackstatus']); 

unset($_SESSION['feedback_hotel_id']); 
unset($_SESSION['conclusion_type']); 
unset($_SESSION['feedback_description']); 

unset($_SESSION['feedback_date']); 

unset($_SESSION['feedback_Explode_Description']); 

unset($_SESSION['feedback_Explode_Date']); 



unset($_SESSION['feedback_Explode_visit_id']); 

unset($_SESSION['feedback_Explode_id']); 

unset($_SESSION['followup_Explode_id']); 

unset($_SESSION['followup_Explode_visit_id']); 

unset($_SESSION['followup_Explode_Description']); 

unset($_SESSION['assign_feedback_user_id']);

unset($_SESSION['assign_followup_user_id']);

unset($_SESSION['assign_user_id']);

unset($_SESSION['date_created']);

unset($_SESSION['followup_date_created']);

unset($_SESSION['feedback_date_created']);





	

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_VISIT."`

								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ";
               
	$db->query($sql);

	if($db->num_rows() > 0){

		$row = $db->fetch_object();
    /*echo $row->id_user;
    echo "<br>";
    echo $_SESSION['userId'];
    exit;*/
    if($row->conveyance_approved==1 || $row->id_user!=$_SESSION['userId']){
      $readonly="readonly='readonly'";
      $disabledEdit = "disabled='disabled'";
    }
    else{
      $readonly='';
      $disabledEdit='';
    }


	}
  					

}	
	



?>    

<?php include_once("includes/header.php")?>

  <?php include_once("includes/left.php")?>

  <div class="content-wrapper"> 

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1> Daily Reports Manager <small>Daily Reports</small> </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Daily Reports </li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row"> 

        <!-- left column -->

        <div class="col-md-12"> 

          <!-- general form elements -->

          

          <div class="nav-tabs-custom">
            
            <div class="box-header with-border" id="recentAdd" style="border:1px solid #252525; border-radius:3px;display: none; ">
              <h3 class="box-title" style="color: green;">Recently Added visits :</h3>
              <table  class="table table-striped" id="recentAddData">
                <tr style="background-color:#c2c2c2;color: #000; ">
                  <td><b>S.No.</b></td>
                  <td><b>Company Visited</b></td>
                  <td><b>Person Met</b></td>
                  <td><b>Discussion Summary</b></td>
                  <td><b>Total Conveyance</b></td>
                  <td><b>Entertainment</b></td>
                  <td><b>Lunch</b></td>
                </tr>
              </table>
            </div>

            <div class="box-header with-border">
                

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Daily Sales Visit <a><?php //echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>

            </div>

            <!-- /.box-header --> 

            <!-- form start -->

            <form name="form1" id="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >

              <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId"  id="eId" />

              <input type="hidden" value="<?php echo $row->id_user;?>" name="user_id" id="user_id"  />

              <div class="form-group has-error" align="center">

                <?php if($_SESSION['errorMsg']){?>

                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

                <?php unset($_SESSION['successMsg']);}?>

              </div>

              <div class="box-body">
             <div class="form-group col-sm-3">

<?php

	if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
		$report_date	= stripslashes(date('d-m-Y',strtotime($row->dated)));


	 }else{  $report_date	=	date('d-m-Y');
	 }	?>
                  <label for="end_date">Report Date</label>
<?php 
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	?>
<input type="text" readonly="readonly"  class="form-control pickerdate_addreport" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
<?php
	
	
	
}else{
if($dsrNoDays==0){
?>
<input type="text" readonly="readonly" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
<?php }else{ 


?>
                  <input type="text" readonly="readonly" class="form-control pickerdate_addreport" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
<?php } 


} ?>

				<?php echo $err_end_date;?>

                </div>
              

               

                    

                <div class="form-group col-sm-4">

                  <label for="id_company">Company Name - City </label>

                  <select class="form-control select2 itemName" name="id_company" id="id_company"  onChange="getExecutiveName(this.value,''); areaExecutive();" data-parsley-errors-container="#companyError" data-parsley-required >
 <?php if($_REQUEST['eId']!=''){
	 $CompanyName	=	 selectColumn(TBL_COMPANY,'CONCAT(name," - ",city)'," WHERE `id_company` = '".$row->id_company."'");
	 echo '<option  '.$selected.' value="'.$row->id_company.'">'.$CompanyName.'</option>';
 }
 ?>

                  </select>
                  <span style="color:red;" id="areaExe"></span>
                  <span id="companyError"></span> </div>
                  

                <div class="form-group col-sm-5">

                  <label for="id_contacts" >Person Met</label>

                  <div class="input-group" id="showbookedby">

                  <select class="form-control select2" name="id_contacts" id="id_contacts"  data-parsley-errors-container="#contactError" data-parsley-required>

                    <option value="">Select Person Met</option>

                  </select>

                  <span id="contactError"></span> 

                  <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>

                  </div></div>

                  

                   

                  

                                   

                  <!--<div class="form-group col-sm-5" >

                           <label for="id_contacts" >Booking By</label>

                           <div class="input-group" id="showbookedby">

                            <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" >

                               <option value="">Select User</option>

                             </select>

                            <span id="contactError"></span>

                            <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>

                          </div>

                         </div>

                         

                        

       -->

                  

                  

                <!--<div class="form-group col-sm-2">

                  <label for="details">Business Potential</label>

                  <input class="form-control" name="business_potential" id="business_potential" type="hidden"  rows="2" placeholder="Enter business potential" automcomplete="off" value="<?php if($_POST) echo $_POST['business_potential'];else echo stripslashes($row->business_potential);?>">

                  

                  <?php echo $err_details;?> </div>-->

                <div class="form-group col-sm-12">

                  <label for="details">Discussion Summary<font style="
                  color: red;">*</font></label>

                  <textarea data-parsley-required class="form-control" name="discussion_summary" id="discussion_summary"    rows="2" placeholder="Enter Discussion Summary" automcomplete="off"><?php if($_POST) echo $_POST['discussion_summary'];else echo stripslashes($row->discussion_summary);?></textarea>

                  <?php echo $err_details;?> </div>

                 <div>

                

                <!---Follow ups--Start---------------------------------------------------->

               

                <div class="row">

                  <div class="col-sm-3">

                    <div class="form-group">

                      <label for="image" style="float:left;">Follow Ups &nbsp;&nbsp; </label>

                      <button class="pull-left btn btn-success btn-xs" type="button" <?php echo $disabledEdit;?> onclick="ajaxAddNewFollowup(0);" >Add Follow Ups </button>
    

                    

                    </div>

                    <?php echo $err_image;?> </div>

                  <div class="col-sm-9"> </div>

                </div>

               <div class="box"> 

               

                <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ 				

				

			$FollowupSql = executeSql("SELECT * from `".TBL_FOLLOWUP_DETAILS."` where status='1'  AND visit_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ");





if(num_rows($FollowupSql) > 0){

	

	

?>

        <div id="showFolowup">

               	

			<div class="box-body table-responsive" id="removeForEdit2">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr style="background-color:#3C8DBC; color:#fff;">

                                   

                    <th>Hotel Name</th>					

                     <th>Follow Up Summary</th>

                     <!--<th>Created  Date</th>-->

                    <th>Follow Up Date</th>

                    <th>Assign To</th>

                    <th>Status</th>

                    <!--<th>Action</th>-->
                  </tr>

                </thead>

                <tbody>

                

        <?php

		$FollowupExpand = 0;

		$CountFollowup = num_rows($FollowupSql);

		

		while($FollowupSqlRow = $db->fetch_assoc2($FollowupSql)){

		

			$FollowupExpand++;

		$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);	

		

		

		$_SESSION['followup_hotel_id'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['hotel_id'];

		//$_SESSION['followup_description'][$OtherChargesuniqueCode]	=	selectColumn(TBL_FOLLOWUP_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$FollowupSqlRow['id']."' AND `visit_id` = '".$FollowupSqlRow['visit_id']."'");

    $_SESSION['followup_description'][$OtherChargesuniqueCode]  = $FollowupSqlRow['follow_up_summary'];

		$_SESSION['followup_date'][$OtherChargesuniqueCode]			=	$FollowupSqlRow['dated'];

		$_SESSION['followupstatus'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['lead_status'];

		$_SESSION['assign_followup_user_id'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['assign_user_id'];

		$_SESSION['followup_date_created'][$OtherChargesuniqueCode]				=	$FollowupSqlRow['date_created'];

		$_SESSION['followup_Explode_id'][$OtherChargesuniqueCode]		=	$FollowupSqlRow['id'];												

		$_SESSION['followup_Explode_visit_id'][$OtherChargesuniqueCode]	=	$FollowupSqlRow['visit_id'];

		

		if($FollowupSqlRow['lead_status'] == 1){

		$StatusEs	=	'btn-success';

		$ActiveINactive	=	"Open";

		

		}if($FollowupSqlRow['lead_status'] == 0){

		$StatusEs	=	   'btn-danger';

		$ActiveINactive	=	"Close";

		$NextFollowUpDisable	= "disabled";  

		}

			

	$DateVisitList	='<tr style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;">';

			   $DateVisitList	.='<td>';

$DateVisitList	.='<a href="javascript://" onClick="showContent('.$FollowupExpand.','.$CountFollowup.')" style="color:#fff;text-transform: uppercase;"><i class="fa  fa-plus-square">&nbsp;';

$DateVisitList	.='</i>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$FollowupSqlRow['hotel_id']."'").'

 <input type="hidden" id="section_'.$FollowupExpand.'_img"  border="0">';

 

$DateVisitList	.='</td>';





			   //$DateVisitList	.='<td>'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$FollowupSqlRow['hotel_id']."'").'</td>';

			  

			  $DateVisitList	.='<td>'. $FollowupSqlRow['follow_up_summary'].'</td>';



//$DateVisitList	.='<td>'.date('d M Y',strtotime($FollowupSqlRow['date_created'])).'</td>';

			   

$DateVisitList	.='<td>'.date('d M Y',strtotime($FollowupSqlRow['dated'])).'</td>';



$DateVisitList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_followup_user_id'][$OtherChargesuniqueCode]."'").'</td>';

 

 //$DateVisitList	.='<td id="ChangeButton_'.$row->id.'"><i data="'.$row->id.'" class="status_checks btn '.$StatusEs.'" "'.$NextFollowUpDisable.'">'.$ActiveINactive.'</td>';

 

$DateVisitList .= '<td id="ChangeButton_'.$FollowupSqlRow['id'].'"><button data="'.$FollowupSqlRow['id'].'" class="btn '.$StatusEs.'" type="button" onclick="OpenPopup('.$FollowupSqlRow['lead_status'].','.$FollowupSqlRow['id'].','.$FollowupSqlRow['visit_id'].','.$FollowupSqlRow['hotel_id'].','.$FollowupSqlRow['type'].');"    >'.$ActiveINactive.'</button>

</td>';



		  /*$DateVisitList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$OtherChargesuniqueCode.'" onclick="ajaxFollowupRemove($(this).attr(\'id\'));");">

				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';*/

			

/*$DateVisitList	.='<td>';

$DateVisitList	.='<a href="javascript://" onClick="showContent('.$FollowupExpand.','.$CountFollowup.')">';

$DateVisitList	.='<i class="fa fa-th-list"></i>

 <input type="hidden" id="section_'.$FollowupExpand.'_img"  border="0">';

 

$DateVisitList	.='</td>';*/

 

 

$DateVisitList	.='<tr><td colspan="9" style="padding-bottom:0px;padding-top:0px;"> ';                 

$DateVisitList	.='<div id="div'.$FollowupExpand.'"></div>                 

                  

                  <div id="section_'.$FollowupExpand.'" style="display:none;">				  

				  

                <table id="example2" class="table table-bordered table-striped">

                  <tr style="background-color:#3C8DBC; color:#fff;">

                    <th>S.No</th>                  

                    <th>Date</th>

                     <th>Follow Summary</th>

                                       

                  </tr>';

 

 $NextFollowUpSql = executeSql("SELECT  `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.*  FROM `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`  WHERE `".TBL_FOLLOWUP_DETAILS_EXPLOAD."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$FollowupSqlRow['id']."' AND `visit_id` = '".$FollowupSqlRow['visit_id']."'  ");

		

		if(num_rows($NextFollowUpSql) > 0){

		$FollwupNext=1;

		while($NextFollowRow = $db->fetch_assoc2($NextFollowUpSql)){

			$FollowExplodeDated	=	$NextFollowRow['dated'];

			

		$_SESSION['followup_Explode_Description'][$OtherChargesuniqueCode][$FollowExplodeDated]	=	$NextFollowRow['summary'];	

		

			

		

				$DateVisitList	.='<tr >

                    <th>'.$FollwupNext++.'</th>                  

                    <th>'.$NextFollowRow['dated'].'</th>

                     <th>'.$NextFollowRow['summary'].'</th>

                                        

                  </tr>';

				  

				  

					}

			

			}

  $DateVisitList	.='</table>

               

				  

				  

				  </div>';

 	

		echo $DateVisitList	.='</tr>';

			}

			

			?>

            

            

            

            </tbody>

                </table></div>

                </div>

            <?php

		}else{?>

                

					<div  id="showFolowup"></div>

				<?php

					}

                }else{?>

                

					<div  id="showFolowup"></div>

				<?php	

					}

				

				?>

               </div>  

                

                

                <!---Follow ups--End----------------------------------------------------> 

                

                <br/> <br/> 

                 <!---FeedBack--Start---------------------------------------------------->

                

                <div class="row">

                  <div class="col-sm-4">

                    <div class="form-group">

                      <label for="image" style="float:left;">FeedBack / Competition Summary &nbsp;&nbsp; </label>

                      <button class="pull-left btn btn-success btn-xs" type="button" <?php echo $disabledEdit;?> onclick="ajaxAddFeedBack(1);" >Add FeedBack</button>

                    </div>

                    <?php echo $err_image;?> </div>

                  <div class="col-sm-9"> </div>

                </div>

       <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ 

				

				;

			$FollowupSql1 = executeSql("SELECT * from `".TBL_FEEDBACK_DETAILS."` where status='1' and  visit_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'");





if(num_rows($FollowupSql1) > 0){

?>

       <div class="box" id="showFeedBack">

               	

			<div class="box-body table-responsive" id="removeForEdit">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr style="background-color:#3C8DBC; color:#fff;">                                  

                                 

                    <th>Hotel Name</th>					

                   <th>FeedBack Summary</th>

                   

                    <th> FeedBack Date</th>

                   <th>Assign To</th>

                    <th>Status</th>					

                    <!--<th>Action</th>-->

                   

                    

                    

                  </tr>

                </thead>

                <tbody>

                

        <?php

		$Expand = 0;

		$CountFeedBack = num_rows($FollowupSql1);

		while($FeedBackwupSqlRow = $db->fetch_assoc2($FollowupSql1)){     

		  $Expand++;	  

$FeedBackuniqueCode = 'FEEDBACK'.rand(0000,9999);



$_SESSION['feedback_hotel_id'][$FeedBackuniqueCode]	=	$FeedBackwupSqlRow['hotel_id'];



$_SESSION['feedback_date'][$FeedBackuniqueCode]	=	$FeedBackwupSqlRow['dated'];


$_SESSION['feedback_description'][$FeedBackuniqueCode]	=	selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$FeedBackwupSqlRow['id']."' AND `visit_id` = '".$FeedBackwupSqlRow['visit_id']."'");

$_SESSION['conclusion_type']=$FeedBackwupSqlRow['conclusion_type'];

$_SESSION['feedback_Explode_id'][$FeedBackuniqueCode]		=	$FeedBackwupSqlRow['id'];

$_SESSION['feedback_date_created'][$FeedBackuniqueCode]				=	$FeedBackwupSqlRow['date_created'];

$_SESSION['feedbackstatus'][$FeedBackuniqueCode]		=	$FeedBackwupSqlRow['lead_status'];

$_SESSION['assign_feedback_user_id'][$FeedBackuniqueCode]	=	$FeedBackwupSqlRow['assign_user_id'];												

$_SESSION['feedback_Explode_visit_id'][$FeedBackuniqueCode]	=	$FeedBackwupSqlRow['visit_id'];






		$FeedBackList	='<tr  style="background-color:#3C8DBC; color:#fff;font-family:"Source Sans Pro","Helvetica Neue",Helvetica,Arial,sans-serif;   ">';

		

		$FeedBackList	.='<td>';

$FeedBackList	.='  <a href="javascript://" onClick="showFeedBack('.$Expand.','.$CountFeedBack.')" style="color:#fff; text-transform: uppercase;"><i class="fa fa-plus-square"> &nbsp;';

$FeedBackList	.='<input type="hidden" id="FeedBack_'.$Expand.'_img"  border="0">';

 

 

 $FeedBackList	.=selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$FeedBackwupSqlRow['hotel_id']."'").'</a></td>';

		

		 $FeedBackList	.='<td>'.selectColumn(TBL_FEEDBACK_DETAILS_EXPLOAD,'summary'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$FeedBackwupSqlRow['id']."' AND `visit_id` = '".$FeedBackwupSqlRow['visit_id']."'").'</td>';

			   

		

		//$FeedBackList	.='<td>'.date('d M Y',strtotime($FeedBackwupSqlRow['date_created'])).'</td>';

		$FeedBackList	.='<td>'.date('d M Y',strtotime($FeedBackwupSqlRow['dated'])).'</td>';

		

		$FeedBackList	.='<td>'.selectColumn(TBL_USERS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_SESSION['assign_feedback_user_id'][$FeedBackuniqueCode]."'").'</td>';

		$StatusFeedBack		=	$FeedBackwupSqlRow['lead_status']==1?'Open':'Close';
		$StatusEs2			=	$FeedBackwupSqlRow['lead_status']==1?'btn-success':'btn-danger';

$FeedBackList .= '<td id="ChangeButton_'.$FeedBackwupSqlRow['id'].'"><button data="'.$FeedBackwupSqlRow['id'].'" class="btn '.$StatusEs2.'" type="button" onclick="OpenPopup('.$FeedBackwupSqlRow['lead_status'].','.$FeedBackwupSqlRow['id'].','.$FeedBackwupSqlRow['visit_id'].','.$FeedBackwupSqlRow['hotel_id'].','.$FeedBackwupSqlRow['type'].');"    >'.$StatusFeedBack.'</button>

</td>';





		  /*$FeedBackList	.='<td> <a class="btn btn-danger btn-sm" href="javascript:void(0);" id="'.$FeedBackuniqueCode.'" onclick="ajaxFeedBAckRemove($(this).attr(\'id\'));");" style="font-weight: 400;font-size: 14px;">

				  <i class="fa fa-trash-o fa-lg"></i> </a></td>';*/

		



 

 

$FeedBackList	.='<tr><td colspan="9" style="padding-bottom:0px;padding-top:0px;"> ';                 

$FeedBackList	.='<div id="div'.$Expand.'"></div>                 

                  

                  <div id="FeedBack_'.$Expand.'" style="display:none;">				  

				  

                <table id="example2" class="table table-bordered table-striped">

                  <tr style="background-color:#3C8DBC; color:#fff;">

                    <th>S.No</th>                  

                    <th>Date</th>

                     <th>FeedBack Summary</th>

                                       

                  </tr>';

 

 $NextFeedBAckUpSql = executeSql("SELECT  `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.*  FROM `".TBL_FEEDBACK_DETAILS_EXPLOAD."`  WHERE `".TBL_FEEDBACK_DETAILS_EXPLOAD."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `details_id` = '".$FeedBackwupSqlRow['id']."' AND `visit_id` = '".$FeedBackwupSqlRow['visit_id']."'  ");

		

		if(num_rows($NextFeedBAckUpSql) > 0){

		$FeedBackupNext=1;

		while($NextFeedBackRow = $db->fetch_assoc2($NextFeedBAckUpSql)){

			$feedBackExplodeDated	=	$NextFeedBackRow['dated'];

			

		$_SESSION['feedback_Explode_Description'][$FeedBackuniqueCode][$feedBackExplodeDated]	=	$NextFeedBackRow['summary'];	

		

			

		

				$FeedBackList	.='<tr >

                    <th>'.$FeedBackupNext++.'</th>                  

                    <th>'.$NextFeedBackRow['dated'].'</th>

                     <th>'.$NextFeedBackRow['summary'].'</th>

                                        

                  </tr>';

				  

				   

					}

			

			}

  $FeedBackList	.='</table>

               

				  

				  

				  </div></td></tr>';

 

 

 

		

		echo $FeedBackList	.='</tr>';

			}

			?>

            

            

            

            </tbody>

                </table></div>

                </div>

              <?php }else {?>

               

               <div class="box" id="showFeedBack">

			   

			   <?php } ?> 

        

         

 

  

  

               <?php }else {?>

               

               <div class="box" id="showFeedBack">

			   

			   <?php } ?>          

                

               

 

                

             <div id="OpenListPopUpshow" class="well" style="display:none;"> </div>  

               

                <!---FeedBack --End----------------------------------------------------> 

                

              </div>

              

               <br/><br/> 
  

              

              <label for="StatFrom">Conveyance	</label>

                 <div class="btn btn-default" style="text-align:left;width:100%;top-border:none;">

             <div class="col-md-5">                  

                <div class="form-group">

                  <label for="StatFrom">Area Covered</label>

                  
                  <textarea rows="5" <?php echo $readonly ?>  class="form-control" automcomplete="off" name="StatFrom" id="StatFrom" data-parsley-errors-container="#StatFromError"><?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->StatFrom.($row->StatTo!=""?"-".$row->StatTo:""));?></textarea>

                  <?php echo $err_StatFrom;?> </div>

                  </div>

                  

                  <!--<div class="col-md-2">

                <div class="form-group">

                  <label for="StatTo">To</label>

                  <input type="text" class="form-control" name="StatTo" id="StatTo"  value="<?php if($_POST) echo $_POST['StatTo'];else echo stripslashes($row->StatTo);?>"  placeholder="Enter To" automcomplete="off"  data-parsley-errors-container="#StatToError">



                  <?php echo $err_StatTo;?> </div>

                  </div>-->
                  <div class="col-md-3"> 
                    <div class="form-group">
                    <label for="userlevelId">Travel Mode 
                     </label> <br>

                    <?php $categoryDropDown = '<select class="form-control select2" name="travelMode" id="travelMode"  $readonly  >
                          <option value="0">Select Travel Mode</option>
                          ';
                        $resUserLevel = selectSql(TBL_TRAVEL_MODES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
                        if($db->num_rows2($resUserLevel)){
                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                       
                          if($_REQUEST['travelMode'] == $resultUserLevel->id){

                            $selected = 'selected="selected"';

                          }elseif($row->id_travel_mode == $resultUserLevel->id){

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




                  <div class="col-md-2">

                <div class="form-group">

                  <label for="KmsRun">Kms Run</label>

                  <input type="number" <?php echo $readonly ?> class="form-control calTotal" name="KmsRun" id="KmsRun"  value="<?php if($_POST) echo $_POST['KmsRun'];else echo stripslashes($row->KmsRun);?>"  placeholder="Enter Kms Run" automcomplete="off"  data-parsley-errors-container="#KmsRunError">

                  <?php echo $err_KmsRun;?> </div>

                  </div>







                  <div class="col-md-2">

                <div class="form-group">

                  <label for="RateKm">Rate/Km or Actuals</label>

                  <input type="number" <?php echo $readonly ?> class="form-control calTotal" name="RateKm" id="RateKm"  value="<?php if($_POST) echo $_POST['RateKm'];else echo stripslashes($row->RateKm);?>"  placeholder="Enter Rate / Km" automcomplete="off"  data-parsley-errors-container="#RateKmError">



                  <?php echo $err_RateKm;?> </div>

                  </div>

                
                

                



                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Parking">Parking / Toll Charges</label>

                  <input type="number" <?php echo $readonly ?> class="form-control calTotal" name="Parking" id="Parking"  value="<?php if($_POST) echo $_POST['Parking'];else echo stripslashes($row->Parking);?>"  placeholder="Enter Parking" automcomplete="off"  data-parsley-errors-container="#ParkingError">



                  <?php echo $err_Parking;?> </div>

                  </div>

                  <div class="col-md-2">

                <div class="form-group">

                  <label for="Total">Total</label>

                  <input  type="hidden" class="form-control" name="Total"   value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">

                  <input  type="text"  disabled="disabled" class="form-control"  id="Total"  value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Total" automcomplete="off"  data-parsley-errors-container="#TotaleError">




                  <?php echo $err_Total;?> </div>

                  </div>
                  <div class="col-md-2">

                  <div class="form-group">

                  <label for="Total">Entertainment</label>

                  <input type="number"  <?php echo $readonly ?> class="form-control" name="entertainment" id="entertainment"  value="<?php if($_POST) echo $_POST['entertainment'];else echo stripslashes($row->entertainment);?>"  placeholder="Enter Entertainment " automcomplete="off"  data-parsley-errors-container="#EntertainmentError">

                  <?php echo $err_Total;?> </div>

                  </div>

                  <div class="col-md-2">

                  <div class="form-group">

                  <label for="Lunch">Lunch</label>

                  <input type="number" <?php echo $readonly ?> class="form-control calTotal" name="lunch" id="lunch"  value="<?php if($_POST) echo $_POST['lunch'];else echo stripslashes($row->lunch);?>"  placeholder="Enter Lunch " automcomplete="off"  data-parsley-errors-container="#LunchError">
                  <?php echo $err_Total;?> </div>

                  </div>


<div class="col-md-12">                  

                <div class="form-group">

                  <label for="Remarks">Remarks (If Any)</label>

                  
                  <textarea rows="2" <?php echo $readonly ?>  class="form-control" autocomplete="off" name="conveyance_remarks" id="conveyance_remarks" ><?php if($_POST) echo $_POST['conveyance_remarks'];else echo stripslashes($row->conveyance_remarks);?></textarea>

                  </div>

                  </div>
</div>
<br/><br/>





<?php /* if($perData->conveyance_approved == 1){ ?>

<div class="form-group" style="padding: 20px 20px; border:1px solid #C0C0C0;border-radius: 5px;">



        <div class="form-group">

                  <label for="name">Conveyance Apporved<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['conveyance_approved'] == '1'){echo "checked";}else{if($row->conveyance_approved == 1)echo "checked";}?> value="1" name="conveyance_approved"/> Approved

         <input type="radio" class="flat-red" <?php if($_POST['conveyance_approved'] == '0'){echo "checked";}else{if($row->conveyance_approved == 0)echo "checked";}?> value="2" name="conveyance_approved"/> Not Approved

        <?php echo $err_name;?>

                </div>



        <label for="email" >Supervisor Remarks</label>

        <textarea class="form-control" name="supervisor_remarks" id="supervisor_remarks"    rows="2" placeholder="Enter Supervisor Remarks" automcomplete="off"  data-parsley-required data-parsley-errors-container="#supervisor_remarksError"><?php if($_POST) echo $_POST['supervisor_remarks'];else echo stripslashes($row->supervisor_remarks);?>

</textarea>

       <?php } echo $err_supervisor_remarks;*/ ?></div>





              <!-- /.box-body -->

              <div class="box-footer">

              <input type='hidden' id="Save" value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save">

                <input type='button' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' <?php echo $disabledEdit; ?> class="btn btn-primary" name="Save" onClick="SaveSalesReport();" />

                <!--<a href="#" id="my_popup_yes" class="btn btn-success"  style="display: none;"><i class="fa fa-plus-square "></i>&nbsp;Add Another</a>-->
                <?php if($_REQUEST['eId']==''){ ?>
                  <a href="#" id="my_popup_preview" class="btn btn-info" style="display: none;"><i class="fa fa-window-maximize"></i>&nbsp;Preview</a>
                  <a href="#"id="my_popup_no" class="btn btn-warning" style="display: none;"><i class="fa fa-paper-plane"></i>&nbsp;Mail</a>
                <?php 
                }
                else{
                ?>  
                <a href="#" id="my_popup_preview" class="btn btn-info" ><i class="fa fa-window-maximize"></i>&nbsp;Preview</a>
                <a href="#"id="my_popup_no" class="btn btn-warning" ><i class="fa fa-paper-plane"></i>&nbsp;Mail</a>
                <?php }?>
                
                <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("ManagervisitReport.php"); '/>

              </div>

            </form>

          </div>

          <!-- /.box --> 

        </div>

      </div>

      

      <!-- /.row --> 

    </section>

    <!-- /.content --> 

  </div>

  

  <!--<div id="bookedby" class="well">

    <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >

       <div class="form-group">

        <label class="title">Title</label>

        <select name="title"  class="form-control input-sm" data-parsley-required >

           <option value="">-Select-</option>

           <option value="Dr.">Dr.</option>

           <option value="Miss.">Miss.</option>

           <option value="Mr.">Mr.</option>

           <option value="Mrs.">Mrs.</option>

           <option value="Ms.">Ms.</option>

           <option value="Pr.">Pr.</option>

           <option value="Prof.">Prof.</option>

           <option value="Rev.">Rev.</option>

         </select>

      </div>

       <div class="form-group">

        <label for="first_name">First Name </label>

        <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required>

      </div>

       <div class="form-group">

        <label for="last_name">Last Name</label>

        <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="">

      </div>

       <div class="form-group">

        <label for="email" >Email Id</label>

        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">

      </div>

       <div class="form-group">

        <label for="mobile" >Mobile No.</label>

        <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">

      </div>

       <input  type="button" class="btn btn-default" onClick="saveBookedbyPopupform();" value="Save">

       <button class="bookedby_close btn btn-default">Close</button>

     </form>

  </div>-->
<div id="bookedby" class="well" style="width:50%;">
  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
    <?php $id_contact=selectColumn(TBL_CUSTOMER,'id_customer','WHERE id_company="'.$row->id_company.'" AND id_customer="'.$row->id_contacts.'" ');?>
  <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="<?php echo $id_contact; ?>" >
  
   <div class="form-group col-sm-4">
        <label >Title <font color="#FF0000">*</font></label>
        <select name="Nametitle" id="Nametitle"  class="form-control input-sm" data-parsley-required >
           <option value="">-Select-</option>
           <option value="Dr.">Dr.</option>
           <option value="Miss.">Miss.</option>
           <option value="Mr.">Mr.</option>
           <option value="Mrs.">Mrs.</option>
           <option value="Ms.">Ms.</option>
           <option value="Pr.">Pr.</option>
           <option value="Prof.">Prof.</option>
           <option value="Rev.">Rev.</option>
         </select>
      </div>
    <div class="form-group col-sm-4">
      <label for="first_name">First Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
   <div class="form-group col-sm-4">
      <label for="last_name">Last Name <font color="#FF0000">*</font></label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>
    </div>
   <div class="form-group col-sm-4">
      <label for="email" >Email Id <font color="#FF0000">*</font></label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off" data-parsley-required>
    </div>
   <div class="form-group col-sm-4">
      <label for="mobile" >Mobile No. <font color="#FF0000">*</font></label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off" data-parsley-required>
    </div>
    
    
     <div class="form-group col-sm-4">
      <label for="first_name">Designation <font color="#FF0000">*</font></label>
       <?php $marketDropDown = '<select class="form-control input-sm" name="designation" id="designation" data-parsley-errors-container="#designationError" data-parsley-required   >
                          <option value="">Select Designation</option>';
                         
                          $resCat = selectSql(TBL_DESIGNATION_MASTER," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');                   
                          if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                              
                            $marketDropDown .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                          }
                          }
                          echo $marketDropDown .= '</select>';
                          ?>      
    </div>
    
    
      <div class="form-group col-sm-6">

        <label for="mobile" style="width: 100%;">DOB.</label>
<select name="dateofBirthMonth" id="dateofBirthMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
         <?php 
     for($i = 1; $i <= 12; $i++){
    $dt = DateTime::createFromFormat('!m', $i);
    echo "<option value=\"$i\">".$dt->format('F')."</option>";
}
     ?>
</select>

<select name="dateofBirthday" id="dateofBirthday" class="form-control" style="width:50%;">
         <option value="">Day</option>
     <?php 
     for($Birthday = 1; $Birthday <= 31; $Birthday++){
    echo "<option value=\"$Birthday\">$Birthday</option>";
} 
     ?>
</select>

      </div>
      
     
      
     <div class="form-group col-sm-6">

        <label for="mobile" style="width: 100%;" >DOA.</label>

        <select name="dateofanniversaryMonth" id="dateofanniversaryMonth" class="form-control" style="width:50% !important; float:left">
        <option value="">Month</option>
         <?php 
     for($i = 1; $i <= 12; $i++){
    $dt = DateTime::createFromFormat('!m', $i);
    echo "<option value=\"$i\">".$dt->format('F')."</option>";
}
     ?>
</select>

<select name="dateofanniversaryday" id="dateofanniversaryday" class="form-control" style="width:50%;">
         <option value="">Day</option>
     <?php 
     for($DayS = 1; $DayS <= 31; $DayS++){
    echo "<option value=\"$DayS\" >$DayS</option>";
} 
     ?>
</select>
      </div>
     <div class="form-group col-sm-12" align="left"> 
    <input  type="button" class="btn btn-default" onClick="saveRateCustomerPopupform();" value="Save">
    <button class="bookedby_close btn btn-default">Close</button>
    </div>
  </form>
</div>  

 <div id="guest" class="well">

    <form id="guestpopupform" data-parsley-validate autocomplete="off" method="post"  >

       <div class="form-group">

        <label class="title">Title</label>

        <select name="title"  class="form-control input-sm" data-parsley-required >

           <option value="">-Select-</option>

           <option value="Dr.">Dr.</option>

           <option value="Miss.">Miss.</option>

           <option value="Mr.">Mr.</option>

           <option value="Mrs.">Mrs.</option>

           <option value="Ms.">Ms.</option>

           <option value="Pr.">Pr.</option>

           <option value="Prof.">Prof.</option>

           <option value="Rev.">Rev.</option>

           <option value="Group.">Group.</option>

         </select>

      </div>

       <div class="form-group">

        <label for="first_name">First Name</label>

        <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required >

      </div>

       <div class="form-group">

        <label for="last_name">Last Name</label>

        <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>

      </div>

       <div class="form-group">

        <label for="email" >Email Id</label>

        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">

      </div>

       <div class="form-group">

        <label for="mobile" >Mobile No.</label>

        <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">

      </div>

       <div class="form-group">

        <label for="id_country" >Country</label>

        <select class="form-control" name="id_country" id="id_country" data-parsley-required>

           <option value="">Select Country</option>

           <?php 

						$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

									  

										while($resultCat = $db->fetch_object2($resCat)){

											

													

											$countryDropDown .= '<option  value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';

									  }

												  echo $countryDropDown;

									

									 ?>

         </select>

      </div>

       <div class="form-group">

        <label class="user_type">Guest type</label>

        <select name="user_type"  class="form-control input-sm"  >

           <option value="">-Select-</option>

           <option value="VIP">VIP</option>

           <option value="CIP">CIP</option>

         </select>

      </div>

       <input  type="button" class="btn btn-default" onClick="saveGuestPopupform();" value="Save">

       <button class="guest_close btn btn-default">Close</button>

     </form>

  </div>

   

  <span class="my_popup_open" style="display:none;"></span>

  <div id="my_popup" class="well">

    <div id="FollowUpNextUpdate"></div>

    <button id='my_popup_ok'  class="my_popup_close btn btn-default pull-left">Yes</button>
    <button id='alertForMail'  class="my_popup_close btn btn-default pull-left">No</button>

    <!--<button  style="margin-left: 5px;" class="btn btn-default pull-left">Preview</button>
    <button id="my_popup_no" style="margin-left: 5px;" class="my_popup_close btn btn-default pull-left">Close and send mail</button>-->

  </div>
  
   <span class="enquiry_failed_open" style="display:none;"></span>
    <div id="enquiry_failed" class="well">
      <div id="FollowUpNextUpdateerror"> </div>
      <br />
      <button id="enquiry_failed_no" style="margin-left: 5px;" class="enquiry_failed_close btn btn-default pull-left">Close</button>
      <button class="enquiry_failed_close btn btn-default pull-right"></button>
    </div>

  



<script language="javascript">
  

 function showContent(content,numRows)

 {

	 //alert(content='section_'+content);

	 var content='section_'+content;

	 sections = new Array("section_1","section_2","section_3","section_4","section_5","section_6","section_7","section_8","section_9","section_10","section_11","section_12","section_13","section_14","section_15","section_16","section_17","section_18","section_19","section_20","section_21","section_22","section_23","section_24","section_25","section_26","section_27","section_28","section_29","section_30","section_31","section_32","section_33","section_34","section_35","section_36","section_37","section_38","section_39","section_40","section_41","section_42","section_43","section_44","section_45","section_46","section_47","section_48","section_49","section_50","section_51","section_52","section_53","section_54","section_55","section_56","section_57","section_58","section_59","section_60","section_61","section_62","section_63","section_64","section_65","section_66","section_67","section_68","section_69","section_70","section_71","section_72","section_73","section_74","section_75","section_76","section_77","section_78","section_79","section_80","section_81","section_82","section_83","section_84","section_85","section_86","section_87","section_88","section_89","section_90","section_91","section_92","section_93","section_94","section_95","section_96","section_97","section_98","section_99","section_100");

 

			 for(i=0; i<sections.length; i++){

				

						 if(document.getElementById(sections[i]).style.display == "none" && sections[i] == content){

						 document.getElementById(sections[i]).style.display = "block";

						 document.getElementById(sections[i]+"_img").src = "fa-minus";

						 }else{

						 document.getElementById(sections[i]).style.display = "none";

						 document.getElementById(sections[i]+"_img").src = "fa-plus";

						 }

			

			 }

			 

			

 }

  function showFeedBack(content,numRows)

 {

	 //alert(content='section_'+content);

	 var content='FeedBack_'+content;

	 sections = new Array("FeedBack_1","FeedBack_2","FeedBack_3","FeedBack_4","FeedBack_5","FeedBack_6","FeedBack_7","FeedBack_8","FeedBack_9","FeedBack_10","FeedBack_11","FeedBack_12","FeedBack_13","FeedBack_14","FeedBack_15","FeedBack_16","FeedBack_17","FeedBack_18","FeedBack_19","FeedBack_20","FeedBack_21","FeedBack_22","FeedBack_23","FeedBack_24","FeedBack_25","FeedBack_26","FeedBack_27","FeedBack_28","FeedBack_29","FeedBack_30","FeedBack_31","FeedBack_32","FeedBack_33","FeedBack_34","FeedBack_35","FeedBack_36","FeedBack_37","FeedBack_38","FeedBack_39","FeedBack_40","FeedBack_41","FeedBack_42","FeedBack_43","FeedBack_44","FeedBack_45","FeedBack_46","FeedBack_47","FeedBack_48","FeedBack_49","FeedBack_50","FeedBack_51","FeedBack_52","FeedBack_53","FeedBack_54","FeedBack_55","FeedBack_56","FeedBack_57","FeedBack_58","FeedBack_59","FeedBack_60","FeedBack_61","FeedBack_62","FeedBack_63","FeedBack_64","FeedBack_65","FeedBack_66","FeedBack_67","FeedBack_68","FeedBack_69","FeedBack_70","FeedBack_71","FeedBack_72","FeedBack_73","FeedBack_74","FeedBack_75","FeedBack_76","FeedBack_77","FeedBack_78","FeedBack_79","FeedBack_80","FeedBack_81","FeedBack_82","FeedBack_83","FeedBack_84","FeedBack_85","FeedBack_86","FeedBack_87","FeedBack_88","FeedBack_89","FeedBack_90","FeedBack_91","FeedBack_92","FeedBack_93","FeedBack_94","FeedBack_95","FeedBack_96","FeedBack_97","FeedBack_98","FeedBack_99","FeedBack_100");

 

			 for(i=0; i<sections.length; i++){

				

						 if(document.getElementById(sections[i]).style.display == "none" && sections[i] == content){

						 document.getElementById(sections[i]).style.display = "block";

						 document.getElementById(sections[i]+"_img").src = "fa-minus";

						 }else{

						 document.getElementById(sections[i]).style.display = "none";

						 document.getElementById(sections[i]+"_img").src = "fa-plus";

						 }

			

			 }

			 

			

 }

 </script>



 

 <style type="text/css">

 a,a:active,a:focus{

 text-decoration: none;

 outline: none;

 color: #000000;

 font-family: Verdana, Arial, Helvetica, sans-serif;

 }

 .heading{

 font-size: 13px;

 font-family: Verdana, Arial, Helvetica, sans-serif;

 font-weight:bold;

 color: #000000;

 background-color: #EDEDED;

 border: 1px #D1D1D1 solid;

 list-style-type: disc;

 padding: 5px;

 margin-bottom:5px;

 margin-top:5px;

 height:15px;

 }

 </style>

   

   <script>

	var GetStartDate=<?php echo $dsrNoDays; ?>;

<?php if($row->id != ''){ ?>



window.onload = function() { 		

		getExecutiveName(<?php echo $row->id_company; ?>,<?php echo $row->id_contacts; ?>);

							

							};

							

<?php } ?>	



</script>

  <?php include_once("includes/footer.php")?>

  <script type="text/javascript">
  
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
//COMPANY AUTO COMPLETE START==================================================================


    /*$('#StatFrom').change(function(){
      var chkDet = $('#StatFrom').val();
      if(chkDet!=""){
        $('#travelMode').attr("data-parsley-required",true);
      }
      else{
        $('#travelMode').removeAttr("data-parsley-required");
      }
    });*/
  </script>
  
 <script type="text/javascript">
   function chkStatus(value){
     if(value==1){
      $('.makeHide').show();
     }
     else{
      $('.makeHide').hide();
     }
  }

  
  
  function areaExecutive(){
    var cmp_id = $('#id_company').val();
     $.ajax({
     type        : 'POST',
     url         : 'ajax/ajaxAreaExecutive.php', 
     data        : 'id_company='+cmp_id,
     success     : function(data){
       $("#areaExe").html(data);
     } 
    })
  }
  areaExecutive();
  
  function chkStatus2(value){
     if(value==1){
      $('.makeHide2').show();
     }
     else{
      $('.makeHide2').hide();
     }
  }
   
 /*$('#my_popup_yes').click(function(){
  //$("#form1").trigger("reset");
  $("input[name=Save]").show();
  $(this).hide();
 });*/

  $('#alertForMail').click(function(){
    alert('Please Click On Mail Button To Send DSR.');
  });

  $('#my_popup_no').click(function(){
    //window.location.href='editDailyReport.php';
    var date = $("#report_date").val();
    
    $( ".my_popup_open" ).click();
    $( "#FollowUpNextUpdate" ).html('Please Wait DSR Mailing In Progress...'); 

    $( "#my_popup_ok" ).hide();
    $('#alertForMail').hide();

     
   if(date !='')  {   
     $.ajax({
       type        : 'POST',
       url         : 'ManagervisitReport.php', 
       data        : 'searchFormSubmit=1&Download=Download&location=set&report_date='+date+' to '+date,
        success     : function(data){
          //console.log(data);
          $.ajax({
           type        : 'POST',
           url         : 'ajax/ajaxSendDsrMail.php', 
           data        : 'report_date='+date,
           success     : function(result){
             alert (result);
             window.location.href='editDailyReport.php';
           } 
          });
       } 
      })
   }
   else{
    alert('date not fetched');
   }

  });




 </script>
   
      
   
  

  <script type="text/javascript">

     $(".calTotal").change(function(){

        var kms = $("#KmsRun").val();
        if(kms=="")
            kms=0;

        var rateKm = $("#RateKm").val();
           if(rateKm=="")
            rateKm=0; 
        var park = $("#Parking").val();
        var lunch= $("#lunch").val();
        var Total   = Number(kms*rateKm)+Number(park);
        $("#Total").val(Total); 
        $('input[name="Total"]' ).val(Total);

     });

     $("#my_popup_preview").click(function(){
        var date = $('#report_date').val();
        window.open('ManagervisitReport.php?searchFormSubmit=1&Download=Download&location=open&report_date='+date+' to '+date+'&usernameid=<?=$_SESSION['userId']?>');
     });

   </script>

  <script>

  

  var count=0;

 function SaveSalesReport(){

	 var form=$("#form1");

	if(form.parsley().validate()){

	$('.loading').show(); 


	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxUpdateSalesReportDemo.php',	   

	   data: form.serialize(), 

	   success: function (result) {

			data = JSON.parse(result);
			if(data.status==1){
				 $( ".enquiry_failed_open" ).click();
				$( "#FollowUpNextUpdateerror" ).html(data.Message);
				
				exit;
			}else{
			//alert(data.Message);

			//alert(data.InsertVisitID);
		    $( ".my_popup_open" ).click();
			$( "#FollowUpNextUpdate" ).html(data.Message);	
			}
		   
		   //$( "#recentVisitId" ).html(data.InsertVisitID);

		   var recentId = $("#recentVisitId").val();
		   
       if(recentId==""){
         recentId = '<?php echo addslashes(encryptor('decrypt',$_REQUEST['eId']))?>';
       }
       else{
         recentId = $("#recentVisitId").val();
       }
       //console.log(recentId);
       $.ajax({
        type        : 'POST',
        url         : 'ajax/ajaxRecentVisitList.php', 
        data        : 'id='+recentId+'&count='+count,
        success     : function(data){
         //console.log(data);
         $("#recentAdd").show();
         $("#recentAddData").append(data);
         $("#removeForNew").remove();
         $("#removeForNew2").remove();
         $("#removeForEdit2").remove();
         $("#removeForEdit").remove();
         //$("#form1").trigger("reset");
         $("#listingForm").trigger("reset");
         $("#id_company").prepend("<option value='' selected='selected'>Select Company</option>");
         $("#id_contacts").prepend("<option value='' selected='selected'>Select Person Met</option>");
         $("#business_potential").val("");
         $("#discussion_summary").val("");
         $("#conveyance_remarks").val("");
         $("#StatFrom").val("");
         $("#KmsRun").val('0');
         $("#RateKm").val('0');
         $("#Parking").val('0');
         $("#Total").val('0'); 
         $("#lunch").val('0');
         $('input[name="Total"]' ).val("0");
         $("#entertainment").val('0');
         $("#eId").val("");
         $("#user_id").val("");
         $( 'input[name="Save"]' ).val("Add");
         //$('#my_popup_yes').show();
         $('#my_popup_no').show();
         $('#my_popup_preview').show();
         $("#areaExe").html('');
         //$('input[name=Save]').hide();
         count++;
        } 
       }); 

		   if(FollowupCoditionType == 'addfollowup'){

				$('#showFolowup').html(result);		   

			   }

		  if(FollowupCoditionType == 'addfeedback'){

				$('#showFeedBack').html(result);		   

			   }	   
        


		  //

		},

	  complete: function(){		  

		$('#OpenListPopUpshow').popup('hide');

		

	  }

	});

	return false;

	}

}







 function ajaxRemovefollowup(){

	 

	$('#OpenListPopUpshow').popup('hide'); 

	}





function ajaxAddNewFollowup(followup_status){

	   var color = '#6c8dbc';

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxAddFollowUp.php',

		   data: 'followup_status='+followup_status+'&color='+color, 

		   success: function (result) {	
        //console.log(result);	
					$('#OpenListPopUpshow').html(result);		
					$('#OpenListPopUpshow').popup('show');
							

				/*resultArray = result.split('|||');	

								

					$('#showOtherCharges').append(resultArray['1']);

					$('#pricingValue').html(resultArray['2']);

					$('#addRoommsg').css('display', 'none');

					$('#flatDiscount').val();

					$('#percentDiscount').val();

					$('#flatAdditionalCharges').val();

					$('#percentAdditionalCharges').val();	*/			

			}

		})



}

function ajaxAddFeedBack(followup_status){

	

	

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxAddFollowUp.php',

		   data: 'followup_status='+followup_status+'&color=#f7640e', 

		   success: function (result) {		

		   

		   		$('#OpenListPopUpshow').html(result);		

					$('#OpenListPopUpshow').popup('show');		   			

							

			}

		})



}





function saveAddFollowupPopUpform(){

	

	var FollowupCoditionType	=	$("#FollowupCoditionType").val();

	

	var form=$("#AddFollowPopUpForm");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxDateFollowupList.php',

	   

	   data: form.serialize()+'&FollowupCoditionType='+FollowupCoditionType, 

	   success: function (result) {

		   if(FollowupCoditionType == 'addfollowup'){

				$('#showFolowup').html(result);		   

			   }

		  if(FollowupCoditionType == 'addfeedback'){

				$('#showFeedBack').html(result);		   

			   }	   



		  //

		},

	  complete: function(){		  

		$('#OpenListPopUpshow').popup('hide');

	  }

	});

	return false;

	}

}



function ajaxFollowupRemove(uniqueCode){	



		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxUpdateSessionFollowupEditPage.php',

			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode, 

			   success: function (result) {	

			    

				$('#showFolowup').html(result);		   

			   /*}

		  		if(FollowupCoditionType == 'Removefeedback'){

				$('#showFeedBack').html(result);		   

			   }	*/ 

			   

					

				}

		});

}



function ajaxFeedBAckRemove(uniqueCode){	



		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxUpdateSessionFeedBackEditPage.php',

			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode, 

			   success: function (result) {	

			    

				$('#showFeedBack').html(result);	   

			   /*}

		  		if(FollowupCoditionType == 'Removefeedback'){

				$('#showFeedBack').html(result);		   

			   }	*/ 

			   

					

				}

		});

}



 function ajaxAddNextFollowup(followup_status){

	

	var followupCode = $("#followupCode").val();

	

	var rate_id = $("#rate_id").val();

	var hotel_id = $("#hotel_id").val();





		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxAddNextFollowUp.php',

		   data: 'followup_status='+followup_status+'&followupCode='+followupCode, 

		   success: function (result) {					

				resultArray = result.split('|||');

													

					$('#AddNextFollowup_'+resultArray['1']).append(resultArray['0']);

									

			}

		})

}











function ajaxOtherChargesRemove(uniqueCode){	

		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxRemoveFollowUp.php',

			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode, 

 success: function (result) {	

			   resultArray = result.split('|||');			

				 $('#'+uniqueCode).remove();

				 	if(resultArray['1']=='removeroomLimitMsg'){

						$('#roomLimitMsg').css('display', 'none');	

					}

					if(resultArray['2']=='roomLimitMsgRoomType'){

						$('#roomLimitMsgRoomType').css('display', 'none');	

					}

					$('#pricingValue').html(resultArray['0']);

					$('#flatDiscount').val();

					$('#percentDiscount').val();

					$('#flatAdditionalCharges').val();

					$('#percentAdditionalCharges').val();

					

					

				}

		});

}



  </script>

 

 



<script>

 function ajaxAddothercharges(rate_id,rate_assign_id,room_id,rate_plan_id,type){

	var reservation_date = $("#reservation_date").val();

	var rate_id = $("#rate_id").val();

	var hotel_id = $("#hotel_id").val();

		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxAddFollowUp.php',

		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type, 

		   success: function (result) {					

				resultArray = result.split('|||');					

					$('#showOtherCharges').append(resultArray['1']);

					$('#pricingValue').html(resultArray['2']);

					$('#addRoommsg').css('display', 'none');

					$('#flatDiscount').val();

					$('#percentDiscount').val();

					$('#flatAdditionalCharges').val();

					$('#percentAdditionalCharges').val();				

			}

		})



}





function ajaxOtherChargesRemove(uniqueCode){					

				 $('#'+uniqueCode).remove();

}

function OpenListPopup(followup_status,followup_id,daily_Visit_id,hotel_id){

	

	$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxListPopUpshow.php',

		    data: 'followup_id='+followup_id+'&daily_Visit_id='+daily_Visit_id+'&hotel_id='+hotel_id, 

		   success: function (result) {			  				

				$('#OpenListPopUpshow').html(result);		

				$('#OpenListPopUpshow').popup('show');				

			}

		})

	}



function OpenPopup(followup_status,followup_id,daily_Visit_id,hotel_id,followup_type){



		if(followup_status	== '0'){		

			alert('Your Follow Up already Closed');

			exit;

		}else{	

		

		$('#followup_id').val(followup_id);

		$('#daily_Visit_id').val(daily_Visit_id);

		$('#hotel_id').val(hotel_id);

		$('#followup_status').val(followup_status);

		$('#followup_type').val(followup_type);

		

		$('#followup_id_hidden').val(followup_id);

		$('#daily_Visit_id_hidden').val(daily_Visit_id);

		$('#hotel_id_hidden').val(hotel_id);

		$('#followup_status_hidden').val(followup_status);

		$('#followup_hidden_type').val(followup_type);



			$('#ColseSummaryPopUp').popup('show');

		}



}



$(document).on('click','.status_checks',function(){

	

	  var current_element = $(this); 

	  var status = ($(this).hasClass("btn-success")) ? '0' : '1';

	  

	  if(status	== '1'){

		  

		  alert('Your Follow Up already Closed');

		  

	 }else{

		 

	  $('#status').val(status);

	  var fs_daily_visit_followup_new	=	$(current_element).attr('data');

	  

	  $('#fs_daily_visit_followup_new').val(fs_daily_visit_followup_new);

	  

	 

	 

		$('#ColseSummaryPopUp').popup('show');

	  }

	

     /* var status = ($(this).hasClass("btn-success")) ? '0' : '1';

	 

      var msg = (status=='0')? 'Close' : 'Open';

      if(confirm("Are you sure to "+ msg)){

        var current_element = $(this);

        url = "ajax/ajaxFollowupstatusChange.php";

        $.ajax({

          type:"POST",

          url: url,

          data: {id:$(current_element).attr('data'),status:status},

          success: function(data)

          {  

		  resultArray = data.split('&&&&');		 

		  $('#ChangeButton_'+resultArray[1]).html(resultArray['0']);



          }

        });*/

      //}      

    });

	

	

function saveColseSummaryPopUpform(){	



	var form=$("#ColseSummaryPopUpForm");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxFollowupstatusChange.php',

	   data: form.serialize(), 

	   success: function (result) {

		  if(result!=''){

			    $('#followup_close_summary').val('');

		  $('#close_type').val('');

		  $('#close_status').val('');

		$('#ColseSummaryPopUp').popup('hide');

		$( ".my_popup_open" ).click();	

		   $( "#FollowUpNextUpdate" ).html(result);

		  // resultArray = result.split('&&&&');		

		  

		   // resultArray2 = resultArray[0].split('|||');	

			

		  /*$('#ChangeButton_'+resultArray[1]).html(resultArray2[0]);

		  $('#ChangeFollowUpSummary_'+resultArray[1]).html(resultArray2[1]);

		   $('#ChangeFollowupButton_'+resultArray[1]).html(resultArray2[2]);*/

			

			//$("#ColseSummaryPopUpForm").reset();

		  }

		},

	  complete: function(){

		  $('#followup_close_summary').val('');

		  $('#close_type').val('');

		  $('#close_status').val('');

		$('#ColseSummaryPopUp').popup('hide');
    $( "#my_popup_yes" ).hide();
     $( "#my_popup_no" ).hide();
     $( "#my_popup_preview" ).hide();
		$( ".my_popup_open" ).click();	

		   $( "#FollowUpNextUpdate" ).html(result);

	  }

	});

	return false;

	}

}

function ajaxAddNextFollowup(followup_id,daily_Visit_id,hotel_id){

	

	var daily_Visit_id = $("#daily_Visit_id").val();

	var hotel_id = $("#hotel_id").val();





		$.ajax({

		   type: "POST",

		   url: 'ajax/ajaxAddNextFollowUp.php',

		   data: 'followup_id='+followup_id+'&daily_Visit_id='+daily_Visit_id+'&hotel_id='+hotel_id, 

		   success: function (result) {					

				resultArray = result.split('|||');		

											

					$('#AddNextFollowup'+resultArray['1']).html(resultArray['0']);

									

			}

		})



}



function savefollowupDate() {  

  var form=$("#nextFollowup");

  if(form.parsley().validate()){

	 $('#ColseSummaryPopUp').popup('hide'); 

  	

	var nextFollowup = form.serialize(); 

	 $.ajax({

	   type: "GET",

	   url: 'ajax/ajaxfollowupCalanderUpdate.php',

	   data: nextFollowup,  

	   success: function (result) {	

	     $( "#my_popup_yes" ).hide();
        $( "#my_popup_no" ).hide();
        $( "#my_popup_preview" ).hide();
		   $( ".my_popup_open" ).click();	

		   $( "#FollowUpNextUpdate" ).html(result);

		}

	})  	

	}

	return false;

}

  </script>

  <script>

		function Toggle(id) {

			alert(document.getElementById(id).style.display);

			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {

				document.getElementById(id).style.display = "block";

			} else if (document.getElementById(id).style.display == "block") {

				document.getElementById(id).style.display = "none";

			} else {

				document.getElementById(id).style.display = "none";

			}

		}

		

		function SelectHotelsList123(HotelDuplicateInsert){

			var test = HotelDuplicateInsert;

			

		        $("div.desc").hide();

		        $("#cars" + test).show();

			}

		

</script>

<script>

  $( function() {	 

    $( ".datepickertest").datepicker();

	$( "#datepicker" ).datepicker();



  } );

  

  



  </script>

  <div id="ColseSummaryPopUp" class="well" style="display:none;">

    <div id="" class="ajaxAddRoom">

        <div class="btn btn-default tablenew1 tablenewmobile1">

        <div class="col-md-9">

            <div class="form-group" style="text-align:left;">

            

            







          

            <label>Follow Up/Feedback Status </label>

            <br>

            <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList123(1);"   value="1"  checked="checked" />

            1) Action Required

            <input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert" onClick="SelectHotelsList123(2);" value="2" />

            2) Close</div>

          </div>

        <div id="cars1" class="desc">

            <form name="nextFollowup" id="nextFollowup"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

            

<input type="hidden" name="followup_id" id="followup_id" value="">

<input type="hidden" name="daily_Visit_id" id="daily_Visit_id" value="">

<input type="hidden" name="hotel_id_hidden" id="hotel_id" value="">

<input type="hidden" name="followup_status" id="followup" value="">

<input type="hidden" name="followup_type" id="followup_type" value="">

<div class="form-group">

                

              <label style="float:left;">Follow Up/Feedback Summary</label>

                                                   

                <textarea   name="followup_description" id="followup_description"  class="form-control" placeholder="Follow Up/Feedback Summary"  data-parsley-required automcomplete="off"></textarea>

                

                

              </div>

              <div class="form-group">

                <input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="<?php echo date('d-m-Y');?>"  data-parsley-required>

              </div>

           <?php   $availableData .='<div class="form-group"><label style="float:left;">Assign To</label>';

               

              

                 $availableData .= '<select class="form-control select2" name="assign_user_id" id="assign_user_id">

											  								<option value="">Select Assign UserName</option>';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND id IN (".$_SESSION['userId'].") ",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';

												}

											  }

											 	 $availableData .= '</select>';

                                              

                                              	 echo $availableData .='</div>';

												 

												 ?>

												

            

            

            <div class="form-group" style="float:left;">

                <button class="btn btn-primary" onclick="savefollowupDate();" type="button">Save</button>

              &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>

              </div>

            '

          </form>

          </div>

          

          

        <div id="cars2" class="desc" style="display: none;">

            <form id="ColseSummaryPopUpForm" class="ColseSummaryPopUpForm" data-parsley-validate autocomplete="off">

<input type="hidden" name="followup_id_hidden" id="followup_id_hidden" value="">

<input type="hidden" name="daily_Visit_id_hidden" id="daily_Visit_id_hidden" value="">

<input type="hidden" name="hotel_id_hidden" id="hotel_id_hidden" value="">

<input type="hidden" name="followup_status_hidden" id="followup_status_hidden" value="">

<input type="hidden" name="followup_hidden_type" id="followup_hidden_type" value="">

            <div class="form-group">

                <input type="hidden" name="fs_daily_visit_followup_new" id="fs_daily_visit_followup_new" value="">

               <div class="form-group"> 

               <select name="close_type"  id="close_type" class="form-control input-sm" data-parsley-required >

									 	 <option value="">Select Close Type</option>';

											<?php  $resultClose = selectSql(TBL_CLOSING_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY name ');
				  

											  while($resultData = $db->fetch_object2($resultClose)){
											
													if($row->id_closing_type	== $resultData->id){

														$selected2 = 'selected="selected"';

													}else{

														$selected2 = '';

													}

													echo $availableDatasales = '<option   '.$selected2.' value="'.$resultData->id.'">'.ucfirst($resultData->name).'</option>';

												}

											 

											 	 

												 

												 ?></select>

                

                

                </div>

                 <div class="form-group"> 

                <textarea   name="followup_close_summary" id="followup_close_summary" class="form-control" placeholder="Close Summary"  data-parsley-required automcomplete="off"></textarea>

                </div>

                <br/>

                <div class="form-group" style="float:left;">

                  <button class="btn btn-primary" onclick="saveColseSummaryPopUpform();" type="button">Save</button>

                 &nbsp;<button class="ColseSummaryPopUp_close btn btn-default">Close</button>

                 </div>

              </div>

          </form>

          </div>

      </div>

      </div>

  </div>

