<?php session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'view');
$image_path = $UPLOAD_FILES.'/plans/';

$image_display_path = $UPLOAD_FILES_PATH ."/plans/";
//echo '<pre>';print_r($_REQUEST);echo '</pre>';
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'activate');
		$statusId = addslashes($_REQUEST['activeId']);
		$statusSql = "	UPDATE `".TBL_PLANS."` 
						SET `status` = '1' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($_REQUEST['activeId'])."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'deactivate');
		$statusId = addslashes($_REQUEST['inactiveId']);
		$statusSql = "	UPDATE `".TBL_PLANS."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."' 
						WHERE `id` = '".addslashes($_REQUEST['inactiveId'])."'";
	}
	if(@mysql_query($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Plan '.selectColumn(TBL_PLANS,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Plan '.selectColumn(TBL_PLANS,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'delete');
	$delSql = "DELETE FROM `".TBL_PLANS."` WHERE `id` = '".addslashes($_REQUEST['delId'])."'";
	$sqlDelPlans = selectRow(TBL_PLANS," WHERE `id` = '".addslashes($_REQUEST['delId'])."'");
	if(@mysql_query($delSql)){
		$err = 0;
		if(file_exists($image_path.$sqlDelPlans['image_small'])){
			@unlink($image_path.$sqlDelPlans['image_small']);
			@unlink($image_path.$sqlDelPlans['image_medium']);
			@unlink($image_path.$sqlDelPlans['image_large']);
		}
		$_SESSION['successMsg'] = 'One Plan '.$sqlDelPlans["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Plan '.$sqlDelPlans["name"];
	}
}


///////////////////Pagging Step 1 start ///////////////////////////////////////////////
$limit=10;
$REQUEST_URI_STRING=$_SERVER['REQUEST_URI'];
$search="page";
$strpos=strpos($REQUEST_URI_STRING,$search);
if(isset($_GET['page']) && $_GET['page']!='' )
{
	$page_url=substr($REQUEST_URI_STRING,0,$strpos-1);
	$page_no=$_GET['page'];
}
else
{
	$page_no=0;
	$page_url=$_SERVER['REQUEST_URI'];
}
$targetpage=$page_url;
$page = $page_no;


if($page){ 
		$start = ($page - 1) * $limit; 			
}else{
		$start = 0;	
}
if($_REQUEST['searchFormSubmit'] != ''){

	$cont='&page=';
}else if($_REQUEST['action'] != ''){
	$cont='&page=';
}else{
	$cont='?page=';
}

////////////////////////Pagging step 1 end  /////////////////////////////

// ----------cate---------
$sqlPlan = " SELECT * FROM `".TBL_PLANS."` WHERE 1 ";



if($_REQUEST['search_name'] != ''){
	$sqlPlan .= " AND `name` LIKE '%".$_REQUEST['search_name']."%'";
	
}
if($_REQUEST['status'] != ''){
	$sqlPlan .= " AND `status` = '".$_REQUEST['status']."%'";
	
}

$sqlPlan .= " ORDER BY `name`";
	




///////////////////paging Step 2 start /////////////////////////
$query_deal = $sqlPlan;		
$res_deal = mysql_query($query_deal);
$totalitems = mysql_num_rows($res_deal);					
$sqlPlan =$sqlPlan." LIMIT $start, $limit";	

////////////////////////pagging Step 2 end //////////////////////////////////////////////

$resPlan = executeSql($sqlPlan);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Panel : Manage Plan</title>
<link href="css/menus.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<link href="css/pagging.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-image: url(images/bg.jpg);
	background-repeat: repeat-x;
}
-->
</style>
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td style="padding:5px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><?php include_once("includes/header.php")?></td>
            </tr>
            <tr>
              <td align="left" valign="top" style="padding-top:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left" valign="top" bgcolor="#ffffff" style="border:#06528C solid 2px; padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr>
                          <td width="203" align="left" valign="top"><?php include_once("includes/left.php");?></td>
                          <td height="450" align="left" valign="top" style="padding-left:10px;"><table width="100%" cellpadding="0" cellspacing="0" style="border:#1a4c6d solid 1px;">
                              <!-- indication bar -->
                              <tr class="back_color">
                                <td width="100%"  align="left" valign="top" height="27"  bgcolor="#f4f4f4" class="admin_heading border03">Plan  Manager - ><a href="managePlans.php" class="admin_heading cursor_text">Plans</a><div style="float:right; display:inline; padding-right:10px;"><a style="border:0px;" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_PLANS;?>"><img border="0"  src="images/excel-icon.jpg" width="20" height="20" title="Export to excel file" /></a>&nbsp;<a href="exportTable.php?fileType=csv&tableName=<?php echo TBL_PLANS;?>"><img  src="images/excel-csv-icon.jpg" width="20" border="0" height="20" title="Export to csv file" /></a>&nbsp;&nbsp;&nbsp;&nbsp;</div></td>
                              </tr>
                              <tr>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                              </tr>
                              <tr>
                                <td>
							<!----- Display SubCategory Listing End --------->
                                  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0" >
                                    <!-- search box -->
                                    <tr class="bg_border">
                                      <td height="30" colspan="7" align="left" valign="middle" class="admin_heading_grey "><form name="searchForm" action="" method="get" >
                                          <input type="hidden" value="1" name="searchFormSubmit" />
                                          <table width="100%" border="0" class="border02" cellpadding="3" cellspacing="1" bgcolor="#cccccc">
                                            
                                            <tr>
                                              <td width="15%" height="25" align="left" valign="middle" bgcolor="#e2e2e2" class="admin_heading"> Plan Title </td>
                                              <td width="34%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"><input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="may_text_area1" />
                                              </td>
                                              <td width="35%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding">&nbsp;</td>
                                              <td width="16%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td width="15%" height="25" align="left" valign="middle" bgcolor="#e2e2e2" class="admin_heading"> Status</td>
                                              <td width="34%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"><?php 
											    if($_REQUEST['status'] == '1'){
														$selected1 = 'selected="selected"';
												}elseif($_REQUEST['status'] == '0'){
														$selected0 = 'selected="selected"';
												}
											  echo $statusDropDown = '<select class="may_text_area1" name="status"> <option value="">Both</option>
											  <option '.$selected1.' value="1">Active</option>
											  <option '.$selected0.' value="0">Inactive</option>
											  </select>';?>
                                              </td>
                                              <td width="35%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"><input name="Search" type="submit" class="css_btns" value="Search" /></td>
                                              <td width="16%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"><b>Total Records:</b> (<?=mysql_num_rows($resPlan);?>) &nbsp;</td>
                                            </tr>
                                          </table>
                                        </form></td>
                                    </tr>
                                    <!-- end search box here -->
                                    <tr>
                                      <td  class="border03"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td><p>
                                              <form name="listingForm" action="" method="post">
                                                <input type="hidden" value="" name="act" />
                                                <div id="listingDiv">
                                                  <table width="100%" border="0" cellspacing="1" cellpadding="4">
                                                    <tr>
                                                      <td height="32" colspan="7" align="right" valign="middle" ><a href="editPlans.php" class="css_link_new">Add New Plan </a> </td>
                                                    </tr>
                                                    <?php if($_SESSION['errorMsg']){?>
                                                    <tr>
                                                      <td  height="50" align="center" valign="middle" colspan="7" ><?php echo messageError($_SESSION['errorMsg']);?></td>
                                                    </tr>
                                                    <?php unset($_SESSION['errorMsg']);}else if($_SESSION['successMsg']){?>
                                                    <tr>
                                                      <td  height="50" align="center" valign="middle" colspan="7" ><?php echo messageSuc($_SESSION['successMsg']);?></td>
                                                    </tr>
													<?php unset($_SESSION['successMsg']);}?>
                                                    <tr bgcolor="#CCCCCC">
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">&nbsp;</td>
                                                      <td  height="20" align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Plan Title</td>
                                                      <td  height="20" align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Plan Id</td>
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Status</td>
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Last Updated</td>
                                                      <td width="170"  align="center" valign="middle" class="admin_heading border04 main_borders">Action</td>
                                                    </tr>
                                                    <?php 
													
													if(mysql_num_rows($resPlan)> 0){$counter = 1;
														while($rowPlan = mysql_fetch_object($resPlan)){?>
                                                    <tr style="background-color:<?=$color;?>;" >
                                                      <td width="45" align="left" valign="middle" class="admin_heading border04 main_borders" ><?php echo $counter++;?>.&nbsp;</td>
                                                      <td width="362" height="27" align="left" valign="middle" class="admin_heading border04 main_borders" id="show<?=$rowPlan->id?>"><?=ucfirst($rowPlan->name);?></td>
                                                      <td width="85" height="27" align="left" valign="middle" class="admin_heading border04 main_borders" id="show<?=$rowPlan->id?>"><?=ucfirst($rowPlan->id);?></td>
                                                      
                                                      <td width="173" align="left" valign="middle" class="admin_heading border04 main_borders" ><?=$rowPlan->status=='1'?'<span onclick="location.href=\'managePlans.php?inactiveId='.$rowPlan->id.'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'managePlans.php?activeId='.$rowPlan->id.'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>
                                                        &nbsp;</td>
                                                        <?php $sqlUserDetail = @mysql_fetch_object(@mysql_query("SELECT `username` FROM `".TBL_USERS."` WHERE `id` = '".$rowPlan->last_modified_by."'"));?>
                                                      <td width="171" align="left" valign="middle" class="admin_heading border04 main_borders" > On <?php echo stripslashes($rowPlan->last_modified);?><br> By <?php echo stripslashes($sqlUserDetail->username);?></td>
                                                      <td height="27" align="center" valign="middle" nowrap="nowrap" class=" admin_heading border05 main_borders"><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editPlans.php?eId=<?=$rowPlan->id?>&networkId=<?=$rowPlan->network_id?>&action=edit';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$rowPlan->name;?>?')){window.location.href='managePlans.php?delId=<?=$rowPlan->id?>&action=delete';}"/> </td>
                                                    </tr><?php }?>
                                                    <tr>
                                                      <td colspan="7" >&nbsp;</td>
                                                    </tr>
                                                    
													
                                                    <tr>
                                                      <td colspan="7" align="center"><?php echo getPaginationStringForBackEnd($page , $totalitems, $limit, $adjacents = 1, $targetpage, $pagestring=$cont );?></td>
                                                    </tr>
													<?php }else{?>
                                                    <tr>
                                                      <td height="200" align="center" class="message_txt" colspan="7">---- No Record Found ---- </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                      <td height="27" colspan="5" align="left" valign="middle" bgcolor="#ffffff" class="border06">&nbsp;</td>
                                                    </tr>
													<?php }?>
                                                  </table>
                                                </div>
                                              </form>
                                              </p></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </table></td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <td align="center" valign="top" class="admin_toptext" style="padding-top:12px;"><?php include_once("includes/footer.php");?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
</body>
</html>
