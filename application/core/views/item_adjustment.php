<!DOCTYPE html>
<html>

<head>
<!-- TABLES CSS CODE -->
<?php include"comman/code_css_datatable.php"; ?>

<!-- Lightbox -->
<link rel="stylesheet" href="<?php echo $theme_link; ?>plugins/lightbox/ekko-lightbox.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <!-- Left side column. contains the logo and sidebar -->

    <?php include"sidebar.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          <?=$page_title;?>
          <small>View/Search Items</small>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active"><?=$page_title;?></li>
        </ol>
      </section>

      <!-- Main content -->
      <!-- <?= form_open('#', array('class' => '', 'id' => 'table_form')); ?> -->
      <!-- <input type="hidden" id='base_url' value="<?=$base_url;?>"> -->

      <section class="content">
        <div class="row">
          <!-- ********** ALERT MESSAGE START******* -->
          <?php include"comman/code_flashdata.php"; ?>
          <!-- ********** ALERT MESSAGE END******* -->
          <div class="col-xs-12">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title"><?=$page_title;?></h3>
                <?php if($CI->permissions('items_add')) { ?>
                <div class="box-tools">
                  <select class="form-control select2" onchange="search_items()" id="company_id" style="width: 100%;">
                    <?php
                        $query1="select * from db_brand_companies where status=1";
                        $q1=$this->db->query($query1);
                        if($q1->num_rows($q1)>0) {
                          echo '<option value="">All Company</option>';
                          foreach($q1->result() as $res1) {
                            echo "<option value='".$res1->id."'>".$res1->company_name."</option>";
                          }
                        } else { ?>
                      <option value="">No Records Found</option>
                    <?php } ?>
                  </select>

                  <select class="form-control select2" onchange="search_items()" id="category_id" style="width: 100%;">
                    <?php
                        $query1="select * from db_category where status=1";
                        $q1=$this->db->query($query1);
                        if($q1->num_rows($q1)>0) {
                          echo '<option value="">All Category</option>';
                          foreach($q1->result() as $res1) {
                            echo "<option value='".$res1->id."'>".$res1->category_name."</option>";
                          }
                        } else { ?>
                      <option value="">No Records Found</option>
                    <?php } ?>
                  </select>
                  <input onkeyup="search_items()" class="form-control input-sm" id="search_p" style="width: 150px;" placeholder="Item Name" >
                  <a class="btn btn-block btn-info " href="<?php echo $base_url; ?>items"><?= $this->lang->line('items_list'); ?></a>
                  <a class="btn btn-block btn-info " href="<?php echo $base_url; ?>items/add">
                  <i class="fa fa-plus " ></i> <?= $this->lang->line('new_item'); ?></a>
                </div>
              <?php } ?>
              </div>
              <style>
                .box-header>.box-tools {
                    position: absolute;
                    right: 10px;
                    top: 5px;
                    display: flex !important;
                    gap: 10px;
                }
                .btn-block+.btn-block {
                    margin-top: 0px !important;
                }
              </style>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="example" class="table table-bordered table-striped" width="100%">
                  <thead class="bg-primary ">
                    <tr>
                      <th><?= $this->lang->line('sl'); ?></th>
                      <th><?= $this->lang->line('item_name'); ?></th>
                      <th><?= $this->lang->line('category'); ?></th>
                      <th><?= $this->lang->line('brand_company'); ?></th>
                      <th><?= $this->lang->line('price'); ?></th>
                      <th style="width: 110px;"><?= $this->lang->line('tax'); ?></th>
                      <th><?= $this->lang->line('purchase_price'); ?></th>
                      <th>Profit Margin <?=$CURRENCY;?></th>
                      <th>discount Type</th>
                      <th><?= $this->lang->line('discount'); ?></th>
                      <th><?= $this->lang->line('sales_price'); ?></th>
                      <th><?= $this->lang->line('stock_qty'); ?></th>
                      <!-- <th><?= $this->lang->line('action'); ?></th> -->
                    </tr>
                  </thead>
                    <?php $taxs = $this->db->get('db_tax')->result(); ?>
                    <tr style="background: #ecf0f5 !important;">
                      <td colspan="5"></td>
                      <td>
                        <select select class="form-control input-sm" id="all_tax" onchange="all_change(this.value)">
                          <?php foreach ($taxs as $tax): ?>
                            <option value="<?= $tax->id ?>"><?= $tax->tax_name ?></option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td></td>
                      <td><input class="form-control input-sm" id="profit" onkeyup="profit_amt(this.value)"></td>
                      <td>
                        <select class="form-control input-sm" id="dis_type" onchange="dis_type(this.value)">
                          <option value="">select one</option>
                          <option value="1">%</option>
                          <option value="2">Fixed</option>
                        </select>
                      </td>
                      <td><input class="form-control input-sm" id="discount" onkeyup="discount_amt(this.value)"></td>
                      <td colspan="2"></td>
                    </tr>
                  <?php echo form_open('items/ajax_update', 'class="email" id="myform"'); ?>
                    <tbody id="table_body">
                      <?php foreach ($items as $key => $row) { ?>
                      <tr>
                          <input type="hidden" name="id[]" value="<?= $row->id ?>">
                          <td> <?= $key+1; ?> </td>
                          <td> <?= $row->item_name ?> </td>
                          <td> <?= $row->category_name ?> </td>
                          <td> <?= $row->company_name ?> </td>

                          <td><input value="<?= $row->price ?>" class="form-control input-sm" name="price[<?= $row->id ?>]" id="price<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)"></td>
                          <td>
                              <select name="tax_name[<?= $row->id ?>]" class="form-control all-tax input-sm" id="tax_name<?= $row->id ?>" onchange="calculateSalePrice(<?= $row->id ?>)">
                                  <?php foreach ($taxs as $tax): ?>
                                      <option value="<?= $tax->id ?>" <?= $tax->id == $row->tax_id ? "selected" : "" ?>><?= $tax->tax_name ?></option>
                                  <?php endforeach; ?>
                              </select>
                          </td>
                          <td><input value="<?= $row->purchase_price ?>" class="form-control input-sm" name="purchase_price[<?= $row->id ?>]" id="purchase_price<?= $row->id ?>" readonly></td>

                          <td>
                              <input value="<?= $row->profit_margin ?>" class="form-control all-profit input-sm" name="profit_margin[<?= $row->id ?>]" id="profit_margin<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)">
                          </td>

                          <td>
                            <select onchange="calculateSalePrice(<?= $row->id ?>)" name="discount_type[<?= $row->id ?>]" class="form-control all-dis-type input-sm" id="discount_type<?= $row->id ?>">
                              <option value="1" <?= $row->discount_type == '1' ? "selected" : "" ?>>%</option>
                              <option value="2" <?= $row->discount_type == '2' ? "selected" : "" ?>>Amount</option>
                            </select>
                          </td>

                          <td>
                              <input value="<?= $row->discount ?>" class="form-control all-discount input-sm" name="discount[<?= $row->id ?>]" id="discount<?= $row->id ?>" onkeyup="calculateSalePrice(<?= $row->id ?>)">
                          </td>

                          <td>
                              <input value="<?= $row->sales_price ?>" class="form-control input-sm" name="sales_price[<?= $row->id ?>]" id="sales_price<?= $row->id ?>" readonly>
                          </td>

                          <td> <input value="<?= $row->stock ?>" class="form-control input-sm" name="stock[<?= $row->id ?>]" id="stock<?= $row->id ?>"> </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                          <td colspan="11"></td>
                          <td>
                              <button onclick="return confirm('Are you sure?')" type="submit" name="submit" value="submit" class="btn btn-sm btn-success">update All </button>
                          </td>
                      </tr>
                    </tfoot>
                  <?php echo form_close(); ?>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
      <!-- /.content -->
      <!-- <?= form_close();?> -->
    </div>
    <!-- /.content-wrapper -->
    <?php include"footer.php"; ?>
      <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- ./wrapper -->

  <!-- SOUND CODE -->
  <?php include"comman/code_js_sound.php"; ?>
  <!-- TABLES CODE -->
  <?php include"comman/code_js_datatable.php"; ?>
  <!-- Lightbox -->

  <script>
    function search_items() {
      var company_id = $('#company_id').val()=='' ? 'All' : $('#company_id').val();
      var category_id = $('#category_id').val() == '' ? 'All' : $('#category_id').val();
      var search_p = $('#search_p').val() == '' ? 'All' : $('#search_p').val();
      table_body = $('#table_body');
      table_body.empty();
      table_body.html('<span class="text-center text-danger">Please Wait...</span>');

      $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>" + "items/ajax_items_by_company_id/" + company_id + "/" + category_id + "/" + search_p,
        data: {
          company_id: company_id,
          category_id: category_id,
          search_p: search_p
         },
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
          table_body.empty();
          $('#table_body').html(result);
        },
        error: function () {
          alert("Fail");
        }
      });
    }
  </script>

<!--   <script>
    function category_change(id) {
       if (id != '') {
        table_body = $('#table_body');
        table_body.empty();
      } else {
        return false;
      }
      $.ajax({
        type: "GET",
        url: "<?php echo base_url(); ?>" + "items/ajax_items_by_category_id/" + id,
        data: { id: id },
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {
          $('#table_body').html(result);
        },
        error: function () {
          alert("Fail");
        }
      });
    }
  </script> -->

  <script>
      function all_change(id) {
          $('.all-tax').val(id).trigger('change');
      }
  </script>

  <script>
      function profit_amt(val) {
          $('.all-profit').val(val).trigger('keyup');
      }
  </script>

  <script>
      function dis_type(val) {
        $('.all-dis-type').val(val).trigger('change');
      }
  </script>

  <script>
      function discount_amt(val) {
        $('.all-discount').val(val).trigger('keyup');
      }
  </script>

  <script>
    var taxs = <?php echo json_encode($taxs); ?>;
    function calculateSalePrice(id) {
        var purchasePrice = 0;
        var basePrice = parseFloat($('#price' + id).val());
        var tax_id = $('#tax_name' + id).val();
        tax_cal = taxs.find(x => x.id == tax_id);
        taxPercentage = tax_cal.tax;

        purchasePrice = parseFloat(basePrice) + ((parseFloat(basePrice) * parseFloat(taxPercentage)) / 100);
        purchasePrice = purchasePrice.toFixed(2);
        $('#purchase_price' + id).val(purchasePrice);

        var profitMargin = parseFloat($('#profit_margin' + id).val());
        var discountType = $('#discount_type' + id).val();
        var disAmt = parseFloat($('#discount' + id).val());

        if (profitMargin != '' && profitMargin != 0) {
          var pro = parseFloat(parseFloat(purchasePrice) * parseFloat(profitMargin) / 100).toFixed(2);
          var sales_price = parseFloat(parseFloat(purchasePrice) + parseFloat(pro)).toFixed(2);
        } else {
          sales_price = parseFloat(purchasePrice).toFixed(2);
        }

      if (discountType == 1 && disAmt != '') {
        var sales_price = (parseFloat(sales_price) - (parseFloat(sales_price) * parseFloat(disAmt) / 100)).toFixed(2);
      } else if(discountType == 2 && disAmt != '') {
        var sales_price = (parseFloat(sales_price) - parseFloat(disAmt)).toFixed(2);
      }
      $('#sales_price' + id).val(sales_price);
    }
  </script>


<script src="<?php echo $theme_link; ?>plugins/lightbox/ekko-lightbox.js"></script>
<script type="text/javascript">
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>

<script src="<?php echo $theme_link; ?>js/items.js"></script>

<!-- Make sidebar menu hughlighter/selector -->
<script>$(".<?php echo basename(__FILE__,'.php');?>-active-li").addClass("active");</script>

</body>
</html>
