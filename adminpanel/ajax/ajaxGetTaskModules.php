<?php
include_once("../../config/auto_loader.php");

/*
	Called when the Team Group dropdown changes on editTask.php.
	Returns a ready-to-use <option> list for the Module dropdown,
	filtered to the selected team. task_module.id_team is a
	comma-separated list of team ids (e.g. "21,22"), matched with
	FIND_IN_SET - same pattern as ids_team on the users table.
*/

$idTeam = isset($_POST['id_team']) ? $_POST['id_team'] : '';

if($idTeam == ''){
	echo '<option value="">Select Team First</option>';
	exit;
}

$options = '<option value="">Select Module</option>';

$resMod = selectSql('task_module'," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' AND FIND_IN_SET('".addslashes($idTeam)."', id_team) ",' ORDER BY `name`');

if($db->num_rows2($resMod)){
	while($rowMod = $db->fetch_object2($resMod)){
		$options .= '<option value="'.$rowMod->id.'">'.ucfirst($rowMod->name).'</option>';
	}
} else {
	$options = '<option value="">No modules for this team</option>';
}

echo $options;
exit;