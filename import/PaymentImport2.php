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
	 
	  echo '<br>';
	  $rowData22=array();
   
	  
	
    //Prints out data in each row.
    //Replace this with whatever you want to do with the data.
	  

	 ///echo $sheet->setCell('H'.$row, PHPExcel_Cell_DataType::TYPE_STRING); 
	 
$excel_date = $sheet->getCell('L'.$row)->getValue();//43010; //here is that value 41621 or 41631
$unix_date = ($excel_date - 25569) * 86400;
$excel_date = 25569 + ($unix_date / 86400);
$unix_date = ($excel_date - 25569) * 86400;
$Due_date= gmdate("d-m-Y", $unix_date);
	  
$excel_date = $sheet->getCell('E'.$row)->getValue();//43010; //here is that value 41621 or 41631
$unix_date = ($excel_date - 25569) * 86400;
$excel_date = 25569 + ($unix_date / 86400);
$unix_date = ($excel_date - 25569) * 86400;
 $Invoice_date= gmdate("d-m-Y", $unix_date);
	  if($sheet->getCell('A'.$row)->getValue()!=''){
	  
	  $rowData22[]=$sheet->getCell('A'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('B'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('C'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('D'.$row)->getValue();
	  $rowData22[]= $Invoice_date;
	  $rowData22[]=$sheet->getCell('F'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('G'.$row)->getFormattedValue();
	  $rowData22[]=$sheet->getCell('H'.$row)->getFormattedValue();
	  $rowData22[]=$sheet->getCell('I'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('J'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('K'.$row)->getValue();
	  $rowData22[]=$Due_date;
	  $rowData22[]=$sheet->getCell('M'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('N'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('O'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('P'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('Q'.$row)->getValue();
	  $rowData22[]=$sheet->getCell('R'.$row)->getValue();
	  
 	  $rowData12[] = array_combine($heading[0],$rowData22);
	 
	  }
  }
	// echo '<pre>';
	//print_r($rowData12);
	$jsonData= json_encode($rowData12);
	/*** Inserting In Request Table ***/
$reqSql = "INSERT INTO `invoice_request` (json_data,uniquecode,date_created) VALUES('".$jsonData."','".$uniquecode."','".date('Y-m-d h:i:s')."') ";
					
	mysqli_query($connNew,$reqSql);

					/*** End ***/
echo json_encode($rowData12);


}