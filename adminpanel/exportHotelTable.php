<?php include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
function exportTable($dataBaseName ='', $tableName = '', $fileType = '',$link = ''){
	global $connNew;
	$err = 0;
	//dbConnection-------------------------------------
	if($link && is_resource($link)){
		$conn = $link;
	}else{
		$conn = @mysqli_connect("localhost", "root", "");
	}
	@mysqli_select_db($dataBaseName,$conn);
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
				
				if($tableName	=='fs_hotels'){
				//print_r($_SESSION);


$query .="SELECT 			a.id AS `idhotel`,
							a.name AS `Hotel Name`, 
							a.hotel_code AS `Hotel Code`,
							b.name AS `Hotel Category`,
							sum(e.inventory) As `Total Rooms`,
							a.address AS `Address`, 
							a.city AS `City`, 
							c.name AS `State`, 
							a.pincode AS `Pincode`,
							a.phone1 As `Phone 1`,
							a.phone2 AS `Phone 2`,
							
							a.email AS Email,
							a.manager AS Manager,
							 
							
							a.status AS `Status`, 
							a.display_order AS `Display Order`, 
							a.date_created AS `Cretion Date`, 
							a.last_modified AS `Modified Date`, 
							d.name AS `Modified By` 
							FROM `fs_hotels` a 
							LEFT JOIN `fs_hotel_type` b ON a.hotel_category = b.id 
							LEFT JOIN `fs_state` c ON a.state = c.id_state 
							LEFT JOIN `fs_users` d ON a.last_modified_by = d.id
							INNER JOIN `fs_assign_hotel_room` e ON a.id = e.hotel_id 
							Where a.id_shop = '".addslashes($_SESSION['shop'])."' and a.status=1 and e.status=1 GROUP BY e.hotel_id ORDER BY a.`name` ASC ";
			
			
				
			
			//print_r($userPrimaryAreaArr);
		//	die;
				
	//echo $query;//	die;					
				$result = mysqli_query($connNew,$query);
				}


				
				
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysqli_num_fields($result);
				//echo $fields_num ;die;
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){

						$dataWrite .= "<table border='1'>
						<tr><td style='height:50px;font-weight:bold;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >Hotel Profile  ".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysqli_fetch_field($result);
							if($i>0){
								$dataWrite .= "<th style='background-color:#3c8dbc;color:#fff;'>{$field->name}</th>";
							}
						}
						$dataWrite .= "</tr>";
						// printing table rows
				
					while($row = mysqli_fetch_row($result)){


						//Replacing Ids with text
						if($tableName	=='fs_hotels'){


							if($row[14] == 0){
								$row[14] = "Inactive";
							}
							elseif($row[14] == 1){
								$row[14] = "Active";
							}

							
						}

						


						//Ids replacement end

						$dataWrite .= "<tr>";
						foreach($row as $field=>$cell){
							//echo '<pre>';print_r($row);
							//echo $cell;
							if($field!=0){
							$dataWrite .= "<td style='background-color:#fff'>$cell</td>";
							}
							
						}	
					
						$dataWrite .= "</tr>";
						
						 $idhotel= $row[0];
						
 $resContact = "SELECT  b.name,a.inventory FROM `".TBL_ASSIGN_HOTEL_ROOM."` as a left Join `".TBL_ROOM_TYPE."` as b ON b.id=a.room_id  WHERE a.`hotel_id` ='".addslashes($idhotel)."' ";

	$resultContact = mysqli_query($connNew,$resContact);
	$dataWrite .= "<tr >";
			$dataWrite .= "<td></td>";
						
							$dataWrite .= "<td style='background-color:#b9e0f7;font-weight:bold;'>Room Type</td>";
							$dataWrite .= "<td style='background-color:#b9e0f7;font-weight:bold;'>No of Rooms</td>";
							
							
					
						$dataWrite .= "</tr>";
while($rowContact = mysqli_fetch_row($resultContact)){
			
			$dataWrite .= "<tr >";
			$dataWrite .= "<td></td>";
						foreach($rowContact as $field=>$cell){
							if($field==0){
								$FName='';
							}
							if($field==1){
								//$FName='Email';
								$FName='';
							}
							if($field==2){
								//$FName='Phone';
								$FName='';
							}
							$dataWrite .= "<td style='background-color:#b9e0f7'>$FName  $cell</td>";
							
						}	
					
						$dataWrite .= "</tr>";
													
													
}
						
						//echo $dataWrite; die;
					}
				//-------------------------------------------------
				
				}elseif($fileType == 'csv'){echo 'csv';
					for($i=0; $i<$fields_num; $i++){
						$field = mysqli_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating(selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$field->id."'"),$endOfLine,'');
					}
					while($row = mysqli_fetch_row($result)){
						$counterCol = 0;
						foreach($row as $cell){
							$endOfLine = ($counterCol == ($fields_num-1))? true:false;
							$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
							$counterCol++;	
						}
					}
					
				}
				//
				//echo $dataWrite;
				//die;
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
//$connNew=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
adminLoginCheck();
//checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST[tableName]){
	exportTable($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType'], $db->conn);
}else{
	echo "Invalid input.";
}?>