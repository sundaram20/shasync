<?php include_once("../../config/auto_loader.php");
	
	if($_REQUEST['rate_level']==0){
		$id_rate = explode('|',$_REQUEST['id_rate']);
		$_REQUEST['rate_level']=$id_rate[1];	
	}

	$result='';
	$id_general_term = selectColumn(TBL_DOCUMENT_CONFIG_DETAILS,'id_general_term','WHERE id_doc_type="'.$_REQUEST['doc_type'].'" AND id_rate_level="'.$_REQUEST['rate_level'].'" ');
	
	$sql="SELECT * FROM ".TBL_GENERAL_TERMS." WHERE id_shop='".$_SESSION['shop']."' ";

	$resGen = mysqli_query($connNew,$sql);
	$result ='<option  value="">Select Terms</option>';
	while($rowGen = mysqli_fetch_object($resGen)){

		if($id_general_term==$rowGen->id){
			$selected='selected="selected"';
		}
		else{
			$selected='';
		}	

		$result .= '<option '.$selected.' value="'.$rowGen->id.'">'.$rowGen->title.'</option>';
	}
	echo $result;
?>