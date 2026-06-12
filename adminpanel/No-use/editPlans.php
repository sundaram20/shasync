<?php session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");//echo '<pre>';print_r($_REQUEST);echo '</pre>';//echo '<pre>';print_r($_FILES);echo '</pre>';
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'view');
$image_path = $UPLOAD_FILES.'/plans/';
$image_display_path = $UPLOAD_FILES_PATH ."/plans/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	/*if($_POST['networkId'] == ''){
		$err++;
		$err_network_id = '<font style="color:red;font-weight:normal;" ><br>Please select network.</font>';
	}*/
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter plan title.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_PLANS."` 
											WHERE `id` NOT IN('".addslashes($_REQUEST[eId])."') AND `name` = '".addslashes($_POST['name'])."'"))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Plan name all-ready exists in our database.</font>';
	}
	//------------------------------
	/*if(empty($_POST['identifier'])){
		$err++;
		$err_identifier = '<font style="color:red;font-weight:normal;" ><br>Please enter plan identifier.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_PLANS."` 
											WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `identifier` = '".addslashes($_POST['identifier'])."'"))){
		$err++;
		$err_identifier = '<font style="color:red;font-weight:normal;" ><br>Plan identifier all-ready exists in our database.</font>';
	}*/
	if(empty($_POST['page_url'])){
		$err++;
		$err_page_url = '<font style="color:red;font-weight:normal;" ><br>Please enter page url.</font>';
	}else if(mysql_num_rows(mysql_query("	SELECT * FROM `".TBL_PLANS."` 
											WHERE `id` NOT IN('".addslashes($_REQUEST[eId])."') AND `page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."'"))){
		$err++;
		$err_page_url = '<font style="color:red;font-weight:normal;" ><br>This page url is all-ready in use.</font>';
	}
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'add');
			$addSql = "   	INSERT INTO `".TBL_PLANS."` SET 
							`name` = '".addslashes($_POST['name'])."'";
			$addSql .= "	,`line_rental` = '".addslashes($_POST['line_rental'])."'";
			$addSql .= "	,`free_minutes` = '".addslashes($_POST['free_minutes'])."'";
			$addSql .= "	,`free_texts` = '".addslashes($_POST['free_texts'])."'";
			$addSql .= "	,`page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."'";
			$addSql .= "	,`free_internet_allowance` = '".addslashes($_POST['free_internet_allowance'])."'";
			$addSql .= "	,`contract_duration` = '".addslashes($_POST['contract_duration'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`display_order` = '".addslashes($_POST['display_order'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New Plan details has been added sucessfully.';
				header("location:managePlans.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Plan details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_PLANS,'edit');
			$editSql = "   	UPDATE `".TBL_PLANS."` SET 
			                `name` = '".addslashes($_POST['name'])."'";
			$editSql .= "	,`line_rental` = '".addslashes($_POST['line_rental'])."'";
			$editSql .= "	,`page_url` = '".addslashes(friendlyUrl($_POST['page_url']))."'";
			$editSql .= "	,`free_minutes` = '".addslashes($_POST['free_minutes'])."'";
			$editSql .= "	,`free_texts` = '".addslashes($_POST['free_texts'])."'";
			$editSql .= "	,`free_internet_allowance` = '".addslashes($_POST['free_internet_allowance'])."'";
			$editSql .= "	,`contract_duration` = '".addslashes($_POST['contract_duration'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`display_order` = '".addslashes($_POST['display_order'])."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['eId'])."'";
							
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Plan details '.selectColumn(TBL_PLANS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' has been updated sucessfully.';
				header("location:managePlans.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Plan '.selectColumn(TBL_PLANS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Plan details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlPlanDetail = "  SELECT * FROM `".TBL_PLANS."`
								WHERE `id` = '".addslashes($_REQUEST['eId'])."'";
	$db->query($sqlPlanDetail);
	if($db->num_rows() > 0){
		$rowPlanDetail = $db->fetch_object();
	}						
}	
							

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Panel : Add/Edit Plan</title>
<link href="css/menus.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.2.6.pack.js"></script>
<script type="text/javascript" src="js/ddaccordion.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
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
                            <td width="100%"  align="left" valign="middle" height="27"  bgcolor="#f4f4f4" class="admin_heading border03">Plan  Manager - > <a href="editPlans.php" class="admin_heading cursor_text">Add/Edit  Plan</a></td>
                          </tr>
                          <tr>
                            <td bgcolor="#FFFFFF">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><!----- Display SubCategory Listing End --------->
                              <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0" >
                                <tr class="bg_border">
                                  <td height="30" colspan="6" align="left" valign="middle" class="admin_heading_grey "><form name="form1"  method="post" enctype="multipart/form-data">
                                      <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
                                      <table width="100%" cellpadding="4" cellspacing="1"  >
                                        <tr >
                                          <td colspan="2" align="left"   bgcolor="#cccccc" class="main_txt_new border_bottom_new"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Plans </td>
                                        </tr>
                                        <?php if($_SESSION['errorMsg']){?>
                                        <tr>
                                          <td  height="30"  align="center" valign="top" colspan="2" ><?php echo messageError($_SESSION['errorMsg']);?></td>
                                        </tr>
                                        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                                        <tr>
                                          <td  height="30"  align="center" valign="top" colspan="2" ><?php echo messageSuc($_SESSION['successMsg']);?></td>
                                        </tr>
                                        <?php unset($_SESSION['successMsg']);}?>
                                        <?php /*?><tr>
                                          <td width="25%" valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Network<font color="#FF0000">*</font></td>
                                          <td width="75%" valign="top" class="text"><?php $categoryDropDown = '<select class="may_text_area1" name="networkId">
											  								<option value="">Selct network</option>';
											  $resNetwork = selectSql(TBL_NETWORKS," WHERE `status` = '1'",' ORDER BY `name`');
											  if(mysql_num_rows($resNetwork)){
											  	while($resultNetwork = mysql_fetch_object($resNetwork)){
													if($_REQUEST['networkId'] == $resultNetwork->id){
														$selected = 'selected="selected"';
													}elseif($rowPlanDetail->network_id == $resultNetwork->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultNetwork->id.'">'.ucfirst($resultNetwork->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                                            <span style="color:#CCCCCC; font-weight:normal;">&nbsp;</span><?php echo $err_network_id;?></td>
                                        </tr><?php */?>
                                        <tr>
                                          <td width="25%" valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Plan Name<font color="#FF0000">*</font></td>
                                          <td width="75%" valign="top" class="text"><input type="text" onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area1" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowPlanDetail->name);?>"/>
                                            <span style="color:#CCCCCC; font-weight:normal;">&nbsp;Must be unique.</span><?php echo $err_name;?></td>
                                        </tr>
                                        <!--<tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Plan Identifier <font color="#FF0000">*</font></td>
                                          <td valign="top" class="text"><input type="text" onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area1" id="identifier" name="identifier"  value="<?php if($_POST) echo $_POST['identifier'];else echo stripslashes($rowPlanDetail->identifier);?>"/>
                                            <span style="color:#CCCCCC; font-weight:normal;">&nbsp;ex: 1  &nbsp;Must be unique.</span><?php echo $err_identifier;?></td>
                                        </tr>-->
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;" colspan="2"></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Line Rental<font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area2" rows="5" name="line_rental"><?php if($_POST)echo $_POST['line_rental'];else echo stripslashes($rowPlanDetail->line_rental);?>
</textarea>
                                            <?php echo $err_line_rental;?> </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Free Minutes<font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area2" rows="5" name="free_minutes"><?php if($_POST)echo $_POST['free_minutes'];else echo stripslashes($rowPlanDetail->free_minutes);?>
</textarea>
                                            <?php echo $err_free_minutes;?> </td>
                                        </tr>
                                        
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Free Texts<font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area2" rows="5" name="free_texts"><?php if($_POST)echo $_POST['free_texts'];else echo stripslashes($rowPlanDetail->free_texts);?>
</textarea>
                                            <?php echo $err_free_texts;?> </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Free Internet Allowances<font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area2" rows="5" name="free_internet_allowance"><?php if($_POST)echo $_POST['free_internet_allowance'];else echo stripslashes($rowPlanDetail->free_internet_allowance);?>
</textarea>
                                              <?php echo $err_free_internet_allowance;?> </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Contract Duration<font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area2" rows="5" name="contract_duration"><?php if($_POST)echo $_POST['contract_duration'];else echo stripslashes($rowPlanDetail->contract_duration);?>
</textarea>
                                            <?php echo $err_contract_duration;?> </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Page URL <font color="#FF0000">*</font></td>
                                          <td valign="top" class="text"><textarea onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'"    class="may_text_area2" rows="5" name="page_url"><?php if($_POST)echo $_POST['page_url'];else echo stripslashes($rowPlanDetail->page_url);?>
</textarea><span style="color:#CCCCCC; font-weight:normal;">&nbsp;Must be unique.</span><?php echo $err_page_url;?>
                                           </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Status</td>
                                          <td valign="top" class="admin_toptextn" style="border: 1px solid rgb(255, 255, 255);" onClick="this.style.border='#666666 solid 2px'" onMouseOut="this.style.border='1px solid #ffffff'"><table width="100%" cellspacing="2" cellpadding="0" border="0" class="new_text">
                                              <tbody>
                                                <tr>
                                                  <td width="5%"><input type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowPlanDetail->status == 1)echo "checked";}?> value="1" name="status"/></td>
                                                  <td width="18%">Active</td>
                                                  <td width="6%"><input type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowPlanDetail->status == 0)echo "checked";}?> value="0" name="status"/></td>
                                                  <td width="71%"> Inactive </td>
                                                </tr>
                                              </tbody>
                                            </table></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Display Order</td>
                                          <td valign="top" class="text"><input type="text" onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area1" id="display_order" name="display_order"  value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($rowPlanDetail->display_order);?>"/>
                                           <?php echo $err_display_order;?></td>
                                        </tr>
                                        <?php if($rowPlanDetail->date_created){?>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;" colspan="2"></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Date Created <font color="#FF0000">&nbsp;</font></td>
                                          <td valign="top" class="text"><input disabled="disabled" type="text" readonly="readonly" class="may_text_area1" id="date_created"  value="<?php echo stripslashes($rowPlanDetail->date_created);?>"/></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Last Updated</td>
                                          <td valign="top" class="text"><input disabled="disabled" type="text" readonly="readonly" class="may_text_area1" id="last_modified" value="<?php echo stripslashes($rowPlanDetail->last_modified);?>"/></td>
                                        </tr>
                                        <tr>
                                          <td valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Last Updated By</td>
                                          <?php $sqlUserDetail = @mysql_fetch_object(@mysql_query("SELECT `username` FROM `".TBL_USERS."` WHERE `id` = '".$rowPlanDetail->last_modified_by."'"));?>
                                          <td valign="top" class="text"><input type="text" disabled="disabled"  readonly="readonly" class="may_text_area1" id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>"/></td>
                                        </tr>
                                        <?php }?>
                                        <tr>
                                          <td  class="new_text padding_left14" bgcolor="#f4f4f4">&nbsp;</td>
                                          <td align="left"  valign="middle" bgcolor="#FFFFFF" class="padding13 padding_left14 "><input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="css_btns" name="Save" >
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type='button' value='Cancel' class="css_btns" onclick=' back_prev_user("managePlans.php"); '></td>
                                        </tr>
                                      </table>
                                    </form></td>
                                </tr>
                                <!-- end search box here -->
                                <tr>
                                  <td  class="border03">&nbsp;</td>
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
