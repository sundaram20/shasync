<?php  include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$CompareYear=$_REQUEST['CompareYear'];
$financial_year=explode('-',$_REQUEST['Currentfinancialyear']);
//print_r($_REQUEST);

//$FinanceStarLastYear=$financial_year[0]-1;
//$FinanceEndLastYear=$financial_year[1]-1;
//$Last_financial_year=$FinanceStarLastYear."-".$FinanceEndLastYear;
//echo "SELECT * from ".TBL_BUDGET_YEAR." where status='1'  and id_shop='".addslashes($_SESSION['shop'])."'  order by name desc";

$resContact = executeSql("SELECT * from `".TBL_BUDGET_YEAR."` where id_shop='".addslashes($_SESSION['shop'])."'  order by name desc");
if(num_rows($resContact) > 0){	
	$contact  =	'<select class="form-control select2" name="CompareYearselected" id="CompareYearselected"  >
				';
		while($rowContact = $db->fetch_object2($resContact)){	
		
		    $SelectYear =   explode('-',$rowContact->name);
		    $CompareYears =   explode('-',$CompareYear);
		    
		    if($SelectYear[0]<$CompareYears[0]){
                //if($rowContact->name==$Last_financial_year){
                //$selected = 'selected="selected"';
                //}else {
               // $selected = '';
                //}
			$contact .= '<option '.$selected.'  value="'.$SelectYear[0].'-20'.$SelectYear[1].'" >'.$rowContact->name.'</option>';
		    }
			
		}				 
		$contact .=	'</select>';
	}else{
	$contact .= '<option value="">--</option>';
	}
echo $contact;
?>