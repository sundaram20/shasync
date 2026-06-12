<?php


$file = $_REQUEST['fileName'];
$file = '../creditform/'.$file;

if(!file_exists($file)){ // file does not exist
    echo "File Not Found";
} else {
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$file");
    header("Content-Type: application/zip");
    header("Content-Transfer-Encoding: binary");

    // read the file from disk
    readfile($file);
}

?>