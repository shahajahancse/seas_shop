<!-- TABLES CSS CODE -->
<?php include"comman/header.php"; ?>

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
  <?php $CI =& get_instance(); ?>
  <div class="wrapper">
    <!-- header top  menu section -->
    <header class="main-header">
      <nav class="navbar navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <a href="<?php echo $base_url; ?>dashboard" class="navbar-brand" title="Go to Dashboard!"><b class="hidden-xs"><?php  echo $SITE_TITLE;?></b><b class="hidden-lg">POS</b></a>
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
              <?php if($CI->permissions('sales_view')) { ?>
              <li class=""><a href="<?php echo $base_url; ?>sales" title="View Sales List!"><i class="fa fa-list text-yellow" ></i> <span><?= $this->lang->line('sales_list'); ?></span></a></li>
              <?php } ?>
              <?php if($CI->permissions('sales_add')) { ?>
              <li class=""><a href="<?php echo $base_url; ?>pos" title="Create New POS Invoice"><i class="fa fa-calculator text-yellow " ></i> <span><?= $this->lang->line('new_invoice'); ?></span></a></li>
              <?php } ?>
              <?php if($CI->permissions('items_view')) { ?>
              <li class=""><a href="<?php echo $base_url; ?>items/" title="View Items List"><i class="fa  fa-cubes text-yellow " ></i> <span><?= $this->lang->line('items_list'); ?></span></a></li>
              <?php } ?>
            </ul>
          </div>
          <!-- /.navbar-collapse -->

          <!-- Navbar Right Menu -->
          <?php $q2=$this->db->query("select * from temp_holdinvoice where status=1 group by invoice_id order by id desc"); ?>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Click To View Hold Invoices">

                  <span class=""><?= $this->lang->line('hold_list'); ?></span>
                  <?php
                    if (!empty($q2->result())) { ?>
                      <span class="label label-danger hold_invoice_list_count"> <?= $q2->num_rows() ?> </span>
                  <?php } ?>
                </a>

                <ul class="dropdown-menu dropdown-width-lg">
                  <!-- Menu Body -->
                  <li class="user-body">
                    <div class="row">
                      <div class="col-xs-12 text-center " style="max-height:300px;overflow-y: scroll;">
                        <table class="table table-bordered" width="100%">
                          <thead>
                          <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Ref.ID</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                          <tbody id="hold_invoice_list" >
                            <?php
                              $str="";
                              $i=0;
                              if($q2->num_rows()>0){
                                foreach($q2->result() as $res2){
                                  $str =$str."<tr>";
                                  $str =$str."<td>".$res2->id."</td>";
                                  $str =$str."<td>".show_date($res2->invoice_date)."</td>";
                                  $str =$str."<td>".$res2->reference_id."</td>";
                                  $str =$str."<td>";
                                  $str =$str.'<a class="fa fa-fw fa-trash-o text-red" style="cursor: pointer;font-size: 20px;" onclick="hold_invoice_delete('.$res2->invoice_id.')" title="Delete Invoive?"></a>';
                                  $str =$str.'<a class="fa fa-fw fa-edit text-success" style="cursor: pointer;font-size: 20px;" onclick="hold_invoice_edit('.$res2->invoice_id.')" title="Edit Invoive?"></a>';
                                  $str =$str."</td>";
                                  $str =$str."</tr>";
                                  $i++;
                                } //for end
                              }
                              echo $str;
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <!-- /.row -->
                  </li>
                </ul>
              </li>

              <!-- Messages: style can be found in dropdown.less-->
              <li class="hidden-xs" id="fullscreen"><a title="Fullscreen On/Off"><i class="fa fa-tv text-white" ></i> </a></li>
              <li class="text-center" id="">
                <a title="Dashboard" href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard text-yellow" ></i> <b class="hidden-xs"><?= $this->lang->line('dashboard'); ?></b></a>
              </li>

              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $theme_link; ?>dist/img/avatar5.png" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php print ucfirst($this->session->userdata('inv_username')); ?></span>
                </a>

                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $theme_link; ?>dist/img/avatar5.png" class="img-circle" alt="User Image">
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?php echo $base_url; ?>logout" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
      </nav>
    </header>
    <!-- end menu section -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- **********************MODALS***************** -->
      <?php include"modals/modal_customer.php"; ?>
      <!-- **********************MODALS END***************** -->

      <!-- Main content -->
      <section class="content">
        <?= form_open('#', array('method' => 'POST', 'class' => 'pos-form', 'id' => 'posForm'))?>
        <div class="row">
          <!-- left column -->
          <div class="col-md-8">
            <!-- general form elements -->
            <div class="box box-primary">
              <!-- form start -->
              <div class="box-header with-border" style="padding-bottom: 0px;">
                <div class="row" >
                  <div class="col-md-12">
                    <h3 class="box-title text-primary"><i class="fa fa-shopping-cart text-aqua"></i> Sales Invoice</h3>
                  </div>
                </div>
              </div>
              <!-- /.box-header -->

              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <input type="hidden" value='' id="hidden_invoice_id" name="hidden_invoice_id">
              <input type="hidden" id="base_url" value="<?php echo $base_url;; ?>">
              <input type="hidden" value='' id="temp_customer_id" name="temp_customer_id">

              <!-- Customer and item search section -->
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" title="Customer"><i class="fa fa-user"></i></span>
                      <select class="form-control select2" id="customer_id" name="customer_id" style="width: 100%;" onkeyup="shift_cursor(event,'expense_for')" >
                        <?php $query1="select * from db_customers where status=1";
                        $q1=$this->db->query($query1);
                        if($q1->num_rows($q1)>0) {
                          foreach($q1->result() as $res1) {
                            echo "<option  value='".$res1->id."'>".$res1->customer_name.'-'.$res1->mobile.''."</option>";
                          } } else { ?>
                          <option value="">No Records Found</option>
                        <?php } ?>
                      </select>
                      <span class="input-group-addon pointer" data-toggle="modal" data-target="#customer-modal" title="New Customer?"><i class="fa fa-user-plus text-primary fa-lg"></i></span>
                    </div>
                    <span class="customer_points text-success" style="display: none;"></span>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" title="Select Items"><i class="fa fa-barcode"></i></span>
                      <input class="form-control" placeholder="Item name/Barcode" id="item_search">
                    </div>
                  </div>
                </div><!-- row end -->
                <br>

                <!-- item section -->
                <div class="row">
                  <div class="form-group">
                    <div class="col-sm-12" style="overflow-y:auto;height: 66vh;" >
                      <table class="table table-condensed table-bordered table-striped table-responsive items_table" style="">
                        <thead class="bg-primary" style="font-size: 14px;font-weight: bold;">
                          <th width="25%"><?= $this->lang->line('item_name'); ?></th>
                          <th width="7%"><?= $this->lang->line('stock'); ?></th>
                          <th width="9%">MR.Price</th>
                          <th width="15%"><?= $this->lang->line('quantity'); ?></th>
                          <th width="10%">S.Price</th>
                          <th width="10%">Discounted</th>
                          <th width="10%"  style="display: none">A.Discount</th>
                          <th width="10%"><?= $this->lang->line('subtotal'); ?></th>
                          <th width="4%"><i class="fa fa-close"></i></th>
                        </thead>
                        <tbody id="pos-form-tbody" style="font-size: 13px;font-weight: bold;overflow: scroll;">
                          <!-- body code -->
                        </tbody>
                        <tfoot>
                          <!-- footer code -->
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!--/.col (left) -->

          <!-- right column -->
          <div class="col-md-4" style='padding-left: 0px !important'>
              <div class="box box-info">
                <div class="box-body" style="height: 82vh;">
                  <div class="box-footer bg-gray" style="height: 80vh;">

                    <!-- **********************MODALS***************** -->
                    <?php include"modals_pos_payment/modal_payments_multi.php"; ?>
                    <!-- **********************MODALS END***************** -->

                    <!-- discount and qnty -->
                    <div class="row" style="padding-top:30px">
                      <div class="col-md-5 text-right font-weight-bold" style="font-size: 17px;">
                          <label> <?= $this->lang->line('quantity'); ?>:</label><br/>
                          <span class="text-bold tot_qty"></span>
                      </div>
                      <div class="col-md-6 text-right font-weight-bold" style="font-size: 17px;">
                        <label> <?= $this->lang->line('total_amount'); ?>:</label><br/>
                        <?= $CI->currency('<span class="tot_amt"></span>');?>
                      </div>
                    </div>

                    <div class="row" style="padding-top:25px">
                      <div class="col-md-6 text-right font-weight-bold" style="font-size: 17px;">
                        <label><?= $this->lang->line('total_discount'); ?>: <a class="fa fa-pencil-square-o cursor-pointer" data-toggle="modal" data-target="#discount-modal"></a></label><br/>
                        <?= $CI->currency('<span class="tot_disc"></span>');?>
                      </div>

                      <div class="col-md-5 text-right font-weight-bold" style="font-size: 17px;">
                        <label>Total vat:</label><br/>
                        <?= $CI->currency('<span class="tot_tax"></span>');?>
                      </div>

                      <!-- price -->
                      <div class="col-md-5 text-right font-weight-bold" style="font-size: 17px; padding-top:25px">
                        <label><?= $this->lang->line('grand_total'); ?>:</label><br/>
                        <?= $CI->currency('<span class="tot_grand"></span>');?>
                      </div>
                      <input type="hidden" name="tot_profit" id="tot_profit" value="0">
                      <input type="hidden" name="item_tot_dis" id="item_tot_dis" value="0">
                    </div>

                    <!-- button -->
                    <div class="row" style="padding-top:30px">
                      <?php if(isset($sales_id)) {
                        $btn_id='update'; $btn_name="Cash"; ?>
                        <input type="hidden" name="sales_id" id="sales_id" value="<?php echo $sales_id;?>"/>
                      <?php } else {
                        $btn_id='save';$btn_name="Cash";} ?>

                      <div class="col-md-12 text-right" style="padding-top:30px">
                        <div class="col-sm-6">
                          <button type="button" id="hold_invoice" name="" class="btn bg-maroon btn-block btn-flat btn-lg" title="Hold Invoice [Ctrl+H]"> <i class="fa fa-hand-paper-o" aria-hidden="true"></i> Hold </button>
                        </div>
                        <div class="col-sm-6">
                          <button type="button" id="" name="" class="btn btn-primary btn-block btn-flat btn-lg show_payments_modal" title="Multiple Payments [Ctrl+M]"> <i class="fa fa-credit-card" aria-hidden="true"></i> Multiple </button>
                        </div>
                        <div class="col-sm-12" style="padding-top: 25px">
                          <button type="button" id="show_cash_modal" name="" class="btn btn-success btn-block btn-flat btn-lg ctrl_c"> <i class="fa fa-money" aria-hidden="true"></i> Cash </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
         <?= form_close() ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- footer -->
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b> <a target="_blank" href="https://mysoftheaven.com/">Salesman</a> -v2.3</b>
      </div>
      <strong>Copyright Mysoftheaven (BD) Ltd &copy; <?=date('Y')?> All Rights Reserved.</strong>
    </footer>
  </div>


  <!-- GENERAL CODE -->
  <?php include"comman/footer.php"; ?>

  <script src="<?php echo $theme_link; ?>js/fullscreen.js"></script>
  <script src="<?php echo $theme_link; ?>js/modals.js"></script>
  <script src="<?php echo $theme_link; ?>js/mousetrap.min.js"></script>
  <script src="<?php echo $theme_link; ?>js/pos.js"></script>
  <script>
    $(document).ready(function() {
      $('#item_search').focus();
    });
  </script>
</body>
</html>
