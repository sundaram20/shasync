<?php //include_once("../config/fron_autoload.php"); 



include("phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include("phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
?>
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
if($_REQUEST['submit']	==	'Upload csv'){
        if($_FILES["fileToUpload"]["name"] != '')
        {
            $allowed_extension = array('xls', 'csv', 'xlsx');
            $file_array = explode(".", $_FILES["fileToUpload"]["name"]);
            $file_extension = end($file_array);
            if(in_array($file_extension, $allowed_extension))
            {
                $file_name = time() . '.' . $file_extension;
                //move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $file_name);
				
				$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/sales/import/";
	$target_file = $target_dir . basename($file_name);

	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    
				
				
				
				
                $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
                $spreadsheet = $reader->load($file_name);
                unlink($file_name);
                echo '===='.$data = $spreadsheet->getActiveSheet()->toArray();
                foreach($data as $row)
                {
                    $insert_data = array(
                        ':test1'          =>  $row[0],
                        ':test2'          =>  $row[1],
                        ':test3'          =>  $row[2],
                        ':test4'          =>  $row[3]
                    );
                 };
               
                $statement = $connect->prepare($query);
                $statement->execute($insert_data);
             }
             echo "succes";
        }else{
           echo "only xls,csv,xlsx are allowed";
        }
}
		?>