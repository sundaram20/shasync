<?php
include_once("../config/auto_loader.php"); // DB connection

$sql = "SELECT id, extra_data FROM `call`";
$result = mysqli_query($connNew, $sql);
echo 'selected<br>';

while ($row = mysqli_fetch_assoc($result)) {
    $call_id = isset($row['id']) ? $row['id'] : '';
    $extra_data = isset($row['extra_data']) ? $row['extra_data'] : '';

    if ($call_id === '') {
        echo "Skipping row with missing call ID.<br>";
        continue;
    }

    
    $escaped_extra_data = mysqli_real_escape_string($connNew, $extra_data);

    echo "✅ Updating Call ID $call_id with extra_data<br>";

    $query = "
        UPDATE `call_details`
        SET extra_data = '$escaped_extra_data'
        WHERE call_id = '$call_id'
    ";

    if (!mysqli_query($connNew, $query)) {
        echo "Error updating call_id $call_id: " . mysqli_error($connNew) . "<br>";
    }
}

echo "<br><strong>Migration completed.</strong>";
?>
