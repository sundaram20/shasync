<? include_once("../../config/auto_loader.php");
if ($_GET['tbl'] != '' and $_GET['id'] > 0)
{
	$dataObj = $db->fetch_array2(selectSql($_GET['tbl'],"WHERE `id` = '".$_GET['id']."'",''));
	if (sizeof($dataObj) > 0)
	{
	foreach($dataObj as $k => $v)
	{
	
		if (!is_numeric($k)) 
		{
			$res[$k] = $v;
			
			if($k== 'date_created'){
			$res[$k] = dateformat($v);
			}
			if($k== 'last_modified'){
			$res[$k] = dateformat($v);
			}		
			if($k == 'last_modified_by'){
			$sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$v."'",''));
			$res[$k] = $sqlUserDetail->username;
		
			}
		}
	  }
	}
}
echo json_encode($res);
?>