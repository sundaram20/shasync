<?php include_once("../config/auto_loader.php");

//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){


	
	if($_REQUEST['activeId'] != ''){

		//if(checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'active'));

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed successfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}				
	
	}elseif($_REQUEST['inactiveId'] != ''){

		//if(checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'inactive'));
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}		
						
	}
	
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'delete');
		
		//$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".encryptor('decrypt',$_REQUEST['delId'])."'";
		$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['delId']."'";

		if(executeSql($delSql)){		
			$err = 0;
			$_SESSION['successMsg'] = 'One Main Group  has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete Main Group ';
		}
	
}
// ----------cate---------
$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  and `table_name` = 'item_group_main' ";
if(!empty($_SESSION['hotel_access'])){
$sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";
}
if($_REQUEST['search_name'] != ''){
	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['hotel_category'] != ''){
	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";
} 

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `display_order` ASC";
}

//echo $sql;
//$sql .= " ORDER BY `display_order` ASC";
$db->query($sql);
$numRows= $db->num_rows();
// $pagging = new pagingClass($sql,$setpage);
// $db->query($pagging->getQuery());
// $total = $db->num_rows();
//var_dump($total);
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<?php// include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	<section class="content-header">
      <h1>
        Main Group 
        <small>Manage Main Groups</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Main Groups</li>
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
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="editProductMainGroups.php" >Add Item Main Group</a>
							  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ATTRIBUTES;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ATTRIBUTES;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul> -->
							</div>
          
        </div>	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Item Group Main List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            	<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Item Main Group Name</th>
				  <th>Item Description</th>
				  <th>Display Order</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		          
		        <tbody>  
		        	<?php 	
		        	$i=1;	 
				  while($row = $db->fetch_object()){ ?> 
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td><?php echo $row->field_value;?></td> 
                  <td><?php echo $row->field_description;?></td> 
                  <td><input type="number" disabled="" value ="<?php echo $row->display_order;?>"></td>  

                
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageProductMainGroups.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageProductMainGroups.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editProductMainGroups.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>
               <?php   } ?>  
				 
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
  		      		window.location.href='manageProductMainGroups.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_main_group="+id, true);
  		  xhttp.send();
  	}
  </script> 

<?php include_once("includes/footer.php")?>  