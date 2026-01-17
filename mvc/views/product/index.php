<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-product"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <style type="text/css">
        .setting-fieldset {
            border: 1px solid #DBDEE0 !important;
            padding: 15px !important;
            margin: 0 20px 25px 20px !important;
            box-shadow: 0px 0px 0px 0px #000;
        }

        .setting-legend {
            font-size: 1.1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            color: #428BCA;
            padding: 5px 15px;
            border: 1px solid #DBDEE0 !important;
            margin: 0px;
        }
    </style>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('product_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('product/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php }?>

                  <!--<div class="row">
                    <fieldset class="setting-fieldset">
                        <legend class="setting-legend"><?=$this->lang->line('product_duration')?>&nbsp<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enter duration and press view to see a product's history"></i></legend>

                        <div class="col-sm-2">
                          <div class="form-group">
                              <label for="date to" class="control-label">
                                  <?=$this->lang->line('product_to')?>
                              </label>
                            <input name="dateTo" id="dateTo" type="date" class="form-control" value="<?=$_GET['to']?>">
                          </div>
                        </div>

                        <div class="col-sm-2">
                          <div class="form-group">
                              <label for="date from" class="control-label">
                                  <?=$this->lang->line('product_from')?>
                              </label>
                            <input name="dateFrom" id="dateFrom" type="date" class="form-control" value="<?=$_GET['from']?>">
                          </div>
                        </div>

                        <div class="col-sm-2">
                          <div class="form-group">
                              <label for="month" class="control-label">
                                  <?=$this->lang->line('product_month')?>
                              </label>
                              <input name="month" id="month" type="text" class="form-control" value="<?=$_GET['month']?>" placeholder="mm-yyyy">
                          </div>
                        </div>
                    </fieldset>

                    <div class="productwarehouseDiv form-group <?=form_error('productwarehouseID') ? 'has-error' : '' ?> col-sm-2 pull-right">
                      <label for="productwarehouse" class="control-label">
                          <?=$this->lang->line('product_warehouse')?>
                      </label>
                        <?php
                            $array = array("0" => $this->lang->line("product_select_warehouse"));
                            if(customCompute($productwarehouses)) {
                                foreach ($productwarehouses as $productwarehouse) {
                                    $array[$productwarehouse->productwarehouseID] = $productwarehouse->productwarehousename;
                                }
                            }
                            echo form_dropdown("productwarehouseID", $array, set_value("productwarehouseID", $_GET['warehouse']), "id='productwarehouseID' class='form-control select2'");
                        ?>
                    </div>
                  </div>-->

                <div id="hide-table">
                    <table id="example2" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_product')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_category')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('product_quantity')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_desc')?></th>
                                <?php if(permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($products)) {$i = 1; foreach($products as $product) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_product')?>">
                                        <?=$product->productname;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_category')?>">
                                        <?=isset($productcategorys[$product->productcategoryID]) ? $productcategorys[$product->productcategoryID] : ''?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_quantity')?>">
                                        <?=$productpurchasequintity[$product->productID]->quantity - $productsalequintity[$product->productID]->quantity?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_desc')?>">
                                        <?=$product->productdesc?>
                                    </td>

                                    <?php if(permissionChecker('product_view') || permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php if(permissionChecker('product_view')) {?> <a href="<?=base_url("product/view?id=$product->productID")?>" class="btn btn-success btn-xs mrg"><i class="fa fa-check-square-o"></i></a> <?php }?>
                                            <?php echo (permissionChecker('product_edit')) ? btn_edit('product/edit/'.$product->productID, $this->lang->line('edit')) : "" ?>
                                            <?php echo (permissionChecker('product_delete')) ? btn_delete('product/delete/'.$product->productID, $this->lang->line('delete')) : "" ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="http://localhost/shule/assets/datepicker/datepicker.css" rel="stylesheet">
<script src="http://localhost/shule/assets/datepicker/datepicker.js"></script>
<script type="text/javascript">
    $(".select2").select2();

    $('#month').datepicker({
      format: 'mm-yyyy',
      viewMode: 'months',
      minViewMode: 'months'
    });

    $('#productwarehouseID').change(function() {
        var productwarehouseID = $(this).val();
        if(productwarehouseID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('product/product_list')?>",
                data: "id=" + productwarehouseID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });

    function view(id) {
      var error=0;
      var field = {
          'productwarehouseID'  : $('#productwarehouseID').val(),
          'dateFrom'            : $('#dateFrom').val(),
          'dateTo'              : $('#dateTo').val(),
          'month'               : $('#month').val(),
      };

      if(field['productwarehouseID'] === '0') {
          $('.productwarehouseDiv').addClass('has-error');
          error++;
      } else {
          $('.productwarehouseDiv').removeClass('has-error');
      }

      if(error === 0) {
        if(field['month'] !== "") {
            window.location = "<?=base_url("product/view?id=")?>" + id + "&warehouse=" + field['productwarehouseID'] + "&month=" + field['month'] + "&tab=history";
        }
        else if(field['dateFrom'] !== "" || field['dateTo'] !== "") {
            window.location = "<?=base_url("product/view?id=")?>" + id + "&warehouse=" + field['productwarehouseID'] + "&from=" + field['dateFrom'] + "&to=" + field['dateTo'] + "&tab=history";
        }
        else {
            window.location = "<?=base_url("product/view?id=")?>" + id + "&warehouse=" + field['productwarehouseID'];
        }
      } else {
        toastr["error"]('Please select a warehouse.')
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
      }
    }
</script>
