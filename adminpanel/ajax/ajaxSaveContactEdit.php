<?php
include_once("../../config/auto_loader.php");

$resCat = selectSql(TBL_CUSTOMER,"where status='1' and id_customer='".$_REQUEST['id_contacts']."'",'');

if(num_rows($resCat)){
  $resultCat = $db->fetch_object2($resCat);
  
  $k .= $resultCat->title;
  $k .= "####".$resultCat->first_name;
  $k .= "####".$resultCat->last_name;
  $k .= "####".$resultCat->email;
  $k .= "####".$resultCat->mobile;
  $k .= "####".$resultCat->designation;
  $k .= "####".$resultCat->dateofBirthMonth;
  $k .= "####".$resultCat->dateofBirthday;
  $k .= "####".$resultCat->dateofanniversaryMonth;
  $k .= "####".$resultCat->dateofanniversaryday;
  $k .= "####";
  
  echo $k;
}
?>