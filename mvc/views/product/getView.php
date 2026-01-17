<?php if(customCompute($profile)) { ?>
	<div class="well">
	    <div class="row">
	        <div class="col-sm-6">
	        	<?php if(!permissionChecker('product_view') && permissionChecker('product_add')) { echo btn_sm_add('product/add', $this->lang->line('add_product')); } ?>
	            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
	            <?php if(permissionChecker('product_edit')) { echo btn_sm_edit('product/edit/'.$profile->productID."/".$set, $this->lang->line('edit')); }
	            ?>

	        </div>
	        <div class="col-sm-6">
	            <ol class="breadcrumb">
	                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
	                <li><a href="<?=base_url("product/index?id="). $_GET['id'] ."&warehouse=". $_GET['warehouse']?><?=isset($_GET['month']) ? "&month=". $_GET['month'] : "";?><?=isset($_GET['from']) ? "&from=". $_GET['from'] : "";?><?=isset($_GET['to']) ? "&to=". $_GET['to'] : "";?>"><?=$this->lang->line('menu_product')?></a></li>
	                <li class="active"><?=$this->lang->line('view')?></li>
	            </ol>
	        </div>
	    </div>
	</div>

	<div id="printablediv">
		<style type="text/css">
		.container {
				min-width: 310px;
				max-width: 800px;
				height: 400px;
				margin: 0 auto;
		}
		</style>

		<div class="row">
		    <div class="col-sm-3">
		    	<div class="box box-primary backgroud-image">
		      		<div class="box-body box-profile">
		      			<!--<?=profileviewimage($profile->photo)?>-->
		              	<h3 class="profile-username text-center"><?=$profile->productname?></h3>

		              	<p class="text-muted text-center"><?=$productcategories[$profile->productcategoryID]?></p>

		              	<ul class="list-group list-group-unbordered">
											<li class="list-group-item" style="background-color: #FFF">
		                    <b><?=$this->lang->line('product_productID')?></b> <a class="pull-right"><?=$profile->productID?></a>
		                  </li>
		              		<li class="list-group-item" style="background-color: #FFF">
		                    <b><?=$this->lang->line('product_desc')?></b> <a class="pull-right"><?=$profile->productdesc?></a>
		                  </li>
		         				</ul>
		            </div>
		        </div>
		    </div>

		    <div class="col-sm-9">
		        <div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              	<li <?=isset($_GET['tab']) ? "" : "class='active'"?>><a href="#profile" data-toggle="tab"><?=$this->lang->line('product_profile')?></a></li>
		              	<li <?=$_GET['tab'] == "history" ? "class='active'" : ""?>><a href="#history" data-toggle="tab"><?=$this->lang->line('product_history')?></a></li>
		            </ul>

		            <div class="tab-content">
		              	<div class="<?=$_GET['tab'] == "history" ? "" : "active"?> tab-pane" id="profile">
				            	<div class="panel-body profile-view-dis">
				            		<div class="row">
					              			<div class="profile-view-tab">
					                        <p><span><?=$this->lang->line('product_lastbuyingprice')?> </span>: <?=number_format($lastbuyingprice->productpurchaseunitprice, 2)?></p>
					                    </div>
					                    <div class="profile-view-tab">
					                        <p><span><?=$this->lang->line('product_averagebuyingprice')?> </span>: <?=number_format($averageunitprice->averageunitprice, 2)?></p>
					                    </div>
															<div class="profile-view-tab">
					                        <p><span><?=$this->lang->line('product_sellingprice')?> </span>: <?=number_format($product->productsellingprice, 2)?></p>
					                    </div>
															<div class="profile-view-tab">
					                        <p><span><?=$this->lang->line('product_lastsupplier')?> </span>: <?=$lastsupplier->productsuppliername?></p>
					                    </div>
							        	</div>
						        	</div>
		              	</div>

		              	<div class="<?=$_GET['tab'] == "history" ? "active" : ""?> tab-pane" id="history">
											<div class="row">
		                    <fieldset class="setting-fieldset">
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
		                              <label for="date to" class="control-label">
		                                  <?=$this->lang->line('product_to')?>
		                              </label>
		                            <input name="dateTo" id="dateTo" type="date" class="form-control" value="<?=$_GET['to']?>">
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

														<div class="form-group col-sm-3 pull-right">
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
		                    </fieldset>
		                  </div>
											<div class="table-responsive">
													<table class="table table-bordered table-responsive">
															<thead>
																<th colspan="2"><?=$this->lang->line('product_quantitiesin')?></th>
																<th colspan="2"><?=$this->lang->line('product_quantitiesout')?></th>
																<th colspan="2"><?=$this->lang->line('product_totals')?></th>
															</thead>
															<tbody>
																<tr>
																	<td><?=$this->lang->line('product_received')?></td>
																	<td><?=$received[0]->quantity?></td>
																	<td><?=$this->lang->line('product_sold')?></td>
																	<td><?=$sold[0]->quantity?></td>
																	<td><?=$this->lang->line('product_netsales')?></td>
																	<td><?=number_format($sold[0]->netsales, 2)?></td>
																</tr>
																<tr>
																	<td><?=$this->lang->line('product_sales_returns')?></td>
																	<td>0</td>
																	<td><?=$this->lang->line('product_vendor_returns')?></td>
																	<td>0</td>
																	<td><?=$this->lang->line('product_cost_of_goods')?></td>
																	<td><?=number_format($sold[0]->quantity*$averageunitprice->averageunitprice, 2)?></td>
																</tr>
																<tr>
																	<td><?=$this->lang->line('product_transfers')?></td>
																	<td><?=customCompute($movements_in) ? $movements_in->quantity : 0?></td>
																	<td><?=$this->lang->line('product_transfers')?></td>
																	<td><?=customCompute($movements_out) ? $movements_out->quantity : 0?></td>
																	<td><?=$this->lang->line('product_profit')?></td>
																	<td><?=number_format($sold[0]->netsales-$sold[0]->quantity*$averageunitprice->averageunitprice, 2)?></td>
																</tr>
																<tr>
																	<td colspan=4></td>
																	<td><?=$this->lang->line('product_profit_margin')?></td>
																	<td><?=($sold[0]->netsales > 0) ? ($sold[0]->netsales-$sold[0]->quantity*$averageunitprice->averageunitprice)/$sold[0]->netsales*100 : 0?>%</td>
																</tr>
															</tbody>
													</table>
											</div>
		              	</div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>

	<link href="http://localhost/shule/assets/datepicker/datepicker.css" rel="stylesheet">
	<script src="http://localhost/shule/assets/datepicker/datepicker.js"></script>
	<script language="javascript" type="text/javascript">
			$(".select2").select2();

			$('#month').datepicker({
				format: 'mm-yyyy',
				viewMode: 'months',
				minViewMode: 'months'
			});

	    function printDiv(divID) {
	        var divElements = document.getElementById(divID).innerHTML;
	        var oldPage = document.body.innerHTML;
	        document.body.innerHTML =
	          "<html><head><title></title></head><body>" +
	          divElements + "</body>";
	        window.print();
	        document.body.innerHTML = oldPage;
	        window.location.reload();
	    }

			$(document).on('change', 'input[type="date"],#productwarehouseID,#month', function() {
				var id = <?=$_GET['id'];?>;
				view(id);
			});

			function view(id) {
	      var field = {
	          'productwarehouseID'  : $('#productwarehouseID').val(),
	          'dateFrom'            : $('#dateFrom').val(),
	          'dateTo'              : $('#dateTo').val(),
	          'month'               : $('#month').val(),
	      };

				var url = "<?=base_url("product/view?id=")?>" + id +"&tab=history";

				if(field['productwarehouseID'] != 0) {
						url += "&warehouse=" + field['productwarehouseID'];
				}
        if(field['month'] !== "") {
            url += "&month=" + field['month'];
        }
        else if(field['dateFrom'] !== "" || field['dateTo'] !== "") {
            url += "&from=" + field['dateFrom'] + "&to=" + field['dateTo'];
        }

				window.location = url;
	    }
	</script>
<?php } ?>
