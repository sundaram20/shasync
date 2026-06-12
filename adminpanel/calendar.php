<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("includes/header.php")?>
  <?php include_once("includes/left.php")?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Calender Manager <small>Event Calender</small> </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Calendar</li>
      </ol>
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
             
            				<div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="manageEvents.php" >Manage Events</a>
							 
							</div>
          </div>
        </div>

         
		  
          
        
        <div class="response"></div>
        <style>
		.response {
    height: 60px;
}

.success {
    background: #cdf3cd;
    padding: 10px 60px;
    border: #c3e6c3 1px solid;
    display: inline-block;
}
</style>
        <div class="col-md-3">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h4 class="box-title">Product </h4>
            </div>
            <div class="box-body"> 
              <!-- the events --> 
             				

				<?php

				$hotelDropDown = '<select class="form-control select2" id="hotelId" name="hotelId" onchange="SelectHotelWiseCal()";>

											    <option value="">Select Product</option>';

  $resCat = selectSql(TBL_HOTELS," where id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($_REQUEST['hotelId'] == $resultCat->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}

													$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $hotelDropDown .= '</select>';

											  ?>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Create Event</h3>
            </div>
            <div class="box-body">
              <div class="form-group">
                 <script src="http://code.jquery.com/jquery-latest.js"></script>
			 <script>
		function Toggle(id) {
			$DuphotelId = $('#DuphotelId').select2();
			if (document.getElementById(id).style.display == "none" || document.getElementById(id).style.display == "") {
				document.getElementById(id).style.display = "block";
			} else if (document.getElementById(id).style.display == "block") {
				document.getElementById(id).style.display = "none";
			} else {
				document.getElementById(id).style.display = "none";
			}
		}
		
		function  SelectHotelsList(HotelDuplicateInsert){
			var test = HotelDuplicateInsert;
		        $("div.desc").hide();
		        $("#cars" + test).show();
			}
		
</script>
<style>
#test01 {  padding-bottom:20px; padding-top: 10px;   }	

</style>
   
   <div id="test01">
		


<div id="myRadioGroup">
    <input type="radio" name="HotelDuplicateInsert"  id="HotelDuplicateInsert" onClick="SelectHotelsList(1);"   value="1"  />All Products

<input type="radio" name="HotelDuplicateInsert" id="HotelDuplicateInsert2" onClick="SelectHotelsList(2);"   value="2" />Select Products
 
   
   <div id="cars2" class="desc" style="display: none;" >
    
					
                
                
                     <style>
					 .select2-container {
    width: 200px !important;
    margin-bottom: 10px;
	
					 }
					 </style>

                     <br/>
                      <label for="DuphotelId">Product <font color="#FF0000">*</font></label>
                      <div class="input-group">
					  <?php $hotelDropDown1 = '<select class="form-control select2" name="DuphotelId[]" id="DuphotelId"  multiple="multiple" data-parsley-errors-container="#DuphotelError">
												  <option value="">Select Product</option>';
												  
												if(empty($_SESSION['hotel_access'])){
												$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
													/*	if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	*/
														$hotelDropDown1 .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $hotelDropDown1 .= '</select>';
												  ?>
					  <span id="DuphotelError"><?php echo $err_Duphotel;?></span> 
                        </div>
                      </div>
   
</div>
                    	
                  </div>
                <label for="booking_date">Start Date </label>
                <div class="input-group">
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start" name="end" value="<?php echo date('d-m-Y');?>">
                </div>
                
                <!-- /.input group -->
                <label for="booking_date">End Date </label>
                <div class="input-group">
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end" name="end" value="<?php echo date('d-m-Y');?>">
                </div>
              </div>
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;"> 
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div>
              <!-- /btn-group -->
              <div class="input-group">
                <input id="new-event" type="text" class="form-control" placeholder="Event Title" name="name">
                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat" onClick="addnewevents();">Add</button>
                </div>
                <!-- /btn-group --> 
              </div>
              <!-- /input-group --> 
            </div>
          </div>
        </div>
        
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-body no-padding"> 
              <!-- THE CALENDAR -->
              
              <div id="calendar"></div>
            </div>
            <!-- /.box-body --> 
          </div>
          <!-- /. box --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
    </section>
    <!-- /.content --> 
  </div>
  <!-- /.content-wrapper --> 
  
  <!-- ./wrapper -->
  <?php include_once("includes/footer.php")?>

<script>
// var hotelId=$('#hotelId').val();
$(document).ready(function () {
	var date = new Date();

  var d = date.getDate();

  var m = date.getMonth();

  var y = date.getFullYear();
	$('#calendar').fullCalendar();
	 var hotelId=$('#hotelId').val();
	
	var calendar = $('#calendar1').fullCalendar({
		
	
        editable: true,
		droppable : true, 
        events: "ajax/fetch-event1.php?selected_hotel_id="+hotelId ,
		
        displayEventTime: false,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
				
            } else {
                event.allDay = false;
				
            }
        },
        selectable: true,
		droppable : true,
        selectHelper: true,
        select: function (start, end, allDay) {
            var title = prompt('Event Title:');

            if (title) {
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                $.ajax({
                    url: 'ajax/add-event.php',
                    data: 'name=' + title + '&start=' + start + '&end=' + end,
                    type: "POST",
                    success: function (data) {
                        displayMessage("Added Successfully");
                    }
                });
                calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                true
                        );
            }
            calendar.fullCalendar('unselect');
        },
        
        editable: true,
        eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url: 'edit-event.php',
                        data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                        type: "POST",
                        success: function (response) {
                            displayMessage("Updated Successfully");
                        }
                    });
                },
        eventClick: function (event) {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
				//alert('111');
                $.ajax({
                    type: "POST",
                    url: "ajax/delete-event.php",
                    data: "&id=" + event.id,
                    success: function (response) {
                        if(parseInt(response) > 0) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                        }
                    }
                });
            }
        }

    });
	
	$('#hotelId').change(function () {
		//alert("");
        $('#calendar').fullCalendar('refetchEvents');
    });
	 /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
	window.currColorselected = currColor
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
	 	window.currColorselected = currColor
      //Add color effect to button
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
	
	
	
	
});
function displayMessage(message) {
	    $(".response").html("<div class='success'>"+message+"</div>");
    	setInterval(function() { $(".success").fadeOut(); }, 1000);
}

 function addnewevents(start, end, allDay) {
	 
  var title  = $('#new-event').val()
  
			//var title = prompt('Event Title:');
			var start = $("#start").val();
			var end = $("#end").val();
			var DuphotelId		=	$("#DuphotelId").val();
			var HotelDuplicateInsert	= $("#HotelDuplicateInsert").val();
			var HotelDuplicateInsert2	= $("#HotelDuplicateInsert2").val();
            if (title) {
              
                $.ajax({
                    url: 'ajax/add-event.php',
                    data: 'name=' + title + '&start=' + start + '&end=' + end + '&currColorselected=' + currColorselected+'&DuphotelId='+DuphotelId+'&HotelDuplicateInsert='+HotelDuplicateInsert+'&HotelDuplicateInsert2='+HotelDuplicateInsert2,
                    type: "POST",
                    success: function (data) {
							//$('#start').val('');
							//$('#end').val('');
							$('#new-event').val('');
                        displayMessage("Added Successfully");
						
                    },
					complete:function(){
						window.location.href='calendar.php';
					},
                });
				
                calendar.fullCalendar('renderEvent',
                        {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                true
                        );
            }
               
       
			calendar.fullCalendar('unselect');
        }
function reportSelection(op,id){
     		if(document.getElementById(id).options[0].selected == true){
    			console.log("selected");
    			selectAll(id,true);
    		}
    	}
	
function SelectHotelWiseCal(hotelId){
	
	 var hotelId  = $('#hotelId').val();
	
 var NeweventSources  = 
 		{
        url: "ajax/fetch-event.php?selected_hotel_id="+hotelId,
        type: 'POST',       
    }

  	$('#calendar').fullCalendar('removeEvents');
  	$('#calendar').fullCalendar('removeEventSource',NeweventSources);
    $('#calendar').fullCalendar('addEventSource', NeweventSources);
    	 
		
   
}	
</script>
</body>
</html>
