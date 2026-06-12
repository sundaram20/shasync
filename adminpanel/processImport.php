<?php include_once("../config/auto_loader.php");
$processImport = $_POST['processImport'];
//echo "<pre>";print_r($_POST);echo "</pre>";
if($_POST){
}else{
	header("location:".$_SERVER['HTTP_REFERER']);
}
switch($processImport){
	case "processDelimiters":
		if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '') &&($_POST['uploadedFileId'] != '')){	
			if(is_array($arrDelimiters)){
				$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
											'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
											'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
											'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
			}elseif($arrDelimiters == ''){
					$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
												'fieldsEnclosedBy' => '"',
												'fieldsEscapsedBy' => '"',
												'linesTerminatedBy' => 'auto'
												); 
			}
			//print_r($_POST);
			$sqlSelectFile = "	SELECT * FROM `".TBL_UPLOADED_FILES."` 
								WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
								AND `table_name` = '".addslashes($_POST['tableName'])."' 
								AND `username` = '".$_SESSION['userName']."' 
								AND `imported` = '0'";
			$db->query($sqlSelectFile);
			$numRowsFile = $db->num_rows();
			if($numRowsFile > 0){
				$rowUploadedFileList = $db->fetch_object();
				$fileNameExplode = explode(".",$rowUploadedFileList->file_name);
				if(file_exists("imported/".$rowUploadedFileList->file_name)){
					///////echo mime_content_type("imported/".$rowUploadedFileList->file_name);
					if($fileNameExplode[1] == 'csv'){
						//$file = fopen("imported/".$rowUploadedFileList->file_name,"r");
						/*if(($arrDelimiters['linesTerminatedBy'] == 'AUTO') || ($arrDelimiters['linesTerminatedBy'] == 'auto')){
							while(! feof($file)){
							  echo fgets($file). "<br />";
							}	
						}else{
							while(! feof($file)){
							  echo fgetc($file). "<br />";
							}
						}
						fclose($file);*/
					////////////////////////////////////////
					echo $setDelimiterHtml =
							'<td colspan="4" width="100%" align="center" class="admin_heading border04 main_borders" id="mappingTd'.$rowUploadedFileList->id.'">
							<form name="formDelimiter'.$rowUploadedFileList->id.'" id="formDelimiter'.$rowUploadedFileList->id.'">
								<input type="hidden" name="tableName" value="'.addslashes($rowUploadedFileList->table_name).'"/> 
								<input type="hidden" name="uploadedFileId" value="'.addslashes(trim($rowUploadedFileList->id)).'"/> 
								<input type="hidden" name="userName" value="'.$_SESSION['userName'].'"/> 
								<table width="60%" border="0" cellpadding="0" cellspacing="1"  align="center">
									<tr bgcolor="#CCCCCC">
										<td  width="100%" class="admin_heading" align="center" colspan="2">Please enter delimiters details to read CSV file:</td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading" align="right">Fields terminated by</td>
										<td align="left"><input style="width:40px;" type="text" class="admin_heading" name="fieldsTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsTerminatedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Fields enclosed by</td>
										<td align="left"><input style="width:40px;" type="text"class="admin_heading" name="fieldsEnclosedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEnclosedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Fields escapsed by</td>
										<td align="left"><input style="width:40px;" class="admin_heading" type="text" name="fieldsEscapsedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEscapsedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Lines terminated by</td>
										<td align="left"><input style="width:40px;" class="admin_heading" type="text" name="linesTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[linesTerminatedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right"></td>
										<td  align="left"><input onclick="subDelimiters('."'".$rowUploadedFileList->id."'".','."'".$_SESSION['userName']."'".','."'".$rowUploadedFileList->table_name."'".');" style="cursor:pointer;"  type="button" class="css_btns" name="submitDelimiters" value="Submit"></td>
									</tr>
								</table>	
							</form>				
							</td>';
					////////////////////////////////////////						
					}elseif($fileNameExplode[1] == 'xls'){
						echo '<td colspan="4" class="admin_heading border04 main_borders">.xls file not suppoted.</td>';
					}
				}else{
					echo '<td colspan="4" class="admin_heading border04 main_borders">No such file found.</td>';
				}			
			}else{
				echo '<td colspan="4" class="admin_heading border04 main_borders">Error 1 in upload table file .</td>';
			}		
		}else{
			echo '<td colspan="4" class="admin_heading border04 main_borders">Error in process variables file.</td>';
		}
	break;
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case "processMappingTableStructure" :
		//print_r($_POST);
		if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '')){	
			$_POST['linesTerminatedBy'] = ($_POST['linesTerminatedBy'] == 'auto') ? chr(13) : $_POST['linesTerminatedBy'];
			$arrDelimiters = array(	'fieldsTerminatedBy' => stripslashes($_POST['fieldsTerminatedBy']),
									'fieldsEnclosedBy' => stripslashes($_POST['fieldsEnclosedBy']),
									'fieldsEscapsedBy' => stripslashes($_POST['fieldsEscapsedBy']),
									'linesTerminatedBy' => stripslashes($_POST['linesTerminatedBy']));
			if(is_array($arrDelimiters)){
				$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
											'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
											'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
											'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
			}elseif($arrDelimiters == ''){
					$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
												'fieldsEnclosedBy' => '"',
												'fieldsEscapsedBy' => '"',
												'linesTerminatedBy' => chr(13)); 
			}
			//print_r($_POST);
			$sqlSelectFile = "	SELECT * FROM `".TBL_UPLOADED_FILES."` 
								WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
								AND `table_name` = '".addslashes($_POST['tableName'])."' 
								AND `username` = '".$_SESSION['userName']."' 
								AND `imported` = '0'";
			$db->query($sqlSelectFile);
			$numRowsFile = $db->num_rows();
			if($numRowsFile > 0){
				$rowUploadedFileList = $db->fetch_object();
				$fileNameExplode = @explode(".",$rowUploadedFileList->file_name);
				if(@file_exists("imported/".$rowUploadedFileList->file_name)){
					//echo mime_content_type("imported/".$rowUploadedFileList->file_name);
					if($fileNameExplode[1] == 'csv'){
						$sqlTableColumbList = @mysqli_query($connNew,"SHOW COLUMNS 
															FROM `".$rowUploadedFileList->table_name."`");
						$numColumbsTable = @mysqli_num_rows($sqlTableColumbList);
						$mappingTableStructure = '';
						if($numColumbsTable > 0){					
							////////////////////////////////////////
							$mappingTableStructure .=
								'<table widht="100%" align="center"><tr><td colspan="4" width="100%" align="center" class="admin_heading border04 main_borders" id="mappingTableStructureTd'.$rowUploadedFileList->id.'">
								<form name="formMappingTable'.$rowUploadedFileList->id.'" method="post" action="processImport.php"  id="formMappingTable'.$rowUploadedFileList->id.'">
									<input type="hidden" name="tableName" value="'.addslashes($rowUploadedFileList->table_name).'"/> 
									<input type="hidden" name="uploadedFileId" value="'.addslashes(trim($rowUploadedFileList->id)).'"/> 
									<input type="hidden" name="processImport" value="processStartFinalImport"/>
									<input type="hidden" name="userName" value="'.$_SESSION['userName'].'"/> 
									<input type="hidden" name="fieldsTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsTerminatedBy]).'"/>
									<input type="hidden" name="fieldsEnclosedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEnclosedBy]).'"/>
									<input type="hidden" name="fieldsEscapsedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEscapsedBy]).'"/>
									<input type="hidden" name="linesTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[linesTerminatedBy]).'"/>
									
									<table width="100%" border="1" cellpadding="0" cellspacing="1"  align="center" style="border-collapse:collapse;">
										<tr bgcolor="#CCCCCC">
											<td  width="100%" class="admin_heading" align="center" colspan="3">Mapping CSV and Database Table '.trim($rowUploadedFileList->table_name).':</td>
										</tr>
										<tr>
											<td width="30%" class="admin_heading" align="center">CSV File Fields</td>
											<td align="center"  class="admin_heading" width="30%">Table '.trim($rowUploadedFileList->table_name).' columns</td>
											<td width="40%" align="center"  class="admin_heading">CSV Second Row</td>
										</tr>';
								////////////////////////
								@ini_set('auto_detect_line_endings',TRUE);
								$row = 0;
								$filePointerCsv = fopen("imported/".$rowUploadedFileList->file_name,"r");
								$arrCsvFields = array();
								if(($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) {
									if ($row == 0) {
										// this is the first line of the csv file
										// it usually contains titles of columns
										$num = count($fileContentForCsv);
										$row++;
										for ($c=0; $c < $num; $c++) {
											array_push($arrCsvFields,$fileContentForCsv[$c]);
										}
									}
								}
								/////////////////////////
								$counterRow = 0;
								$arrCsvRow1 = array();
								while (($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) {
									if ($counterRow == 1) {
										$num = count($fileContentForCsv);
										for ($c=0; $c < $num; $c++) {
											array_push($arrCsvRow1,$fileContentForCsv[$c]);
										}
									}
									$counterRow++;
									if($counterRow == 2) break;
								}
								
								////////////////////////
								
								fclose($filePointerCsv);
								if(count($arrCsvFields) > 0){
									$countCsvFieldLoop = 1;
									$countCsvFieldLoop_new = 0;
									foreach($arrCsvFields as $csvFields){
										$mappingTableStructure .=		
												'<tr>
													<td  class="admin_heading"  align="center"><div style="float:left;padding-left:5px;">'.$countCsvFieldLoop.'.</div><input type="text" readonly  name ="csvColumn[]" value="'.$csvFields.'"></td>
													<td  align="center"><select name="tableColumn[]">'.listColumb($countCsvFieldLoop, $rowUploadedFileList->table_name).'</select></td>
													<td align="left"  valign="top" style="padding-left:10px;">'.$arrCsvRow1[$countCsvFieldLoop_new].'</td>
												</tr>';
										$countCsvFieldLoop_new++;		
										$countCsvFieldLoop++;		
									}
								}		
								$totalCol = --$numColumbsTable;
								$mappingTableStructure .=		
												'<tr>
													<td width="100%" colspan="3" style="color:red;"  align="center">Please select only '.$totalCol.' columns.</td>
												</tr>';	
								$mappingTableStructure .=		
									'<tr>
										<td width="100%" colspan="3" align="center"><input onclick="return hideMappingTableStructure(this.form,\''.$totalCol .'\',\'formMappingTable'.$rowUploadedFileList->id.'\');" type="submit"  name="import" value="Start Importing"></td>
									</tr>';		
								$mappingTableStructure .=			
									'</table>	
								</form>				
								</td>';
							}else{
								$mappingTableStructure .= '<td colspan="4" width="100%" align="center" class="admin_heading border04 main_borders" id="mappingTd'.$rowUploadedFileList->id.'"></td>';
							}
							echo $mappingTableStructure;
					////////////////////////////////////////						
					}elseif($fileNameExplode[1] == 'xls'){
						echo '<td class="admin_heading border04 main_borders">.xls file not suppoted.</td>';
					}
				}else{
					echo '<td class="admin_heading border04 main_borders">No such file found.</td>';
				}			
			}else{
				echo '<td class="admin_heading border04 main_borders">Error in upload table file.</td>';
			}		
		}else{
			echo '<td class="admin_heading border04 main_borders">Error in process variables file.</td>';
		}
		echo '</tr></table>';
	break;	
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	case "processStartFinalImport" :
		$err = 0;
		 '<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.0 Transitional//EN">
				<html>
						<BODY >
						<center><img src="images/preloader.gif"/></center>
						<center><h5> Do not refresh page or press back button.</h5> </center>';
						if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '')){	
							$_POST['linesTerminatedBy'] = ($_POST['linesTerminatedBy'] == 'auto') ? chr(13) : $_POST['linesTerminatedBy'];
							$arrDelimiters = array(	'fieldsTerminatedBy' => stripslashes($_POST['fieldsTerminatedBy']),
													'fieldsEnclosedBy' => stripslashes($_POST['fieldsEnclosedBy']),
													'fieldsEscapsedBy' => stripslashes($_POST['fieldsEscapsedBy']),
													'linesTerminatedBy' => stripslashes($_POST['linesTerminatedBy']));
							if(is_array($arrDelimiters)){
								$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
															'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
															'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
															'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
							}elseif($arrDelimiters == ''){
									$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
																'fieldsEnclosedBy' => '"',
																'fieldsEscapsedBy' => '"',
																'linesTerminatedBy' => chr(13)); 
							}
							$sqlSelectFile = "	SELECT * FROM `".TBL_UPLOADED_FILES."` 
												WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
												AND `table_name` = '".addslashes($_POST['tableName'])."' 
												AND `username` = '".$_SESSION['userName']."' 
												AND `imported` = '0'";
							$db->query($sqlSelectFile);
							$numRowsFile = $db->num_rows();
							if($numRowsFile > 0){
								$rowUploadedFileList = $db->fetch_object();
								$fileNameExplode = @explode(".",$rowUploadedFileList->file_name);
								if(@file_exists("imported/".$rowUploadedFileList->file_name)){
										//echo "<pre>";print_r($_POST);echo "</pre>";
								}else{
									$err++;
									echo '<td class="admin_heading border04 main_borders">No such file found.</td>';
								}			
							}else{
								$err++;
								echo '<td class="admin_heading border04 main_borders">Error in upload table file.</td>';
							}		
						}else{
							$err++;
							echo '<td class="admin_heading border04 main_borders">Error in process variables file.</td>';
						}
						if($err == 0){
							$row = 0;
							ini_set('auto_detect_line_endings',TRUE);
							$filePointerCsv = fopen("imported/".$rowUploadedFileList->file_name,"r");
							$countPostTableCol = count($_POST[tableColumn]);
							$arrayInsertCol = array();
							$counterI = 0;
							foreach($_POST['tableColumn'] as $valTabColumn){
								if($valTabColumn !== ''){
									@array_push($arrayInsertCol,$valTabColumn);
								}
								$counterI++;	
							}	
								
							$insertTableStruHtml = '<table width="100%" border="1"><tr>';
							$dataWrite = '';
							//-----------------Start reading file line by line------------------------------//
							while (($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) 				 								{
									$sqlInsertRow = "INSERT INTO `".addslashes($_POST['tableName'])."` ";
									$arrayInsertVal = array();
									$insertTableStruHtml .= '<tr>';
										if ($row == 0) {//
											// this is the first line of the csv file
											// it usually contains titles of columns
											$num = count($fileContentForCsv);
											$counterI = 0;
											foreach($_POST['tableColumn'] as $valTabColumn){
												if($valTabColumn !== ''){
													@array_push($arrayInsertVal,$fileContentForCsv[$counterI]);
													$insertTableStruHtml .= "<th>" . $fileContentForCsv[$counterI] . "</th>";
												}
												$counterI++;	
											}
											$counterCol = 0;
											foreach($fileContentForCsv as $cell){
												$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
												$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
												$counterCol++;	
											}
											$row++;
										}else{
											// this handles the rest of the lines of the csv file
											$num = count($fileContentForCsv);
											$counterI = 0;
											foreach($_POST['tableColumn'] as $valTabColumn){
												if($valTabColumn !== ''){
													@array_push($arrayInsertVal,$fileContentForCsv[$counterI]);
													$insertTableStruHtml .= "<td>" . $fileContentForCsv[$counterI] . "</td>";
												}
												$counterI++;	
											}
											$row++;
											////////////////////////////////////////////////making query
											$arrayInsertCol = array_unique($arrayInsertCol);
											if(count($arrayInsertCol) > 0){
												$sqlInsertRow .= "( ";//
												$counterI = 0;
												foreach($arrayInsertCol as $colIns){
													if(preg_match("/^[a-z]+_names$/",$colIns) && ($colIns != 'mapping_names')){
														$specificCounter = $counterI;
													}
													$sqlInsertRow .= "`".addslashes($colIns)."`";
													if($counterI < count($arrayInsertCol)-1){
														$sqlInsertRow .= ", ";
													}
													$counterI++;	
												}
												$sqlInsertRow .= " )";
											}
											//$arrayInsertVal = array_unique($arrayInsertVal);
											if(count($arrayInsertVal) > 0){
												$sqlInsertRow .= " VALUES ( ";//
												$counterI = 0;
												foreach($arrayInsertVal as $valIns){
													$sqlInsertRow .= "'".addslashes($valIns)."'";
													if($counterI < count($arrayInsertVal)-1){
														$sqlInsertRow .= ", ";
													}
													if($specificCounter == $counterI){
														$isDataQuery = mappingData(addslashes($_POST['tableName']),'', $valIns);
													}
													$counterI++;	
												}
												$sqlInsertRow .= " )";
												
											}
											$insertTableStruHtml .= '</tr>';
											//insert rows process/////
											if($isDataQuery == 0){
												if(@mysqli_query($connNew,$sqlInsertRow)){
													//echo "$row--Inserted --->>".$sqlInsertRow."<br>";
												}else{
													$counterCol = 0;
													foreach($fileContentForCsv as $cell){
														$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
														$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
														$counterCol++;	
													}
													//echo "$row--Error in insertion --->>".$sqlInsertRow."<br>".mysql_error()."<br>";
												}
											}else{///write to csv
												$counterCol = 0;
													foreach($fileContentForCsv as $cell){
														$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
														$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
														$counterCol++;	
													}
												//echo "$row--Exists in mapping column ,Not inserted --->>".$sqlInsertRow."<br>";
											}											
										}
										//echo "<pre>".$dataWrite."</pre>";
										////////////////////////////////////////////////ending query
									}
									$fileEnding = "csv";
									$fileType = "csv";
									$filename = tempnam(sys_get_temp_dir(), $fileType);
									$file = @fopen($filename,"w");
									@fwrite($file,$dataWrite);
									@fclose($file);
									$savedFileName = $_POST['tableName']."_".date("Y-m-d_h-i-s_".rand(11111,99999));
									header($contentType);
									header("Content-type: application/octet-stream"); 
									header("Content-Disposition: attachment;Filename=".$savedFileName.".".$fileEnding."");
									//send file to browser
									header("Pragma: no-cache");
									header("Expires: 0");
									readfile($filename);
									unlink($filename);
							//-----------------end reading file line by line------------------------------//		
									//echo $insertTableStruHtml .= '</tr></table>';
									//echo $insertTableStruHtml;
									/*
									$rs1 = mysqli_query($connNew,"START TRANSACTION");
									$rs2 = mysqli_query($connNew,$query1);
									$rs3 = mysqli_query($connNew,$query2);
									if($error)
									{ $rsx = mysqli_query($connNew,"ROLLBACK"); }
									else
									{ $rsx = mysqli_query($connNew,"COMMIT"); }
									*/
							}
					'</BODY>
				</HTML>';
	break;
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////For feed/////////////////////////////////////////////////////////////
	case "processFeedDelimiters":
		if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '') ){	
			//echo "you are in ".$_POST['uploadedFileId'].$_POST['userName'].$_POST['tableName'];
			//exit;
			if(is_array($arrDelimiters)){
				$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
											'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
											'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
											'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
			}elseif($arrDelimiters == ''){
					$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
												'fieldsEnclosedBy' => '"',
												'fieldsEscapsedBy' => '"',
												'linesTerminatedBy' => 'auto'); 
			}
			//print_r($_POST);
			//exit;
			$sqlSelectFile = "	SELECT * FROM `".TBL_CLIENT."` 
								WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
								AND `username` = '".$_SESSION['userName']."' 
								AND `status` = '0'";
			$db->query($sqlSelectFile);
			$numRowsFile = $db->num_rows();
			if($numRowsFile > 0){
				$rowUploadedFileList = $db->fetch_object();
				$fileNameExplode = explode(".",$rowUploadedFileList->file_name);
				if(file_exists("feedFileList/".$rowUploadedFileList->file_name)){
					//echo mime_content_type("feedFileList/".$rowUploadedFileList->file_name);
					if($fileNameExplode[1] == 'csv'){
						//$file = fopen("feedFileList/".$rowUploadedFileList->file_name,"r");
						/*if(($arrDelimiters['linesTerminatedBy'] == 'AUTO') || ($arrDelimiters['linesTerminatedBy'] == 'auto')){
							while(! feof($file)){
							  echo fgets($file). "<br />";
							}	
						}else{
							while(! feof($file)){
							  echo fgetc($file). "<br />";
							}
						}
						fclose($file);*/
					////////////////////////////////////////
					echo $setDelimiterHtml =
							'<td colspan="5" width="100%" align="center" class="admin_heading border04 main_borders" id="mappingTd'.$rowUploadedFileList->id.'">
							<form name="formDelimiter'.$rowUploadedFileList->id.'" id="formDelimiter'.$rowUploadedFileList->id.'">
								<input type="hidden" name="tableName" value="'.TBL_CLIENT.'"/> 
								<input type="hidden" name="uploadedFileId" value="'.addslashes(trim($rowUploadedFileList->id)).'"/> 
								<input type="hidden" name="userName" value="'.$_SESSION['userName'].'"/> 
								<table width="60%" border="0" cellpadding="0" cellspacing="1"  align="center">
									<tr bgcolor="#CCCCCC">
										<td  width="100%" class="admin_heading" align="center" colspan="2">Please enter delimiters details to read CSV file:</td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading" align="right">Fields terminated by</td>
										<td align="left"><input style="width:40px;" type="text" class="admin_heading" name="fieldsTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsTerminatedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Fields enclosed by</td>
										<td align="left"><input style="width:40px;" type="text"class="admin_heading" name="fieldsEnclosedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEnclosedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Fields escapsed by</td>
										<td align="left"><input style="width:40px;" class="admin_heading" type="text" name="fieldsEscapsedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEscapsedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right">Lines terminated by</td>
										<td align="left"><input style="width:40px;" class="admin_heading" type="text" name="linesTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[linesTerminatedBy]).'"></td>
									</tr>
									<tr>
										<td width="50%" class="admin_heading"  align="right"></td>
										<td  align="left"><input onclick="subDelimiters('."'".$rowUploadedFileList->id."'".','."'".$_SESSION['userName']."'".','."'".TBL_CLIENT."'".');" style="cursor:pointer;"  type="button" class="css_btns" name="submitDelimiters" value="Submit"></td>
									</tr>
								</table>	
							</form>				
							</td>';
					////////////////////////////////////////						
					}elseif($fileNameExplode[1] == 'xls'){
						echo '<td colspan="5" class="admin_heading border04 main_borders">.xls file not suppoted.</td>';
					}
				}else{
					echo '<td colspan="5" class="admin_heading border04 main_borders">No such file found.</td>';
				}			
			}else{
				echo '<td colspan="5" class="admin_heading border04 main_borders">Error in upload table file.</td>';
			}		
		}else{
			echo '<td colspan="5" class="admin_heading border04 main_borders">Error in process variables file.</td>';
		}
	break;
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	case "processFeedMappingTableStructure" :
		//print_r($_POST);
		if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '')){	
			$_POST['linesTerminatedBy'] = ($_POST['linesTerminatedBy'] == 'auto') ? chr(13) : $_POST['linesTerminatedBy'];
			$arrDelimiters = array(	'fieldsTerminatedBy' => stripslashes($_POST['fieldsTerminatedBy']),
									'fieldsEnclosedBy' => stripslashes($_POST['fieldsEnclosedBy']),
									'fieldsEscapsedBy' => stripslashes($_POST['fieldsEscapsedBy']),
									'linesTerminatedBy' => stripslashes($_POST['linesTerminatedBy']));
			if(is_array($arrDelimiters)){
				$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
											'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
											'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
											'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
			}elseif($arrDelimiters == ''){
					$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
												'fieldsEnclosedBy' => '"',
												'fieldsEscapsedBy' => '"',
												'linesTerminatedBy' => chr(13)); 
			}
			//print_r($_POST);
			$sqlSelectFile = "	SELECT * FROM `".TBL_CLIENT."` 
								WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
								AND `username` = '".$_SESSION['userName']."' 
								AND `status` = '0'";
			$db->query($sqlSelectFile);
			$numRowsFile = $db->num_rows();
			if($numRowsFile > 0){
				$rowUploadedFileList = $db->fetch_object();
				$fileNameExplode = @explode(".",$rowUploadedFileList->file_name);
				if(@file_exists("feedFileList/".$rowUploadedFileList->file_name)){
					//echo mime_content_type("feedFileList/".$rowUploadedFileList->file_name);
					if($fileNameExplode[1] == 'csv'){
						$sqlTableColumbList = @mysqli_query($connNew,"SHOW COLUMNS 
															FROM `".TBL_MOBILE_DEALS."`");
						$numColumbsTable = 	@mysqli_num_rows($sqlTableColumbList);
						$mappingTableStructure = '';
						if($numColumbsTable > 0){					
							////////////////////////////////////////
							$mappingTableStructure .=
								'<table width="800"  align="center"><tr><td colspan="4" width="100%" align="center" id="mappingTableStructureTd'.$rowUploadedFileList->id.'">
								<form name="formMappingTable'.$rowUploadedFileList->id.'" method="post" action="processImport.php"  id="formMappingTable'.$rowUploadedFileList->id.'">
									<input type="hidden" name="tableName" value="'.addslashes(TBL_CLIENT).'"/> 
									<input type="hidden" name="uploadedFileId" value="'.addslashes(trim($rowUploadedFileList->id)).'"/> 
									<input type="hidden" name="processImport" value="processFeedStartFinalImport"/>
									<input type="hidden" name="userName" value="'.$_SESSION['userName'].'"/> 
									<input type="hidden" name="fieldsTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsTerminatedBy]).'"/>
									<input type="hidden" name="fieldsEnclosedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEnclosedBy]).'"/>
									<input type="hidden" name="fieldsEscapsedBy" value="'.htmlspecialchars($arrayDelimiters[fieldsEscapsedBy]).'"/>
									<input type="hidden" name="linesTerminatedBy" value="'.htmlspecialchars($arrayDelimiters[linesTerminatedBy]).'"/>
									
									<table width="800" border="1" cellpadding="0" cellspacing="1"  align="center" style="border-collapse:collapse;">
										<tr bgcolor="#CCCCCC">
											<td  width="100%" class="admin_heading" align="center" colspan="3">Mapping CSV and Database Table '.trim(TBL_MOBILE_DEALS).':</td>
										</tr>
										<tr>
											<td width="30%" class="admin_heading" align="center">CSV File Fields</td>
											<td width="30%" align="center"  class="admin_heading">Table '.trim(TBL_CLIENT).' columns</td>
											<td width="40%" align="center"  class="admin_heading">CSV Second Row</td>
										</tr>';
								////////////////////////
								@ini_set('auto_detect_line_endings',TRUE);
								$row = 0;
								$filePointerCsv = fopen("feedFileList/".$rowUploadedFileList->file_name,"r");
								$arrCsvFields = array();
								if(($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) {
									if ($row == 0) {
										$num = count($fileContentForCsv);
										$row++;
										for ($c=0; $c < $num; $c++) {
											array_push($arrCsvFields,$fileContentForCsv[$c]);
										}
									}
								}
								/////////////////////////
								$counterRow = 0;
								$arrCsvRow1 = array();
								while (($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) {
									if ($counterRow == 0) {
										$num = count($fileContentForCsv);
										for ($c=0; $c < $num; $c++) {
											array_push($arrCsvRow1,$fileContentForCsv[$c]);
										}
									}
									$counterRow++;
									if($counterRow == 2) break;
								}
								//echo '<pre>';print_r($arrCsvRow1);echo '</pre>';
								////////////////////////
								
								fclose($filePointerCsv);
								if(count($arrCsvFields) > 0){
									$countCsvFieldLoop = 1;
									$countCsvFieldLoop_new = 0;
									foreach($arrCsvFields as $csvFields){
										$mappingTableStructure .=		
												'<tr>
													<td  class="admin_heading"  align="center" valign="top"><span style="float:left;padding-left:5px;">'.$countCsvFieldLoop.'.</span><input type="text" readonly  name ="csvColumn[]" value="'.$csvFields.'"></td>
													<td align="center"  valign="top"><select name="tableColumn[]">'.listColumbFeed($countCsvFieldLoop, TBL_CLIENT).'</select></td>
													<td align="left"  valign="top">'.$arrCsvRow1[$countCsvFieldLoop_new].'</td>
												</tr>';
										$countCsvFieldLoop_new++;		
										$countCsvFieldLoop++;		
									}
								}		
								$totalCol = --$numColumbsTable;
								//echo "totalCol===".$totalCol;
								$mappingTableStructure .=		
												'<tr>
													<td width="100%" colspan="3" style="color:red;"  align="center">Please select only '.$totalCol.' columns.</td>
												</tr>';	
								$mappingTableStructure .=		
									'<tr>
										<td width="100%" colspan="3" align="center"><input onclick="return hideMappingTableStructure(this.form,\''.$totalCol .'\',\'formMappingTable'.$rowUploadedFileList->id.'\');" type="submit"  name="import" value="Start Importing"></td>
									</tr>';		
								$mappingTableStructure .=			
									'</table>	
								</form>				
								</td>';
							}else{
								$mappingTableStructure .= '<td colspan="4" width="100%" align="center" class="admin_heading border04 main_borders" id="mappingTd'.$rowUploadedFileList->id.'"></td>';
							}
							echo $mappingTableStructure;
					////////////////////////////////////////						
					}elseif($fileNameExplode[1] == 'xls'){
						echo '<td class="admin_heading border04 main_borders">.xls file not suppoted.</td>';
					}
				}else{
					echo '<td class="admin_heading border04 main_borders">No such file found.</td>';
				}			
			}else{
				echo '<td class="admin_heading border04 main_borders">Error in upload table file.</td>';
			}		
		}else{
			echo '<td class="admin_heading border04 main_borders">Error in process variables file.</td>';
		}
		echo '</tr>
		<tr id="formMappingTable'.$rowUploadedFileList->id.'DownTr"   style="display:none;"><td colspan="4" width="100%" align="center"><FONT COLOR="RED" style="font-size:9px;margin-left:0px;"><img src="images/ajax-loader.gif"/>Uploading File... Please be patience!</FONT></td></tr>
		
		
		</table>';
	break;	
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	case "processFeedStartFinalImport" :
		$err = 0;
		'<!DOCTYPE HTML PUBLIC -//W3C//DTD HTML 4.0 Transitional//EN">
				<html>
						<BODY >
						<center><img src="images/preloader.gif"/></center>
						<center><h5> Do not refresh page or press back button.</h5> </center>';
						if(($_POST['uploadedFileId'] != '') && ($_POST['userName'] != '') &&($_POST['tableName'] != '')){	
							$_POST['linesTerminatedBy'] = ($_POST['linesTerminatedBy'] == 'auto') ? chr(13) : $_POST['linesTerminatedBy'];
							$arrDelimiters = array(	'fieldsTerminatedBy' => stripslashes($_POST['fieldsTerminatedBy']),
													'fieldsEnclosedBy' => stripslashes($_POST['fieldsEnclosedBy']),
													'fieldsEscapsedBy' => stripslashes($_POST['fieldsEscapsedBy']),
													'linesTerminatedBy' => stripslashes($_POST['linesTerminatedBy']));
							if(is_array($arrDelimiters)){
								$arrayDelimiters = array(	'fieldsTerminatedBy' => $arrDelimiters['fieldsTerminatedBy'],
															'fieldsEnclosedBy' => $arrDelimiters['fieldsEnclosedBy'],
															'fieldsEscapsedBy' => $arrDelimiters['fieldsEscapsedBy'],
															'linesTerminatedBy' => $arrDelimiters['linesTerminatedBy']);
							}elseif($arrDelimiters == ''){
									$arrayDelimiters = array(	'fieldsTerminatedBy' => ',',
																'fieldsEnclosedBy' => '"',
																'fieldsEscapsedBy' => '"',
																'linesTerminatedBy' => chr(13)); 
							}
							$sqlSelectFile = "	SELECT * FROM `".TBL_CLIENT."` 
												WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
												AND `username` = '".$_SESSION['userName']."' 
												AND `status` = '0'";
							$db->query($sqlSelectFile);
							$numRowsFile = $db->num_rows();
							if($numRowsFile > 0){
								$rowUploadedFileList = $db->fetch_object();
								$fileNameExplode = @explode(".",$rowUploadedFileList->file_name);
								if(@file_exists("feedFileList/".$rowUploadedFileList->file_name)){
										//echo "<pre>";print_r($_POST);echo "</pre>";
										//exit;
								}else{
									$err++;
									echo '<td class="admin_heading border04 main_borders">No such file found.</td>';
								}			
							}else{
								$err++;
								echo '<td class="admin_heading border04 main_borders">Error in upload table file.</td>';
							}		
						}else{
							$err++;
							echo '<td class="admin_heading border04 main_borders">Error in process variables file.</td>';
						}
						if($err == 0){
							$row = 0;
							ini_set('auto_detect_line_endings',TRUE);
							$filePointerCsv = fopen("feedFileList/".$rowUploadedFileList->file_name,"r");
							$countPostTableCol = count($_POST[tableColumn]);
							$arrayInsertCol = array();
							$counterI = 0;
							//@array_push($arrayInsertCol,'feed_list_id');
							foreach($_POST['tableColumn'] as $valTabColumn){
								if($valTabColumn !== ''){
									@array_push($arrayInsertCol,$valTabColumn);
								}
								$counterI++;	
							}	
								
							$insertTableStruHtml = '<table width="100%" border="1"><tr>';
							$dataWrite = '';
							//-----------------Start reading file line by line------------------------------//
							while (($fileContentForCsv = fgetcsv($filePointerCsv,0,$arrayDelimiters['fieldsTerminatedBy'],$arrayDelimiters['fieldsEnclosedBy'])) !== FALSE) 				 								{
									$sqlInsertRow = "INSERT INTO `".addslashes($_POST['tableName'])."` ";
									$arrayInsertVal = array();
									$insertTableStruHtml .= '<tr>';
									if ($row == 0) {//
									
											// this is the first line of the csv file // it usually contains titles of columns,
											$num = count($fileContentForCsv);
											$counterI = 0;
											//@array_push($arrayInsertVal,$rowUploadedFileList->id);
											foreach($_POST['tableColumn'] as $valTabColumn){
												if($valTabColumn !== ''){
													@array_push($arrayInsertVal,$fileContentForCsv[$counterI]);
													$insertTableStruHtml .= "<th>" . $fileContentForCsv[$counterI] . "</th>";
												}
												$counterI++;	
											}
											$counterCol = 0;
											foreach($fileContentForCsv as $cell){
												$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
												$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
												$counterCol++;	
											}
											$row++;
											//echo "dataWrite1-------".$dataWrite;
											//exit();
										}else{
											// this handles the rest of the lines of the csv file
											//@array_push($arrayInsertVal,$rowUploadedFileList->id);
											$num = count($fileContentForCsv);
											$counterI = 0;
											foreach($_POST['tableColumn'] as $valTabColumn){
												if($valTabColumn !== ''){
													@array_push($arrayInsertVal,$fileContentForCsv[$counterI]);
													$insertTableStruHtml .= "<td>" . $fileContentForCsv[$counterI] . "</td>";
												}
												$counterI++;	
											}
											$row++;
											////////////////////////////////////////////////making query
											$arrayInsertCol = array_unique($arrayInsertCol);
											if(count($arrayInsertCol) > 0){
												$sqlInsertRow .= "( ";//
												$counterI = 0;
												foreach($arrayInsertCol as $colIns){
													if(preg_match("/^[a-z]+_names$/",$colIns) && ($colIns != 'mapping_names')){
														$specificCounter = $counterI;
													}
													$sqlInsertRow .= "`".addslashes($colIns)."`";
													if($counterI < count($arrayInsertCol)-1){
														$sqlInsertRow .= ", ";
													}
													$counterI++;	
												}
												$sqlInsertRow .= " )";
											}
											//(Dont remove comment for this-->>>)$arrayInsertVal = array_unique($arrayInsertVal);
											if(count($arrayInsertVal) > 0){
												$sqlInsertRow .= " VALUES ( ";//
												$counterI = 0;
												foreach($arrayInsertVal as $valIns){
													$sqlInsertRow .= "'".addslashes($valIns)."'";
													if($counterI < count($arrayInsertVal)-1){
														$sqlInsertRow .= ", ";
													}
													if($specificCounter == $counterI){
														$isDataQuery = mappingData(addslashes($_POST['tableName']),'', $valIns);
													}
													$counterI++;	
												}
												$sqlInsertRow .= " )";
												
											}
											$insertTableStruHtml .= '</tr>';
											//insert rows process/////
											if($isDataQuery == 0){
												if(@mysqli_query($connNew,$sqlInsertRow)){
													//////////echo "$row--Inserted --->>".$sqlInsertRow."<br>";
													//echo "$row--Inserted --->>".$sqlInsertRow."<br>";
												}else{
													$counterCol = 0;
													foreach($fileContentForCsv as $cell){
														$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
														$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
														$counterCol++;	
													}
													////////echo "$row--Error in insertion --->>".$sqlInsertRow."<br>".mysql_error()."<br>";
													//echo "$row--Error in insertion --->>".$sqlInsertRow."<br>".mysql_error()."<br>";
												}
											}else{///write to csv
												$counterCol = 0;
													foreach($fileContentForCsv as $cell){
														$endOfLine = ($counterCol == count($fileContentForCsv)-1)? true:false;
														$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
														$counterCol++;	
													}
												//echo "$row--Exists in mapping column ,Not inserted --->>".$sqlInsertRow."<br>";
												//echo "$row--Exists in mapping column ,Not inserted --->>".$sqlInsertRow."<br>";
											}											
										}
										
										////////////////////////////////////////////////ending query
								}//echo "<pre>".$dataWrite."</pre>";
								 $insertTableStruHtml .= "</table>";
								//exit;
							$sqlUpdateFeesListTable = 	"UPDATE `".TBL_CLIENT."` 
														SET `status` = '1' 
														WHERE `id` = '".addslashes($_POST['uploadedFileId'])."' 
														AND `username` = '".$_SESSION['userName']."' 
														AND `status` = '0'";
								if(mysqli_query($connNew,$sqlUpdateFeesListTable)){	
									$_SESSION['successMsg'] = "Feeds has been sucessfully imported to feed table";							
									header("location:manageFeeds.php");
								}else{
									$_SESSION['errorMsg'] = "Unable to import feed. please Try again!!!";		
									header("location:manageFeeds.php");
								}									
							}
					'</BODY>
				</HTML>';
	break;
	////////////////////////////////////////////////////////////////////////////////////////////////////////
	
}
//////////////////////////////////////////////////////
function listColumb($selectedColumb = '', $tablename){
	$sqlTableColumbList = @mysqli_query($connNew,"SHOW COLUMNS 
										FROM `".$tablename."`");
	$numColumbeTable = 	@mysqli_num_rows($sqlTableColumbList);
	$options = '<option value="">Select Column</option>';
	$counterCol = 1;
	while($resColumbTable  = @mysqli_fetch_object($sqlTableColumbList)){
		if($resColumbTable->Key != 'PRI'){
			if($selectedColumb == $counterCol){
				$selected = 'selected = "selected" style="background-color:#999999"';
			}else{
				$selected = '';
			}
			$options .= '<option '.$selected.' value='.$resColumbTable->Field.'>'.$resColumbTable->Field.'</option>';
		}
		$counterCol++;
	}
	return	$options;
}
/////////////////////////////////////////////////////
function listColumbFeed($selectedColumb = '', $tablename){
	$sqlTableColumbList = @mysqli_query($connNew,"SHOW COLUMNS 
										FROM `".$tablename."`");
	$numColumbeTable = 	@mysqli_num_rows($sqlTableColumbList);
	
	$counterCol = 0;
	while($resColumbTable  = @mysqli_fetch_object($sqlTableColumbList)){
		if(($resColumbTable->Key != 'PRI') && ($resColumbTable->Field != 'feed_list_id')){
			if($selectedColumb == $counterCol){
				$selected = 'selected = "selected" style="background-color:#999999"';
			}else{
				$selected = '';
			}
			$options .= '<option '.$selected.' value='.$resColumbTable->Field.'>'.$resColumbTable->Field.'</option>';
		}
		$counterCol++;
	}
	return	$options;
}
/////////////////////////////////////////////////////
function mappingData($tablename, $mappingColumn = '', $mappingVal = ''){
	if($mappingColumn == ''){
		$mappingColumn = 'mapping_names';
	}else{
		$mappingColumn = $mappingColumn;
	}
	if($tablename != '' && $mappingVal != ''){
		$selectUniSearch = "SELECT * FROM  `".addslashes($tablename)."` WHERE 1 AND FIND_IN_SET('".$mappingVal."',`".$mappingColumn."`)";
		$resUniSearch = @mysqli_query($connNew,$selectUniSearch);
		return @mysqli_num_rows($resUniSearch);
	}else{
		return 0;
	}	
}?>