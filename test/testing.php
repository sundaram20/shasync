<?php 
error_reporting(0);
include_once("../config/fron_autoload.php"); 
$connNew = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$connew = $head_hotel_row;

			echo $tempTable = "CREATE TEMPORARY TABLE executive_port(	
    					sno INT ,
    					executive VARCHAR(50),
    					company_name VARCHAR(50),
    					company_group VARCHAR(50),
    					area VARCHAR(50),
    					summer_rate VARCHAR(50),
    					winter_rate VARCHAR(50),
    					year_rate VARCHAR(50),
    					year_pre_2 INT,
    					year_pre  INT,
						year	INT,
						budg_year INT,
						v2b INT,
						visit1 date,
						visit2 date,
						visit3 date,
						visit4 date,
						visit5 date
						);";
			mysqli_query($connNew,$tempTable);			
			$tempInsert = "INSERT INTO executive_port VALUES(1,'hitesh','cryonicz','IT','delhi 6','fern90','fern91','fern92',500,1000,200,100,100,'01-05-2019','02-05-2019','03-05-2019','04-05-2019','05-05-2019')";

			mysqli_query($connNew,$tempInsert);

			$checkTemp = "SELECT * from executive_port";

			$resTemp = mysqli_query($connNew,$checkTemp);

			$rowTemp = mysqli_fetch_object($resTemp);	
			echo '<pre>';		
print_r($rowTemp);
die;
			debugData($resTemp);
			exit;
			while($row = mysqli_fetch_object($res)){
				print_r($rowTemp);
				}
?>