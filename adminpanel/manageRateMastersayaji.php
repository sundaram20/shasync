<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
//$hotelId='1';

if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){

 	checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'activate');
	$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));

	$statusSql = "	UPDATE `".TBL_RATE."`
					SET `status` = '1'
					,`last_modified` = '".currenDateTime()."'
					,`last_modified_by` = '".$_SESSION['userId']."'
					WHERE `id` = '".addslashes($statusId)."'";


	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_RATE."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}



		



		//$assignHotelId = selectColumn(TBL_RATE,'hotel_id'," WHERE `id` = '".$statusId."'");



		//$assignRoomId = selectColumn(TBL_RATE,'room_id'," WHERE `id` = '".$statusId."'");



	if(executeSql($statusSql)){

		$err = 0;		
		$_SESSION['successMsg'] = selectColumn(TBL_RATE,'rate_name'," WHERE `id` = '".$statusId."'").'- Rate Letter status has been changed sucessfully.';

	}else{



		$err = 1;



		$_SESSION['errorMsg'] = selectColumn(TBL_RATE,'rate_name'," WHERE `id` = '".$statusId."'").'- status has not been changed sucessfully.';



	}



}











if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'delete');

	   $checkExisting = executeSql("SELECT * FROM `".TBL_ORDERS."` where `id_rate` = '".$_REQUEST['delId']."'");
	   $checkExisting_rate_table = executeSql("SELECT * FROM `".TBL_RATE."` where `rate_category_id` = '".$_REQUEST['delId']."'");
	  $sqlDelUsers1 = selectRow(TBL_RATE," WHERE `id` = '".$_REQUEST['delId']."'");

	if(num_rows($checkExisting)!=0  || num_rows($checkExisting_rate_table)!=0 ){



	 $_SESSION['errorMsg'] = $sqlDelUsers1['rate_name'].' - Unable to delete this Rate Letter ';



		



	}else{



		$sqlDelUsers = "DELETE FROM `".TBL_RATE."` WHERE `id` = '".$_REQUEST['delId']."'";



		



		 executeSql($sqlDelUsers);	



			 $_SESSION['errorMsg'] = $sqlDelUsers1['rate_name'].' - Rate Letter has been deleted sucessfully';



	



		}



	



}







$sql = " SELECT  `".TBL_RATE."`.*, `".TBL_RATE_ASSIGN_DETAILS."`.hotel_id  FROM `".TBL_RATE."` LEFT JOIN `".TBL_RATE_ASSIGN_DETAILS."` ON `".TBL_RATE."`.id=`".TBL_RATE_ASSIGN_DETAILS."`.rate_id  WHERE `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.`company_id` = '0' ";



if($_REQUEST['searchFormSubmit'] =='1'){



if($_REQUEST['rate_level_id'] != ''){



	$sql .= " AND `".TBL_RATE."`.`rate_level_id` = '".addslashes($_REQUEST['rate_level_id'])."'";



}



if($_REQUEST['companyId'] != ''){



	$sql .= " AND `".TBL_RATE."`.`company_id` = '".addslashes($_REQUEST['companyId'])."'";



}



if($_REQUEST['seasonId'] != ''){



	$sql .= " AND `".TBL_RATE."`.`seasonId` = '".addslashes($_REQUEST['seasonId'])."'";



}



if($_REQUEST['hotelId'] != ''){



	$sql .= " AND `".TBL_RATE_ASSIGN_DETAILS."`.`hotel_id` = '".addslashes($_REQUEST['hotelId'])."'";



}



}







if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 



	 $sql .= " AND `".TBL_RATE_ASSIGN_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";



}



	$sql .= "group by `".TBL_RATE_ASSIGN_DETAILS."`.rate_id order by id desc";







// $sql;



//echo $sql;



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

      <h1> Rate Manager <small>Rate Master</small> </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Rate Master</li>

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

            <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editRateMasters.php" >Add Rate</a>

              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>

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

                <div class="form-group col-sm-6">

                  <label for="seasonId">Hotel <font color="#FF0000">*</font></label>

                  <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" '.$disabledHotel.'>



														  <option value="">Select Hotel</option>';



														if(empty($_SESSION['hotel_access'])){



															$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		



														  }else{



														  $resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}



														  if($db->num_rows2($resCat)){



															while($resultCat = $db->fetch_object2($resCat)){



																if($resultCat->id == $row->hotelId){



																	$selected = 'selected="selected"';



																}else if($_REQUEST['hotelId']== $resultCat->id){



																	$selected = 'selected="selected"';



																}else{



																	$selected = '';



																}	



																$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';



															}



														  }



															echo $hotelDropDown .= '</select>';



														  ?>

                </div>

                <div class="col-md-6">

                  <div class="form-group">

                    <label>Company</label>

                    <?php $companyDropDown = '<select class="form-control select2" name="companyId" '.$disabledCompany.'>



											    <option value="">Select Company</option>';



											  $resCat = selectSql(TBL_COMPANY,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



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

                </div>

                <div class="col-md-6">

                  <div class="form-group">

                    <label>Rate Level</label>

                    <?php $levelDropDown = '<select class="form-control select2" name="rate_level_id">



											    <option value="">Select Rate Level</option>';



											  $resCat = selectSql(TBL_RATE_LEVEL,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if($_REQUEST['rate_level_id'] == $resultCat->id){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}



													$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



												}



											  }



											 	echo $levelDropDown .= '</select>';



											  ?>

                  </div>

                </div>

                

                <!-- /.col -->

                

                <div class="form-group col-sm-6">

                  <label for="seasonId">Season<font color="#FF0000">*</font></label>

                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" >



											  <option value="">Select Season</option>';



											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){



													if($resultCat->id == $_REQUEST['seasonId']){



														$selected = 'selected="selected"';



													}else{



														$selected = '';



													}	



													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



												}



											  }



											 	echo $seasonDropDown .= '</select>';



											  ?>

                </div>

                

                <!-- /.row --> 

                

              </div>

            </div>

            

            <!-- /.box-body -->

            

            <div class="box-footer">

              <input name="Search" type="submit" class="btn btn-primary" value="Search" />

            </div>

          </form>

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Rate List</h3>

            </div>

            <form name="listingForm"  name="listingForm" action="" method="post">

              <input type="hidden" value="" name="act" />

              <div id="listingDiv"></div>

              

              <!-- /.box-header -->

              

              <div class="box-body table-responsive">

                <table id="example2" class="table table-bordered table-striped">

                  <thead>

                    <tr>

                      <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />

                        Check All&nbsp;</th>

                      <th>Rate Name</th>

                      <th>Company Name</th>

                      <th>Rate Category</th>

                      <th>Market</th>

                      <th>Season</th>

                      <th>Date</th>

                      <th>Status</th>

                      <th>Action</th>

                    </tr>

                  </thead>

                  <tbody>

                    <?php 



				  



					 				



				if($total > 0){$counter = 1;



				$FollowupExpand = 1;



				

				 $CountFollowup = $numRows+1;

				  while($row = $db->fetch_object()){



					 



					  ?>

                  <div data-role="header">

                    <tr>

                      <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>

                        <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>

                      <td><?php echo $row->rate_name.'-'.$row->sub_code.$row->rate_details_id;   ?></td>

                      <td><?php if($row->company_id==0){echo 'Template Rate';}else {echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'"); }  ?></td>

                      <td align="center"><?php echo selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$row->rate_level_id."'");   ?></td>

                      <td><?php echo selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'");   ?></td>

                      <td><?php echo selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$row->seasonId."'");   ?></td>

                      <td><?php echo dateformat_date($row->start_date).'-'.dateformat_date($row->end_date);   ?></td>

                      <td><?=$row->status=='1'?'<span onclick="location.href=\'manageRateMaster.php?inactiveId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageRateMaster.php?activeId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>

                        &nbsp;</td>

                      <td><?php


		$rateletterurl  =	selectColumn(TBL_DOCUMENT_CONFIG,'rateletter_url','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');


		if($rateletterurl!=''){

		$rateletter_url  =	selectColumn(TBL_DOCUMENT_CONFIG,'rateletter_url','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');

		}else{

		$rateletter_url  =	'generateRatePdf.php';

		}






?>     

                        

                       <a href="javascript://" onClick="showContent(<?php echo $FollowupExpand.','.$CountFollowup; ?>)" style="color:#fff;text-transform: uppercase;"><i class="fa fa-copy" style="color:rebeccapurple"> </i></a>

                        <input type="hidden" id="section_<?php echo $FollowupExpand; ?>_img"  border="0"> 

                         &nbsp;&nbsp;<a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-pdf-o"></i></a> 

                        <!--  &nbsp;&nbsp; <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>--> 

                        

                        <!-- &nbsp;&nbsp; <a href="ex.php?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>--> 

                        

                        &nbsp;&nbsp; <a href="editRateMasters.php?hotelId=<?php echo encryptor('encrypt',$hotelId); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a> &nbsp;&nbsp; <!--<a href="javascript:void(0)" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);" title="Delete"><i class="fa fa-remove" ></i></a>--> 

                       

               

                      <tr>                    

                      <td colspan="9" style=" padding-bottom:0px;padding-top:0px;">                    

                    <div id="div<?php echo $FollowupExpand; ?>"></div>

                      <div id="section_<?php echo $FollowupExpand;?>" style="display:none;">

                    

                    <table id="example2" class="table table-striped" style="border:1px solid #3c8dbc !important;">

                      <tr style="background-color:#3C8DBC; color:#fff;">

                        <th>Create Duplicate Rate Master Name  : <?php echo $row->rate_name.'-'.$row->sub_code.$row->rate_details_id;   ?></th>

                      </tr>

                      <tr  style="border:1px solid #3c8dbc !important;">

                        <td>

         <form name="duplicateform<?php  echo $FollowupExpand; ?>" id="duplicateform<?php  echo $FollowupExpand; ?>"  action="" data-parsley-validate autocomplete="off" method="post">

     				 <input type="hidden" id="dupId<?php  echo $FollowupExpand; ?>" name="dupId<?php  echo $FollowupExpand; ?>" value="<?php echo $row->id;?>" >

                     

                        <div class="row"><div class="col-md-4">

                            <div class="form-group">

                              <label>Rate Category</label><font color="#FF0000">*</font>

                              <br/>

                              <?php $levelDropDown = '<select class="form-control select2" name="rate_level_id" id="rate_level_id'.$FollowupExpand.'" style="width: 100%;" data-parsley-errors-container="#rate_level_id'.$FollowupExpand.'Error" data-parsley-required >



											    <option value="">Select Rate Level</option>';



											  $resCat = selectSql(TBL_RATE_LEVEL,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');



											  if($db->num_rows2($resCat)){



											  	while($resultCat = $db->fetch_object2($resCat)){

													 if($resultCat->id == $row->rate_level_id){

															$selected = 'selected="selected"';

															}else{

															$selected = '';

															}



													$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';



												}



											  }



											 	echo $levelDropDown .= '</select>';



											  ?>

                             <span id="rate_level_id_<?php echo $FollowupExpand; ?>"><?php   $err_rate_level_id.$FollowupExpand;?></span> 

                             

                             </div>

                          </div>

                          

                          <div class="form-group col-sm-4">

            <label for="remarks">Market </label><font color="#FF0000">*</font><br/>

            <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="market" id="market'.$FollowupExpand.'" data-parsley-errors-container="#market'.$FollowupExpand.'Error" data-parsley-required '.$disabled.' style="width: 100%;" >

												  <option value="">Select Market</option>';

												 

												  $resCat = selectSql(TBL_RATE_MARKET," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										

												  if($db->num_rows2($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($resultCat->id == $row->market){

															$selected = 'selected="selected"';

														}else if($_REQUEST['market']== $resultCat->id){

															$selected = 'selected="selected"';

														}else{

															$selected = '';

														}	

														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

													echo $marketDropDown .= '</select>';

												  ?>

            <span id="market<?php echo $FollowupExpand; ?>Error"><?php  $err_market.$FollowupExpand;?></span> </div>   

            </div>

             <div class="row">

                          <div class="form-group col-sm-4">

                            <label for="seasonId22">Season<font color="#FF0000">*</font></label>

                            <br>

                            <?php $seasonDropDown = '<select class="form-control select2" name="seasonId'.$FollowupExpand.'" id="seasonId'.$FollowupExpand.'" data-parsley-errors-container="#seasonError"  data-parsley-required  style="width: 100%;" onchange="getDuplicateseasonDate(this.value,'.$FollowupExpand.');">

											  <option value="">Select Season</option>';

											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->id == $row->seasonId){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													

													$seasonDropDown .= '<option  '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown .= '</select>';

											  ?>

                            <span id="seasonError"><?php echo $err_season;?></span> </div>

                            

                            

                          <div class="form-group col-sm-2">

                            <label for="start_date">Start Date</label>

                            <div class="input-group">

                              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                              <input type="text" class="form-control " id="start_date<?php echo $FollowupExpand;?>" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php // echo $disabled; ?> >

                              <input type="hidden" class="form-control " id="start_date<?php echo $FollowupExpand;?>" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" >

                            </div>

                            <!-- /.input group --> 

                            <span id="start_dateError"><?php echo $err_start_date;?></span> </div>

                          <div class="form-group col-sm-2">

                            <label for="end_date">End Date </label>

                            <div class="input-group">

                              <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>

                              <input type="text" class="form-control " id="end_date<?php echo $FollowupExpand;?>" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php // echo $disabled; ?> >

                              <input type="hidden" class="form-control " id="end_date<?php echo $FollowupExpand;?>" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  >

                            </div>

                            <!-- /.input group --> 

                            <span id="end_dateError"><?php echo $err_end_date;?></span> </div>

                          

                        </div>

                      

                      </div>

                    <button class="btn btn-primary" onclick="SaveDuplicateRate(<?php echo $FollowupExpand; ?>);" type="button">Generate</button>  

                      

      

                       </form>

                        </td>

                      

                        </tr>

                      

                    </table>

                  </div>

                    </td>

                  

                    </tr>

                  

                    </td>

                  

                  <?php $Expand++;

$FollowupExpand++;

				  



				  	}?>

                  <tr>

                    <td align="right" colspan="10"><?php  echo $pagging->getLinks();?></td>

                  </tr>

                  <?php }else {?>

                  <tr>

                    <td height="200" align="center" colspan="8">---- No Record Found ---- </td>

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

  

  <span class="my_popup_open" style="display:none;"></span>

<div id="my_popup" class="well">

  <div id="rateUpdateData"></div>

 

  <button class="my_popup_close btn btn-default pull-right">Close</button>

</div>



 

                                 

                                 

                                 

                                 

  <script type="text/javascript">

  



function SaveDuplicateRate(FollowupExpand) {

  

var rate_level_id 		= $("#rate_level_id"+FollowupExpand).val();

var seasonId 			= $("#seasonId"+FollowupExpand).val();



var start_date 			= $("#start_date"+FollowupExpand).val();

var end_date 			= $("#end_date"+FollowupExpand).val();

var market 				= $("#market"+FollowupExpand).val();

var dupId 				= $("#dupId"+FollowupExpand).val();

    	  

		  //alert(seasonId);

		  

 // var form=$("#duplicateform"+FollowupExpand);		

  //if(form.parsley().validate()){

	

		  $.ajax({

			   type: "POST",

			   url: 'ajax/ajaxSaveDuplicateMasterRateLetter.php',

			   data: 'FollowupExpand='+FollowupExpand+'&start_date='+start_date+'&end_date='+end_date+'&dupId='+dupId+'&seasonId='+seasonId+'&rate_level_id='+rate_level_id+'&market='+market, 

			   success: function (result) {

					

					$( ".my_popup_open" ).click();	

					$("#rateUpdateData").html(result);

				}

		})

	return false;

	

 // }

 }

 

   

 function showContent(content,numRows)

 {



	 var content='section_'+content;

	 sections = new Array("section_1","section_2","section_3","section_4","section_5","section_6","section_7","section_8","section_9","section_10","section_11","section_12","section_13","section_14","section_15","section_16","section_17","section_18","section_19","section_20","section_21","section_22","section_23","section_24","section_25","section_26","section_27","section_28","section_29","section_30","section_31","section_32","section_33","section_34","section_35","section_36","section_37","section_38","section_39","section_40","section_41","section_42","section_43","section_44","section_45","section_46","section_47","section_48","section_49","section_50","section_51","section_52","section_53","section_54","section_55","section_56","section_57","section_58","section_59","section_60","section_61","section_62","section_63","section_64","section_65","section_66","section_67","section_68","section_69","section_70","section_71","section_72","section_73","section_74","section_75","section_76","section_77","section_78","section_79","section_80","section_81","section_82","section_83","section_84","section_85","section_86","section_87","section_88","section_89","section_90","section_91","section_92","section_93","section_94","section_95","section_96","section_97","section_98","section_99","section_100");

 

			 for(i=0; i<numRows; i++){

				 

						 if(document.getElementById(sections[i]).style.display == "none" && sections[i] == content){

						 document.getElementById(sections[i]).style.display = "block";

						 document.getElementById(sections[i]+"_img").src = "fa-minus";

						 }else{

						 document.getElementById(sections[i]).style.display = "none";

						 document.getElementById(sections[i]+"_img").src = "fa-plus";

						 }

			

			 }

			 

			

 }

  	function deleteMe(id,name){

  		var xhttp = new XMLHttpRequest();

  		  xhttp.onreadystatechange = function() {

  		    if (this.readyState == 4 && this.status == 200) {

  		    	//alert(this.responseText);

  		      if(this.responseText == 1){

  		      	alert("Transaction Found In the Table");

  		      }

  		      else{

  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){

  		      		window.location.href='manageRateMaster.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';

  		      	}

  		      }

  		    }

  		  };

  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_rate_letter="+id, true);

  		  xhttp.send();

  	}

  </script>

  <?php include_once("includes/footer.php")?>

<script>





function getDuplicateseasonDate(seasonId,listId){

 $.ajax({

   type: "GET",

   url: 'ajax/ajaxDuplicateSeasonDate.php',

   data: 'seasonId='+seasonId, 

   success: function (result) {	

	   if(result !=''){

			seasondateArray = result.split(',');

			$('#start_date'+listId).val(seasondateArray[0]);			

			seasondateArraySecond = seasondateArray[1].split('|||');			

			$('#end_date'+listId).val(seasondateArraySecond[0]);

		}



	}



	})



}

</script>