<?php
include_once("../config/auto_loader.php"); // Include DB connection

$sql = "SELECT id, id_list_name FROM `call`";
$calls = mysqli_query($connNew, $sql);
echo 'selected';

while($row = mysqli_fetch_assoc($calls)) {
    $call_id = isset($row['id']) ? $row['id'] : '';
    $id_list_name = isset($row['id_list_name']) ? $row['id_list_name'] : '';

    if ($call_id === '' || $id_list_name === '') {
        echo "⚠️ Skipping row due to missing data: ";
        print_r($row);
        echo "<br>";
        continue;
    }

    echo "✅ Updating Call ID $call_id with id_list_name = $id_list_name<br>";

    $query = "
        UPDATE `call_details` 
        SET id_list_name = '$id_list_name' 
        WHERE call_id = '$call_id'
    ";

    if (!mysqli_query($connNew, $query)) {
        echo "❌ Error for Call ID $call_id: " . mysqli_error($connNew) . "<br>";
    }
}
echo "<br><strong>Update completed.</strong>";


?>
