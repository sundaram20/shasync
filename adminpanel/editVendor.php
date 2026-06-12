<?php include_once("../config/auto_loader.php");

if($_REQUEST['Save']){
    $err = 0;
    
    $name       = addslashes($_REQUEST['vendor_name']);
    $mobile     = addslashes($_REQUEST['vendor_mobile']);
    $email      = addslashes($_REQUEST['vendor_email']);
    $type       = addslashes($_REQUEST['vendor_type']);
    $company    = addslashes($_REQUEST['vendor_company']);
    $shop       = addslashes($_SESSION['shop']);

    if($err == 0){
        if($_REQUEST['Save'] == 'Add'){
            $addSql = "INSERT INTO `fs_vendors` SET
                        `name` = '$name',
                        `mobile` = '$mobile',
                        `email` = '$email',
                        `vendor_type` = '$type',
                        `company_name` = '$company',
                        `id_shop` = '$shop',
                        `status` = '".addslashes($_REQUEST['status'])."',
                        `date_created` = '".currenDateTime()."',
                        `last_modified` = '".currenDateTime()."'";
            if(executeSql($addSql)){
                $_SESSION['successMsg'] = 'Vendor has been added successfully.';
                header("location:manageVendors.php");
                exit;
            }else{
                $_SESSION['errorMsg'] = 'Unable to add vendor.';
            }
        }else{
            $editId = addslashes(encryptor('decrypt',$_REQUEST['eId']));
            $editSql = "UPDATE `fs_vendors` SET
                        `name` = '$name',
                        `mobile` = '$mobile',
                        `email` = '$email',
                        `vendor_type` = '$type',
                        `company_name` = '$company',
						`status` = '".addslashes($_REQUEST['status'])."',
                        `last_modified` = '".currenDateTime()."'
                        WHERE `id` = '$editId' AND `id_shop` = '$shop'";
            if(executeSql($editSql)){
                $_SESSION['successMsg'] = 'Vendor has been updated successfully.';
                header("location:manageVendors.php");
                exit;
            }else{
                $_SESSION['errorMsg'] = 'Unable to update vendor.';
            }
        }
    }
}

// Load existing data for edit
if(!empty($_REQUEST['eId']) && $_REQUEST['action'] == 'edit'){
    $sql = "SELECT * FROM `fs_vendors` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
    $db->query($sql);
    if($db->num_rows() > 0){
        $row = $db->fetch_object();
    }
}
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $_REQUEST['eId']=='' ? 'Add' : 'Edit'; ?> Vendor <small>Vendor Manager</small></h1>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="manageVendors.php">Manage Vendors</a></li>
            <li class="active"><?php echo $_REQUEST['eId']=='' ? 'Add' : 'Edit'; ?> Vendor</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $_REQUEST['eId']=='' ? 'Add' : 'Edit'; ?> Vendor</h3>
            </div>

            <div class="form-group has-error" align="center">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
            </div>

            <form name="vendorForm" id="vendorForm" method="post" action="" data-parsley-validate autocomplete="off">
                <input type="hidden" name="eId" value="<?php echo $_REQUEST['eId'];?>">
                <input type="hidden" name="Save" value="<?php echo $_REQUEST['eId']=='' ? 'Add' : 'Edit'; ?>">

                <div class="box-body">
                    <div class="row">

                        <div class="form-group col-md-4">
                            <label>Name <font color="#FF0000">*</font></label>
                            <input type="text" class="form-control" placeholder="Enter Vendor Name"
                                name="vendor_name" id="vendor_name"
                                value="<?php echo stripslashes($row->name);?>"
                                data-parsley-required>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Mobile <font color="#FF0000">*</font></label>
                            <input type="text" class="form-control" placeholder="Enter Mobile"
                                name="vendor_mobile" id="vendor_mobile"
                                value="<?php echo stripslashes($row->mobile);?>"
                                data-parsley-required
                                data-parsley-type="digits"
                                data-parsley-length="[10,10]">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email"
                                name="vendor_email" id="vendor_email"
                                value="<?php echo stripslashes($row->email);?>"
                                data-parsley-type="email">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Type <font color="#FF0000">*</font></label>
                            <select class="form-control select2" name="vendor_type" id="vendor_type" data-parsley-required>
                                <option value="">Select Type</option>
                                <option value="1" <?php echo $row->vendor_type=='1' ? 'selected' : '';?>>Hard Vendor</option>
                                <option value="2" <?php echo $row->vendor_type=='2' ? 'selected' : '';?>>Tally Partner</option>
                                <option value="3" <?php echo $row->vendor_type=='3' ? 'selected' : '';?>>Influencer</option>
                                <option value="4" <?php echo $row->vendor_type=='4' ? 'selected' : '';?>>CA</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Company Name</label>
                            <input type="text" class="form-control" placeholder="Enter Company Name"
                                name="vendor_company" id="vendor_company"
                                value="<?php echo stripslashes($row->company_name);?>">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Status</label>
                            <select class="form-control select2" name="status">
                                <option value="1" <?php echo ($row->status=='1' || $_REQUEST['eId']=='') ? 'selected' : '';?>>Active</option>
                                <option value="0" <?php echo $row->status=='0' ? 'selected' : '';?>>Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="box-footer">
                    <input type="submit" class="btn btn-primary" value="<?php echo $_REQUEST['eId']=='' ? 'Add' : 'Update'; ?>">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" value="Cancel" class="btn btn-default" onclick="location.replace('manageVendors.php');">
                </div>
            </form>
        </div>
    </section>
</div>

<style>
.select2-container { width:100%!important; }
</style>

<?php include_once("includes/footer.php")?>