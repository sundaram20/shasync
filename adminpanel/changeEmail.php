<?php include_once("../config/auto_loader.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Panel : Change Email</title>
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
</style>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function validate_form(form)
{
	if(trimspace(document.form1.oldEmail.value) ==""){
        alert("Please Enter Your Old Email")          
        document.form1.oldEmail.focus();
        return false;
    }
    else if(trimspace(document.form1.newEmail.value) =="")
    {
        alert("Please Enter Your New Email")          
        document.form1.newEmail.focus();
        return false;
    }
  
  
    else if( document.form1.newEmail.value.length<5)
    {
        alert("Please Enter The Email in Minimum Five Character")          
        document.form1.newEmail.focus()
        return false;
    }
  
    else if( trimspace(document.form1.conEmail.value) =="")
    {
        alert("Please Confirm Your New Email")          
        document.form1.conEmail.focus()
        return false;
    }
    else if( trimspace(document.form1.conEmail.value)!=trimspace(document.form1.newEmail.value))
    {
        alert("Confirm Email Mismatch")          
        document.form1.conEmail.focus()
        return false;
    }
	else
	{
		return true;
	}
	
}
</script>
</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  
  <tr>
    <td style="padding:10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><?php include_once("includes/header.php")?></td>
      </tr>
      <tr>
        <td align="left" valign="top" style="padding-top:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          
          <tr>
            <td align="left" valign="top" bgcolor="#ffffff" style="border:#06528C solid 2px; padding:12px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
              <tr>
                <td width="203" align="left" valign="top">
				<?php include_once("includes/left.php");?></td>
                <td height="450" align="left" valign="top" style="padding-left:10px;">
				<table width="100%" cellpadding="0" cellspacing="0">
									<tr><td>
				
					<!----- Display SubCategory Listing End --------->									
			<table width="100%" border="0" align="left" cellpadding="3" cellspacing="0" style="border:#999999 solid 1px;">
			 <!-- indication bar -->
					  <tr class="back_color">
                        <td width="100%" colspan="6" align="left" valign="middle" height="27"  bgcolor="#CCCCCC" class="admin_heading border03">Settings - ><a href="categories.php" class="admin_heading cursor_text">Change Email </a></td>                      
					  </tr>
                      <tr>
                        <td  class="border03">
                              <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td><p><form action="process.php" method="post" name="form1" onsubmit=" return validate_form(this);" >
						<input type='hidden' name='process' value='changePassword'>
                                    <table width="100%" cellpadding="4" cellspacing="1" bgcolor="#1a4c6d">
                                      
                                      <?php if($_SESSION['sessErrorMsg']!=''){?>
                                      <tr>
                                        <td  height="30" bgcolor="#f8f9f9" align="center" valign="middle" colspan="2" ><?php echo messageError($arrSiteMsgs[$_SESSION['sessErrorMsg']] );?></td>
                                      </tr>
                                      <?php unset($_SESSION['sessErrorMsg']);}?>
                                      <?php if($_SESSION['sessSucMsg']!=''){?>
                                      <tr>
                                        <td  height="30" bgcolor="#f8f9f9" align="center" valign="middle" colspan="2" ><?php echo messageSuc($arrSiteMsgs[$_SESSION['sessSucMsg']] );?></td>
                                      </tr>
                                      <?php unset($_SESSION['sessSucMsg']);}?>
                                      <tr>
                                        <td width="34%"  bgcolor="#f4f4f4" class="new_text padding13 ">Old Email <span style="color:#FF0000;">*</span></td>
                                        <td width="66%" align="left" valign="middle" bgcolor="#FFFFFF" class="padding_left14 "><input type="text" name="oldEmail" id="oldEmail" onFocus="this.style.border='#72AEC0 solid 2px'" onBlur=" style.border='1px solid #6fb4da';" class="may_text_area1" required="Old Email"/>                                        </td>
                                      </tr>
                                       <tr>
                                        <td width="34%"  bgcolor="#f4f4f4" class="new_text padding13 ">New Email <span style="color:#FF0000;">*</span></td>
                                        <td width="66%" align="left" valign="middle" bgcolor="#FFFFFF" class="padding_left14 "><input name="newEmail" type="text" onFocus="this.style.border='#72AEC0 solid 2px'" onBlur=" style.border='1px solid #6fb4da';" class="may_text_area1" id="newEmail"  required="Confirm Email"/>                                       </td>
                                      </tr>
									   <tr>
                                        <td width="34%"  bgcolor="#f4f4f4" class="new_text padding13 ">Confirm Email <span style="color:#FF0000;">*</span></td>
                                        <td width="66%" align="left" valign="middle" bgcolor="#FFFFFF" class="padding_left14 "><input type="text" name="conEmail" id="conEmail" onFocus="this.style.border='#72AEC0 solid 2px'" onBlur=" style.border='1px solid #6fb4da';" class="may_text_area1" required="Confirm Email"/>                                        </td>
                                      </tr>
                                      
                                      <tr>
                                        <td bgcolor="#f4f4f4" class="new_text padding_left14">&nbsp;</td>
                                        <td align="left"  valign="middle" bgcolor="#FFFFFF" class="padding13 padding_left14 "><input name="Submit" type="submit" class="css_btns" value="Submit" /></td>
                                      </tr>
                                    </table>
                                  </div></form>
                                  </p></td>
                                </tr>
                              </table>							 </td>
						</tr>
					</table></td></tr>
			

					
							  
					     </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" valign="top" class="admin_toptext" style="padding-top:12px;"><?php include_once("includes/footer.php");?> </td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
