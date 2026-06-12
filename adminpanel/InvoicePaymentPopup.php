<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content"> 
      <!-- <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>-->
      <div class="modal-body p-0">
        <div class="row">
          <div class="col-md-12">
            <div class="">
              <div class="box-body table-responsive" >
                <table id="myTableOrder1" class="table dataTable no-footer table-responsive" cellspacing="0"
                        style="">
                  <thead style="">
                    <tr >
                      <th></th>
                      <th style=""> Payment Mode.&nbsp;</th>
                      <th style="">Amount</th>
                      <th style="">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr id="trbgcolor">
                      <td style="width: 2.5%;"><div class="" aria-checked="false" aria-disabled="false"
                                >
                          <input type="checkbox"
                                  class="flat-red i-checks " name="checkboxpayamount"
                                  id="checkboxpayamount" value="" >
                        </div></td>
                      <td><div class="info-box paymentmode sales-info"> <span class="info-box-icon  paymode-span"> <img src="images/cashpay.png" class="img-responsive"  style="cursor:pointer;" title="Cash"> </span>
                          <div class="info-box-content"> <span class="info-box-text">CASH</span> </div>
                          <!-- /.info-box-content --> 
                        </div>
                        
                        <!-- /.info-box --></td>
                      <td><input type="text" class="form-control first-input billingamount_39498"
                               style="float: left;"
                                data-parsley-required="" data-parsley-errors-container="#payamountError"></td>
                      <td><input type="text" class="form-control first-input" placeholder="Remarks"
                                style="float: left;"
                                ></td>
                    </tr>
                    <!----------------------CARD PAYMENT------------------------------------>
                    
                    <tr style="background-color:#fff;" >
                      <td style="width: 2.5%;"><div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false"
                                >
                          <input type="checkbox"
                                  class="flat-red i-checks " name="checkboxpayamount"
                                  id="checkboxpayamount" value="" data-parsley-multiple="checkboxpayamount"
                                  >
                          <ins class="iCheck-helper"
                                  style=""></ins> </div></td>
                      <td><div class="sale-info"> 
                       
                          <div class="info-box sales-info"
                               > <span class="info-box-number">
                            <div class="box-body"
                                      style="width:2%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                              <div class="form-group">
                                <div>
                                  <label for="name" class="paymentlable">
                                  <div class="iradio_flat-green" aria-checked="false" aria-disabled="false">
                                    <input type="radio" class="flat-red" value="1"
                                                name="" id="cardtype"    style="position: absolute; opacity: 0;"
                                              >
                                    <ins class="iCheck-helper"
                                                style=""></ins> </div>
                                  </label>
                                </div>
                                <img class="visaimg"
                                          src="images/visa.png" alt="Visa"> </div>
                            </div>
                            <div class="box-body upibox" style="">
                              <div class="form-group" >
                                <div>
                                  <label for="name" class="paymentlable">
                                  <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                             >
                                    <input type="radio" class="flat-red" value="2"
                                                name="" id="cardtype"
                                                data-parsley-multiple=""
                                                >
                                    <ins class="iCheck-helper"
                                                ></ins> </div>
                                  </label>
                                </div>
                                <img class="upiimg" src="images/upi.png" style="cursor:pointer;" title="upi"> </div>
                            </div>
                            <div class="box-body neftbox">
                              <div class="form-group">
                                <div>
                                  <label for="name" class="paymentlable">
                                  <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                            >
                                    <input type="radio" class="flat-red" value="3"
                                                name="" id="cardtype"
                                                data-parsley-multiple=""
                                                style="">
                                    <ins class="iCheck-helper"
                                                style=""></ins> </div>
                                  </label>
                                </div>
                                <img class="neftimg" src="images/neft.png" style="cursor:pointer;" title="upi"> </div>
                            </div>
                            </span> </div>
                        </div></td>
                      <td style="width: 12.5%;"><input type="text"
                                class="form-control" 
                                value="0"
                                style="float: left;" data-parsley-required="" data-parsley-errors-container="#payamountError"></td>
                      <td style="width:100%;" class="d-flex"><input type="text" class="form-control first-input" placeholder="Remarks"
                                name="" id="" value="" style="float: left;">
                        <div>
                          <div class="bankbox">
                            <select class="form-control first-input select2 select2-hidden-accessible"
                                    style="width:100% !important;" name="" id=""
                                    tabindex="-1" aria-hidden="true">
                              <option value="0">--- Select Bank --- </option>
                              <!--select bank-->
                              <option value="1">Amex Credit </option>
                              <option value="2">Bank2 </option>
                            </select>
                          </div>
                        </div></td>
                    </tr>
                    <tr id="trbgcolor">
                      <td colspan="5" style="padding:0px !important;"><div id="rowGrid_39498"></div></td>
                    </tr>
                    
                    <!----------------------ONLINE TRANSFER ------------------------------------>
                    
                    <tr id="trbgcolor">
                      <td style="width: 2.5%;"><div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false"
                                    >
                          <input type="checkbox"
                                      class="flat-red i-checks " name="checkboxpayamount"
                                      id="checkboxpayamount" data-parsley-multiple="checkboxpayamount"
                                   >
                          <ins class="iCheck-helper"
                                     ></ins> </div></td>
                      <td><div class="info-box paymentmode sales-info"> <span class="info-box-icon  paymode-span"> <img
                                        src="images/cheq.png" style="cursor:pointer;" title="Cheque"> </span>
                          <div class="info-box-content"> <span class="info-box-text">CHEQUE</span> </div>
                          <!-- /.info-box-content --> 
                        </div></td>
                      <td><input type="text"  class="form-control"
                                  value="0" style="float: left;"
                                    data-parsley-required="" data-parsley-errors-container="#payamountError"></td>
                      <td><input type="text"  class="form-control first-input" placeholder="Remarks"
                                    name="" id="" value="" style="float: left;"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="modaldate">
          <label for="start_date">Payment Date:</label>
          <input type="text" class="form-control pickerdate_addreport" placeholder="Enter Enquiry date" id="enquiryDate" name="enquiryDate" value="<?php echo  $report_date; ?>" >
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
