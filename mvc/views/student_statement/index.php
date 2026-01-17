<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-balance-scale"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_global_payment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="col-sm-12">

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="classesID" class="control-label">
                                                <?=$this->lang->line('global_classes')?>
                                            </label>
                                            <?php
                                                $classArray = array("0" => $this->lang->line("global_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $classArray[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $classArray, set_value("classesID", $set_classesID), "id='classesID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="sectionID" class="control-label"><?=$this->lang->line('global_section')?></label>
                                            <?php
                                                $sectionArray = array('0' => $this->lang->line("global_select_section"));
                                                if($sections != 0) {
                                                    foreach ($sections as $section) {
                                                        $sectionArray[$section->sectionID] = $section->section;
                                                    }
                                                }

                                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $set_sectionID), "id='sectionID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('studentID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="studentID" class="control-label">
                                                <?=$this->lang->line('global_student')?> <!--<span class="text-red"></span>-->
                                            </label>

                                            <?php
                                                $studentArray = array('0' => $this->lang->line("global_select_student"));
                                                if(customCompute($students)) {
                                                    foreach ($students as $student) {
                                                        $studentArray[$student->srstudentID] = $student->srname.' - '.$this->lang->line('global_register_no').' - '.$student->srstudentID;
                                                    }
                                                }

                                                echo form_dropdown("studentID", $studentArray, set_value("studentID", $set_studentID), "id='studentID' class='form-control select2'");
                                            ?>
                                        </div>
									                  </div>

                                    <?php if($usertypeID != 4 && $usertypeID != 3) {?>
									                  <div class="col-md-3">
										                    <div class="<?php echo form_error('parentID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="parentID" class="control-label">
                                                <?=$this->lang->line('global_parent')?> <!--<span class="text-red">*</span>-->
                                            </label>

                                            <?php
                                                $parentArray = array('0' => $this->lang->line("global_select_parent"));
                                                if(customCompute($parents)) {
                                                    foreach ($parents as $parent) {
                                                        $parentArray[$parent->parentsID] = $parent->name;
                                                    }
                                                }

                                                echo form_dropdown("parentID", $parentArray, set_value("parentID", $set_parentID), "id='parentID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>
                                  <?php }?>
                                </div>

                								<div class="row">
                                    <div class="col-md-3">
                                          <div class="form-group" >
                                              <label for="schoolYearID" class="control-label"><?=$this->lang->line("global_schoolyear")?></label>

                                              <?php
                          												$schoolYearArray = array('0' => $this->lang->line('global_select_schoolyear'));
                          												foreach ($schoolYears as $schoolYear) {
                          													$schoolYearArray[$schoolYear->schoolyearID] = $schoolYear->schoolyear;
                          												}
                                                  echo form_dropdown("schoolYearID", $schoolYearArray, set_value("schoolYearID", $set_schoolYearID), "id='schoolYearID' class='form-control select2'");
                                              ?>
                                          </div>
  									                </div>

                                    <div class="col-md-3">
                                      <div class="form-group" >
                                        <label for="schooltermID">
                                          <?=$this->lang->line("global_schooltermID")?>
                                        </label>
                                        <?php
                                            $termsArray = array('0' => $this->lang->line("global_select_schoolterm"));
                                            if(customCompute($terms)) {
                                                foreach ($terms as $term) {
                                                    $termsArray[$term->schooltermID] = $term->schooltermtitle;
                                                }
                                            }
                                            echo form_dropdown("schooltermID", $termsArray, set_value("schooltermID", $set_schooltermID), "id='schooltermID' class='form-control select2'");
                                        ?>
                                      </div>

                                    </div>

                                    <div class="col-md-3">
                                          <div class="form-group">
                                              <label for="date from" class="control-label">
                                                <?=$this->lang->line("global_from")?>
                                              </label>
  											                      <input name="dateFrom" id="dateFrom" type="date" class="form-control" value="<?=$set_dateFrom?>">
  										                    </div>
  									                </div>

                                    <div class="col-md-3">
                                          <div class="form-group">
                                              <label for="date to" class="control-label">
                                                <?=$this->lang->line("global_to")?>
                                              </label>
  											                      <input name="dateTo" id="dateTo" type="date" class="form-control" value="<?=$set_dateTo?>">
  										                    </div>
  									                </div>
								                 </div>
                            </div>

                            <div class="col-md-2 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12 col-xs-12 global_payment_search"><?=$this->lang->line('global_payment_search')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-12" >
                    <?php if(customCompute($statements)) { ?>

						<div class="well">
        <div class="row">

            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('global_print')?> / Save as PDF </button>
				<button class="btn-cs btn-sm-cs" onclick="javascript:csvDiv('printablediv')"> Download CSV </button>
			</div>
        </div>
    </div>

    <div id="printablediv">
    	<section class="content invoice" >
		<?php if(customCompute($statements)) {
			foreach ($statements as $student) {?>
    		<div class="row">
    		    <div class="col-xs-12">
    		        <h2 class="page-header">
    		            <?php
    	                    if($siteinfos->photo) {
    		                    $array = array(
    		                        "src" => base_url('uploads/images/'.$siteinfos->photo),
    		                        'width' => '25px',
    		                        'height' => '25px',
    		                        'class' => 'img-circle',
                                    'style' => 'margin-top:-10px',
    		                    );
    		                    echo img($array);
    		                }
    	                ?>
    	                <?php  echo $siteinfos->sname; ?>
    		            <small class="pull-right"><?=$this->lang->line('global_date').' : '.date('d M Y')?></small>
    		        </h2>
    		    </div><!-- /.col -->
    		</div>
			<h3 style="text-align:center">Student Statement</h3>
    		<div class="row invoice-info">
    		    <div class="col-sm-4 invoice-col" style="font-size: 16px;">
    				<?php echo $this->lang->line("global_from"); ?>
    				<address>
    					<strong><?=$siteinfos->sname?></strong><br>
    					<?=$siteinfos->address?><br>
    					<?php echo $this->lang->line("global_phone_number"); ?> : <?=$siteinfos->phone?><br>
					    <?php echo $this->lang->line("global_email"); ?> : <?=$siteinfos->email?><br>
    				</address>

    		    </div><!-- /.col -->
    		    <div class="col-sm-4 invoice-col" style="font-size: 16px;">
    	        	<?php echo $this->lang->line("global_to"); ?>
    	        	<address>
    	        		<strong><?=$student->name?></strong><br>
    	        		<?=$this->lang->line("global_classes"). " : ". $student->classes?><br>
    	        		<?=$this->lang->line("global_register_no"). " : ". $student->studentID?><br>
                  <?php echo ($set_schoolYearID > 0) ? $this->lang->line("global_schoolyear") ." : ". $schoolyear->schoolyeartitle : "" ?>
    	        	</address>
    		    </div><!-- /.col -->
            <div class="col-sm-4 invoice-col" style="font-size: 16px;">
    	        	<?php echo $this->lang->line("global_parent"); ?>
    	        	<address>
    	        		<strong><?=$guardians[$student->parentID]['name']?></strong><br>
    	        		<?=$this->lang->line("global_phone_number"). " : ". $guardians[$student->parentID]['phone']?><br>
                  <?=$this->lang->line("global_email"). " : ". $guardians[$student->parentID]['email']?><br>
    	        	</address>
    		    </div><!-- /.col -->
    		</div>
            <br />

			<h3><?=$student->name?></h3>
      <div class="row">
        <div class="col-xs-12">
          <div class="table-responsive">
            <table class="table product-style">
              <thead>
								<tr>
									<th><?=$this->lang->line('global_date')?></th>
									<th><?=$this->lang->line('global_description')?></th>
									<th><?=$this->lang->line('global_debit')?></th>
									<th><?=$this->lang->line('global_credit')?></th>
									<th><?=$this->lang->line('global_balance')?></th>
								</tr>
              </thead>
              <tbody>
                <?php if(customCompute($student->statement)) {
									$tdebit = $tcredit = 0;
									foreach ($student->statement as $date => $row) {
                    if($date != "") {?>
                    <tr>
                      <td colspan="5"><b><?=$date?><b></td>
                    </tr>
                    <?php } foreach($row as $data) {
										  if ($data['column'] == "debit")
											  $tdebit += $data['amount'];
										  elseif ($data['column'] == "credit")
											  $tcredit += $data['amount']; ?>
										  <tr>
										    <td><?=$data['date']?></td>
											  <td><?=$data['fee_type']?></td>
											  <td><?=$data['column'] == "debit" ? number_format($data['amount'], 2) : ''?></td>
											  <td><?=$data['column'] == "credit" ? number_format($data['amount'], 2) : ''?></td>
											  <td><?=number_format($data['balance'], 2)?></td>
										  </tr>
                <?php } } } ?>
								<tr>
									<td></td>
									<td><b><?=$this->lang->line('global_total')?></b></td>
									<td><b><?=number_format($tdebit, 2)?></b></td>
									<td><b><?=number_format($tcredit, 2)?></b></td>
									<td></td>
								</tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
			<div style="break-after:page"></div>
			<?php }}?>

    		<!-- this row will not appear when printing -->
    	</section><!-- /.content -->
    </div>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$('.select2').select2();

function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            window.location.reload();
        }

function csvDiv(divID) {
	//Get the HTML of div
	var divElements = document.getElementById(divID).innerHTML;

	//Reset the page's HTML with div's HTML only
	var html =
	  "<html><head><title></title></head><body>" +
	  divElements + "</body>";
	htmlToCSV(html, "statement.csv");
}

function htmlToCSV(html, filename) {
	var data = [];
	var rows = document.querySelectorAll("table tr");

	for (var i = 0; i < rows.length; i++) {
		var row = [], cols = rows[i].querySelectorAll("td, th");

		for (var j = 0; j < cols.length; j++) {
		        row.push(cols[j].innerText.replaceAll(",", ""));
        }

		data.push(row.join(","));
	}

	downloadCSVFile(data.join("\n"), filename);
}

function downloadCSVFile(csv, filename) {
	var csv_file, download_link;

	csv_file = new Blob([csv], {type: "text/csv"});

	download_link = document.createElement("a");

	download_link.download = filename;

	download_link.href = window.URL.createObjectURL(csv_file);

	download_link.style.display = "none";

	document.body.appendChild(download_link);

	download_link.click();
}

$("#classesID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#sectionID').val(0);
            $('#studentID').val(0);
			$('#parentID').val(0);
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

			$.ajax({
                type: 'POST',
                url: "<?=base_url('student_statement/parentcall')?>",
                data: {"classesID" : id},
                dataType: "html",
                success: function(data) {
                   $('#parentID').html(data);
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

				$.ajax({
                    type: 'POST',
                    url: "<?=base_url('student_statement/parentcall')?>",
                    data: {"classesID" : classesID, "sectionID" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#parentID').html(data);
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

		$.ajax({
            type: 'POST',
            url: "<?=base_url('student_statement/parentcall')?>",
            data: {"classesID" : classesID, "sectionID" : id},
            dataType: "html",
            success: function(data) {
               $('#parentID').html(data);
            }
        });
    }
});

$("#schoolYearID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#schooltermID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student_statement/termcall')?>",
                data: {"schoolYearID" : id},
                dataType: "html",
                success: function(data) {
                   $('#schooltermID').html(data);
                }
            });
        }
    }
});

$("#schooltermID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#dateFrom').val('');
            $('#dateTo').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('student_statement/datescall')?>",
                data: {"schooltermID" : id},
                dataType: "json",
                success: function(data) {
                   $('#dateFrom').val(data.startingdate);
                   $('#dateTo').val(data.endingdate);
                }
            });
        }
    }
});

$(".student_row").on("click", function() {
    var studentID = $(this).attr("id");
	$("#studentID").val(studentID).change();
	$('.global_payment_search').trigger('click');
});

function isInt(data) {
    var val = data;
    if(isNumeric(val)) {
        return true;
    } else {
        return false;
    }
}

function parseSentenceForNumber(sentence){
    var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
    return matches && matches[0] || null;
}


function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function floatChecker(value) {
    var val = value;
    if(isNumeric(val)) {
        return true;
    } else {
        return false;
    }
}

$(document).on("keyup", "#paymentyear", function() {
    var input_value = parseInt($(this).val());
    if(isInt(input_value)) {
        if($(this).val().length > 0 && $(this).val().length <= 4) {
            if($(this).val().length == 4) {
                var str = "01/05/" + $(this).val();
                var year = str.match(/\/(\d{4})/)[1];
                var currYear = new Date().toString().match(/(\d{4})/)[1];
                if (year >= 1500 && year <= currYear) {
                    $(this).removeClass('errorClass');
                } else {
                    $(this).addClass('errorClass');
                }
            }
        } else {
            $(this).val('');
        }
    } else {
        $(this).val('');
    }
});


$('#add_payment').on('click',function(e){
    var error = 0;
    var invoicename            = $('#invoicename');
    var invoicedescription     = $('#invoicedescription');
    var invoicenumber          = $('#invoicenumber');
    var paymentyear            = $('#paymentyear');
    var payment_status         = $('#payment_status');
    var payment_type           = $('#payment_type');

    if(invoicename.val() == '') {
        invoicename.addClass('errorClass');
        error++;
    } else if(invoicename.val().length > 127)  {
        invoicename.addClass('errorClass');
        error++;
    } else {
        invoicename.removeClass('errorClass');
    }

    if(invoicedescription.val().length > 127) {
        invoicedescription.addClass('errorClass');
        error++;
    } else {
        invoicedescription.removeClass('errorClass');
    }

    if(invoicenumber.val() == '') {
        invoicenumber.addClass('errorClass');
        error++;
    } else {
        invoicenumber.removeClass('errorClass');
    }

    if(paymentyear.val() == '') {
        paymentyear.addClass('errorClass');
        error++;
    } else if(paymentyear.val().length > 4 || paymentyear.val().length <= 3)  {
        paymentyear.addClass('errorClass');
        error++;
    } else {
        paymentyear.removeClass('errorClass');
    }

    var classesID           = <?=$set_classesID?>;
    var studentID           = <?=$set_studentID?>;
    invoicename             = invoicename.val();
    invoicedescription      = invoicedescription.val();
    invoicenumber           = invoicenumber.val();
    paymentyear             = paymentyear.val();
    payment_status          = payment_status.val();
    payment_type            = payment_type.val();

    var paid = $('input[name^=paid]').map(function(){
        return { paidFieldID: this.name , value: this.value };
    }).get();

    var weaver = $('input[name^=weaver]').map(function(){
        return { weaverFieldID: this.name , value: this.value };
    }).get();

    var fine = $('input[name^=fine]').map(function(){
        return { fineFieldID: this.name , value: this.value };
    }).get();

    if(globalPaid == 0 && globalFine == 0 && globalWeaver == 0) {
        $('input[name^=paid]').addClass('errorClass');
        $('input[name^=weaver]').addClass('errorClass');
        $('input[name^=fine]').addClass('errorClass');
        error++;
    } else {
        $('input[name^=paid]').removeClass('errorClass');
        $('input[name^=weaver]').removeClass('errorClass');
        $('input[name^=fine]').removeClass('errorClass');
    }

    if(error == 0) {
        $(this).attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: "<?=base_url('student_statement/paymentSend')?>",
            data: {
                "classesID" : classesID,
                "studentID" : studentID,
                'invoicename' : invoicename,
                'invoicedescription' : invoicedescription,
                'invoicenumber' : invoicenumber,
                'paymentyear' : paymentyear,
                'payment_status' : payment_status,
                'payment_type' : payment_type,
                "paid" : paid,
                "weaver" : weaver,
                "fine" : fine
            },
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);

                if(response.status) {
                    window.location.reload();
                } else {
                    $(this).removeAttr("disabled");
                    $(this).removeAttr("disabled", '');
                    if(response.paid) {
                        $('input[name^=paid]').addClass('errorClass');
                        $('input[name^=weaver]').addClass('errorClass');
                        $('input[name^=fine]').addClass('errorClass');
                    } else {
                        $('input[name^=paid]').removeClass('errorClass');
                        $('input[name^=weaver]').removeClass('errorClass');
                        $('input[name^=fine]').removeClass('errorClass');
                    }

                    if(response.invoicename) {
                        $('#invoicename').addClass('errorClass');
                    } else {
                        $('#invoicename').removeClass('errorClass');
                    }

                    if(response.invoicenumber) {
                        $('#invoicenumber').addClass('errorClass');
                    } else {
                        $('#invoicenumber').removeClass('errorClass');
                    }

                    if(response.paymentyear) {
                        $('#paymentyear').addClass('errorClass');
                    } else {
                        $('#paymentyear').removeClass('errorClass');
                    }

                    if(response.invoicedescription) {
                        $('#invoicedescription').addClass('errorClass');
                    } else {
                        $('#invoicedescription').removeClass('errorClass');
                    }
                }
            }
        });
    }
});

</script>
