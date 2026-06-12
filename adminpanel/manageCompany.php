<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'view');

if($_SESSION['userLevel'] !=1){
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

//echo $_SESSION['teamMemberAreas'];
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_COMPANY."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_company` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_COMPANY."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_company` = '".addslashes($statusId)."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'delete');
	$delSql = "DELETE FROM `".TBL_COMPANY."` WHERE `id_company` = '".$_REQUEST['delId']."'";
	
	$sqlDelUserLevel = selectRow(TBL_COMPANY," WHERE `id_company` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One Company '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Company '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_COMPANY."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_company` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_COMPANY."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_company` IN (".addslashes($deactivateIds).")";
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_COMPANY."` WHERE `id_company` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_COMPANY,"where `id_company` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}

// ----------cate---------
$sql = "SELECT `".TBL_COMPANY."`.* FROM `".TBL_COMPANY."` LEFT JOIN ".TBL_AREAS." Ar ON `".TBL_COMPANY."`.area=Ar.id WHERE `".TBL_COMPANY."`.id_shop='".addslashes($_SESSION['shop'])."' AND FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."')  ";

if($_REQUEST['search_name'] != ''){
	//$sql .= " AND `".TBL_COMPANY."`.`name` LIKE '%".$_REQUEST['search_name']."%'";
	$sql .= " AND `".TBL_COMPANY."`.`id_company` = '".$_REQUEST['search_name']."'";
}


if($_REQUEST['id_area'] != ''){
	$sql .= " AND `area` = '".addslashes($_REQUEST['id_area'])."%'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `".TBL_COMPANY."`.`status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['id_default_group'] != ''){
	$sql .= " AND `id_default_group` = '".addslashes($_REQUEST['id_default_group'])."'";
}
if($_REQUEST['id_email'] != ''){
	$sql .= " AND `email` LIKE '%".addslashes($_REQUEST['id_email'])."%' ";
}
if($_REQUEST['id_phone'] != ''){
	$sql .= " AND `phone` LIKE '%".addslashes($_REQUEST['id_phone'])."%' ";
}
if($_REQUEST['user_id']!=''){
	$sql .= " AND Ar.user_id='".$_REQUEST['user_id']."' ";
}

//$sql .= $_SESSION['Ids_user_access_Company'] ;
if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `".TBL_COMPANY."`.`date_created`  DESC limit 0,25 ";
}else{
	$sql .= " ORDER BY `".TBL_COMPANY."`.`date_created` DESC limit 0,25 ";
}

//echo $sql;

$db->query($sql);
$numRows= $db->num_rows();
//$pagging = new pagingClass($sql,30);
//$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        Company Manager
        <small>Manage Company</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Company</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
		 <!--########## Company Import jump#######-->  
		   
		   <!-- Modal -->
		     <div class="modal fade" id="importComapnyModal" role="dialog" >
		       <div class="modal-dialog">
		       
		         <!-- Modal content-->
		         <div class="modal-content" style="width: 300px; margin: 0px auto;">
		           <div class="modal-header">
		             <button type="button" class="close" data-dismiss="modal">&times;</button>
		             <h4 class="modal-title">Import Company</h4><br>
		             <span id="returnTxt" style="color: Green;"></span>
		           </div>
		           <div class="modal-body">
		             <form name="companyimport" method="post" enctype="multipart/form-data" id="companyimport">
		               <div >
		                 <label for="file">Choose File : <span style="color: red;">*</span></label>
		                 <input type="file" name="companyImport" class="form-control" id="companyImport">
		               </div><br>
		               <div >
		                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCompany"><span style="color:red;margin-left:50px; ">*</span> = Required 
		                 Field<br>
		               </div>

		            </form>
		           </div>
		         </div>
		         
		       </div>
		     </div>
		     
		   
		<!--########## Import Company  Modal End#######-->  

	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border">
          <?php /*?><h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3><?php */?>
		  <div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editCompany.php">Add Company</a>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                 
                  <ul class="dropdown-menu" role="menu">
                  	<li><a title="Import to excel file" href="#" data-toggle="modal" data-target="#importComapnyModal" ><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Import</a></li>
                    <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Creation Based</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_COMPANY;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export Creation Based</a></li>
                  <li><a title="Export to excel file" href="exportCompanyTable.php?fileType=xls&tableName=<?php echo TBL_COMPANY;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export Profile Based </a></li>
                   
                  </ul>
                </div>          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <!--<div class="col-md-6">
              <div class="form-group">
                <label>Company Name</label>				
				<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              
            </div>-->
<div class="form-group col-sm-4">

                  <label for="id_company">Company Name - City </label>

                  <select class="form-control select2 itemName" name="search_name" id="search_name"   >

                  </select>
                   </div>
            <?php /*?><div class="col-md-4">
              <div class="form-group">
                <label>Company Name - City</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="search_name" id="search_name">
											    <option value="">Select Company </option>';
											  $resCat = selectSql(TBL_COMPANY," where  id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' AND FIND_IN_SET(area,'".$_SESSION['teamMyAreas']."')  ",' ORDER BY `name` ');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['search_name'] == $resultCat->name){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->name).'">'.ucfirst($resultCat->name.'-'.$resultCat->city).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div><?php */?>

          <div class="form-group col-md-4 ">
                                <label>Executive</label>
                                <!--<input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />-->
                               <?php 
                        
                         
                         $categoryDropDown = '<select class="form-control select2 " name="user_id" id="user_id" >
                                                        <option value="">Select Executive</option>';
                                       
                                        $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' ".$teamMembers."  $UserRestriction ",' ORDER BY `name`');
                                        
                                        if($db->num_rows2($resUserLevel)){
                                          while($resultUserLevel = $db->fetch_object2($resUserLevel)){
                                          if($_REQUEST['user_id'] == $resultUserLevel->id){
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
            <!--Area Executive-->
		            <div class="col-md-4">
		                <div class="form-group">
		                  <label>Area </label>				
		  				 <?php $categoryDropDown = '<select class="form-control select2" name="id_area">
		  											  <option value="">Select Area</option>  ';
		  											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name!='' ".$_SESSION['Ids_user_access_Company']." ",' ORDER BY `name`');
		  											  if($db->num_rows2($resCat)){
		  											  	while($resultCat = $db->fetch_object2($resCat)){
		  													/*if($_REQUEST['id_area'] == $resultCat->id_group){
		  														$selected = 'selected="selected"';
		  													}else{
		  														$selected = '';
		  													}*/
		  													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
		  												}
		  											  }
		  											 	echo $categoryDropDown .= '</select>';
		  											  ?>
		                </div>
		  			  			
		            </div>
			 <!--Search by Company Mobile-->
		            		  <div class="col-md-4">
		                          <div class="form-group">
		                            <label>Company Phone No.</label>				
		            				<?php 
		     					$categoryDropDown = '<select class="form-control select2" name="id_phone">
		  								<option value="">Select Phone</option>  ';
		  								  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and phone !='' and phone!='--' ",' ORDER BY `name`');
		  									  if($db->num_rows2($resCat)){
		  									  	while($resultCat = $db->fetch_object2($resCat)){
		  											/*if($_REQUEST['id_area'] == $resultCat->id_group){
		  												$selected = 'selected="selected"';
		  												}else{
		  												$selected = '';
		  											}*/
		  											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->phone.'">'.ucfirst($resultCat->phone).'</option>';
		  											}
		  									   }
		  								echo $categoryDropDown .= '</select>';?>
		                          </div>
		                          <!-- /.form-group -->
		                        </div>   

			<div class="col-md-4">
              <div class="form-group">
                <label>Company Group</label>				
				 <?php $categoryDropDown = '<select class="form-control select2" name="id_default_group">
											    <option value="">Select Company Group</option>';
											  $resCat = selectSql(TBL_GROUP," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `id_group`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_default_group'] == $resultCat->id_group){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_group.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
              </div>
			  			
          </div>
		            
		            
		            <!--Area Executive End-->
		            <!--Search by Company Email-->
		     		  <!--<div class="col-md-3">
		                   <div class="form-group">
		                     <label>Company Email</label>				
		     				<?php 
		     					$categoryDropDown = '<select class="form-control select2" name="id_email">
		  								<option value="">Select Email</option>  ';
		  								  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
		  									  if($db->num_rows2($resCat)){
		  									  	while($resultCat = $db->fetch_object2($resCat)){
		  											/*if($_REQUEST['id_email'] == $resultCat->email){
		  												$selected = 'selected="selected"';
		  												}else{
		  												$selected = '';
		  											}*/
		  											$categoryDropDown .= '<option '.$selected.' value="'.htmlentities($resultCat->email).'">'.ucfirst($resultCat->email).'</option>';
		  											}
		  									   }
		  								echo $categoryDropDown .= '</select>';?>
		                   </div>
		                  
		                 </div> -->      	

		            <!-- End-->

		                      	

		                   <!-- End-->




		  <div class="col-md-4">
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
        <!--<a  href="companyExport.php?Download=Generate&id_area=<?php echo $_REQUEST['id_area']; ?>&search_name=<?php echo $_REQUEST['search_name']; ?>&status=<?php echo $_REQUEST['status']; ?>&id_default_group=<?php echo $_REQUEST['id_default_group']; ?>&id_email=<?php echo $_REQUEST['id_email']; ?>&id_phone=<?php echo $_REQUEST['id_phone']; ?>" class="btn btn-primary" />Generate</a>-->
        </div>
		</form>		
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Company List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="5%"><!--<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />--> S.No.&nbsp;</th>
                  <th>Company Name</th>
                  <th>Created By</th>
				  <th>Company Group</th>
				  <th>Area</th>
				  <th>Area Description</th>
				  <?php if($_SESSION['userLevel']==1){ ?>
                  <th>Status</th>
              	  <?php } ?>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id_company;?>"/>--> 
				  <?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$row->name.'-'.$row->city;?></td>
                  <td><?=selectColumn(TBL_USERS,'name','where id="'.$row->created_by.'" ');?></td>
				  <td><?php if($row->id_default_group ==0){ echo 'Default/Guest'; }else {echo selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$row->id_default_group."'"); }  ?></td>
				  <td><?php echo selectColumn(TBL_AREAS,'name'," WHERE `id` = '".$row->area."'");  ?></td>
				  <td><?php echo selectColumn(TBL_AREAS,'description'," WHERE `id` = '".$row->area."'");  ?></td>
				  <?php if($_SESSION['userLevel']==1){ ?>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCompany.php?inactiveId='.encryptor('encrypt',$row->id_company).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCompany.php?activeId='.encryptor('encrypt',$row->id_company).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	<?php } ?>		 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCompany.php?eId=<?=encryptor('encrypt',$row->id_company)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id_company;?>" onClick="deleteMe(this.id,this.name);"/></td>
                </tr>
               <?php }?> 
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<?php /*?><tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr> <?php */?>              
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="5">---- No Record Found ---- </td>
                 </tr>                 
				<?php  }?>
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
	  //COMPANY AUTO COMPLETE END==================================================================
 
  	function deleteMe(id,name){
  		//var companyName='<?php echo $_REQUEST['search_name'];?>';
  		var companyName=$("#search_name").val();
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageCompany.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>&search_name='+companyName+'&searchFormSubmit=1&Search=Search';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_company="+id, true);
  		  xhttp.send();
  	}
  </script> 

<!--jump-->
<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCompany").click(function(){
        $("#companyimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#companyImport").val();
          console.log(fileName);
          if(fileName == ""){
          	$("#returnTxt").css("color","red");
          	$("#returnTxt").html(" !! Kindly Select a file !!");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false, 
            url         : 'ajax/ajaxCompanyImport.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data);
              /*$("#credithidden").val(data[1]);*/
              //alert(data);
            } 
           })
          }
        });
      });
	});
</script>