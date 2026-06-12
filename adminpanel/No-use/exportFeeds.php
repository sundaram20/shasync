<?php session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");

function exportTable($dataBaseName ='', $tableName = '', $fileType = '',$link = ''){
	$err = 0;
	//dbConnection-------------------------------------
	if($link && is_resource($link)){
		$conn = $link;
	}else{
		$conn = @mysql_connect("localhost", "root", "");
	}
	@mysql_select_db($dataBaseName,$conn);
	//endDbConnection-------------------------------------
	if($dataBaseName != ''){
		if($tableName != ''){
			if($fileType != ''){
				//Export type---------------------------------------	
				if($fileType == 'csv'){
					$contentType = '"Content-Type: text/comma-separated-values"';
					$fileEnding = "csv";
				}elseif($fileType = 'xls'){
					$contentType = '"Content-Type: application/vnd.ms-excel"';
					$fileEnding = "xls";
				}elseif($fileType = 'doc'){
					$contentType = '"Content-Type: msword"';
					$fileEnding = "doc";
				}
				//End export type-----------------------------------
				$filename = tempnam(sys_get_temp_dir(), $fileType);
				$dataWrite = '';
				// Write column names
				//--------------------------------------------------
				$result = mysql_query("SELECT * FROM `$tableName`", $conn);
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysql_num_fields($result);
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){
					$dataWrite .= "<table border='1'><tr>";
					// printing table headers
					for($i=0; $i<$fields_num; $i++){
						$field = mysql_fetch_field($result);
						$dataWrite .= "<th>{$field->name}</th>";
					}
					$dataWrite .= "</tr>";
					// printing table rows
					while($row = mysql_fetch_row($result)){
						$dataWrite .= "<tr>";
						foreach($row as $cell)
							$dataWrite .= "<td>$cell</td>";
					
						$dataWrite .= "</tr>";
					}
				//-------------------------------------------------
				}elseif($fileType == 'csv'){
					for($i=0; $i<$fields_num; $i++){
						$field = mysql_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
					}
					while($row = mysql_fetch_row($result)){
						$counterCol = 0;
						foreach($row as $cell){
							$endOfLine = ($counterCol == ($fields_num-1))? true:false;
							if($counterCol == '2'){//merchant_name
								$sqlMerchantName = @mysql_query("SELECT `id` FROM `".TBL_FEED_LIST."` WHERE `merchant_name` = '".$cell."'");
								if(@mysql_num_rows($sqlMerchantName)>0){
									$resMerchantName = mysql_fetch_object($sqlMerchantName);
									$cell = $resMerchantName->id;
								}else{
									$cell = $cell;
								}
							}elseif($counterCol == '3'){//phone_name
								$sqlPhoneName = @mysql_query("SELECT `mobile_phone_identifier` FROM `".TBL_MOBILE_PHONES."` WHERE `mobile_phone_name` = '".$cell."'");
								if(@mysql_num_rows($sqlPhoneName)>0){
									$resPhoneName = mysql_fetch_object($sqlPhoneName);
									$cell = $resPhoneName->mobile_phone_identifier;
								}else{
									$cell = $cell;
								}
							}elseif($counterCol == '4'){//plan_name
								$sqlPlanName = @mysql_query("SELECT `plan_identifier` FROM `".TBL_PLANS."` WHERE `plan_name` = '".$cell."'");
								if(@mysql_num_rows($sqlPlanName)>0){
									$resPlanName = mysql_fetch_object($sqlPlanName);
									$cell = $resPlanName->plan_identifier;
								}else{
									$cell = $cell;
								}							
							}else{
							
							}
							$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
							$counterCol++;	
						}
					}
					
				}
				//
				$file = @fopen($filename,"w");
				fwrite($file,$dataWrite);
				fclose($file);
				$savedFileName = $tableName."_".date("Y-m-d_h-i-s_".rand(11111,99999));
				//header($contentType);
				header("Content-type: application/octet-stream"); 
				header("Content-Disposition: attachment;Filename=".$savedFileName.".".$fileEnding."");
				// send file to browser
				header("Pragma: no-cache");
				header("Expires: 0");
				readfile($filename);
				unlink($filename);
			}else{
				$err++;
				$message = "Invalid file type.";
			}
		}else{
			$err++;
			$message = "Invalid table name.";
		}
	}else{
		$err++;
		$message = "Invalid database name.";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());
adminLoginCheck();
$_REQUEST['fileType'] = 'csv';
$_REQUEST['tableName'] = TBL_MOBILE_DEALS;
checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST['tableName']){
	exportTable($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType'], $db->conn);
}else{
	echo "Invalid input.";
}?>