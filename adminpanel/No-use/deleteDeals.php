<?php session_start();
//echo "----------".$_SESSION[sessAdminUsername];
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],TBL_MOBILE_DEALS,'view');
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_MOBILE_DEALS,'activate');
		$statusSql = "UPDATE `".TBL_MOBILE_DEALS."` SET `status` = '1' WHERE `merchant_id` = '".addslashes($_REQUEST['activeId'])."'";
		$mesId=$_REQUEST['activeId'];
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_MOBILE_DEALS,'deactivate');
		$statusSql = "UPDATE `".TBL_MOBILE_DEALS."` SET `status` = '0' WHERE `merchant_id` = '".addslashes($_REQUEST['inactiveId'])."'";
		$mesId=$_REQUEST['inactiveId'];
	}
	if(@mysql_query($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] ='Status of All Deals of '.selectColumn(TBL_MERCHANT, "name" , "WHERE `id` = '".$mesId."'").' has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Merchant Deals status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_MOBILE_DEALS,'delete');
	$delSql = "DELETE FROM `".TBL_MOBILE_DEALS."` WHERE `merchant_id` = '".addslashes($_REQUEST['delId'])."'";
	
	if(@mysql_query($delSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'All Deals of '.selectColumn(TBL_MERCHANT, "name" , "WHERE `id` = '".$_REQUEST['delId']."'").' Merchant has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Deals.';
	}
	
}else if ($_REQUEST['action'] == 'refreshId'){
	$selectIdSql = "SELECT id FROM `".TBL_MOBILE_DEALS."` ORDER BY `id` ASC";
	//echo "$delSql";
	$resIdSql=mysql_query($selectIdSql);
	
	if(mysql_num_rows($resIdSql) > 0 ){
		$first=mysql_fetch_array($resIdSql);
		$DeleteHowMuch=$first[0]-1;
		//echo "DeleteHowMuch--->".$DeleteHowMuch;
		$UpdateIdSql="UPDATE `".TBL_MOBILE_DEALS."` SET `id` = `id`-".$DeleteHowMuch." ORDER BY `id` ASC";
			//echo "<br>$UpdateIdSql";
			
			if(@mysql_query($UpdateIdSql)){
				$err = 0;
				$_SESSION['successMsg'] ="Deals are refreshed and Now Id Starts from 1";
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'Deals are not refreshed !!.';
			}
	}else{
		$_SESSION['errorMsg'] = 'No Deals in Deal Table.';
	}
	
	
}
$setpage =10;
$image_path = $UPLOAD_FILES.'/photo/';
$image_display_path = $UPLOAD_FILES_PATH ."/photo/";

// ----------cate---------
$sqlDeal = " SELECT * FROM `".TBL_MOBILE_DEALS."` WHERE 1 ";

	$sqlDeal .= " GROUP BY merchant_id ORDER BY `merchant_id`";

$db->query($sqlDeal);
$numRows =$db->num_rows();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Panel : Manage Merchant Deals</title>
<link href="css/menus.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
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
/*select {
   width: 200px;
   height: 24px;
   overflow: hidden;
   background:  #e2e2e2;
}*/
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
                                <td width="100%"  align="left" valign="top" height="27"  bgcolor="#f4f4f4" class="admin_heading border03">Feed Manager (Contract Deals) - ><a href="deleteDeals.php" class="admin_heading cursor_text">Merchants Deal</a>
                                  <div style="float:right; display:inline; padding-right:10px;"><a style="border:0px;" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_MOBILE_DEALS;?>"><img border="0"  src="images/excel-icon.jpg" width="20" height="20" title="Export to excel file" /></a>&nbsp;<a href="exportTable.php?fileType=csv&tableName=<?php echo TBL_MOBILE_DEALS;?>"><img  src="images/excel-csv-icon.jpg" width="20" border="0" height="20" title="Export to csv file" /></a></div></td>
                              </tr>
                              <tr>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                              </tr>
                              <tr>
                                <td>
							<!----- Display SubCategory Listing End --------->
                                  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0" >
                                   
                                    <tr>
                                      <td  class="border03"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td><p>
                                              
                                                <div id="listingDiv">
                                                  <table width="100%" border="0" cellspacing="1" cellpadding="4">
                                                    <tr>
                                                      <td height="32" colspan="5" align="right" valign="middle" ><span class="css_link_new" style="cursor:pointer;" title="Refresh" onClick="if(confirm('Are you sure that you want to refresh Deal Id ?')){window.location.href='deleteDeals.php?action=refreshId';}"> Refresh Deals Id </span> </td>
                                                    </tr>
                                                    <?php if($_SESSION['errorMsg']){?>
                                                    <tr>
                                                      <td  height="50" align="center" valign="middle" colspan="5" ><?php echo messageError($_SESSION['errorMsg']);?></td>
                                                    </tr>
                                                    <?php unset($_SESSION['errorMsg']);}else if($_SESSION['successMsg']){?>
                                                    <tr>
                                                      <td  height="50" align="center" valign="middle" colspan="5" ><?php echo messageSuc($_SESSION['successMsg']);?></td>
                                                    </tr>
													<?php unset($_SESSION['successMsg']);}?>
                                                    <tr bgcolor="#CCCCCC">
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">&nbsp;</td>
                                                      <td  height="20" align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Merchant Name<!--<a href="#;" onclick="fieldOrder('albumAjax.php','<?=$setpage?>','<?=addslashes($ajax_query) ?>','title','asc','listingDiv');" ><img src="images/asce.gif" border="0" /></a>&nbsp;&nbsp;<a href="#;" onclick="fieldOrder('albumAjax.php','<?=$setpage?>','<?=addslashes($ajax_query) ?>','title','desc','listingDiv');"><img src="images/desc.gif" border="0" /></a>--></td>
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Date Created</td>
                                                      <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Status</td>
                                                      <td width="131"  align="left" valign="middle" class="admin_heading border04 main_borders">&nbsp;</td>
                                                    </tr>
                                                    <?php 
													
													if($numRows > 0){$counter = 1;
														while($rowFeedList = $db->fetch_object()){?>
                                                    <tr style="background-color:<?=$color;?>;" id="importTr<?=$rowFeedList->id?>">
                                                      <td width="66" align="left" valign="middle" class="admin_heading border04 main_borders" ><?php echo $counter++;?>.&nbsp;</td>
                                                      <td width="520" height="27" align="left" valign="middle" class="admin_heading border04 main_borders" ><?= selectColumn(TBL_MERCHANT, "name" , "WHERE `id` = '".$rowFeedList->merchant_id."'"); ?></td> 
                                                      <td width="305" align="left" valign="middle" class="admin_heading border04 main_borders" ><?=$rowFeedList->date_created?>
&nbsp;</td>
                                                      <td width="305" align="left" valign="middle" class="admin_heading border04 main_borders" id="importTd<?=$rowFeedList->id?>"><?=$rowFeedList->status=='1'?'<span onclick="location.href=\'deleteDeals.php?inactiveId='.$rowFeedList->merchant_id.'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'deleteDeals.php?activeId='.$rowFeedList->merchant_id.'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?></td>
                                                      <td height="27" align="center" valign="middle" nowrap="nowrap" class=" admin_heading border05 main_borders">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$rowFeedList->feed_name;?>?')){window.location.href='deleteDeals.php?delId=<?=$rowFeedList->merchant_id?>&action=delete';}"/> </td>
                                                    </tr><?php }?>
                                                    <tr>
                                                      <td colspan="5" >&nbsp;</td>
                                                    </tr>
                                                    
													
                                                    <tr>
                                                      <td colspan="5" align="center"></td>
                                                    </tr>
													<?php }else{?>
                                                    <tr>
                                                      <td height="200" align="center" class="message_txt" colspan="5">---- No Record Found ---- </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                      <td height="27" colspan="5" align="left" valign="middle" bgcolor="#ffffff" class="border06">&nbsp;</td>
                                                    </tr>
													<?php }?>
                                                  </table>
                                                </div>
                                              
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
 