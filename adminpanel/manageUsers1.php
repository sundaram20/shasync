<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'activate');
		$statusId = addslashes($_REQUEST['activeId']);
		$statusSql = "	UPDATE `".TBL_USERS."` 
						SET `status` = '1' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'deactivate');
		$statusId = addslashes($_REQUEST['inactiveId']);
		$statusSql = "	UPDATE `".TBL_USERS."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."' 
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'delete');
	$delSql = "DELETE FROM `".TBL_USERS."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUsers = selectRow(TBL_USERS," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){
		$err = 0;
		if(file_exists($image_path.$sqlDelUsers['image_small'])){
			@unlink($image_path.$sqlDelUsers['image_small']);
			@unlink($image_path.$sqlDelUsers['image_medium']);
			@unlink($image_path.$sqlDelUsers['image_large']);
		}
		$_SESSION['successMsg'] = 'One User '.$sqlDelUsers["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete User '.$sqlDelUsers["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_USERS."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_USERS."`
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
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_USERS."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}

// ----------cate---------
//AND `sales_status_active` = '1'
$sqlUser = " SELECT * FROM `".TBL_USERS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  ";
if(($_SESSION['userLevel'] != '1') ){
	$sqlUser .= " AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
}
if($_REQUEST['name'] != ''){
	$sqlUser .= " AND `id` = '".addslashes($_REQUEST['name'])."'";
}
if($_REQUEST['username'] != ''){
	$sqlUser .= " AND `id` = '".addslashes($_REQUEST['username'])."'";
}
if($_REQUEST['userlevelId'] != ''){
	$sqlUser .= " AND `user_level` = '".addslashes($_REQUEST['userlevelId'])."'";
}
if($_REQUEST['search_name'] != ''){
	$sqlUser .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['team'] != ''){
	$sqlUser .= " AND FIND_IN_SET('".$_REQUEST['team']."',ids_team) ";
}
if($_REQUEST['status'] != ''){
	$sqlUser .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['hotel_id'] != ''){
	$sqlUser .= " AND  FIND_IN_SET ('".$_REQUEST['hotel_id']."',`hotel_access`) ";
}
if($_REQUEST['order'] != ''){
	$sqlUser .= " ORDER BY `name`";
}else{
	$sqlUser .= " ORDER BY `name`";
}

echo $sqlUser;

$db->query($sqlUser);
$numRows= $db->num_rows();
$pagging = new pagingClass($sqlUser,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Manager
        <small>Manage Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUsers.php">User  Manager</a></li>
        <li class="active">Manage Users</li>
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
          <h3 class="box-title">Search <small>Total Records: (<?php echo $numRows;?>) &nbsp;</small> </h3>
			<div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editUsers.php">Add User</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                   <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_USERS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_USERS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
                  
                  </ul>
                </div>
				
        </div>
        <!-- /.box-header -->
		
		
		<form name="searchForm" action="" method="get">
           <input type="hidden" value="1" name="searchFormSubmit" /> 
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label> User Level Title</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="userlevelId">
											  								<option value="">All user levels</option>';
				  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `status` = '1' and `id_shop` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
				  if($db->num_rows2($resUserLevel)){
					while($resultUserLevel = $db->fetch_object2($resUserLevel)){
						if($_REQUEST['userlevelId'] == $resultUserLevel->id){
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
              <!-- /.form-group -->
              <div class="form-group">
                <label>User Name</label>
                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
               <?php $categoryDropDown = '<select class="form-control select2" name="name">
											  								<option value="">All user Name</option>';
											  $resUserLevel = selectSql(TBL_USERS," WHERE   `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['name'] == $resultUserLevel->id){
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
                                              
                                              
              <!-- /.form-group -->
            </div>
              <div class="col-md-6">
            <div class="form-group">
                <label>User ID</label>
               
               <?php $categoryDropDown = '<select class="form-control select2" name="username">
											  								<option value="">All user Name</option>';
											  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' and `id_shop` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['username'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->username).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                              
                                              </div></div>
              <div class="col-md-6">                                
             <div class="form-group">
                <label>Hotel</label>
               
               <?php $categoryDropDown = '<select class="form-control select2" name="hotel_id">
											  								<option value="">All Hotels</option>';
											  $resUserHotel = selectSql(TBL_HOTELS," WHERE `status` = '1' and `id_shop` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
											  if($db->num_rows2($resUserHotel)){
											  	while($resultUserHotel = $db->fetch_object2($resUserHotel)){
													if($_REQUEST['hotel_id'] == $resultUserHotel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserHotel->id.'">'.ucfirst($resultUserHotel->name.', '.$resultUserHotel->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                              
                </div>
            </div>                             
            <!-- /.col -->
            <div class="col-md-6">
            <div class="form-group ">
                  <label for="state">Team </label>
                  <?php   //AND status='1'
                  $sqlUserSql = "SELECT * FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";
						 $sqlUserActions = mysqli_query($conn,$sqlUserSql);  
				   ?>
                  
				  <select class="form-control select2" name="team" id="team" >
                  <option value="">Select Team</option>			  
                  <?php 
									
					$iCounterActions = 0;
					while($resUserActions = mysqli_fetch_object($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_USERS."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_team ) AND id='".addslashes($_REQUEST['eId'])."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>                    
                </div></div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <?php 
						if($_REQUEST['status'] == '1'){
								$selected1 = 'selected="selected"';
						}elseif($_REQUEST['status'] == '0'){
								$selected0 = 'selected="selected"';
						}
						 echo $statusDropDown	 = '<select class="form-control select2" name="status"> <option value="">Both</option>
											 		 	<option '.$selected1.' value="1">Active</option>
											 		 	<option '.$selected0.' value="0">Inactive</option>
											  		</select>';
								?>
              </div>              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
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
              <h3 class="box-title">Users List</h3>
            </div>
			 <form name="listingForm" action="" method="post">
                <input type="hidden" value="" name="act" />
				  <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Name</th>
				  <th>Hotels Allowed</th>
                  <th>User Level</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				 <?php 
				if($total > 0){
					$counter = 1;
				  while($rowUser = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$rowUser->id;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?php echo ucfirst($rowUser->name);?></td>
				  

				  
				  <td>
				  	<?php 
					if($rowUser->hotel_access !=""){				  
				  	$hotels = explode(",",$rowUser->hotel_access);
				  	?>
				  	<ul>
				  	<?php for($i=0 ;$i<count($hotels);$i++ ){
				  		echo '<li>'.selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)'," WHERE `id` = '".$hotels[$i]."'").'</li>';
				  	  }		
				  	}else{ echo "<ul><li>All Hotels</li></ul>";}?>
				  	</ul>
				  </td>                

                  <td><?php echo selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$rowUser->user_level."'");?></td>
                  <td><?php echo $rowUser->status=='1'?'<span onclick="location.href=\'manageUsers.php?inactiveId='.$rowUser->id.'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageUsers.php?activeId='.$rowUser->id.'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>
				 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUsers.php?eId=<?=$rowUser->id?>&userlevelId=<?=$rowUser->user_level?>&action=edit';" />&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $rowUser->name; ?>" id="<?php echo $rowUser->id;?>" onClick="deleteMe(this.id,this.name);"/> -->
				  
				  
				   &nbsp;&nbsp; 
                <!--<a href="editUsersAccess.php?eId=<?php echo $rowUser->id;?>&userlevelId=<?=$rowUser->user_level?>&action=edit" style="cursor:pointer;" title=" User Permissions " ><i class="fa fa-user" ></i> </a>-->&nbsp;&nbsp; </td>
                </tr>
               <?php }?>    
			   
			    <!--<tr>
                     <td align="left" colspan="6">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="6"><?php echo $pagging->getLinks();?> </td>
                 </tr>             
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
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
  		      		window.location.href='manageUsers.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_user="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  
                                             
