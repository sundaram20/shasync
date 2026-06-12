<?php include_once("../config/auto_loader.php");

if($_REQUEST['tname']==''){
  checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'add');
}
else{
  checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'update');
}

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php /*?><?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?><?php */?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php //echo breadCrumbs(); ?>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
             <?php /*?>  <h3 class="box-title"><?php echo $_REQUEST['tname']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo encryptor('decrypt', $_REQUEST['tname']); ?> </span> <?php */?> 
            </div>
            <!-- /.box-header -->
            <!-- form start -->   
            <form name="phpForm" id="reportConfig_form23" method="post" enctype="multipart/form-data">
              <input type='hidden' value='<?=($_REQUEST['tname']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save">
              <?php 
			  
			  
                $tname = '';
                if(isset($_REQUEST['tname'])){
                  $disabled = "disabled='disabled'";
                  $tname = encryptor('decrypt', $_REQUEST['tname']);

              ?>
              <input type="hidden"  value="<?php echo $tname ?>" name="tname"  /> 

            <?php }  ?>
              <div class="form-group has-error">
                <?php if($_SESSION['errorMsg']){?>
                 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
              </div>
              <div class="box-body">
                <div class="form-group col-sm-4 col-md-4">
                  <?php
                    $resShowTables = mysqli_query($connNew,"SHOW TABLES");
                 ?> 
                  <select <?php echo $disabled; ?>class="select2 form-control" required="" name="id_report_config" id="id_report_config" onchange="showTableFields(this.value);">
                    <option value="">---Select Report Config---</option>
                    <?php
					$DataBaseTableName=	'Tables_in_'.$_SESSION['database'];
                      while($rowShowTables = mysqli_fetch_object($resShowTables)){
print_r($rowShowTables);
                        if($rowShowTables->$DataBaseTableName == $tname)
                          $selectedTbl = "selected='selected'";
                        else
                          $selectedTbl = "";

                        echo '<option '.$selectedTbl.' value="'.$rowShowTables->$DataBaseTableName.'">'.$rowShowTables->$DataBaseTableName.'</option>';
                      }
                    ?>
                  </select>
                </div>
                <div id="subMenuGrid" class="box-body">
                    
                </div>  
              </div>
              <div class="box-footer">                                       
                <input type='submit' value='<?=($_REQUEST['tname']==''?'Add':'Edit')?>' class="btn btn-primary" >
                &nbsp;&nbsp;&nbsp;&nbsp;
                 <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageReportConfig.php"); '>
             </div>
            </form>  
			    
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	

  <script type="text/javascript">

    function showTableFields(table_name){
     // alert(table_name);
      $.ajax({
        url:'ajax/ajaxReportConfigTable.php',
        method:'POST',
        data:'table_name='+table_name+'&id_report_config='+$("#id_report_config").val(),
        success:function(data){
          //console.log(data);
          //alert(data);
          $("#subMenuGrid").html(data);
        },
      });
    }

    $("#reportConfig_form23").submit(function(e){
      alert("hello");
   
    });  

    <?php
      if($tname!=''){
    ?>
      showTableFields($("#id_report_config").val());
    <?php } ?>


    function changeDisplay(display_order,table_field,fieldId){
    var table_name = $('#id_report_config').val();
    //alert(table_name+" "+table_field+" "+fieldId);
      $.ajax({
        url : 'ajax/ajaxUpdateDisplayReportConfigOrder.php',
        type : 'POST',
        data : {table_name:table_name,table_field:table_field,display_order:display_order,fieldId:fieldId},
        success : function(resp){
          alert(resp);
        }

      });
    }

  </script>						
<?php include_once("includes/footer.php")?>