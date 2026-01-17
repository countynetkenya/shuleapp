
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-wrench"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_quickbooks')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-12">
                  <div class="nav-tabs">
                      <ul class="nav nav-tabs">
                        <li <?=$tab == 'balances' ? '' : 'class="active"'?>><a data-toggle="tab" href="#sync" aria-expanded="true"><?=$this->lang->line('sync_records')?></a></li>
                        <!--<li><a data-toggle="tab" href="#recover" aria-expanded="true"><?=$this->lang->line('recover_records')?></a></li>-->
                        <li><a data-toggle="tab" href="#logs" aria-expanded="true"><?=$this->lang->line('logs')?></a></li>
                        <li  <?=$tab == 'balances' ? 'class="active"' : ''?>><a data-toggle="tab" href="#balances" aria-expanded="true"><?=$this->lang->line('customer_balances')?></a></li>
                      </ul>

                      <div class="tab-content">
                        <div class="tab-pane <?=$tab == 'balances' ? '' : 'active'?>" id="sync" role="tabpanel">
                          <br>
                          <div class="row">
                            <div class="col-sm-12">
                              <h2>Sync Records</h2>
                              <h6>Sync invoices, credit notes and payments.</h6>
                              <div class="margin-bottom">
                                <div class="btn-group">
                                  <?php if (empty($config['sessionAccessToken'])) {?>
                                      <input onclick="oauth.loginPopup()" id="connectQuickBooksButton" type="button" class="btn btn-warning" value="<?=$this->lang->line("connect_quickbooks")?>" >
                                  <?php } else {
                                    if (now() > $config['sessionAccessTokenExpiry']) {?>
                                      <a href="<?=base_url("quickbooks/refreshToken")?>" class="btn btn-warning"><?=$this->lang->line("reconnect_quickbooks")?></a>
                                    <?php } else {?>
                                      <input id="syncButton" type="button" class="btn btn-success" value="<?=$this->lang->line('update')?>">
                                    <?php }
                                  }?>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="date from" class="control-label">
                                          <?=$this->lang->line('from')?>
                                      </label>
                                    <input name="dateFrom" id="dateFrom" type="date" class="form-control" value="<?=$set_dateFrom?>">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                      <label for="date to" class="control-label">
                                          <?=$this->lang->line('to')?>
                                      </label>
                                    <input name="dateTo" id="dateTo" type="date" class="form-control" value="<?=$set_dateTo?>">
                                  </div>
                                </div>
                              </div>

                              <div class="nav-tabs-custom">
                                  <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#invoices" aria-expanded="true"><?=$this->lang->line('invoices')?></a></li>
                                    <li><a data-toggle="tab" href="#creditmemos" aria-expanded="true"><?=$this->lang->line('creditmemos')?></a></li>
                                    <li><a data-toggle="tab" href="#payments" aria-expanded="true"><?=$this->lang->line('payments')?></a></li>
                                  </ul>

                                  <div class="tab-content">
                                    <div class="tab-pane active" id="invoices" role="tabpanel">
                                      <br>
                                      <div class="row">
                                          <div class="col-sm-12">
                                            <table id="example6" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="checkAll" title="Select All"/></th>
                                                        <th><?=$this->lang->line('student')?></th>
                                                        <th><?=$this->lang->line('class')?></th>
                                                        <th><?=$this->lang->line('invoice_amount')?></th>
                                                        <th><?=$this->lang->line('invoice_date')?></th>
                                                        <th><?=$this->lang->line('posted')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(customCompute($invoices)) {$i = 1; foreach($invoices as $invoice) { ?>
                                                        <tr data-id="<?=$invoice->invoiceID?>">
                                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                                <?php echo $invoice->invoiceID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('student')?>">
                                                                <?php echo $invoice->name; ?>
                                                            </td>

                                                             <td data-title="<?=$this->lang->line('class')?>">
                                                                <?php echo $invoice->classesID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_total')?>">
                                                                <?php echo number_format($invoice->amount, 2); ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_date')?>">
                                                                <?php echo date("d M Y", strtotime($invoice->date)) ; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('posted')?>">
                                                              <?= ($invoice->quickbooks_status == 1) ? '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>' : '<i class="fa fa-exclamation" aria-hidden="true" style="color:red"></i>'?>                                                   </td>
                                                            </td>
                                                        </tr>
                                                    <?php $i++; }} ?>
                                                </tbody>
                                            </table>
                                          </div>
                                      </div>
                                    </div>

                                    <div class="tab-pane" id="creditmemos" role="tabpanel">
                                      <br>
                                      <div class="row">
                                          <div class="col-sm-12">
                                            <table id="example7" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="checkAll" title="Select All"/></th>
                                                        <th><?=$this->lang->line('student')?></th>
                                                        <th><?=$this->lang->line('class')?></th>
                                                        <th><?=$this->lang->line('invoice_amount')?></th>
                                                        <th><?=$this->lang->line('invoice_date')?></th>
                                                        <th><?=$this->lang->line('posted')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(customCompute($creditmemos)) {$i = 1; foreach($creditmemos as $creditmemo) { ?>
                                                        <tr data-id="<?=$creditmemo->creditmemoID?>">
                                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                                <?php echo $creditmemo->creditmemoID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('student')?>">
                                                                <?php echo $creditmemo->name; ?>
                                                            </td>

                                                             <td data-title="<?=$this->lang->line('class')?>">
                                                                <?php echo $creditmemo->classesID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_total')?>">
                                                                <?php echo number_format($creditmemo->amount, 2); ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_date')?>">
                                                                <?php echo date("d M Y", strtotime($creditmemo->date)) ; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('posted')?>">
                                                              <?= ($creditmemo->quickbooks_status == 1) ? '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>' : '<i class="fa fa-exclamation" aria-hidden="true" style="color:red"></i>'?>                                                   </td>
                                                            </td>
                                                        </tr>
                                                    <?php $i++; }} ?>
                                                </tbody>
                                            </table>
                                          </div>
                                      </div>
                                    </div>

                                    <div class="tab-pane" id="payments" role="tabpanel">
                                      <br>
                                      <div class="row">
                                          <div class="col-sm-12">
                                            <table id="example8" class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="checkAll" title="Select All"/></th>
                                                        <th><?=$this->lang->line('student')?></th>
                                                        <th><?=$this->lang->line('class')?></th>
                                                        <th><?=$this->lang->line('invoice_amount')?></th>
                                                        <th><?=$this->lang->line('invoice_date')?></th>
                                                        <th><?=$this->lang->line('posted')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(customCompute($payments)) {$i = 1; foreach($payments as $payment) { ?>
                                                        <tr data-id="<?=$payment->paymentID?>">
                                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                                <?php echo $payment->paymentID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('student')?>">
                                                                <?php echo $payment->name; ?>
                                                            </td>

                                                             <td data-title="<?=$this->lang->line('class')?>">
                                                                <?php echo $payment->classesID; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_total')?>">
                                                                <?php echo number_format($payment->paymentamount, 2); ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('invoice_date')?>">
                                                                <?php echo date("d M Y", strtotime($payment->paymentdate)) ; ?>
                                                            </td>

                                                            <td data-title="<?=$this->lang->line('posted')?>">
                                                              <?= ($payment->quickbooks_status == 1) ? '<i class="fa fa-check" aria-hidden="true" style="color:green"></i>' : '<i class="fa fa-exclamation" aria-hidden="true" style="color:red"></i>'?>                                                   </td>
                                                            </td>
                                                        </tr>
                                                    <?php $i++; }} ?>
                                                </tbody>
                                            </table>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                            </div> <!-- col-sm-12 -->
                          </div>
                        </div>

                        <div class="tab-pane" id="recover" role="tabpanel">
                          <br>
                          <div class="row">
                            <div class="col-sm-12">
                              <h2>Recover Records</h2>
                              <h6>Recover invoices, credit notes and payments.</h6>
                              <br />
                              <form>
                                  <div class="row">
                                      <div class="col-md-9">
                                          <div class="row">
                                            <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="start date" class="control-label">
                                                      <?=$this->lang->line('start_date')?>
                                                  </label>
                          											<input name="startdate" id="startdate" type="date" class="form-control" value="<?=$set_date?>">
                          										</div>
                          									</div>

                                            <div class="col-md-6">
                                              <div class="form-group">
                                                  <label for="end date" class="control-label">
                                                      <?=$this->lang->line('end_date')?>
                                                  </label>
                          											<input name="enddate" id="enddate" type="date" class="form-control" value="<?=$set_date?>">
                          										</div>
                          									</div>
                                          </div>
                                      </div>

                                      <div class="col-md-3 col-xs-12">
                                        <div class="row">
                                          <div class="col-md-12 col-xs-12">
                                            <div class="form-group">
                                              <?php if (empty($config['sessionAccessToken'])) {?>
                                                  <input onclick="oauth.loginPopup()" id="connectQuickBooksButton" type="button" class="btn btn-warning col-md-12 col-xs-12 global_payment_search" value="<?=$this->lang->line("connect_quickbooks")?>" >
                                              <?php } else {
                                                if (now() > $config['sessionAccessTokenExpiry']) {?>
                                                  <a href="<?=base_url("quickbooks/refreshToken")?>" class="btn btn-warning col-md-12 col-xs-12 global_payment_search"><?=$this->lang->line("reconnect_quickbooks")?></a>
                                                <?php } else {?>
                                                  <input id="recoverButton" type="button" class="btn btn-success col-md-12 col-xs-12 global_payment_search" value="<?=$this->lang->line('recover')?>">
                                                <?php }
                                              }?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                              </form>
                              <div class="margin-bottom">

                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="tab-pane" id="logs" role="tabpanel">
                          <br>
                          <div class="row">
                            <div class="col-sm-12">
                              <h2>QuickBooks Logs</h2>
                              <h6>Logs of QuickBooks queries.</h6>
                              <!--<a href="quickbooks/download" type="button" class="btn btn-success"><?=$this->lang->line('download')?></a>-->
                              <br />
                              <form>
                                  <div class="row">
                                      <div class="col-md-10">
                                          <div class="row">
                                            <div class="col-md-3">
                                              <div class="form-group">
                                                  <label for="date from" class="control-label">
                                                      Date
                                                  </label>
                          											<input name="date" id="date" type="date" class="form-control" value="<?=$set_date?>">
                          										</div>
                          									</div>

                                            <div class="col-md-3">
                                              <div class="form-group" >
                                                  <label for="status">
                                                      <?=$this->lang->line("status")?>
                                                  </label>
                                                  <?php
                                                      $statusArray = array('' => $this->lang->line("select_status"), 'OK' => $this->lang->line("ok"), 'ERROR' => $this->lang->line("error"));

                                                      echo form_dropdown("status", $statusArray, '', "id='status' class='form-control select2'");
                                                  ?>
                                              </div>
                                            </div>
                                          </div>
                                      </div>
                                  </div>
                              </form>

                              <table id="example4" class="table table-striped table-bordered table-hover dataTable no-footer">
                                  <thead>
                                      <tr>
                                          <th><?=$this->lang->line('slno')?></th>
                                          <th><?=$this->lang->line('ip')?></th>
                                          <th><?=$this->lang->line('request')?></th>
                                          <th><?=$this->lang->line('status')?></th>
                                          <th><?=$this->lang->line('time')?></th>
                                          <th><?=$this->lang->line('message')?></th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php if(customCompute($logs)) {$i = 1; foreach($logs as $log) { ?>
                                          <tr>
                                              <td data-title="<?=$this->lang->line('slno')?>">
                                                  <?php echo $log->quickbookslogID; ?>
                                              </td>

                                              <td data-title="<?=$this->lang->line('ip')?>">
                                                  <?php echo $log->ip; ?>
                                              </td>

                                               <td data-title="<?=$this->lang->line('request')?>">
                                                  <?php echo $log->request; ?>
                                              </td>

                                              <td data-title="<?=$this->lang->line('status')?>">
                                                  <?php echo $log->status; ?>
                                              </td>

                                              <td data-title="<?=$this->lang->line('time')?>">
                                                  <?php echo $log->logged_date ." ". $log->logged_time;?>
                                              </td>

                                              <td data-title="<?=$this->lang->line('message')?>">
                                                  <?php echo $log->message; ?>
                                              </td>
                                          </tr>
                                      <?php $i++; }} ?>
                                  </tbody>
                              </table>
                            </div>
                          </div>
                        </div>

                        <div class="tab-pane <?=$tab == 'balances' ? 'active' : ''?>" id="balances" role="tabpanel">
                          <br>
                          <div class="row">
                            <div class="col-sm-12">
                              <h2>Customer Balances</h2>
                              <h6>A comparison between Shulelabs customer balance vis-à-vis QuickBooks customer balance</h6>
                              <br/>
                              <div class="col-sm-12">

                                  <form method="POST">
                                      <div class="row">
                                          <div class="col-md-10">
                                              <div class="row">

                                                  <div class="col-md-4">
                                                      <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                                          <label for="classesID" class="control-label">
                                                              <?=$this->lang->line('class')?> <span class="text-red">*</span>
                                                          </label>
                                                          <?php
                                                              $classArray = array("0" => $this->lang->line("select_classes"));
                                                              foreach ($classes as $classa) {
                                                                  $classArray[$classa->classesID] = $classa->classes;
                                                              }
                                                              echo form_dropdown("classesID", $classArray, set_value("classesID", $set_classesID), "id='classesID' class='form-control select2'");
                                                          ?>
                                                      </div>
                                                  </div>

                                                  <div class="col-md-4">
                                                      <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                                          <label for="sectionID" class="control-label"><?=$this->lang->line('section')?></label>
                                                          <?php
                                                              $sectionArray = array('0' => $this->lang->line("select_section"));
                                                              if($sections != 0) {
                                                                  foreach ($sections as $section) {
                                                                      $sectionArray[$section->sectionID] = $section->section;
                                                                  }
                                                              }

                                                              echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $set_sectionID), "id='sectionID' class='form-control select2'");
                                                          ?>
                                                      </div>
                                                  </div>

                                                  <div class="col-md-4">
                                                      <div class="<?php echo form_error('studentID') ? 'form-group has-error' : 'form-group'; ?>" >
                                                          <label for="studentID" class="control-label">
                                                              <?=$this->lang->line('student')?> <!--<span class="text-red"></span>-->
                                                          </label>

                                                          <?php
                                                              $studentArray = array('0' => $this->lang->line("select_student"));
                                                              if(customCompute($students)) {
                                                                  foreach ($students as $student) {
                                                                      $studentArray[$student->srstudentID] = $student->srname.' - '.$this->lang->line('register_no').' - '.$student->srstudentID;
                                                                  }
                                                              }

                                                              echo form_dropdown("studentID", $studentArray, set_value("studentID", $set_studentID), "id='studentID' class='form-control select2'");
                                                          ?>
                                                     </div>
              									                 </div>
                                              </div>
                                          </div>

                                          <div class="col-md-2 col-xs-12">
                                              <div class="row">
                                                  <div class="col-md-12 col-xs-12">
                                                      <div class="form-group" >
                                                          <button type="submit" class="btn btn-success col-md-12 col-xs-12 global_payment_search"><?=$this->lang->line('submit')?></button>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </form>

                                  <div class="col-sm-12" >
                                      <?php if(customCompute($balances)) { ?>
                                        <div class="row">
                                          <div class="col-xs-12">
                                            <div class="table-responsive">
                                              <table class="table product-style">
                                                <thead>
                                  								<tr>
                                  									<th><?=$this->lang->line('slno')?></th>
                                  									<th><?=$this->lang->line('student')?></th>
                                  									<th><?=$this->lang->line('shulelabs')?></th>
                                  									<th><?=$this->lang->line('quickbooks')?></th>
                                  								</tr>
                                                </thead>
                                                <tbody>
                                                  <?php if(customCompute($students)) {
                                                    foreach($students as $student) { ?>
                                                      <tr>
                                                        <td><?=$student->srstudentID?></td>
                                                        <td><?=$student->name?></td>
                                                        <td><?=number_format($balances[$student->srstudentID]['shulelabs'],2)?></td>
                                                        <td><?=number_format($balances[$student->srstudentID]['quickbooks'],2)?></td>
                                                      </tr>
                                                  <?php } }?>
                                                </tbody>
                                              </table>
                                            </div>
                                          </div>
                                        </div>
                                      <?php }?>
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                  </div>
               </div> <!-- col-sm-12 -->
            </div> <!-- col-sm-12 -->
        </div> <!-- row -->
    </div> <!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
  $(document).ready(function () {
    $('.select2').select2();

    $('#startdate, #enddate').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });

    $(document).on('click', '#syncButton', function() {
      $(this).attr('disabled', 'disabled');
      $.ajax({
          type: 'POST',
          url: "<?=base_url('quickbooks/sync')?>",
          async: true,
          dataType: "html",
          success: function(data) {
              var response = JSON.parse(data);
              errrorLoader(response);
          },
          cache: false,
          contentType: false,
          processData: false
      });
    });

    $(document).on('click', '#recoverButton', function() {
      $(this).attr('disabled', 'disabled');
      var start = $("#startdate").val();
      var end = $("#enddate").val();
      $.ajax({
          type: 'POST',
          url: "<?=base_url('quickbooks/sync')?>",
          data: {"start" : start, "end" : end},
          async: true,
          dataType: "html",
          success: function(data) {
              var response = JSON.parse(data);
              errrorLoader(response);
          },
          cache: false,
          contentType: false,
          processData: false
      });
    });

    function errrorLoader(response) {
        if(response.status) {
            window.location = "<?=base_url("quickbooks/index")?>";
        } else {
            $('#syncButton').removeAttr('disabled');
            $('#recoverButton').removeAttr('disabled');
            $.each(response.error, function(index, val) {
                toastr["error"](val)
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
        }
    }

    $("#classesID").change(function() {
        var id = $(this).val();
        if(parseInt(id)) {
            if(id === '0') {
                $('#sectionID').val(0);
                $('#studentID').val(0);
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('student_statement/sectioncall')?>",
                    data: {"id" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#sectionID').html(data);
                    }
                });

                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('student_statement/studentcall')?>",
                    data: {"classesID" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#studentID').html(data);
                    }
                });
            }
        }
    });

    $("#sectionID").change(function() {
        var id = $(this).val();
        var classesID = $('#classesID').val();
        if(parseInt(id)) {
            if(id === '0') {
                $('#sectionID').val(0);
            } else {
                if(classesID === '0') {
                    $('#classesID').val(0);
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "<?=base_url('student_statement/studentcall')?>",
                        data: {"classesID" : classesID, "sectionID" : id},
                        dataType: "html",
                        success: function(data) {
                           $('#studentID').html(data);
                        }
                    });
                }
            }
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student_statement/studentcall')?>",
                data: {"classesID" : classesID, "sectionID" : id},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });
  });

  var url = '<?php echo $authUrl; ?>';

  var OAuthCode = function(url) {

      this.loginPopup = function (parameter) {
          this.loginPopupUri(parameter);
      }

      this.loginPopupUri = function (parameter) {

          // Launch Popup
          var parameters = "location=1,width=800,height=650";
          parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;

          var win = window.open(url, 'connectPopup', parameters);
          var pollOAuth = window.setInterval(function () {
              try {

                  if (win.document.URL.indexOf("code") != -1) {
                      window.clearInterval(pollOAuth);
                      win.close();
                      location.reload();
                  }
              } catch (e) {
                  console.log(e)
              }
          }, 100);
      }
  }

  var oauth = new OAuthCode(url);

</script>
