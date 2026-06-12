<?php include_once("../../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'update');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 



$availableData .= '<table><tr id="rateMaster">';

	

		 
				  
				  $availableData .='<td><button type="button" name="add" class="btn btn-success btn-sm add" onClick="AddTextBox();"><span class="glyphicon glyphicon-plus"></span></button></td>
				  
';	
				// if(addslashes(encryptor('decrypt',$_REQUEST['id']))==''){ 
				  
				  
				// }
				$availableData .='</tr>';	
			
			     	
			
								 
											 
 $availableData .='</table>
				<div id="TextBoxContainer"></div>';
				  
				
			
	
	//}
 $availableData .= '  
            </div>';
		


					  
					



			  
echo $availableData;
?>




<script type="text/javascript">
function GetDynamicTextBox(value){
    return '<table class="table table-hover" style="margin-bottom:0px !important;"><tr><td style="float:left;"><input type="text" class="form-control  tax_slabs_from" id="tax_slabs_from" name="tax_slabs_from[]" value="" data-parsley-required automcomplete="off" placeholder="Tariff From" data-parsley-type="number" style="width:160px;"></td><td style="float:left;"><input type="text" class="form-control  tax_slabs_to" id="tax_slabs_to" name="tax_slabs_to[]" value="" data-parsley-required automcomplete="off" placeholder="Tariff To" data-parsley-type="number" style="width:160px;"></td>' + '<td style="float:left;"><input class="form-control  tax_percentage" type="text"  id="tax_percentage[]" name="tax_percentage[]" placeholder="Tax %" value="" data-parsley-required automcomplete="off" data-parsley-type="number" style="width:160px;"></td>' + '<td style="float:left;"><button type="button" value="Remove" onclick = "RemoveTextBox(this)" class="btn btn-danger btn-sm remove"><span class="glyphicon glyphicon-minus"></span></button></td></tr></table>'
}

function AddTextBox() {
    var div = document.createElement('DIV');
    div.innerHTML = GetDynamicTextBox("");
    document.getElementById("TextBoxContainer").appendChild(div);
}
 
function RemoveTextBox(div) {
	
	document.getElementById("TextBoxContainer").removeChild(table.parentNode);
  //document.getElementById("TextBoxContainer").removeChild(div.parentNode);
   
	//y.remove();
}
 
function RecreateDynamicTextboxes() {
    var values = eval('<%=Values%>');
    if (values != null) {
        var html = "";
        for (var i = 0; i < values.length; i++) {            		
			html += "<div>" + room_type_id(values[i]) + "</div>";
			html += "<div>" + rate_plan_id(values[i]) + "</div>";	
        }
        document.getElementById("TextBoxContainer").innerHTML = html;
    }
}
window.onload = RecreateDynamicTextboxes;
</script>

<script type="text/javascript">
        function addTextArea(){}
		
		 $(document).on('click', '.remove', function(){
  		$(this).closest('div').remove();
 });
   
   
   
  </script>
