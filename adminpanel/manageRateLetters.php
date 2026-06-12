<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_DETAILS,'view');

//$hotelId='1';

/////////////////////////////////////////////////////////////////////////////////////

/*$sql_order = mysql_query("SELECT * FROM `".TBL_RATE."` WHERE `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'");
while($record = mysql_fetch_array($sql_order)){
	$sqlOrderDetail2 = executeSql("Select * from `".TBL_COMPANY."` where id_company=".addslashes($record['company_id']));
			if(num_rows($sqlOrderDetail2) >0 ){
				while($rowOrderDetail_update= $db->fetch_object2($sqlOrderDetail2)){
					$id_state	 = $rowOrderDetail_update->id_state;
					
					$updateInventory = executeSql("UPDATE  `".TBL_RATE."`  SET 
						  `id_state`='".addslashes($id_state)."'
								where  `id`='".addslashes($record['id'])."'");
				}				
			}
	}
	*/




if($_REQUEST['action'] == 'change'){

	

	if($_REQUEST['activeId'] != ''){		

		if($_SESSION['userLevel'] !=1){
		  restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
		}

		checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'activate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));

		$statusSql = "	UPDATE `".TBL_RATE."`

						SET `status` = '1'

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` = '".addslashes($statusId)."'";

						

	}elseif($_REQUEST['inactiveId'] != ''){
		if($_SESSION['userLevel'] !=1){
		  restrictRateForZone($connNew,addslashes(encryptor('decrypt',$_REQUEST['id'])));
		}

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
	if($_SESSION['userLevel'] !=1){
		  restrictRateForZone($connNew,$_REQUEST['delId']);
		}
	checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'delete');
	
   $checkExisting = executeSql("SELECT * FROM `".TBL_ORDERS."` where `id_rate` = '".$_REQUEST['delId']."'");

	  $sqlDelUsers1 = selectRow(TBL_RATE," WHERE `id` = '".$_REQUEST['delId']."'");

	  

	if(num_rows($checkExisting)>0){

	 $_SESSION['errorMsg'] = $sqlDelUsers1['rate_name'].' - Unable to delete this Rate Letter ';

		

	}else{
		
		$sqlDelUsers = "DELETE FROM `".TBL_RATE."` WHERE `id` = '".$_REQUEST['delId']."'";
		executeSql($sqlDelUsers);	
			 $_SESSION['errorMsg'] = $sqlDelUsers1['rate_name'].' - Rate Letter has been deleted sucessfully';
		}
}





/*$sql = " SELECT  `".TBL_RATE."`.*, `".TBL_RATE_DETAILS."`.hotel_id  FROM `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id=`".TBL_RATE_DETAILS."`.rate_id  WHERE `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_RATE."`.`company_id` != '0'  ";
*/
$sql="SELECT id,rate_name,company_id,id_state,rate_level_id,seasonId,rate_category_id,market,status,created_by,start_date,end_date FROM ".TBL_RATE." WHERE id_shop='".$_SESSION['shop']."' ";


//if($_REQUEST['searchFormSubmit'] =='1'){

if($_REQUEST['rate_level_id'] != ''){

	$sql .= " AND `".TBL_RATE."`.`rate_level_id` = '".addslashes($_REQUEST['rate_level_id'])."'";

}

if($_REQUEST['companyId'] != ''){

	$sql .= " AND `".TBL_RATE."`.`company_id` = '".addslashes($_REQUEST['companyId'])."'";

}

if($_REQUEST['seasonId'] != ''){

	$sql .= " AND `".TBL_RATE."`.`seasonId` = '".addslashes($_REQUEST['seasonId'])."'";

}

if($_REQUEST['created_by'] != ''){

	$sql .= " AND `".TBL_RATE."`.`created_by` = '".addslashes($_REQUEST['created_by'])."'";

}


/*if($_REQUEST['hotelId'] != ''){

	$sql .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` = '".addslashes($_REQUEST['hotelId'])."'";

}*/



if($_REQUEST['state'] != ''){

	$sql .= " AND `".TBL_RATE."`.`id_state` = '".addslashes($_REQUEST['state'])."'";

}



if($_REQUEST['rate_name_str'] != ''){

	$sql .= " AND `".TBL_RATE."`.`rate_name` = '".addslashes($_REQUEST['rate_name_str'])."'";

}

if($_REQUEST['market'] != ''){

	$sql .= " AND `".TBL_RATE."`.`market` = '".addslashes($_REQUEST['market'])."'";

}
if($_REQUEST['status'] != ''){

	$sql .= " AND `".TBL_RATE."`.`status` = '".addslashes($_REQUEST['status'])."'";

}
$sql.=" Order by id DESC LIMIT 10 ";



//}

/*if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 

	 $sql .= " AND `".TBL_RATE_DETAILS."`.`hotel_id` IN  (".addslashes($_SESSION['HotelUserPermission']).")";

}

	$sql .= " group by `".TBL_RATE_DETAILS."`.rate_id  order by id  desc LIMIT 10";*/



// $sql;

/*echo $sql;*/
/*exit;*/
$db->query($sql);

$numRows= $db->num_rows();

//$pagging = new pagingClass($sql,$setpage);

//$db->query($pagging->getQuery());

$total = $db->num_rows();

//$countRate=selectColumn(TBL_RATE,'count(id)',' WHERE id_shop="'.$_SESSION['shop'].'" ');

?>

<?php include_once("includes/header.php")?>



<?php include_once("includes/left.php")?>

<div class="content-wrapper">

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1> Corporate Rate Letters <small></small> </h1>

    <ol class="breadcrumb">

      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

      <li class="active">Corporate Rate Letters</li>

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

          <h3 class="box-title"><!--Search <small>Total Records: (

            <?=$countRate;?>

            ) </small> --></h3>

          <div class="btn-group  pull-right"><a type="button" class="btn btn-success" href="editRateLetters.php" >Add Rate</a>

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

                  <label for="id_company">Company Name - City </label>

                  <select class="form-control select2 itemName" name="companyId" id="companyId"   >

                  </select>
                   </div>

             <!--<div class="form-group col-sm-6">

                <label for="seasonId">Hotel</label>

                <?php /*$hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" '.$disabledHotel.'>

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

															echo $hotelDropDown .= '</select>';*/

														  ?>

              </div>-->

                 <div class="form-group col-sm-6">

                  <label for="remarks">Prepared By </label>

                  <?php $preparedByDropDown = '<select class="form-control  select2 input-sm" name="created_by" id="created_by" data-parsley-errors-container="#marketError" data-parsley-required >

												  <option value="">Select Prepared By</option>';

												 

												  $resCat = selectSql(TBL_USERS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										

												  if($db->num_rows2($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($resultCat->id ==$_REQUEST['created_by']){

															$selected = 'selected="selected"';

														}else if($_REQUEST['created_by']== $resultCat->id){

															$selected = 'selected="selected"';

														}else{

															$selected = '';

														}	

														$preparedByDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

													echo $preparedByDropDown .= '</select>';

												  ?>

					  <!--<span id="marketError"><?php echo $err_market;?></span>-->

                </div>

              <!-- /.col -->


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
              <div class="form-group col-sm-6">

                  <label for="remarks">Market </label>

                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="market" id="market" data-parsley-errors-container="#marketError" data-parsley-required >

												  <option value="">Select Market</option>';

												 

												  $resCat = selectSql(TBL_RATE_MARKET," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										

												  if($db->num_rows2($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($resultCat->id ==$_REQUEST['market']){

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

					  <span id="marketError"><?php echo $err_market;?></span>

                </div>

              <!-- /.col -->

              <div class="form-group col-sm-6">

                <label for="seasonId">Season</label>

                <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" >

											  <option value="">Select Season</option>';

											  $resCat = selectSql(TBL_RATE_SEASON," where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

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

              <div class="col-md-6">

                <div class="form-group">

                  <label>Rate Number</label>
					<input type="text" name="rate_name_str" class="form-control">
                  <?php

				  

				 // $sql = ;

				  

				   /*$levelDropDown = '<select class="form-control select2" name="rateletter_id">

											    <option value="">Select Rate Number</option>';

											  $resCat = executeSql(" SELECT  `".TBL_RATE."`.*, `".TBL_RATE_DETAILS."`.hotel_id  FROM `".TBL_RATE."` LEFT JOIN `".TBL_RATE_DETAILS."` ON `".TBL_RATE."`.id=`".TBL_RATE_DETAILS."`.rate_id  WHERE `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."' AND `".TBL_RATE."`.`company_id` != '0' group by `".TBL_RATE_DETAILS."`.rate_id order by id desc");

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['rateletter_id'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}// $row->rate_name.'-'.$row->sub_code.$row->rate_details_id

													$levelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).'</option>';

												}

											  }

											 	echo $levelDropDown .= '</select>';*/

											  ?>

                </div>

              </div>
				  <!--Status Starts-->
			  <div class="col-md-6">
					<div class="form-group">
						<label>Status</label>				
						<?php 
							if($_REQUEST['status'] == '1'){
									$selected1 = 'selected="selected"';
							}elseif($_REQUEST['status'] == '0'){
									$selected0 = 'selected="selected"';
							}
						echo $statusDropDown = '<select class="form-control select2" name="status"> <option value="">Both</option>
						<option '.$selected1.' value="1">Active</option>
						<option '.$selected0.' value="0">Inactive</option>
						</select>';?>
					</div>
              <!-- /.form-group -->
            </div>
             <!--End of Status-->

              <!--<div class="form-group col-sm-6">

                  <label for="remarks">State</label>

                  <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="state" id="state" >

												  <option value="">Select State</option>';
												 

												  $resCat = selectSql(TBL_STATE," where status='1' AND id_country='110' ",' ORDER BY `name`');										

												  if($db->num_rows2($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($resultCat->id_state ==$_REQUEST['state']){

															$selected = 'selected="selected"';

														}else if($_REQUEST['state']== $resultCat->id_state){

															$selected = 'selected="selected"';

														}else{

															$selected = '';

														}	

														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

													echo $marketDropDown .= '</select>';

												  ?>

					 

                </div>

              

            </div>-->
          </div>

          <!-- /.box-body -->

          <div class="box-footer">

            <input name="Search" type="submit" class="btn btn-primary" value="Search" />

          </div>

        </form>

        

        <!-- /.box -->

        
        <div class="box">

          <div class="box-header">

            <h3 class="box-title">Rate Letter List</h3>

          </div>

          <form name="listingForm" action="" method="post">

            <input type="hidden" value="" name="act" />

            <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                  <tr>

                    <!--<th width="5%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />

                      S.No.&nbsp;</th>-->

                    <th>Rate No.</th>

                    <th>Company Name - City</th>
                    <th>Prepared by</th>

                    <th>Rate Category</th>
	                <th>Market</th>

                    <th>Season</th>

                    <th>Date</th>
					<?php if($_SESSION['userLevel']==1){ ?>					
                    <th>Status</th>
					<?php }?>
                    <th>Action</th>

                  </tr>

                </thead>

                <tbody>

                  <?php 

				  

							 				

				if($total > 0){$counter = 1;				

				

				  while($row = $db->fetch_object()){

if($row->sub_code ==0){
	$subCode=	'';
}else{
	$subCode=	'-'.$row->sub_code;
	}
					 

					  ?>

                  <div data-role="header">

                  <tr>

                    <!--<td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>

                      <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>-->

                    <td><?php echo $row->rate_name.$subCode;   ?></td>

                    <td><?php if($row->company_id==0){echo 'Template Rate';}else {echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."'").'-'.selectColumn(TBL_COMPANY,'city'," WHERE `id_company` = '".$row->company_id."'"); }  ?></td>

                    <?php 
                    /*$id_area=selectColumn(TBL_COMPANY,'area'," WHERE `id_company` = '".$row->company_id."'");
                    $id_user=selectColumn(TBL_AREAS,'user_id'," WHERE `id` = '".$id_area."'");
                    $handeledBy = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$id_user."'");*/
                    
                    ?>
                    <td width="100px"><?php echo selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->created_by."'"); ?></td>

                    <td align="center"><?php echo selectColumn(TBL_RATE_LEVEL,'name'," WHERE `id` = '".$row->rate_level_id."'");   ?></td>

                    

                    

                    <td><?php echo selectColumn(TBL_RATE_MARKET,'name'," WHERE `id` = '".$row->market."'");   ?></td>

                    <td><?php echo selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".$row->seasonId."'");   ?></td>

                    <td><?php echo dateformat_date($row->start_date).'-'.dateformat_date($row->end_date);   ?></td>
					<?php if($_SESSION['userLevel']==1){ ?>	
                     <td><?=$row->status=='1'?'<span onclick="location.href=\'manageRateLetters.php?inactiveId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageRateLetters.php?activeId='.encryptor('encrypt',$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;
                     </td>
                     <?php }?>

                     <td>
					
                 

                    

<?php


		$rateletterurl  =	selectColumn(TBL_DOCUMENT_CONFIG,'rateletter_url','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');


		if($rateletterurl!=''){

		$rateletter_url  =	selectColumn(TBL_DOCUMENT_CONFIG,'rateletter_url','WHERE doc_type=1 AND id_shop="'.$_SESSION['shop'].'" ');

		}else{

		$rateletter_url  =	'generateRatePdf.php';

		}





$hotelId= encryptor('encrypt',addslashes($_REQUEST['hotelId']));
if($_SESSION['shop']=='6'){
?>
 <a href="mail-template/sendRateMailCustom.php?id=<?=encryptor('encrypt',$row->id)?>" target="_blank"><i class="fa fa-paper-plane"></i></a>&nbsp
 <?php }else{ ?>
  <a href="mail-template/sendRateMail.php?id=<?=encryptor('encrypt',$row->id)?>" target="_blank"><i class="fa fa-paper-plane"></i></a>&nbsp
 <?php } ?> 	
  <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
					 <!-- &nbsp;&nbsp; <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>-->

                      &nbsp;&nbsp; <a href="ex.php?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>
                     
                    <?php 
					 if($row->sub_code	==0){
						$sub_code	= $row->sub_code+1; 
						 ?>
						 &nbsp;&nbsp; <a href="editRateLetters.php?hotelId=<?php echo encryptor('encrypt',addslashes($_REQUEST['hotelId'])); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>&revise_code=0&sub_code=0" title="Edit" name="<?php echo $hotelId; ?>"   id="<?php echo $row->id;?>"  ><i class="fa fa-pencil-square-o" ></i></a>
                         
                        
                        
  <div id="FeedBack_<?php echo $row->id;?>" class="well" style="display:none;">
    <form id="FeedBackpopupForm" data-parsley-validate autocomplete="off">
        <div class="form-group">
        <label for="room_name">Rate Letter</label>
      
      </div>

         <a href="editRateLetters.php?hotelId=<?php echo encryptor('encrypt',addslashes($_REQUEST['hotelId'])); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>&revise_code=1&sub_code=<?php echo $sub_code;?>" title="Edit"   class="btn btn-default">Revise</a>
         
         
         <a href="editRateLetters.php?hotelId=<?php echo encryptor('encrypt',addslashes($_REQUEST['hotelId'])); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>&revise_code=0&sub_code=0" title="Edit"   class="btn btn-default">Modify</a>
      </form>
  </div>
  
  
					 <?php }else{
					$sub_code	= $row->sub_code+1; 
?>&nbsp;&nbsp;<a href="editRateLetters.php?hotelId=<?php echo encryptor('encrypt',addslashes($_REQUEST['hotelId'])); ?>&id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>&revise_code=1&sub_code=<?=$sub_code;?>" title="Edit"  ><i class="fa fa-pencil-square-o" ></i></a>


                    
<?php } ?>
                    &nbsp;&nbsp; <!--<a href="javascript:void(0)" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);" title="Delete"><i class="fa fa-remove" ></i></a>--> </td>

                  </tr>

                

                 

                 

                 

                  <?php

				  

				  	}?>

                  <tr>

                    <td align="right" colspan="8"><?php  //echo $pagging->getLinks();?>

                    </td>

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

  <div id="bookedby" class="well">

  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >

    <div class="form-group">

      <label for="first_name">First Name </label>

      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">

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

    <input  type="button" class="btn btn-default" onClick="saveRateContactPopupform();" value="Save">

    <button class="bookedby_close btn btn-default">Close</button>

  </form>

</div>

  <div id="duplicate" class="well" style="display:none;">

    

  </div>

  <script type="text/javascript">
  	function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageRateLetters.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>&state=<?=$_REQUEST['state']?>';
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
 

function duplicate(id){

	var Id = id;

	$('#dupId').val(Id);

	

}
function submitRateLetterCheckSubcodeForm(hotelId,id){
	
	$('#RowIds').val(id);
	 $('#FeedBack_'+id).popup('show');
	/*var hi= confirm("Are you sure that you want to Revise this Rate Letter?");
    if (hi== true){
        
		window.location.href =  'editRateLetters.php?revise_code=1&hotelId='+hotelId+'&id='+id+'&action=edit';
    }else{        
		window.location.href = 'editRateLetters.php?revise_code=0&hotelId='+hotelId+'&id='+id+'&action=edit';
		
    }*/	
	}
</script>