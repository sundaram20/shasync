<?php include_once("../config/auto_loader.php");
//////////////////////////////////////////////////////////////////////////////////////
checkUserLevelPermission($_SESSION['userLevel'],TBL_UPLOADED_FILES,'view');

if($_REQUEST['tableName'] != ''){
	if($_FILES['uploadFile']['name']){
		if(($_FILES['uploadFile']['type'] == 'application/vnd.ms-excel') || ($_FILES['uploadFile']['type']== 'text/comma-separated-values')){
			if($_FILES['uploadFile']['type'] == "text/comma-separated-values"){
					$fileEnding = "csv";
				}elseif($_FILES['uploadFile']['type'] == "application/vnd.ms-excel"){
					$fileEnding = "csv";
				}
			$filNameFirst = @explode('.',$_FILES['uploadFile']['name']);
			$savedFileName = $filNameFirst[0]."_".date("Y-m-d_h-i-s_".rand(11111,99999))."_im0.".$fileEnding;
			if(@move_uploaded_file($_FILES['uploadFile']['tmp_name'],"imported/".$savedFileName)){
				if(file_exists("imported/".$savedFileName)){
					if(@mysqli_query($connNew,"	INSERT INTO `".TBL_UPLOADED_FILES."`
										SET `file_name` = '".$savedFileName."',
										`table_name` = '".addslashes($_REQUEST['tableName'])."',
										`date_created` = '".date("Y-m-d h-i-s")."',
										`username` = '".$_SESSION['userName']."'")){
						$_SESSION['successMsg'] = $_FILES['uploadFile']['name'].' File has been uploaded sucessfully on server. Plz proceed to import process.';				
				     }else{
					 	$_SESSION['errorMsg'] = $_FILES['uploadFile']['name'].' File has not been uploaded';
					 }
				}else{
					$_SESSION['errorMsg'] = $_FILES['uploadFile']['name'].' File has not been uploaded';
				}
			}else{
				$_SESSION['errorMsg'] = $_FILES['uploadFile']['name'].' Unable to upload file.';
			}
		}else{
			$_SESSION['errorMsg'] = $_FILES['uploadFile']['name'].' Invalid file type '.$_FILES["uploadFile"]["type"].'!  Only .csv required.'.$_FILES["uploadFile"]["error"];
		}
	}	
}
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_UPLOADED_FILES,'delete');
	$delSql = "DELETE FROM `".TBL_UPLOADED_FILES."` WHERE `id` = '".addslashes($_REQUEST['delId'])."'";
	$sqlDelUsers = selectRow(TBL_UPLOADED_FILES," WHERE `id` = '".addslashes($_REQUEST['delId'])."'");
	if(@mysqli_query($connNew,$delSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'One Data '.$sqlDelUsers["name"].' has been deleted sucessfully.';
		header('location:importTable.php?fileType=csv&tableName=fs_client');
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete data '.$sqlDelUsers["name"];
	}
}
////////////////////////////
$sqlUploadedFileList = "SELECT * FROM `".TBL_UPLOADED_FILES."` 
						WHERE `table_name` = '".addslashes($_REQUEST['tableName'])."'
						AND `username` = '".$_SESSION['userName']."'";					
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin Panel : Import Clients</title>
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
                                <td width="100%"  align="left" valign="middle" height="27"  bgcolor="#f4f4f4" class="admin_heading border03"><?php echo $_REQUEST['tableName'];?> Manager - ><a href="manageManufacturer.php" class="admin_heading cursor_text"> Import <?php echo $_REQUEST['tableName'];?></a></td>
                              </tr>
                              <tr>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                              </tr>
                              <tr>
                                <td><!----- Display SubCategory Listing End --------->
                                  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0" >
                                    <!-- search box -->
                                    <tr class="bg_border">
                                      <td height="30" colspan="6" align="left" valign="middle" class="admin_heading_grey "><form name="uploadForm" action=""  method="post" enctype="multipart/form-data" onsubmit="return dissable();">
                                          <input type="hidden" value="<?php echo $_REQUEST['tableName'];?>" name="tableName" />
                                          <input type="hidden" value="1" name="uploadFormSubmit" />
                                          <table width="100%" border="0" class="border02" cellpadding="3" cellspacing="1" bgcolor="#cccccc">
                                            <tr>
                                              <td width="33%" height="25" align="left" valign="middle" bgcolor="#e2e2e2" class="admin_heading"> Import File to <?php echo $_REQUEST['tableName'];?> :</td>
                                              <td width="45%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"><input type="file" name="uploadFile" id="uploadFile" value="" class="may_text_area1" />
                                              </td>
                                              <td width="22%" align="left" valign="middle" bgcolor="#e2e2e2" class="main_padding"  id="uploadFormId"><input name="Search" type="submit" class="css_btns" value="Upload" style="cursor:pointer;" /></td>
                                            </tr>
                                          </table>
                                        </form></td>
                                    </tr>
                                    <!-- end search box here -->
                                    <tr>
                                      <td  class="border03"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                                          <tr>
                                            <td><p>
                                              <div id="listingDiv">
                                                <table width="100%" border="0" cellspacing="1" cellpadding="4">
                                                  <?php if($_SESSION['errorMsg']){?>
                                                  <tr>
                                                    <td  height="50" align="center" valign="middle" colspan="4" ><?php echo messageError($_SESSION['errorMsg']);?></td>
                                                  </tr>
                                                  <?php unset($_SESSION['errorMsg']);}else if($_SESSION['successMsg']){?>
                                                  <tr>
                                                    <td  height="50" align="center" valign="middle" colspan="4" ><?php echo messageSuc($_SESSION['successMsg']);?></td>
                                                  </tr>
                                                  <?php unset($_SESSION['successMsg']);}?>
                                                  <tr bgcolor="#CCCCCC">
                                                    <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">&nbsp;</td>
                                                    <td  height="20" align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Uploaded file Title</td>
                                                    <td align="left" valign="middle" nowrap="nowrap" class="admin_heading border04 main_borders">Import Status </td>
                                                    <td width="61"  align="left" valign="middle" class="admin_heading border04 main_borders">Action</td>
                                                  </tr>
                                                  <?php 
													$db->query($sqlUploadedFileList);
													$numRowsFile =$db->num_rows();
													if($numRowsFile > 0){$counter = 1;
														while($rowUploadedFileList = $db->fetch_object()){?>
                                                  <tr style="background-color:<?=$color;?>;" id="importTr<?=$rowUploadedFileList->id?>" >
                                                    <td width="33" align="left" valign="middle" class="admin_heading border04 main_borders" ><?php echo $counter++;?>.&nbsp;</td>
                                                    <td width="397" height="27" align="left" valign="middle" class="admin_heading border04 main_borders" id="show<?=$rowUploadedFileList->id?>"><?=$rowUploadedFileList->file_name;?></td>
                                                    <td width="172" align="left" valign="middle" class="admin_heading border04 main_borders" id="importTd<?=$rowUploadedFileList->id?>" ><?php echo $rowUploadedFileList->imported=='1' ? '<span onclick="location.href=\'manageClients.php?inactiveId='.$rowUploadedFileList->id.'&action=change\'" style="color:green;cursor:pointer;">Imoported</span>' : '<input style="color:red;cursor:pointer;" onclick="startImportingFile('."'".$rowUploadedFileList->id."'".','."'".$_SESSION['userName']."'".','."'".htmlspecialchars($rowUploadedFileList->table_name)."'".');" title="Click to start importing this file." name="Search" type="buton" class="css_btns" value="Import" />';?> </td>
                                                    <td height="27" align="center" valign="middle" nowrap="nowrap" class=" admin_heading border05 main_borders">&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$rowUploadedFileList->file_name;?>?')){window.location.href='importTable.php?delId=<?=$rowUploadedFileList->id?>&action=delete';}"/> </td>
                                                  </tr>
                                                  <?php }?>
                                                  <tr>
                                                    <td colspan="4" >&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" align="center"><?php // echo $pagging->getLinks();?></td>
                                                  </tr>
                                                  <?php }else{?>
                                                  <tr>
                                                    <td height="200" align="center" class="message_txt" colspan="4">---- No Record Found ---- </td>
                                                  </tr>
                                                  <tr>
                                                    <td height="27" colspan="3" align="left" valign="middle" bgcolor="#ffffff" class="border06">&nbsp;</td>
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
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
function startImportingFile(uploadedFileId,userName,tableName){
	//alert(uploadedFileId+" "+userName+" "+tableName);
	var importTr = 'importTr'+uploadedFileId;
	var importTd = 'importTd'+uploadedFileId;
	var confirmVal = confirm("Are you sure you want to import this file?");
 	if(confirmVal == true){
		$("#"+importTd).html("<FONT COLOR='RED' style='font-size:9px;margin-left:0px;'><img src='images/ajax-loader.gif'/>Reading File...</FONT>");
		$.post("processImport.php", {processImport:"processDelimiters",uploadedFileId:uploadedFileId,userName:userName,tableName:tableName},
		function(data){   
			$("#"+importTr).html(data);
		});
	}
}
function subDelimiters(upLoadId,userName,tableName){
	var delimitersFomName = "formDelimiter"+upLoadId;
	var fieldsTerminatedBy = document.getElementById(delimitersFomName).fieldsTerminatedBy.value;
	var fieldsEnclosedBy = document.getElementById(delimitersFomName).fieldsEnclosedBy.value;
	var fieldsEscapsedBy = document.getElementById(delimitersFomName).fieldsEscapsedBy.value;
	var linesTerminatedBy = document.getElementById(delimitersFomName).linesTerminatedBy.value;
	//var re5digit=/^["\;\t\n]{1,2}$/ ;//regular expression defining a 5 digit number
	if(fieldsTerminatedBy == ''){
		alert("Please enter fields terminated by.");
		return false;
	}else if(fieldsEnclosedBy == ''){
		alert("Please enter fields enclosed by.");
		return false;
	}else if(fieldsEscapsedBy == ''){
		alert("Please enter fields escapsed by.");
		return false;
	}else if(linesTerminatedBy == ''){
		alert("Please enter lines terminated by.");
		return false;
	}else{
		var mapDisplayId = "mappingTd"+upLoadId;
		var htmlData = "<FONT COLOR='RED' style='font-size:9px;margin-left:0px;'><img src='images/ajax-loader1.gif'/>Reading File and table structure...</FONT>";
		$("#"+mapDisplayId).html(htmlData);
		$.post("processImport.php", {processImport:"processMappingTableStructure",uploadedFileId:upLoadId,userName:userName,tableName:tableName,fieldsTerminatedBy:fieldsTerminatedBy,fieldsEnclosedBy:fieldsEnclosedBy,fieldsEscapsedBy:fieldsEscapsedBy,linesTerminatedBy:linesTerminatedBy},
		function(data){   
			//document.getElementById(mapDisplayId).innerHTML=data;
			$("#"+mapDisplayId).html(data);
		});
	}
}
//onclick="javascript:startFinalImport('."'".$rowUploadedFileList->id."'".','."'".$_SESSION[sessAdminUsername]."'".','."'".htmlspecialchars($rowUploadedFileList->table_name)."'".');"
//function startFinalImport(upLoadId,userName,tableName){
//alert(upLoadId);
//	var dispalyImportResult = "formMappingTable"+upLoadId;
//	alert(dispalyImportResult);
//	var csvColumn = document.getElementById(dispalyImportResult).elements["csvColumn[]"];
//	var tableColumn = document.getElementById(dispalyImportResult).elements["tableColumn[]"];
//	
//	$.post("processImport.php", {processImport:"processStartFinalImport",csvColumn:csvColumn,tableColumn:tableColumn,userName:userName,tableName:tableName},
//		function(data){   
//			document.getElementById(dispalyImportResult).innerHTML=data;
//			//document.getElementById(importTr).innerHTML="File imported sucessfully.";
//		});
//}
function validateTableUniqueKey(theform,maxSelect){
	var len = theform.elements.length;
	var err = 0;
	var countSelect = 0;
	<?php $sqlTableColumbList = @mysqli_query($connNew,"SHOW COLUMNS FROM `".addslashes($_REQUEST['tableName'])."`");
	$numColumbeTable = 	@mysqli_num_rows($sqlTableColumbList);
	$counterCol = 0;
	echo "var uniqueKeys = new Array();\n"; 
	$mendentoryFields = '';
	while($resColumbTable  = @mysqli_fetch_object($sqlTableColumbList)){
		if($resColumbTable->Key == 'UNI'){
			echo "uniqueKeys[" . $counterCol. "]=\"" .$resColumbTable->Field. "\";\n";
			$mendentoryFields .= $resColumbTable->Field.",";
			$counterCol++;
		}
	}?>
	var uniqueValid = false;
	var found = 0;
	for(var i=0; i < len; i++){ 
		if(theform.elements[i].type == "select-one"){ 
			if(theform.elements[i].value != ''){
				for(var j=0; j<uniqueKeys.length; j++){
					//alert(uniqueKeys.indexOf(theform.elements[i].value))
					if (uniqueKeys[j] == theform.elements[i].value) {
						found++;
						break;
					}
				}
				countSelect++;	
 			}
		}
  	}
	alert("found="+found+"uni="+uniqueKeys.length);
	if(uniqueKeys.length == found){
		uniqueValid = true;
	}
	if(countSelect == 0 ){
		alert('Please select mapped table column list.');
		err++;
	}else if(countSelect > maxSelect){
		alert('Selected mapped table column list must be less then or equals to '+maxSelect);
		err++;
	}else if(!uniqueValid){
		alert('You must select the Mendentory fields - <?php echo $mendentoryFields;?>');
		return false;
	}else{
		return true;
	}
	return false;
}
function hideMappingTableStructure(theform,maxSelect,mappingTableStru){
	if(validateTableUniqueKey(theform,maxSelect) == true){
	$("#"+mappingTableStru).slideUp();
		//$("#"+mappingTableStru).html("<FONT COLOR='RED' style='font-size:9px;margin-left:0px;'><img src='images/ajax-loader.gif'/>Importing File to database...Please be patience.</FONT>");
		return true;
	}else{
		return false;
	}
}
function dissable(formNam){
	if(document.uploadForm.uploadFile.value!=''){
		$("#"+uploadFormId).html("<FONT COLOR='RED' style='font-size:9px;margin-left:0px;'><img src='images/ajax-loader.gif'/>Uploading File...</FONT>");
		return true;
	}else{
		return false;
	}	
}
</script>
