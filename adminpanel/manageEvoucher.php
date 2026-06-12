<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'view');



//---------------------------------------------------------------------------------------------------------

if($_REQUEST['action'] == 'change'){

	if($_REQUEST['activeId'] != ''){

		

		checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'activate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));

		$statusSql = "	UPDATE `".TBL_PROMO_CODE."`

						SET `status` = '1'

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` = '".addslashes($statusId)."'";

	}elseif($_REQUEST['inactiveId'] != ''){

		checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'deactivate');

		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));

		$statusSql = "	UPDATE `".TBL_PROMO_CODE."` 

						SET `status` = '0' 

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` = '".addslashes($statusId)."'";

	}

	if(executeSql($statusSql)){

		$err = 0;

		$_SESSION['successMsg'] = 'status has been changed sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = ' status has not been changed sucessfully.';

	}

}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'delete');

	$delSql = "DELETE FROM `".TBL_PROMO_CODE."` WHERE `id` = '".$_REQUEST['delId']."'";

	$sqlDelUserLevel = selectRow(TBL_PROMO_CODE," WHERE `id` = '".$_REQUEST['delId']."'");

	if(executeSql($delSql)){		

		$err = 0;

		if(file_exists($image_path.$sqlDelUserLevel['image'])){

			@unlink($image_path.$sqlDelUserLevel['image']);

			@unlink($image_path.'small-'.$sqlDelUserLevel['image']);

			@unlink($image_path.'medium-'.$sqlDelUserLevel['image']);

		}

		$_SESSION['successMsg'] = 'One Evoucher '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Unable to delete Evoucher '.$sqlDelUserLevel["name"];

	}

}

if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'activate');	

	$activateIds = implode(',',$_REQUEST['ids']);	

	$statusSql = "	UPDATE `".TBL_PROMO_CODE."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	

										

	if(executeSql($statusSql)){

		$err = 0;

		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';

	}	

}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_PROMO_CODE."`

						SET `status` = '0'

						,`last_modified` = '".currenDateTime()."'

						,`last_modified_by` = '".$_SESSION['userId']."'

						WHERE `id` IN (".addslashes($deactivateIds).")";	

										

	if(executeSql($statusSql)){

		$err = 0;

		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';

	}	

}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){

	checkUserLevelPermission($_SESSION['userLevel'],TBL_PROMO_CODE,'delete');	

	$deleteIds = implode(',',$_REQUEST['ids']);	

	$delSql = "DELETE FROM `".TBL_PROMO_CODE."` WHERE `id` IN (".addslashes($deleteIds).")";

	 $delSqlImage = selectSql(TBL_PROMO_CODE_DETAILS,"where `promo_code_id` in (".addslashes($deleteIds).") ",'');

	

		

	if(executeSql($delSql)){		

		$err = 0;

		while($delResultImage = mysqli_fetch_array($delSqlImage)){



			$DeletePromoCodeDetails = "DELETE FROM `".TBL_PROMO_CODE_DETAILS."` WHERE `id`='".$delResultImage['id']."'";

			executeSql($DeletePromoCodeDetails);

		}

		

		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';

	}else{

		$err = 1;

		$_SESSION['errorMsg'] = 'Unable to delete selected records';

	}

}



// ----------cate---------

$sql = " SELECT * FROM `".TBL_PROMO_CODE."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";

if(!empty($_SESSION['hotel_access'])){

$sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";

}

if($_REQUEST['search_name'] != ''){

	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";

}

if($_REQUEST['companyId'] != ''){



	$sql .= " AND `".TBL_PROMO_CODE."`.`company_id` = '".addslashes($_REQUEST['companyId'])."'";



}

if($_REQUEST['status'] != ''){

	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."'";

}

if($_REQUEST['hotel_category'] != ''){

	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";

}



if($_REQUEST['order'] != ''){

	$sql .= " ORDER BY `date_created` DESC";

}else{

	$sql .= " ORDER BY `date_created` DESC";

}

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

      <h1>

        Evoucher Manager

        <small>Manage Evouchers</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Evouchers</li>

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

        <div class="box-header with-border">

          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>

		  <div class="btn-group  pull-right">

							  <a type="button" class="btn btn-success" href="editEvoucher.php" >Add Evoucher</a>

							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">

								<span class="caret"></span>

								<span class="sr-only">Toggle Dropdown</span>

							  </button>

							  <ul class="dropdown-menu" role="menu">

							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_PROMO_CODE;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>

								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_PROMO_CODE;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>

							  

							  </ul>

							</div>

          

        </div>

        <!-- /.box-header -->

		<form name="searchForm" action="" method="get">

            <input type="hidden" value="1" name="searchFormSubmit" />

        <div class="box-body">

          <div class="row">

            

            <!-- /.col -->  

			<div class="col-md-5">



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

          <!-- /.row -->

        </div>

		</div>

        <!-- /.box-body -->

        <div class="box-footer">

        <input name="Search" type="submit" class="btn btn-primary" value="Search" />

        </div>

		</form>		

      </div>

      <div class="row">

        <div class="col-xs-12">		     

          <!-- /.box -->

          <div class="box">

            <div class="box-header">

              <h3 class="box-title">Evoucher List</h3>

            </div>

			<form name="listingForm" action="" method="post">

               <input type="hidden" value="" name="act" />

			     <div id="listingDiv"></div>

            <!-- /.box-header -->

            <div class="box-body table-responsive">

              <table id="example2" class="table table-bordered table-striped">

                <thead>

                <tr>

                  <th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>

                  <th>Company Name</th>

				  <th>Voucher Value</th>

                  <th>Nos Of Voucher </th>

                   <th>Date Valid From & To</th>

                  <th>Status</th>

				  <th>Action</th>

                </tr>

                </thead>

                <tbody>

				<?php 				 				

				if($total > 0){$counter = 1;

				  while($row = $db->fetch_object()){

					  

					  $rateletter_url  =	'promocode.php';

				?>

                <tr>

                  <td><input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>

                  <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->company_id."' AND id_shop = ".$_SESSION['shop']."");  ?></td>

				  <td><?php echo $row->vaoucher_value;   ?></td>

                   <td><?php echo $row->no_of_coupons;   ?></td>

                    <td><?php echo dateformat_date($row->date_valid_from);  echo ' To '; echo dateformat_date($row->date_valid_to);?></td>

                    

                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageEvoucher.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageEvoucher.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

				 

                  <td>

                   <a href="pdf-template/<?PHP echo $rateletter_url ?>?id=<?=encryptor('encrypt',$row->id)?>" title="Download" target="_blank"><i class="fa fa-file-excel-o"></i></a>

                   

                    &nbsp;&nbsp; <a href="manageEvoucherDetails.php?id=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="List"><i class="fa fa-th-list" ></i></a>





  <!--&nbsp;&nbsp; <a href="editEvoucher.php?eId=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>-->

                	 &nbsp;&nbsp;<a href="editEvoucherDetail.php?eId=<?=encryptor('encrypt',$row->id);?>&action=edit&page=<?=$_REQUEST['page']?>" title="Edit"><i class="fa fa-pencil-square-o" ></i></a>

                   &nbsp;&nbsp; <a href="javascript:void(0)" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);" title="Delete"><i class="fa fa-remove" ></i></a> 

                  </td>

                </tr>

               <?php }?> 

			    <tr>

                     <td align="left" colspan="5">

					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 

					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;

					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>

				</tr>

				<tr>	 

					  <td align="right" colspan="7"><?php  echo $pagging->getLinks();?> </td>

                 </tr>               

				<?php }else {?>

				

				 <tr>

                      <td height="200" align="center" colspan="5">---- No Record Found ---- </td>

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

  		      		window.location.href='manageEvoucher.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';

  		      	}

  		      }

  		    }

  		  };

  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?Evoucher_id="+id, true);

  		  xhttp.send();

  	}

  </script> 



<?php include_once("includes/footer.php")?>  