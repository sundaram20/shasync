

///////hide loading div/////////////////////
 $('.loading').hide(); 
 
///////////check all input boxes///////////////////////////////////////
$('#CheckAll').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    }else {
	 $(':checkbox').each(function() {
            this.checked = false;                        
        });
	}
});
/////////////////////////Confirmation Popup start/////////////////////
function formSubmit(purpose)
{
	var obj2= document.listingForm.ids;
	var flag =false;
	
	if(obj2.checked == true)								
	{
		flag = true;
	}
	else
	{
		for(i=0;i<obj2.length;i++)
		{
			if(obj2[i].checked == true)
			{
				flag = true; 
			}
			
		}
	}
	if(flag)
	{
		if(confirm('Are you sure that you want to '+purpose+' this record'))
		{
			document.listingForm.act.value=purpose;
			document.listingForm.submit();
		}
	}
	else
	{
		alert("Please select atleast one record.");
	}
}

/////////////////////////COnfirmation check popup end/////////////////////  
///////////////select type and checkbox color ////////////////////////////
$('.select2').select2();
$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass   : 'iradio_flat-green'
    });

$pkg_status1 = $('#pkg_status1').iCheck({
      radioClass   : 'iradio_flat-green'
    });
$pkg_status2 = $('#pkg_status2').iCheck({
      radioClass   : 'iradio_flat-green'
    });
$pkg_status = $('input[name="pkg_status"]').iCheck({
      radioClass   : 'iradio_flat-green'
    });
var pkg_status_value=0;
$pkg_status1.on('ifClicked', function() {
	   pkg_status_value = $(this).val();
	});
$pkg_status2.on('ifClicked', function() {
	  pkg_status_value = $(this).val();
	});

$company = $('#id_company').select2();
$rate = $('#rate_id').select2();
$guest = $('#id_guest').select2();	
/////////////////////////image remove/////////////////////  
function removeImage(page_name,table_name,id,column_name,callbackId,image_path,image_name) {
 $.ajax({
           type: "POST",
           url: page_name,
           data: 'table_name='+table_name+'&id='+id+'&column_name='+column_name+'&image_path='+image_path+'&image_name='+image_name, // serializes the form's elements.
           success: function(data)
           {
               //alert(data); // show response from the php script.
			     $("#"+callbackId).html(data);
           }
         });
}
////////////////////////add more button////////////////////////////////////////////
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="clearfix"></div><div class="btn btn-default btn-file"><i class="fa fa-upload"></i>&nbsp;Upload<br><input type="file" class="form-control" id="image" name="image[]" value=""><input type="hidden" name="old_image" value=""/></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
///////////////add form ajax//////////////////////

function addForm(fid, txt)
{
   $('.modal-title', $('#'+fid)).html(txt);

   $('form', $('#'+fid)).trigger("reset");

   $('.form-control', $('#'+fid)).val('');

   $('.form-control', $('#'+fid)).each(function() {

   //alert( fid);

	  if ($(this).attr('data-ckeditor') == 1)

	  CKEDITOR.instances[$(this).attr('id')].setData('');

   });

}

/////////////////edit form ajax/////////////////////////////

function editForm(fid, txt, tbl, id)
{

    $('.modal-title', $('#'+fid)).html(txt);
    $('form', $('#'+fid)).trigger("reset");
    $.get("ajax/_edit_ajax.php", {  tbl: tbl, id: id},
		function(ajaxdata){
			//alert(ajaxdata);
			var data= JSON.parse(ajaxdata);
			$('.form-control', $('#'+fid)).each(function() {
				var n = $(this).attr('name');
				$(this).val(data[n]);

				$('#'+n+'_colorpicker').css('background-color', data[n]);

				if ($(this).attr('data-ckeditor') == 1)
				{
					CKEDITOR.instances[$(this).attr('id')].setData(stripslashes (data[n]));
				}

				if ($(this).hasClass('icons-selector'))
				{
				$(this).val(data[n].replace('u', '&#x'));
				}
			});
			$('input[type=radio]', $('#'+fid)).each(function() {
				var n = $(this).attr('name');
				$('#'+n+data[n]).attr('checked', 'checked');
			});
			$('input[type=checkbox]', $('#'+fid)).each(function() {
				var n = $(this).attr('name');
				if ($(this).val() == data[n])
				$(this).attr('checked', true);
				else
				$(this).attr('checked', false);
			});
	});
}


    
///////////////////delete ajax////////////////////////////////

function deleteFunction(txt, id, tbl,path)
{   	
	if (confirm('Are you sure to delete this '+ txt+'?'))
    	{
    	$.get("ajax/_delete_ajax.php", {tbl:tbl,id:id,path:path},
		function(newstatus){
		//alert(newstatus );
			if (newstatus == 1) 
			{
			$('#'+id).fadeOut();
			}
			else if(newstatus == 2) 
			{			
			alert('You don\'t have permission to take action delete on this page.');
			}else {
			alert('There is some error. Please try again.');	
			}
		});    		
    }
}


////////////////////////set status ajax/////////////////////////////////


function swapStatus(id,status, tbl, btnObj)
{

		$.get("ajax/_status_ajax.php", {tbl: tbl,status:status, id: id},
		function(newstatus){
		//alert(newstatus );
			if (newstatus == 1) 
			{
			$('#'+id).removeClass('inactive-record');
			btnObj.html('<i class="fa fa-eye-slash fa-fw"></i> Hide');
			}
			else if(newstatus == 0) 
			{
			$('#'+id).addClass('inactive-record');
			btnObj.html('<i class="fa fa-eye fa-fw"></i> Show');
			}
			else if(newstatus == 3) 
			{			
			alert('You don\'t have permission to take action show on this page.');
			}
			else if(newstatus == 4) 
			{			
			alert('You don\'t have permission to take action hide on this page.');
			}else {
			alert('There is some error. Please try again.');	
			}
		});
}


////////////////////////////////////////////////////////////////////////


	
	
/////////////////add active class on the basis of url/////////////////	

		var url = window.location;
		
		////////////////////////for mangage pages//////////
		var element = $('ul.sidebar-menu li ul li a').filter(function() {	
													  
			return this.href == url || url.href.indexOf(this.href) == 0;
			
		}).addClass('active').parent().addClass('active').parent().parent();
		
		if (element.is('li')) {
			element.addClass('active menu-open');
		}		
		/////////////////////////////for edit pages////////////
		var element = $('ul.sidebar-menu li ul li a').filter(function() {															 
			return this.rel == url || url.href.indexOf(this.rel) == 47;			
		}).addClass('active').parent().addClass('active').parent().parent();		
		if (element.is('li')) {
			element.addClass('active menu-open');
		}
	
	
	

///////////////get room //////////////////////////////////////////////////////

function getRoom(hotelId,roomdefaultvalue) {
	//alert(roomdefaultvalue);	
	 $.ajax({
           type: "POST",
           url: 'ajax/ajaxgetRoom.php',
           data: 'hotelId='+hotelId+'&roomdefaultvalue='+roomdefaultvalue, // serializes the form's elements.
           success: function(data)
           {
                 $("#room_id").empty();
			     $("#room_id").html(data);
           }
         });
	}
	
	
///////////////get hotel //////////////////////////////////////////////////////

function getHotel(shopId,hotel_access,userId) {
	//alert(roomdefaultvalue);	
	 $.ajax({
           type: "POST",
           url: 'ajax/ajaxgetHotels.php',
           data: 'shopId='+shopId+'&userId='+userId+'&hotel_access='+hotel_access, // serializes the form's elements.
           success: function(data)
           {
                 $("#hotel_access").empty();
			     $("#hotel_access").html(data);
           }
         });
	}
	
function getHotelMapping(shopId,hotel_id) {
	//alert(roomdefaultvalue);	
	 $.ajax({
           type: "POST",
           url: 'ajax/ajaxgetHotelsMapping.php',
           data: 'shopId='+shopId+'&hotel_id='+hotel_id, // serializes the form's elements.
           success: function(data)
           {
                 $("#hotel_id").empty();
			     $("#hotel_id").html(data);
           }
         });
	}
	
function getRateMapping(shopId,rate_id) {
	//alert(roomdefaultvalue);	
	 $.ajax({
           type: "POST",
           url: 'ajax/ajaxgetRateMapping.php',
           data: 'shopId='+shopId+'&rate_id='+rate_id, // serializes the form's elements.
           success: function(data)
           {
                 $("#rate_id").empty();
			     $("#rate_id").html(data);
           }
         });
	}
	
function getCompanyMapping(shopId,company_id) {
	//alert(roomdefaultvalue);	
	 $.ajax({
           type: "POST",
           url: 'ajax/ajaxgetCompanyMapping.php',
           data: 'shopId='+shopId+'&company_id='+company_id, // serializes the form's elements.
           success: function(data)
           {
                 $("#company_id").empty();
			     $("#company_id").html(data);
           }
         });
	}
///////////////////////////////////////////////////////////////////////////////
//////////////////////////////Date range picker with message- book-now.php////////////////////////////

$('.dateRange').daterangepicker({
	"autoApply": true,
	locale: {
	format: 'DD-MM-YYYY',
	separator: ' to ',	
	autoclose: true,	
	 },
	minDate:new Date(),	
	//endDate: moment().add(1, 'day'),
			
}).on('apply.daterangepicker', function(ev, picker) {

	ajaxAddRoommsgUpdate();
	$company.val('').trigger('change');
	$rate.val('').trigger('change');
	showInventory();
	
});

$('.dateRangeNewEdit').daterangepicker({
	"autoApply": true,
	locale: {
	format: 'DD-MM-YYYY',
	separator: ' to ',	
	autoclose: true,	
	 },
	minDate:new Date(),	
	//endDate: moment().add(1, 'day'),
			
}).on('apply.daterangepicker', function(ev, picker) {

	EditCheckRateLatterAvailable();
	NewchangeGreedData();
	NewchangeEditData();
	
	
});


$('.dateRangeEdit').daterangepicker({
	"autoApply": true,
	locale: {
	format: 'DD-MM-YYYY',
	separator: ' to ',	
	autoclose: true,	
	 },
	//minDate:new Date(),	
	//endDate: moment().add(1, 'day'),	
}).on('apply.daterangepicker', function(ev, picker) {
	getRateEdit();	
	changePaymentDate(picker.startDate.format('YYYY-MM-DD'));
	
});
//////////////////////////////Date picker and time - common function////////////////////////////


$('.pickerdate').datetimepicker({	
    format: 'dd-mm-yyyy',
    autoclose: true,
	minView: 2,
});

$('.pickertime').datetimepicker({
	pickDate: false,
    minuteStep: 15,
    pickerPosition: 'bottom-right',
    format: 'HH:ii p',
    autoclose: true,
    showMeridian: true,
    startView: 1,
    maxView: 1,	
	
});



    //Date range as a button
       $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(),moment()],
          'Tommorrow'   : [moment(),moment().add(1, 'days') ],
          'Next 7 Days' : [moment(),moment().add(6, 'days') ],
          'Next 30 Days': [moment(),moment().add(29, 'days')],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Next Month'  : [moment().add(1, 'month').startOf('month'),moment().add(1, 'month').endOf('month')]
        },
		locale: {
			format: 'DD-MM-YYYY',
			separator: ' to ',	
			autoclose: true,	
		 },	
		"opens" : "right",		
      },
      function (start, end) {
        $('#daterange-btn span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'))
      }
    )


////////////////////////get State- common function/////////////////////////////////////////
function getState(countryId,stateId,otherState){
 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxState.php',
			   data: 'countryId='+countryId+'&stateId='+stateId+'&otherState='+otherState, 
			   success: function (result) {				   
			     $('#state').empty();
				 $('#state').html(result);
				}
		});
}

////////////////////////get contact- common function/////////////////////////////////////////

function getCompanyGuestName(companyId,contactId){
 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxCompanyGuestName.php',
			   data: 'companyId='+companyId+'&contactId='+contactId, 
			   success: function (result) {				   
			     $('#id_contacts').empty();
				 $('#id_contacts').html(result);
				 
				}
		});
}
function getContact(companyId,contactId){	

 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxContacts.php',
			   data: 'companyId='+companyId+'&contactId='+contactId, 
			   success: function (result) {				   
			     $('#id_contacts').empty();
				 $('#id_contacts').html(result);
				 
				}
		});
}
//////////////////////////////save guest popup form common//////////////////////////////////////////////////////////
function saveGuestPopupform(){	
	var form=$("#guestpopupform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSaveGuest.php',
	   data: form.serialize(), 
	   success: function (result) {
		  if(result!=''){
		    $('#id_guest').empty();
			$('#id_guest').html(result);
			$('#showGuest').click();
			$("#guestpopupform")[0].reset();
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}

function saveBookedbyPopupform(){	
var companyId = $("#id_company").val();

	var form=$("#bookedbypopupform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSaveCustomer.php',
	   data: form.serialize()+"&id_company="+companyId, 
	   success: function (result) {
		  if(result!=''){
		    $('#id_contacts').empty();
			$('#id_contacts').html(result);
			$('#showbookedby').click();
			$("#bookedbypopupform")[0].reset();
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}

function saveRateContactPopupform(){	
var companyId = $("#company_id").val();

	var form=$("#bookedbypopupform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSaveCustomer.php',
	   data: form.serialize()+"&id_company="+companyId, 
	   success: function (result) {
		  if(result!=''){
		    $('#id_contacts').empty();
			$('#id_contacts').html(result);
			$('#showbookedby').click();
			$("#bookedbypopupform")[0].reset();
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}


function saveRateCustomerPopupform(){	
var companyId = $("#company_id").val();

	var form=$("#bookedbypopupform");
	if(form.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxRateSaveCustomer.php',
	   data: form.serialize()+"&id_company="+companyId, 
	   success: function (result) {
		  if(result!=''){
		    $('#id_contacts').empty();
			$('#id_contacts').html(result);
			$('#showbookedby').click();
			$("#bookedbypopupform")[0].reset();
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}

////////////////////////////////////////////////////////////
function savecontactPopupform(){
	var companyId = $("#id_company").val();
	
	var form2=$("#addRoomForm");
	var form=$("#contactpopupform");
	if(form.parsley().validate() && form2.parsley().validate()){
	$('.loading').show(); 
	$.ajax({
	   type: "POST",
	   url: 'ajax/ajaxSaveCustomer.php',
	   data: form.serialize()+"&id_company="+companyId, 
	   success: function (result) {
		  if(result!=''){
		    $('#id_contacts').empty();
			$('#id_contacts').html(result);
			$('#showContact').click();
			$("#contactpopupform")[0].reset();
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
	});
	return false;
	}
}

