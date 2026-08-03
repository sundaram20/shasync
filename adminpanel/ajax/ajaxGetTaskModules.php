<?php
include_once("../../config/auto_loader.php");

/*
	Called when the Team Group dropdown changes on editTask.php.
	Returns a ready-to-use <option> list for the Module dropdown,
	filtered to the selected team.
*/

$idTeam = isset($_POST['id_team']) ? $_POST['id_team'] : '';

if($idTeam == ''){
	echo '<option value="">Select Team First</option>';
	exit;
}

$options = '<option value="">Select Module</option>';

$resMod = selectSql('task_module'," WHERE `status`='1' AND `id_shop`='".addslashes($_SESSION['shop'])."' AND `id_team`='".addslashes($idTeam)."' ",' ORDER BY `name`');

if($db->num_rows2($resMod)){
	while($rowMod = $db->fetch_object2($resMod)){
		$options .= '<option value="'.$rowMod->id.'">'.ucfirst($rowMod->name).'</option>';
	}
} else {
	$options = '<option value="">No modules for this team</option>';
}

echo $options;
exit;