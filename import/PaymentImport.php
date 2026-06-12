<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>Company Import</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

echo  date('d-m-Y','42058');

  //Had to change this path to point to IOFactory.php.
  //Do not change the contents of the PHPExcel-1.8 folder at all.
  include('../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');

if($_REQUEST['submit']	==	'Upload csv'){  
  	$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/sales/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
  
  
  //Use whatever path to an Excel file you need.
  $inputFileName = $_FILES["fileToUpload"]["name"];

  
  try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
  } catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
        $e->getMessage());
  }

  $sheet = $objPHPExcel->getSheet(0);
  $highestRow = $sheet->getHighestRow();
  $highestColumn = $sheet->getHighestColumn();
 $heading = $sheet->rangeToArray('A1:'  . $highestColumn . 1, 
                                    null, true, false);$rowData1=array();
  for ($row = 2; $row <= $highestRow; $row++) { 
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
                                    null, true, true);
									
 	$rowData1[] = array_combine($heading[0],$rowData[0]);
    //Prints out data in each row.
    //Replace this with whatever you want to do with the data.
	  $field2= $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue(); //Excel Column 3
$date = PHPExcel_Shared_Date::ExcelToPHP($field2); //unix value
echo $field2= gmdate("Y-m-d", $date); //you can echo this value
	  
	// var_dump($sheet->getActiveSheet()->getCell(5,$row)->getValue());
//var_dump(PHPExcel_Shared_Date::isDateTime($sheet->getActiveSheet()->getCell(5,$row)));
//var_dump($sheet->getActiveSheet()->getCell('E'.$row)->getStyle()->getNumberFormat()->getFormatCode());
var_dump($sheet->getCell('E'.$row)->getFormattedValue()); 
	  
	 if (PHPExcel_Shared_Date::isDateTime($sheet->getCell('E'.$row))) {
    $dateTimeObject = PHPExcel_Shared_Date::ExcelToPHPObject($sheet->getCell('E'.$row)->getValue());
    echo $dateTimeObject->format('d-m-Y'), PHP_EOL;
} 
	  
	  
   echo '<pre>';
	print_r($rowData);
  }


echo json_encode($rowData1);


}