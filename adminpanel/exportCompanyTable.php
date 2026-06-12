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

if($_SESSION['userLevel']==1){
 $connSql =	"AND c.status = '1'";

}else{
 $connSql =	"AND c.id = '".addslashes($_SESSION['userId'])."'";
}

$query .=" SELECT  a.id_company AS id_company,
a.name AS Company,

j.name AS `Company Group`,
@`Contact Email` AS `Contact Email`,
@`Contact Phone` AS `Contact Phone`, 
a.address AS Address, 
e.name AS Area,

 

a.email AS Email,
a.secondary_email AS `Secondry Email`, 
a.phone AS Phone,
a.mobile AS Mobile,
a.company_credibility As `Company Credibility` , 
a.status AS Status,
 
a.city AS City,
a.postcode AS Postcode,
d.name AS Country,
f.name AS State,
a.date_created AS `Creation Date`,
a.last_modified AS `Modified Date`,
c.name AS `Executive Name` ,
g.name AS `Modified By`

FROM `".TBL_COMPANY."` AS a


			LEFT JOIN `".TBL_AREAS."` AS b ON a.area=b.id

			LEFT JOIN `".TBL_USERS."` AS c ON FIND_IN_SET(c.id,b.ids_unit_user)
			LEFT JOIN fs_areas_assign e ON a.area = e.id 
			LEFT JOIN fs_country_lang d ON a.id_country = d.id_country 
            LEFT JOIN fs_state f ON a.id_state = f.id_state 
            LEFT JOIN fs_users g ON e.user_id = g.id 
            LEFT JOIN fs_users h ON a.last_modified_by = h.id
            left join fs_company_group j ON a.id_default_group = j.id_group 
			WHERE a.`id_shop` = '".addslashes($_SESSION['shop'])."' 
			".$connSql." ";
			
			
				
			
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
						<tr><td style='height:50px;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >Company Profile Based ".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysqli_fetch_field($result);
							if($i>0){
								$dataWrite .= "<th style='color:#000;'>{$field->name}</th>";
							}
						}
						$dataWrite .= "</tr>";
						// printing table rows
				
					while($row = mysqli_fetch_row($result)){


						//Replacing Ids with text
						if($tableName	=='fs_company'){

							if($row[11] == 1){
								$row[11] = "Credit Allowed";
							}
							elseif($row[11] == 2){
								$row[11] = "Credit Not Allowed";
							}

							if($row[12] == 0){
								$row[12] = "Inactive";
							}
							elseif($row[12] == 1){
								$row[12] = "Active";
							}

							if($row[9] == ""){
								$row[9] = "N/A";
							}
						}

						


						//Ids replacement end

						$dataWrite .= "<tr>";
						foreach($row as $field=>$cell){
							//echo '<pre>';print_r($row);
							//echo $cell;
							if($field!=0){
							$dataWrite .= "<td >$cell</td>";
							}
							
						}	
					
						$dataWrite .= "</tr>";
						
						 $id_contacts= $row[0];
						
 $resContact = "SELECT CONCAT(title,' ', first_name,' ',last_name) AS  first_name,email,mobile from `".TBL_CUSTOMER."` where status='1' and id_company='".addslashes($id_contacts)."' and type='2' order by first_name";

	$resultContact = mysqli_query($connNew,$resContact);
while($rowContact = mysqli_fetch_row($resultContact)){
			
			$dataWrite .= "<tr >";
			$dataWrite .= "<td></td>";
						foreach($rowContact as $field=>$cell){
							if($field==0){
								$FName='Name :';
							}
							if($field==1){
								//$FName='Email';
								$FName='';
							}
							if($field==2){
								//$FName='Phone';
								$FName='';
							}
							$dataWrite .= "<td >$FName  $cell</td>";
							
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
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
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