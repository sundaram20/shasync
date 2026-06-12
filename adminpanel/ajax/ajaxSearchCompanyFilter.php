<?php include_once("../../config/auto_loader.php");



	 $logincheck=adminLoginCheck(1);     
	 if($logincheck==1){
    $key=$_GET['q'];
    $response = array();
   $SQL	= "
select *  from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$key}%'  order BY name LIMIT 0,25
";
	
	
	$query=mysqli_query($connNew, $SQL);
	
	//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
	
    while($row=mysqli_fetch_assoc($query))
    {
      $name = htmlspecialchars_decode($row['name']);
	  $response[] = array('id'=>$row['id_company'], 'text'=>$name.' - '.$row['city']);
	 // $response[] = array('id'=>$row['id_company'], 'text'=>$row['name'].' - '.$row['city']);
	  
	  //array("value"=>$row['id_company'],"label"=>$row['name'].' - '.ucfirst($row['city']));
	 			
    }
	
    echo json_encode($response);
	 }else{
		 
		 $response[0] = array('id'=>0, 'text'=>'Session expired');
		     echo json_encode($response);
	 }
	   ?>