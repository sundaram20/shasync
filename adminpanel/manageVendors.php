<?php include_once("../config/auto_loader.php");

// Status change
if($_REQUEST['action'] == 'change'){
    if($_REQUEST['activeId'] != ''){
        $statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
        $statusSql = "UPDATE `fs_vendors` SET `status` = '1', `last_modified` = '".currenDateTime()."' WHERE `id` = '".$statusId."'";
    }elseif($_REQUEST['inactiveId'] != ''){
        $statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
        $statusSql = "UPDATE `fs_vendors` SET `status` = '0', `last_modified` = '".currenDateTime()."' WHERE `id` = '".$statusId."'";
    }
    if(executeSql($statusSql)){
        $_SESSION['successMsg'] = 'Vendor status has been changed successfully.';
    }else{
        $_SESSION['errorMsg'] = 'Vendor status has not been changed.';
    }
}

// Bulk actions
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
    $activateIds = implode(',',$_REQUEST['ids']);
    $statusSql = "UPDATE `fs_vendors` SET `status` = '1', `last_modified` = '".currenDateTime()."' WHERE `id` IN (".addslashes($activateIds).")";
    if(executeSql($statusSql)){
        $_SESSION['successMsg'] = 'Selected records activated successfully.';
    }else{
        $_SESSION['errorMsg'] = 'Selected records could not be activated.';
    }
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
    $deactivateIds = implode(',',$_REQUEST['ids']);
    $statusSql = "UPDATE `fs_vendors` SET `status` = '0', `last_modified` = '".currenDateTime()."' WHERE `id` IN (".addslashes($deactivateIds).")";
    if(executeSql($statusSql)){
        $_SESSION['successMsg'] = 'Selected records inactivated successfully.';
    }else{
        $_SESSION['errorMsg'] = 'Selected records could not be inactivated.';
    }
}

// Vendor type labels
function getVendorTypeLabel($type){
    $types = [1=>'Hard Vendor', 2=>'Tally Partner', 3=>'Influencer', 4=>'CA'];
    return $types[$type] ?? '-';
}

// Search query
$sql = "SELECT * FROM `fs_vendors` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'";
if($_REQUEST['search_name'] != ''){
    $sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['search_company'] != ''){
    $sql .= " AND `company_name` LIKE '%".addslashes($_REQUEST['search_company'])."%'";
}
if($_REQUEST['vendor_type'] != ''){
    $sql .= " AND `vendor_type` = '".addslashes($_REQUEST['vendor_type'])."'";
}
if($_REQUEST['status'] != ''){
    $sql .= " AND `status` = '".addslashes($_REQUEST['status'])."'";
}
$sql .= " ORDER BY `date_created` DESC";

$db->query($sql);
$numRows = $db->num_rows();
$pagging = new pagingClass($sql, $setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Vendor Manager <small>Manage Vendors</small></h1>
        <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Vendors</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-default">
            <div class="form-group has-error" align="center">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
            </div>

            <div class="box-header with-border">
                <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small></h3>
                <div class="btn-group pull-right">
                    <a type="button" class="btn btn-success" href="editVendor.php">Add Vendor</a>
                </div>
            </div>

            <form name="searchForm" action="" method="get">
                <input type="hidden" value="1" name="searchFormSubmit" />
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vendor Name</label>
                                <input type="text" name="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" placeholder="Search by name"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" name="search_company" value="<?php echo trim($_REQUEST['search_company']);?>" class="form-control" placeholder="Search by company"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vendor Type</label>
                                <?php
                                $selType = $_REQUEST['vendor_type'];
                                echo '<select class="form-control select2" name="vendor_type">
                                    <option value="">All Types</option>
                                    <option value="1"'.($selType=='1'?' selected':'').'>Hard Vendor</option>
                                    <option value="2"'.($selType=='2'?' selected':'').'>Tally Partner</option>
                                    <option value="3"'.($selType=='3'?' selected':'').'>Influencer</option>
                                    <option value="4"'.($selType=='4'?' selected':'').'>CA</option>
                                </select>';
                                ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <?php
                                $sel1 = $_REQUEST['status']=='1' ? 'selected="selected"' : '';
                                $sel0 = $_REQUEST['status']=='0' ? 'selected="selected"' : '';
                                echo '<select class="form-control select2" name="status">
                                    <option value="">Both</option>
                                    <option '.$sel1.' value="1">Active</option>
                                    <option '.$sel0.' value="0">Inactive</option>
                                </select>';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <input name="Search" type="submit" class="btn btn-primary" value="Search" />
                    <a href="manageVendors.php" class="btn btn-default" style="margin-left:10px;">Clear</a>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Vendors List</h3>
                    </div>
                    <form name="listingForm" action="" method="post">
                        <input type="hidden" value="" name="act" />
                        <div class="box-body table-responsive">
                            <table id="example2" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%"><input type='checkbox' id="CheckAll"/> #</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if($total > 0){ $counter = 1;
                                    while($row = $db->fetch_object()){?>
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="<?=$row->id;?>"/> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.</td>
                                    <td><?=$row->name;?></td>
                                    <td><?=$row->mobile;?></td>
                                    <td><?=$row->email ?: '-';?></td>
                                    <td><?=getVendorTypeLabel($row->vendor_type);?></td>
                                    <td><?=$row->company_name ?: '-';?></td>
                                    <td>
                                        <?=$row->status=='1'
                                            ? '<span onclick="location.href=\'manageVendors.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>'
                                            : '<span onclick="location.href=\'manageVendors.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:red;cursor:pointer;">Inactive</span>';?>
                                    </td>
                                    <td>
                                        <img src="images/view_edit.gif" style="cursor:pointer;" title="View / Edit" onClick="window.location.href='editVendor.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';"/>
                                    </td>
                                </tr>
                                <?php }} ?>
                                <tr>
                                    <td align="left" colspan="8">
                                        <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right" colspan="8"><?php echo $pagging->getLinks();?></td>
                                </tr>
                                <?php if($total == 0){?>
                                <tr>
                                    <td height="200" align="center" colspan="8">---- No Record Found ----</td>
                                </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once("includes/footer.php")?>