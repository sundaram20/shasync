<?php include_once("../../config/auto_loader.php");



	 $logincheck=adminLoginCheck(1);     
	 if($logincheck==1){
    $key=$_GET['q'];
       

    $response = array();
	if($key==''){

	//$id_customer=$_GET['id_customer'];
	//$id_customersql=" AND `id_customer` = '".addslashes($id_customer)."'";
	 /*$SQL	= "select *  from ".TBL_CUSTOMER." where type='2' and status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  first_name !='' ".$id_customersql."  order BY first_name LIMIT 0,25"; */
	  $SQL	= "select *  from ".TBL_CUSTOMER." where type='2' and status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  (first_name!='' OR  last_name!='' OR  mobile!='' ) order BY first_name LIMIT 0,25";
	 // echo "echo1";
}else{	 
		 
   $SQL	= "
select *  from ".TBL_CUSTOMER." where type='2' and status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and (first_name !='' OR last_name !='' OR mobile !='') AND (first_name LIKE '{$key}%' || last_name LIKE '{$key}%'  || mobile LIKE '{$key}%' )   order BY first_name LIMIT 0,25
";
//echo "echo2";
	}
	
	$query=mysqli_query($connNew, $SQL);
	
	//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and first_name !=''  AND first_name LIKE '%{$key}%' order BY first_name LIMIT 0,25");
	
    while($row=mysqli_fetch_assoc($query))
    {
      $first_name = htmlspecialchars_decode($row['first_name']);
      $last_name = htmlspecialchars_decode($row['last_name']);
      $mobile = htmlspecialchars_decode($row['mobile']);

	  $response[] = array('id'=>$row['id_customer'], 'text'=>$first_name.' '.$last_name.', '.$mobile);
	 // $response[] = array('id'=>$row['id_customer'], 'text'=>$row['first_name'].' - '.$row['city']);
	  
	  //array("value"=>$row['id_customer'],"label"=>$row['first_name'].' - '.ucfirst($row['city']));
	 			
    }
	
    echo json_encode($response);
	 }else{
		 
		 $response[0] = array('id'=>0, 'text'=>'Session expired');
		     echo json_encode($response);
	 }
	   ?>