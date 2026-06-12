<?php
include_once("../../config/auto_loader.php");

$Sql = "SELECT * FROM `call_request` WHERE `id` = '" . addslashes($_REQUEST['id_call_request']) . "' AND status='0'";
$row = mysqli_fetch_object(mysqli_query($connNew, $Sql));
$jsonData = json_decode($row->json_data, true);

$listarray = array();
$list = '';
$CallStatusSet = '0'; // Default status

if (!empty($jsonData)) {
    // Get headers from the first record
    $headers = array_keys($jsonData[0]);
    
    // Initialize validation messages and table rows
    foreach ($jsonData as $Call) {
        $color = 'red';
        $CallTextCheckNull = '';
        
        // Check for null or empty values in each column
        foreach ($headers as $header) {
            if (empty($Call[$header])) {
                $CallTextCheckNull .= '<br>' . htmlspecialchars($header) . ' is Null';
            }
        }

        // Build table row
        $list .= '<tr>';
        foreach ($headers as $header) {
            $list .= '<td>' . htmlspecialchars($Call[$header] ?? '') . '</td>';
        }
        $list .= '<td style="color:' . $color . ';">' . $CallTextCheckNull . '</td>';
        $list .= '</tr>';
    }

    // Build table headers
    $headerRow = '';
    foreach ($headers as $header) {
        $headerRow .= '<th>' . htmlspecialchars($header) . '</th>';
    }

    // Construct the table
    $listarray['content'] = '<div class="box-body table-responsive">
        <table id="" class="table table-bordered table-striped">
            <thead>
                <tr>
                    ' . $headerRow . '
                    <th style="width:220px !important; float:left;height:57px;">Validate</th>
                </tr>
            </thead>
            <tbody>
                ' . $list . '
            </tbody>
        </table>
    </div>';
} else {
    // Handle empty JSON data
    $listarray['content'] = '<div class="box-body table-responsive">
        <table id="" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No Data Available</th>
                    <th style="width:220px !important; float:left;height:57px;">Validate</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="2">No records found.</td></tr>
            </tbody>
        </table>
    </div>';
}

$listarray['status'] = $CallStatusSet;
echo json_encode($listarray);
?>