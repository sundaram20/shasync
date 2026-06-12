<?php
include_once("../../config/auto_loader.php");

$arrayMsg = array();

if (!empty($_FILES['files']['name'])) {
    $file_name = explode(".", $_FILES['files']['name']);
    $allowed_ext = array("xls", "xlsx", "csv");
    $ext = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);

    if (in_array($ext, $allowed_ext)) {
        $new_name = substr(sha1(mt_rand()), 0, 8) . '.' . $ext;
        $sourcePath = $_FILES['files']['tmp_name'];
        $target = "/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/module_call/" . $new_name;

        if (move_uploaded_file($sourcePath, $target)) {
            try {
                // Load PHPExcel
                $inputFileType = PHPExcel_IOFactory::identify($target);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                
                // For CSV files, set delimiter if needed
                if ($inputFileType === 'Csv') {
                    $objReader->setDelimiter(',');
                }
                
                $objPHPExcel = $objReader->load($target);
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Get headers
                $heading = $sheet->rangeToArray('A1:' . $highestColumn . '1', null, true, false)[0];
                
                // Clean headers to avoid null or empty values
                $heading = array_map('trim', array_filter($heading));
                
                $rowData = array();
                
                // Read all rows starting from row 2
                for ($row = 2; $row <= $highestRow; $row++) {
                    $rowDataTemp = array();
                    
                    // Read all columns dynamically
                    foreach (range('A', $highestColumn) as $col) {
                        $cellValue = $sheet->getCell($col . $row)->getValue();
                        
                        // Handle Excel date formats
                        if (PHPExcel_Shared_Date::isDateTime($sheet->getCell($col . $row))) {
                            $excel_date = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                            $cellValue = gmdate("d-m-Y", $excel_date);
                        }
                        
                        $rowDataTemp[] = $cellValue;
                    }
                    
                    // Only add row if it contains data
                    if (array_filter($rowDataTemp)) {
                        // Combine headers with row data, handling mismatched lengths
                        $combinedRow = array_combine(
                            array_slice($heading, 0, count($rowDataTemp)),
                            array_slice($rowDataTemp, 0, count($heading))
                        );
                        if ($combinedRow !== false) {
                            $rowData[] = $combinedRow;
                        }
                    }
                }
                
                // Generate unique code and convert to JSON
                $uniquecode = rand(0000, 9999);
                $jsonData = json_encode($rowData);
                $format = $_REQUEST['format_type']?? '';
				$list_id = $_REQUEST['call_list']?? '';
                // Insert into database
                $reqSql = "INSERT INTO `call_request` (json_data, uniquecode,format_type, list_id, date_created) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($connNew, $reqSql);
                $dateCreated = date('Y-m-d H:i:s');
                mysqli_stmt_bind_param($stmt, "sssis", $jsonData, $uniquecode,$format,$list_id, $dateCreated);
                
                if (mysqli_stmt_execute($stmt)) {
                    $last_insert = mysqli_insert_id($connNew);
                    $arrayMsg['Msg'] = 'File uploaded successfully.';
                    $arrayMsg['status'] = '1';
                    $arrayMsg['id'] = $last_insert;
                } else {
                    $arrayMsg['Msg'] = 'File not uploaded. Please check the data.';
                    $arrayMsg['status'] = '0';
                }
                
                mysqli_stmt_close($stmt);
                
            } catch (Exception $e) {
                $arrayMsg['Msg'] = 'Error loading file "' . pathinfo($target, PATHINFO_BASENAME) . '": ' . $e->getMessage();
                $arrayMsg['status'] = '0';
            }
        } else {
            $arrayMsg['Msg'] = 'Failed to move uploaded file.';
            $arrayMsg['status'] = '0';
        }
    } else {
        $arrayMsg['Msg'] = 'Invalid File Type. Please upload xls, xlsx, or csv files.';
        $arrayMsg['status'] = '0';
    }
} else {
    $arrayMsg['Msg'] = 'No file uploaded.';
    $arrayMsg['status'] = '0';
}

echo json_encode($arrayMsg);
?>