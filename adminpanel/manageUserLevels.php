<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'view');
$image_path = $UPLOAD_FILES.'/manufacturers/';
$image_display_path = $UPLOAD_FILES_PATH ."/manufacturers/";
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'activate');
		$statusId = addslashes($_REQUEST['activeId']);
		$statusSql = "	UPDATE `".TBL_USER_LEVELS."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'deactivate');
		$statusId = addslashes($_REQUEST['inactiveId']);
		$statusSql = "	UPDATE `".TBL_USER_LEVELS."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'User Level '.selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'User Level '.selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'delete');
	$delSql = "DELETE FROM `".TBL_USER_LEVELS."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUserLevel = selectRow(TBL_USER_LEVELS," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One User Level '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete manufacturer '.$sqlDelUserLevel["name"];
	}
}	

// ----------cate---------
if($_POST['Search'] == 'Search' && $_POST['search_name'] != ''){
	$sqlUserLevel = " SELECT * FROM `".TBL_USER_LEVELS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `name` LIKE '%".addslashes($_POST['search_name'])."%'ORDER BY `display_order`,`name`";
}else{
	$sqlUserLevel = " SELECT * FROM `".TBL_USER_LEVELS."` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY `display_order`,`name`";
}
$db->query($sqlUserLevel);
$numRows =$db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Levels Manager
        <small>Manage User Levels</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUserLevels.php">User Levels Manager</a></li>
        <li class="active">Manage User Levels</li>
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
							  <a type="button" class="btn btn-success" href="editUserLevels.php" >Add User Level</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_USER_LEVELS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_USER_LEVELS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label> User Level Title</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
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
              <h3 class="box-title">User Levels List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>User Level Title</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php if($numRows > 0){$counter = 1;
						while($rowUserLevel = $db->fetch_object()){?>
                <tr>
                  <td><?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$rowUserLevel->name;?></td>
                  <td><?=$rowUserLevel->status=='1'?'<span onclick="location.href=\'manageUserLevels.php?inactiveId='.$rowUserLevel->id.'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageUserLevels.php?activeId='.$rowUserLevel->id.'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>				 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUserLevels.php?eId=<?=$rowUserLevel->id?>&action=edit';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $rowUserLevel->name; ?>" id="<?php echo $rowUserLevel->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>
               <?php }?>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
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
                window.location.href='manageUserLevels.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
              }
            }
          }
        };
        xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_user_lvl="+id, true);
        xhttp.send();
    }
  </script> 

<?php include_once("includes/footer.php")?>  