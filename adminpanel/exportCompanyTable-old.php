<?php include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
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
				/*SELECT a.name As Company , a.address As Address, b.name As Area
FROM  `fs_company` AS a,  `fs_areas_assign` AS b
WHERE a.`id_shop` =2
AND a.area = b.id*/
				if($tableName	=='fs_company'){
				//print_r($_SESSION);
if($_SESSION['teamMemberAreas'] !=""){
$teamMembers = "  AND FIND_IN_SET(area,'".$_SESSION['teamMemberAreas']."')";//"AND id IN (".$_SESSION['teamMembers'].")";
}
else{
$teamMembers ="";
}
				 $query="  SELECT  a.id_company AS id_company,a.name AS Company, b.name AS `Company Group`, a.address AS Address, e.name AS Area, f.name AS State, a.city AS City, d.name AS Country, a.postcode AS Postcode, g.name AS `Executive Name` , a.email AS Email, a.secondary_email AS `Secondry Email`, a.phone AS Phone, a.mobile AS Mobile, a.company_credibility As `Company Credibility` , a.status AS Status, a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, h.name AS `Modified By` FROM fs_company a left join fs_company_group b ON a.id_default_group = b.id_group LEFT JOIN fs_company_area c ON a.deals_in = c.id LEFT JOIN fs_country_lang d ON a.id_country = d.id_country LEFT JOIN fs_state f ON a.id_state = f.id_state LEFT JOIN fs_areas_assign e ON a.area = e.id LEFT JOIN fs_users g ON e.user_id = g.id LEFT JOIN fs_users h ON a.last_modified_by = h.id
					WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND a.name!='' ".$teamMembers." ORDER BY a.name ASC  ";	
				$fileName = 'Company Data Base Report As On ';
				
				
				
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

 $sql;	
//	die;			
				$result = mysql_query($query, $conn);
				}


				if($tableName	=='fs_hotels'){

				$query="SELECT 
							a.name AS `Hotel Name`, 
							a.hotel_code AS `Hotel Code`,
							b.name AS `Hotel Category`,
							a.address AS `Address`, 
							a.city AS `City`, 
							c.name AS `State`, 
							a.pincode AS `Pincode`,
							a.phone1 As `Phone 1`,
							a.phone2 AS `Phone 2`,
							a.fax AS FAX, 
							a.email AS Email,
							a.manager AS Manager,
							a.historical_background AS `Historical Background`,
							a.hotel_tagline AS `Hotel Tagline`,
							a.special_notes AS `Special Notes`, 
							a.brief_description AS `Description`, 
							a.hotel_services AS `Hotel Services`, 
							a.latitude AS Latitude, 
							a.longitude AS Longitude, 
							a.renovation_status AS `Renovation Status`, 
							a.renovation_type AS `Renovation Type`, 
							a.renovation_message AS `Renovation Message`, 
							a.renovation_start_date AS `Renovation Start Date`, 
							a.renovation_end_date AS `Renovation End Date`, 
							a.status AS `Status`, 
							a.display_order AS `Display Order`, 
							a.date_created AS `Cretion Date`, 
							a.last_modified AS `Modified Date`, 
							d.name AS `Modified By` 
							FROM `fs_hotels` a 
							LEFT JOIN `fs_hotel_type` b ON a.hotel_category = b.id 
							LEFT JOIN `fs_state` c ON a.state = c.id_state 
							LEFT JOIN `fs_users` d ON a.last_modified_by = d.id 
							Where a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.`name` ASC ";	
					
				$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_hotel_type'){
					$query = "SELECT 
									a.name AS `Category Title`,
									a.description AS `Description`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_hotel_type` a
									LEFT JOIN `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";			
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_room_type'){
					$query = "SELECT 
									a.name AS `Hotel Type`,
									a.description AS Description,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_room_type` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";			
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_general_services'){
					$query = "SELECT 
									a.name AS `General service Title`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_general_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_outdoor_activities'){
					$query = "SELECT 
									a.name AS `Outdoor Activity Title`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_outdoor_activities` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_dining_services'){
					$query = "SELECT 
									a.name AS `Dining Service`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_dining_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";			
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_hotel_services'){
					$query = "SELECT 
									a.name AS `Hotel Service`,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`,
									a.status AS Status
									FROM `fs_hotel_services` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC";
											
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_company_group'){
					$query = "SELECT 
									a.name AS `Comapany Name`,
									a.reduction AS `Reduction`,
									a.price_display_method AS `Price Display`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_company_group` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_company_area'){
					$query = "SELECT 
									a.name AS `Comapany Domain`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_company_area` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_operator_master'){
					$query = "SELECT 
									a.name AS `Operator Title`,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									b.name AS `Modified By`
									FROM `fs_operator_master` a
									LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
											
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_customer'){
					/* $query = "SELECT 
									b.name AS `Company`,
									a.first_name AS `First Name`,
									a.last_name  AS `Last Name`,
									des.name AS `Designation`,
									a.mobile AS Mobile,
									a.email AS 	Email,
									a.dob AS `DOB`,
									a.doa AS `DOA`,
									a.address AS Address,
									d.name AS `State`,
									a.city AS City,
									c.name AS `Country`,
									a.postcode AS Postcode,
									a.phone AS Phone,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									e.name AS `Modified By`
									FROM `fs_customer` a
									LEFT JOIN `fs_company` b ON a.id_company = b.id_company
									LEFT JOIN `fs_country_lang` c ON a.id_country = 

									c.id_country
									LEFT JOIN `fs_state` d ON a.id_state = d.id_state
									LEFT JOIN  `fs_users` e ON a.last_modified_by = e.id
									LEFT JOIN `fs_designation_master` des ON a.designation=des.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND b.name!='' AND type = 2 ORDER BY b.name";*/
							$query = "SELECT 
									b.name AS `Company`,
									a.first_name AS `First Name`,
									a.last_name  AS `Last Name`,
									des.name AS `Designation`,
									a.mobile AS Mobile,
									a.email AS 	Email,
									a.dob AS `DOB`,
									a.doa AS `DOA`,
									a.address AS Address,
									d.name AS `State`,
									a.city AS City,
									c.name AS `Country`,
									a.postcode AS Postcode,
									a.phone AS Phone,
									a.status AS Status,
									a.date_created AS `Creation Date`,
									a.last_modified AS `Modified Date`,
									e.name AS `Modified By`,
                                    
                                    y.name as `Executive Name`
									FROM `fs_customer` a
									LEFT JOIN `fs_company` b ON a.id_company = b.id_company
									LEFT JOIN `fs_country_lang` c ON a.id_country = 

									c.id_country
									LEFT JOIN `fs_state` d ON a.id_state = d.id_state
									LEFT JOIN  `fs_users` e ON a.last_modified_by = e.id
                                   
									LEFT JOIN `fs_designation_master` des ON a.designation=des.id
                                    LEFT JOIN `fs_areas_assign` x ON b.area=x.id
                                      LEFT JOIN  `fs_users` y ON x.user_id = y.id
								WHERE a.id_shop = '".addslashes($_SESSION['shop'])."' AND b.name!='' AND type = 2 ORDER BY b.name";
					$fileName = 'Contact Database As On ';						
					$result = mysql_query($query, $conn);
				}


				if($tableName	=='fs_areas_assign'){
					 $query = "SELECT 
								 a.name as Area,
								 b.name AS `Executive Name`,
								 a.date_created AS `Area Creation Date`,
								 a.last_modified AS `Area Modified date`,
								 b.name AS `Area Modified By`,
								 a.status AS Status 
								 FROM `fs_areas_assign` a 
								 LEFT JOIN `fs_users` b ON a.user_id = b.id
								WHERE a.`id_shop` ='".addslashes($_SESSION['shop'])."'
								ORDER BY a.name ASC";
								
				$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_series_master'){
					$query = "SELECT 
							 a.name AS `Series Title`,
							 a.remarks AS `Remarks`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified by`
							 FROM `fs_series_master` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_cancellation_master'){
					$query = "SELECT 
							 a.name AS `Operator Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified by`
							 FROM `fs_cancellation_master` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_amendment_remarks'){
					$query = "SELECT 
							 a.name AS `Amendment Remarks Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified by`
							 FROM `fs_amendment_remarks` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_user_levels'){
					$query = "SELECT 
							 a.name AS `User Level Title`,
							 a.status AS `Status`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 b.name AS `Modified By`
							 FROM `fs_user_levels` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_users'){
					$query = "SELECT 
							 a.name AS 'Name',
							 b.name AS `User Level`,
							 a.status AS `Status`,
							 a.email AS `Email`,
							 a.phone AS `Phone`,
							 a.mobile As `mobile`,
							 a.hotel_access AS `Hotel Access`,
							 a.company AS `Company Name`,
							 a.address AS `Address 1`,
							 a.address AS `Address 2`,
							 a.city AS `City`,
							 a.zip AS `ZIP Code`,
							 a.last_login `Last Login`,
							 a.last_logout `Last Logout`,
							 a.date_created AS `Creation Date`,
							 a.last_modified AS `Modified Date`,
							 c.name  AS `Modified By`
							 FROM `fs_users` a
							 LEFT JOIN `fs_user_levels` b ON a.user_level = b.id
							 LEFT JOIN `fs_users` c ON a.last_modified_by = c.id
							 WHERE a.id_shop = '".$_SESSION['shop']."'  ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}

				if($tableName	=='fs_segment_master'){
					$query = "SELECT 
							 a.name AS `Segment Code`, a.description AS `Description`, a.status AS `Status`, a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, b.name AS `Modified By` FROM `fs_segment_master` a
							 LEFT JOIN  `fs_users` b ON a.last_modified_by = b.id
							 WHERE a.id_shop= '".addslashes($_SESSION['shop'])."' ORDER BY a.name ASC ";
					
					$result = mysql_query($query, $conn);
				}
				
				if($tableName	=='fs_user_permissions'){
					 $query = "SELECT 
							 b.name AS `User Level`,
							 c.name AS `Module`,
							 a.user_actions AS `User Can Perform Below Actions`,
							 a.date_created AS `Creation Date`,
							 a.last_modified As `Modified Date`,
							 a.status AS `Status`,
							 d.name AS `Modified By`
							 FROM `fs_user_permissions` a
							 LEFT JOIN `fs_user_levels` b ON a.user_level_id = b.id
							 LEFT JOIN `fs_modules` c ON a.module_id = c.id
							 LEFT JOIN `fs_users` d ON a.last_modified_by = d.id
							 WHERE a.id_shop = '".addslashes($_SESSION['shop'])."'
							 ";
					$result = mysql_query($query, $conn);
				}

				if($tableName == 'fs_budget_master'){
					$query = "SELECT 
					b.username AS `User Name` ,
					c.name AS `Hotel Name`,
					a.month AS `Month (01-mm-YYYY)`,
					a.qty AS `Room Nights`,
					a.month_value AS `Value (in Lacs)`
					FROM `fs_budget_master` a
					LEFT JOIN `fs_users` b ON a.id_user = b.id
					LEFT JOIN `fs_hotels` c ON a.id_hotel = c.id
					WHERE a.id_shop = ".$_SESSION['shop']." ORDER BY c.name,a.month";
					
					$result = mysql_query($query, $conn);
				}
				
				
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysql_num_fields($result);
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){

						$dataWrite .= "<table border='1'>
						<tr><td style='height:50px;font-weight:bold;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysql_fetch_field($result);
							if($i>0){
								$dataWrite .= "<th>{$field->name}</th>";
							}
						}
						$dataWrite .= "</tr>";
						// printing table rows
				
					while($row = mysql_fetch_row($result)){

						if($tableName == 'fs_users'){}

						if($tableName == 'fs_user_permissions'){}

						//Replacing Ids with text
						if($tableName	=='fs_company'){

							if($row[13] == 1){
								$row[13] = "Credit Allowed";
							}
							elseif($row[13] == 2){
								$row[13] = "Credit Not Allowed";
							}

							if($row[14] == 0){
								$row[14] = "Inactive";
							}
							elseif($row[14] == 1){
								$row[14] = "Active";
							}

							if($row[9] == ""){
								$row[9] = "N/A";
							}
						}

						if( $tableName	=='fs_company_area'  OR $tableName	=='fs_operator_master' OR $tableName	=='fs_cancellation_master' OR $tableName	=='fs_amendment_remarks' OR $tableName	=='fs_user_levels' ){
							if($row[1] == 1 ){
								$row[1] = "Active";
							}
							elseif($row[1] == 0){
								$row[1] = "Inactive";
							}
						}

						if($tableName	=='fs_customer'){
							if($row[14] == 1 ){
								$row[14] = "Active";
							}
							elseif($row[14] == 0){
								$row[14] = "Inactive";
							}
						}

						if($tableName	=='fs_series_master' OR $tableName	=='fs_segment_master' OR $tableName	=='fs_users' ){
							if($row[2] == 1 ){
								$row[2] = "Active";
							}
							elseif($row[2] == 0){
								$row[2] = "Inactive";
							}
						}

						if($tableName	=='fs_company_group'){
							if($row[2] == 1 ){
								$row[2] = "TAX included";
							}
							elseif($row[2] == 0){
								$row[2] = "TAX excluded";
							}

							if($row[3] == 1 ){
								$row[3] = "Active";
							}
							elseif($row[3] == 0){
								$row[3] = "Inactive";
							}


						}

						if($tableName	=='fs_general_services' OR $tableName	=='fs_outdoor_activities' OR $tableName	=='fs_dining_services' OR $tableName	=='fs_hotel_services'){}

						if($tableName	=='fs_room_type'){}

						if($tableName	=='fs_hotel_type'){}

						if($tableName	=='fs_areas_assign' OR $tableName	=='fs_user_permissions'){}

						if($tableName	=='fs_hotels'){}

						//Ids replacement end

						$dataWrite .= "<tr>";
						foreach($row as $field=>$cell){
							//echo '<pre>';print_r($row);
							//echo $cell;
							if($field!=0){
							$dataWrite .= "<td style='background-color:#c4c2c2'>$cell</td>";
							}
							
						}	
					
						$dataWrite .= "</tr>";
						
						 $id_contacts= $row[0];
						
 $resContact = "SELECT CONCAT(title,' ', first_name,' ',last_name) AS  first_name,email,mobile from `".TBL_CUSTOMER."` where status='1' and id_company='".addslashes($id_contacts)."' and type='2' order by first_name";

	$resultContact = mysql_query($resContact, $conn);
while($rowContact = mysql_fetch_row($resultContact)){
			
			$dataWrite .= "<tr style='background-color:#cccc'>";
			$dataWrite .= "<td></td>";
						foreach($rowContact as $field=>$cell){
							if($field==0){
								$FName='Name';
							}
							if($field==1){
								$FName='Email';
							}
							if($field==2){
								$FName='Phone';
							}
							$dataWrite .= "<td>$FName : $cell</td>";
							
						}	
					
						$dataWrite .= "</tr>";
													
													
}
						
						//echo $dataWrite; die;
					}
				//-------------------------------------------------
				
				}elseif($fileType == 'csv'){echo 'csv';
					for($i=0; $i<$fields_num; $i++){
						$field = mysql_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
					}
					while($row = mysql_fetch_row($result)){
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
adminLoginCheck();
//checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST[tableName]){
	exportTable($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType'], $db->conn);
}else{
	echo "Invalid input.";
}?>