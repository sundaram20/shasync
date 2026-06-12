<?php  include_once("../../config/auto_loader.php");
//include('../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//echo "<pre>";print_r($_REQUEST);echo "</pre>";

//die;


$arrayMsg=array();


	if($_FILES['files']['name']!='')   
 {  
       
           $file_name = explode(".", $_FILES['files']['name']);  
           $allowed_ext = array("xls", "xlsx", "csv");  
		   $path = $_FILES['files']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);
           if(in_array($ext, $allowed_ext))  
           {  
                $new_name = substr(sha1(mt_rand()),0,8) . '.' . $ext;  
                $sourcePath = $_FILES['files']['tmp_name'];  
                 $target = "/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/module_call/".$new_name;  
                if(move_uploaded_file($sourcePath, $target))  
                {  
                     //mysqli_query($con, "INSERT INTO images VALUES('".$target."')");
                    // echo "<img src='$target' />";
					//Use whatever path to an Excel file you need.
					  $inputFileName = $target;
					  //"/var/www/vhosts/roomstatushub.in/httpdocs/sync/uploaded_files/module_call/".$_FILES["files"]["name"];
					
					  
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
														null, true, false);
					  $rowData1=array();
					  
					  for ($row = 2; $row <= $highestRow; $row++) { 
	 
	  
	  $rowData22=array();
   
	  
	
    //Prints out data in each row.
    //Replace this with whatever you want to do with the data.
	  

	 ///echo $sheet->setCell('H'.$row, PHPExcel_Cell_DataType::TYPE_STRING); 
	 
		$excel_date = $sheet->getCell('N'.$row)->getValue();//43010; //here is that value 41621 or 41631
		$unix_date = ($excel_date - 25569) * 86400;
		$excel_date = 25569 + ($unix_date / 86400);
		$unix_date = ($excel_date - 25569) * 86400;
		$Due_date= gmdate("d-m-Y", $unix_date);
			  
		$excel_date = $sheet->getCell('G'.$row)->getValue();//43010; //here is that value 41621 or 41631
		$unix_date = ($excel_date - 25569) * 86400;
		$excel_date = 25569 + ($unix_date / 86400);
		$unix_date = ($excel_date - 25569) * 86400;
		$call_date= gmdate("d-m-Y", $unix_date);
	  if($sheet->getCell('A'.$row)->getValue()!=''){
	  
		  $rowData22[]=$sheet->getCell('A'.$row)->getValue();
		  $rowData22[]=$sheet->getCell('B'.$row)->getValue();
		  $rowData22[]=$sheet->getCell('C'.$row)->getValue();
		  
		  $rowData12[] = array_combine($heading[0],$rowData22);
	 
	  }
  }
  	$uniquecode	=rand(0000,9999);
	// echo '<pre>';
	//print_r($rowData12);
	 $jsonData= json_encode($rowData12);
	/*** Inserting In Request Table ***/
	 $reqSql = "INSERT INTO `call_request` (json_data,uniquecode,date_created) VALUES('".$jsonData."','".$uniquecode."','".date('Y-m-d h:i:s')."') ";
	 
      if(mysqli_query($connNew,$reqSql)){
				$last_insert= mysqli_insert_id($connNew);
				$arrayMsg['Msg'] = 'File uploaded sucessfully.';
				$arrayMsg['status'] ='1';
				$arrayMsg['id'] =$last_insert;
				//exit;
			}else{
				$err++;
				$arrayMsg['Msg'] = 'File not Uploaded. Please make corrections.';
				$arrayMsg['status'] ='0';
			}
			
			
			
			          }                 
           }else{
			   $arrayMsg['Msg'] =  'Invalid File Type.';
			   $arrayMsg['status'] ='0';
			   }            
     echo json_encode($arrayMsg);   
 }


?>