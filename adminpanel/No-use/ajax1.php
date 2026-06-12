<?php //session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
//adminLoginCheck();
//checkUserLevelPermission($_SESSION['userLevel'],TBL_LEADS_ASSIGN,'view');
$sql = "Select * from `".TBL_LEADS_ASSIGN."` where id = '".$_GET['id']."'";
$res = executeSql($sql);
//$line_booking=mysql_fetch_array($res);
$rowUsers = @mysql_fetch_object($res);
	     
?>
<div  style="width:auto; margin:auto;">
  <form name="form"  method="post" enctype="multipart/form-data" action="ajaxprocess1.php">
  <input type="hidden" name="id" value="<?=$rowUsers->id?>" />
    <table >
      <tr>
        <td width="25%" valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Client Name<font color="#FF0000">*</font></td>
        <td width="75%" valign="top" class="text"><?php echo selectColumn(TBL_CLIENT,'name'," WHERE `id` = '".$rowUsers->client_id."'"); ?>
          <input type="hidden" value="<?php echo stripslashes($rowUsers->client_id);?>" name="clientId" />
          <span style="color:#CCCCCC; font-weight:normal;">&nbsp;</span><?php echo $err_clientId;?></td>
      </tr>
      <tr>
        <td width="25%" valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Lead Status<font color="#FF0000">*</font></td>
        <td width="75%" valign="top" class="text"><?php 
			  $leadDropDown = '<select class="may_text_area1" name="leadStatus">
												<option value="">---Select Lead Status---</option>';
				  $resLead = selectSql(TBL_LEADS_STATUS," WHERE `status` = '1' and `lead_status` = '3'",' ORDER BY `name`');
				  if(mysql_num_rows($resLead)){
					while($resultLead = mysql_fetch_object($resLead)){
						if($_REQUEST['leadStatus'] == $resultLead->id){
							$selected = 'selected="selected"';
						}elseif($rowUsers->lead_status == $resultLead->id){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						$leadDropDown .= '<option '.$selected.' value="'.$resultLead->id.'">'.ucfirst($resultLead->name).'</option>';
					}
				  }
					echo $leadDropDown .= '</select>';
				 ?>
          <span style="color:#CCCCCC; font-weight:normal;">&nbsp;</span><?php echo $err_leadId;?></td>
      </tr>
      <tr>
        <td width="25%" valign="top" bgcolor="#f4f4f4" class="new_text" style="padding-left: 6px;">Work Status</td>
        <td width="75%" valign="top" class="text"><input type="text" onBlur="this.style.border='1px solid #999999'" onFocus="this.style.border='#666666 solid 2px'" class="may_text_area1" id="workStatus" name="workStatus" value="<?php if($_POST) echo $_POST['workStatus'];else echo stripslashes($rowUsers->work_status);?>"/>
          <span style="color:#5F5F5F; font-weight:normal;">Short Description &nbsp;</span> <?php echo $err_work_status;?></td>
      </tr>
      <tr>
        <td  class="new_text padding_left14" bgcolor="#f4f4f4">&nbsp;</td>
        <td align="left"  valign="middle" bgcolor="#FFFFFF" class="padding13 padding_left14 "><input type='Submit' value='Save' class="css_btns" name="Submit" >
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type='button' value='Cancel' class="css_btns" onclick=' back_prev_user("manageLeadsAssign.php"); '></td>
      </tr>
    </table>
  </form>
</div>
