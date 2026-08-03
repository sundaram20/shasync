<?php
include_once("../../config/auto_loader.php");

/*
	Called when the Team Group dropdown changes on editTask.php.
	Returns a ready-to-use <option> list for the Executive dropdown,
	filtered to users whose `ids_team` (comma-separated) contains the
	selected team id.
*/

$idTeam = isset($_POST['id_team']) ? $_POST['id_team'] : '';

if($idTeam == ''){
	echo '<option value="">Select Team First</option>';
	exit;
}

$options = '<option value="">Select Executive</option>';

$resExec = selectSql(TBL_USERS," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' AND (user_type=1 OR user_type=0) AND FIND_IN_SET('".addslashes($idTeam)."', ids_team) ",' ORDER BY `name`');

if($db->num_rows2($resExec)){
	while($rowExec = $db->fetch_object2($resExec)){
		$options .= '<option value="'.$rowExec->id.'">'.ucfirst($rowExec->name).'</option>';
	}
} else {
	$options = '<option value="">No executives for this team</option>';
}

echo $options;
exit;