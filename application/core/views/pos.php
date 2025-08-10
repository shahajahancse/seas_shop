<!DOCTYPE html>
<html>

<head>
<!-- TABLES CSS CODE -->
<?php include"comman/code_css_form.php"; ?>
<!-- iCheck -->
  <link rel="stylesheet" href="<?php echo $theme_link; ?>plugins/iCheck/square/blue.css">

  <style type="text/css">
    .select2-container--default .select2-selection--single{
      border-radius: 0px;
    }
    /*LEFT SIDE: ITEMS TABLE*/
    .table-striped > tbody > tr:nth-of-type(2n+1) {
      background-color: #ede3e3;
    }
    .table-striped > tbody > tr {
      background-color: #ddc8c8;
    }

    /*SET TOTAL FONT*/
    .tot_qty, .tot_amt, .tot_disc, .tot_grand {
      font-size: 19px;
      color: #023763 ;
    }
    /*CURSOR POINTER CLASS*/
    .pointer{
      cursor:pointer;
    }
    .navbar-nav > .user-menu > .dropdown-width-lg{
      width: 350px;
    }
    .header-custom{
      background-image: -webkit-gradient(linear, left top, right top, from(#20b9ae), to(#006fd6)); color: white;
    }
    .border-custom-bottom{
      border-bottom: 1px solid;
      padding-top: 10px;
      padding-bottom: 5px;
    }
    .custom-font-size{
      font-size: 22px;
    }
    .search_item{
      text-transform: uppercase;
      font-size: 10px;
      color: #000000;
      text-align: center;
      text-overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }
    .item_image{
      min-width: 70px;
      min-height:  70px;
      max-width: 70px;
      max-height:  70px;
    }
    .item_box{
      border-top:none;
    }
  </style>
</head>

<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-blue layout-top-nav">
  <script type="text/javascript">
    if(theme_skin!='skin-blue'){
      $("body").addClass(theme_skin);
      $("body").removeClass('skin-blue');
    }
    if(sidebar_collapse=='true'){
      $("body").addClass('sidebar-collapse');
    }
  </script>
  <?php $CI =& get_instance(); ?>
<div class="wrapper">


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
                            }//for end
                          }
                          echo $str;
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <!-- /.row -->
              <!--</li>-->
            </ul>
          </li>

            <!-- Messages: style can be found in dropdown.less-->
            <li class="hidden-xs" id="fullscreen"><a title="Fullscreen On/Off"><i class="fa fa-tv text-white" ></i> </a></li>
            <li class="text-center" id="">
            <a title="Dashboard" href="<?php echo $base_url; ?>dashboard"><i class="fa fa-dashboard text-yellow" ></i><b class="hidden-xs"><?= $this->lang->line('dashboard'); ?></b></a>
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

               <!-- <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>-->
              </li>
              <!-- Menu Body -->
              <!--<li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>-->
                <!-- /.row -->
              <!--</li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <!--<div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>-->
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

  <?php $css = ($this->session->userdata('language')=='Arabic' || $this->session->userdata('language')=='Urdu') ? 'margin-right: 0 !important;': '';?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="<?=$css;?>">
    <!-- Content Header (Page header) -->
    <!--  <section class="content-header">
      <h1>
        General Form Elements
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Forms</a></li>
        <li class="active">General Elements</li>
      </ol>
    </section> -->

    <!-- **********************MODALS***************** -->
    <?php include"modals/modal_customer.php"; ?>

    <!-- **********************MODALS END***************** -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <form class="form-horizontal" id="pos-form" >
        <input type="hidden" value='0' id="check_multiple_balance">
        <div class="col-md-8">

          <!-- general form elements -->
          <div class="box box-primary">
            <!-- form start -->

            <div class="box-header with-border" style="padding-bottom: 0px;">
              <div class="row" >
                <div class="col-md-12" >
                <div class="col-md-4">
                  <h3 class="box-title text-primary"><i class="fa fa-shopping-cart text-aqua"></i> Sales Invoice</h3>
                </div>
                  <!-- <div class="col-md-4 pull-right" >
                  <div class="form-group">
                     <select class="form-control select2" id="warehouse_id" name="warehouse_id"  style="width: 100%;" onkeyup="shift_cursor(event,'mobile')">
                          <?php

                             $query1="select * from db_warehouse where status=1";
                             $q1=$this->db->query($query1);
                             if($q1->num_rows($q1)>0)
                                {

                                  foreach($q1->result() as $res1)
                                {
                                  $selected=($warehouse_id==$res1->id) ? 'selected' : '';
                                  echo "<option $selected  value='".$res1->id."'>".$res1->warehouse_name ."</option>";
                                }
                              }
                              else
                              {
                                 ?>
                          <option value="">No Records Found</option>
                          <?php
                             }
                             ?>
                       </select>
                    <span id="warehouse_id_msg" style="display:none" class="text-danger"></span>
                  </div>
                </div> -->
                <?php if(isset($sales_id)): ?>
                  <?php if($CI->permissions('sales_add')) { ?>
                  <div class="col-md-4 pull-right">
                    <a href='<?= $base_url;?>pos' class="btn btn-primary pull-right">New Invoice</a>
                  </div>
                  <?php } ?>
                <?php endif; ?>

              </div>
              </div>




          </div>
            <!-- /.box-header -->

              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
              <input type="hidden" value='0' id="hidden_rowcount" name="hidden_rowcount">
              <input type="hidden" value='' id="hidden_invoice_id" name="hidden_invoice_id">
              <input type="hidden" id="base_url" value="<?php echo $base_url;; ?>">

              <input type="hidden" value='' id="temp_customer_id" name="temp_customer_id">

              <!-- **********************MODALS***************** -->
             <?php include"modals_pos_payment/modal_payments_multi.php"; ?>
              <!-- **********************MODALS END***************** -->
              <!-- **********************MODALS***************** -->
              <div class="modal fade" id="discount-modal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title">Set Discount</h4>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                          <div class="col-md-6">
                            <div class="box-body">
                              <div class="form-group">
                                <label for="discount_input">Discount</label>
                                <input class="form-control" id="discount_input" name="discount_input" value="0" onkeyup="check_max_dis(this.value)">
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="box-body">
                              <div class="form-group">
                                <label for="discount_type">Discount Type</label>
                                <select onchange="check_max_dis()" class="form-control" id='discount_type' name="discount_type">
                                  <option value='in_percentage'>Per%</option>
                                  <option value='in_fixed'>Fixed</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary discount_update">Update</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->
              <!-- **********************MODALS END***************** -->
              <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-addon" title="Customer"><i class="fa fa-user"></i></span>
                     <select class="form-control select2" id="customer_id" name="customer_id"  style="width: 100%;" onkeyup="shift_cursor(event,'expense_for')" >
                        <?php
                        $query1="select * from db_customers where status=1";
                        $q1=$this->db->query($query1);

                        if($q1->num_rows($q1)>0)
                         {
                             foreach($q1->result() as $res1)
                           {
                             echo "<option  value='".$res1->id."'>".$res1->customer_name.'-'.$res1->mobile.''."</option>";
                           }
                         }
                         else
                         {
                            ?>
                            <option value="">No Records Found</option>
                            <?php
                         }
                        ?>
                              </select>
                    <span class="input-group-addon pointer" data-toggle="modal" data-target="#customer-modal" title="New Customer?"><i class="fa fa-user-plus text-primary fa-lg"></i></span>
                  </div>
                    <span class="customer_points text-success" style="display: none;"></span>


                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-addon" title="Select Items"><i class="fa fa-barcode"></i></span>
                     <input type="text" class="form-control" placeholder="Item name/Barcode/Itemcode" id="item_search">
                  </div>
                </div>
              </div><!-- row end -->
              <br>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="col-sm-12" style="overflow-y:auto;height: 66vh;" > <!-- border:1px solid #337ab7; -->
                      <style>
                        .input-group-sm>.input-group-btn>.btn {
                          padding: 3px 7px !important;
                        }
                        .form-control {
                          height: 30px !important;
                        }
                        input[type=number]::-webkit-inner-spin-button {
                          -webkit-appearance: none ! important;
                        }
                      </style>
                      <table class="table table-condensed table-bordered table-striped table-responsive items_table" style="">
                        <thead class="bg-primary" style="font-size: 14px;font-weight: bold;">
                          <th width="25%"><?= $this->lang->line('item_name'); ?></th>
                          <!-- <th width="10%">P.Price</th> -->
                          <th width="7%"><?= $this->lang->line('stock'); ?></th>
                          <th width="9%">MR.Price</th>
                          <th width="15%"><?= $this->lang->line('quantity'); ?></th>
                          <!-- <th width="9%">tax</th> -->
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
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-4" >
            <div class="box box-info">
              <div class="box-body">
                <div class="box-footer bg-gray">

                  <div class="row" style="padding-top:35px">
                    <div class="col-md-5 text-right text-bold font-weight-bold" style="font-size: 19px;">
                        <label> <?= $this->lang->line('quantity'); ?>:</label><br/>
                        <span class="text-bold tot_qty"></span>
                    </div>
                    <div class="col-md-5 text-right text-bold font-weight-bold" style="font-size: 19px;">
                      <label><?= $this->lang->line('total_amount'); ?>:</label><br/>
                      <?= $CI->currency('<span class="tot_amt"></span>');?>
                    </div>

                  </div>

                  <div class="row" style="padding-top:30px">
                    <div class="col-md-5 text-right text-bold font-weight-bold" style="font-size: 19px;">
                        <label><?= $this->lang->line('total_discount'); ?>:<a class="fa fa-pencil-square-o cursor-pointer" data-toggle="modal" data-target="#discount-modal"></a></label><br/>
                        <?= $CI->currency('<span class="tot_disc"></span>');?>
                    </div>
                    <div class="col-md-5 text-right text-bold font-weight-bold" style="font-size: 19px;">
                       <label>Total vat:</label><br/>
                       <?= $CI->currency('<span class="tot_tax"></span>');?>
                    </div>
                    <div class="col-md-5 text-right text-bold font-weight-bold" style="font-size: 19px;">
                       <label><?= $this->lang->line('grand_total'); ?>:</label><br/>
                       <?= $CI->currency('<span class="tot_grand"></span>');?>
                    </div>
                    <input type="hidden" name="tot_profit" id="tot_profit" value="0">
                    <input type="hidden" name="item_tot_dis" id="item_tot_dis" value="0">

                  </div>



                  <div class="row" style="padding-top:85px">
                 <!-- SMS Sender while saving -->
                      <?php
                         //Change Return
                          $send_sms_checkbox='disabled';
                          if($CI->is_sms_enabled()){
                            if(!isset($sales_id)){
                              $send_sms_checkbox='checked';
                            }else{
                              $send_sms_checkbox='';
                            }
                          }

                    ?>

                    <div class="col-xs-12 ">
                           <div class="checkbox icheck" style="padding-left: 16px;">
                            <label>
                              <!-- <input type="checkbox" <?=$send_sms_checkbox;?> class="form-control" id="send_sms" name="send_sms" > <label for="sales_discount" class=" control-label"> -->
                              <input type="hidden" class="form-control" id="send_sms" name="send_sms" >
                              <label for="sales_discount" class=" control-label">
                                <!-- <?= $this->lang->line('send_sms_to_customer'); ?> -->
                                <!-- <i class="hover-q " data-container="body" data-toggle="popover" data-placement="top" data-content="If checkbox is Disabled! You need to enable it from SMS -> SMS API <br><b>Note:<i>Walk-in Customer will not receive SMS!</i></b>" data-html="true" data-trigger="hover" data-original-title="" title="Do you wants to send SMS ?"> -->
                                  <!-- <i class="fa fa-info-circle text-maroon text-black hover-q"></i>
                                </i> -->
                              </label>
                            </label>
                          </div>

                             <!-- /.box-body -->

                       <!-- /.box -->
                    </div>
                </div>
                  <div class="row">

                    <?php if(isset($sales_id)){ $btn_id='update';$btn_name="Cash"; ?>
                      <input type="hidden" name="sales_id" id="sales_id" value="<?php echo $sales_id;?>"/>
                    <?php } else{ $btn_id='save';$btn_name="Cash";} ?>

                    <div class="col-md-12 text-right" style="padding-top:30px">
                      <div class="col-sm-6">
                        <button type="button" id="hold_invoice" name="" class="btn bg-maroon btn-block btn-flat btn-lg" title="Hold Invoice [Ctrl+H]">
                        <i class="fa fa-hand-paper-o" aria-hidden="true"></i>
                        Hold
                      </button>
                      </div>
                      <div class="col-sm-6">
                        <button type="button" id="" name="" class="btn btn-primary btn-block btn-flat btn-lg show_payments_modal" title="Multiple Payments [Ctrl+M]">
                              <i class="fa fa-credit-card" aria-hidden="true"></i>
                              Multiple
                            </button>
                      </div>
                      <div class="col-sm-12" style="padding-top: 25px">
                        <button type="button" id="<?php echo "show_cash_modal";?>" name="" class="btn btn-success btn-block btn-flat btn-lg ctrl_c" title="By Cash & Save [Ctrl+C]">
                              <i class="fa fa-money" aria-hidden="true"></i>
                              <?php echo $btn_name;?>
                            </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        </form>
        <div class="col-md-5"  style="display:none">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <!-- form start -->

              <div class="box-body">

            <!-- row end -->

              <div class="">
                <div class="" style="display:none">
                  <!-- <div class="form-group"> -->
                   <!--  <div class="col-sm-12"> -->
                      <!-- <style type="text/css">

                      </style> -->

                            <section class="content" >
                              <div class="row search_div" style="overflow-y: scroll;min-height: 100px;max-height: 500px;">

                              </div>
                              <h3 class='text-danger text-center error_div' style="display: none;">Sorry! No Records Found</h3>
                            </section>


                    <!-- </div> -->
                  <!-- </div> -->
                </div>
              </div>

              </div>
              <!-- /.box-body -->



          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php include"footer.php";?>
</div>
<!-- ./wrapper -->

<!-- SOUND CODE -->
<?php include"comman/code_js_sound.php"; ?>
<!-- GENERAL CODE -->
<?php include"comman/code_js_form.php"; ?>

<!-- iCheck -->
<script src="<?php echo $theme_link; ?>plugins/iCheck/icheck.min.js"></script>

<script src="<?php echo $theme_link; ?>js/fullscreen.js"></script>
<script src="<?php echo $theme_link; ?>js/modals.js"></script>
<script src="<?php echo $theme_link; ?>js/pos.js"></script>
<script src="<?php echo $theme_link; ?>js/mousetrap.min.js"></script>
<!-- DROP DOWN -->
<script src="<?php echo $theme_link; ?>dist/js/bootstrap3-typeahead.min.js"></script>
<!-- DROP DOWN END-->

<script>
  function check_max_dis(val = 0) {
    if (val == 0) {
      var val = parseFloat($("#discount_input").val()).toFixed(2);
    }

    var get_profit = parseFloat($("#tot_profit").val()).toFixed(2);
    var get_dis = parseFloat($("#item_tot_dis").val()).toFixed(2);
	  var total = parseFloat($(".tot_amt").text());
    var get_tot_dis = 0;

    var discount_type=$("#discount_type").val();
    if(discount_type == 'in_percentage'){
      get_tot_dis = (1 + parseFloat((total*val)/100)).toFixed(2);
    }else{
      get_tot_dis = (1 + parseFloat(val) + parseFloat(get_dis)).toFixed(2);
    }
    console.log(get_tot_dis + " = " + get_profit);


    if (!isNaN(parseFloat(get_tot_dis)) && parseFloat(get_tot_dis) > parseFloat(get_profit)) {
      var max = (get_profit - 1).toFixed(2);
      $('#discount_input').val("");
      toastr['error']("Sorry! Maximum Discount amount is " + max);
      return false;
    }
  }
</script>

<script>
  //RIGHT SIT DIV:-> FILTER ITEM INTO THE ITEMS LIST
  function search_it(){
    console.clear();
    var input = $("#search_it").val().trim();
    var item_count=$(".search_div .search_item").length;
    var error_count=item_count;
    for(i=0; i<item_count; i++){
      if($("#item_"+i).html().toUpperCase().indexOf(input.toUpperCase())>-1){
        // console.log("found");
        $("#item_"+i).show();
        $("#item_parent_"+i).show();
      }else{
      // console.log("not-found");
      $("#item_"+i).hide();
      $("#item_parent_"+i).hide();
      error_count--;
      }
      if(error_count==0){
        $(".error_div").show();
      }
      else{
        $(".error_div").hide();
      }

    }
  }
  //REMOTELY FETCH THE ALL ITEMS OR CATEGORY WISE ITEMS.
  function get_details(){
    $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    $.post("<?php echo $base_url; ?>pos/get_details",{id:$("#category_id").val()},function(result){
      $(".search_div").html('');
      $(".search_div").html(result);
      $(".overlay").remove();
    });
  }

  //LEFT SIDE: ON CLICK ITEM ADD TO INVOICE LIST
  function addrow(id){

    //CHECK SAME ITEM ALREADY EXIST IN ITEMS TABLE
    var item_check=check_same_item($('#div_'+id).attr('data-item-id'));
    if(!item_check){return false;}
    var rowcount        =$("#hidden_rowcount").val();//0,1,2...
    var item_id         =$('#div_'+id).attr('data-item-id');
    var item_name       =$('#div_'+id).attr('data-item-name');
    // var pPrice          =$('#div_'+id).attr('data-item-purchase-price');
    var stock           =$('#div_'+id).attr('data-item-available-qty');
    var mrp             =$('#div_'+id).attr('data-item-mrp');
    //var gst_per         =$('#div_'+id).attr('data-item-tax-per');
    //var gst_amt         =$('#div_'+id).attr('data-item-gst-amt');
    var item_cost       =$('#div_'+id).attr('data-item-cost');
    var sales_price     =$('#div_'+id).attr('data-item-sales-price');
    var item_tax_amt    =$('#div_'+id).attr('data_item_tax_amt');
    var discount        =$('#div_'+id).attr('data-item-discount');
    var profit_margin   =$('#div_'+id).attr('data_item_profit_margin');
    var sales_price_temp=sales_price;
    // sales_price         =(parseFloat(sales_price+item_tax_amt)).toFixed(2);


    var quantity ='<div class="input-group input-group-sm"><span class="input-group-btn"><button onclick="decrement_qty('+item_id+','+rowcount+')" type="button" class="btn btn-default btn-flat"><i class="fa fa-minus text-danger"></i></button></span>';
        quantity +='<input type="text" value="1" class="form-control" onkeyup="item_qty_input('+item_id+','+rowcount+')" id="item_qty_'+item_id+'" name="item_qty_'+item_id+'">';
        quantity +='<input type="hidden" value="'+item_tax_amt+'" class="form-control" id="item_tax_'+item_id+'">';

        quantity +='<input type="hidden" name="dis_hide_'+item_id+'" id="dis_hide_'+item_id+'" value="'+discount+'">';
        quantity +='<input type="hidden" name="mrp_hide_'+item_id+'" id="mrp_hide_'+item_id+'" value="'+mrp+'">';
        quantity +='<input type="hidden" name="profit_hide_'+item_id+'" id="profit_hide_'+item_id+'" value="'+profit_margin+'">';
        quantity +='<input type="hidden" name="profit_row_'+item_id+'" id="profit_row_'+item_id+'" value="'+profit_margin+'">';
        quantity +='<input type="hidden" name="dis_row_'+item_id+'" id="dis_row_'+item_id+'" value="'+discount+'">';

        quantity +='<input type="hidden" id="sales_price_'+rowcount+'" name="sales_price_'+rowcount+'" value="'+sales_price+'">';

        quantity +='<span class="input-group-btn"><button onclick="increment_qty('+item_id+','+rowcount+')" type="button" class="btn btn-default btn-flat"><i class="fa fa-plus text-success"></i></button></span></div>';


    var sub_total       =(parseFloat(1)*parseFloat(sales_price)).toFixed(2);//Initial
    var remove_btn      ='<a class="fa fa-fw fa-trash-o text-red" style="cursor: pointer;font-size: 20px;" onclick="removerow('+rowcount+')" title="Delete Item?"></a>';

        var str=' <tr id="row_'+rowcount+'" data-row="0" data-item-id='+item_id+'>';/*item id*/
        str+='<td id="td_'+rowcount+'_0">'+ item_name +'</td>';/* td_0_0 item name*/
        // str+='<td id="td_'+rowcount+'_7">'+ pPrice +'</td>';/* td_0_7 item purchase price*/
        str+='<td id="td_'+rowcount+'_1">'+ stock +'</td>';/* td_0_1 item available qty*/
        str+='<td id="td_'+rowcount+'_8">'+ mrp +'</td>';/* td_0_8 item MRP*/
        str+='<td id="td_'+rowcount+'_2">'+ quantity +'</td>';/* td_0_2 item available qty*/
        // str+='<td id="td_'+rowcount+'_9">'+ item_tax_amt +'</td>';/* td_0_9 item tax amount*/

        /* info='<input id="sales_price_'+rowcount+'" onblur="set_to_original('+rowcount+','+item_cost+')" onkeyup="update_price('+rowcount+','+item_cost+')" name="sales_price_'+rowcount+'" type="text" class="form-control" value="'+sales_price+'" readonly >'; */

        adis ='<input type="number" value="0" class="form-control" onkeyup="make_subtotal('+item_id+','+rowcount+')" id="item_adis_'+item_id+'" name="item_adis_'+item_id+'" ></input>';

        str+='<td id="td_'+rowcount+'_3" class="text-right">'+ sales_price +'</td>';/* td_0_3 item sales price*/
        str+='<td id="td_'+rowcount+'_6" class="text-right">'+ discount +'</td>';/* td_0_3 item discount*/
        str+='<td id="td_'+rowcount+'_7" class="text-right" style="display:none">'+ adis +'</td>';/* td_0_3 item additional discount*/
        str+='<td id="td_'+rowcount+'_4" class="text-right">'+ sub_total +'</td>';/* td_0_4 item sub_total */
        str+='<td id="td_'+rowcount+'_5">'+ remove_btn +'</td>';/* td_0_5 item gst_amt */

        str+='<input type="hidden" name="tr_item_id_'+rowcount+'" id="tr_item_id_'+rowcount+'" value="'+item_id+'">';
       // str+='<input type="hidden" id="tr_item_per_'+rowcount+'" name="tr_item_per_'+rowcount+'" value="'+gst_per+'">';
        str+='<input type="hidden" id="tr_sales_price_temp_'+rowcount+'" name="tr_sales_price_temp_'+rowcount+'" value="'+sales_price_temp+'">';

        str+='</tr>';

    //LEFT SIDE: ADD OR APPEND TO SALES INVOICE TERMINAL
    $('#pos-form-tbody').append(str);

    //LEFT SIDE: INCREMANT ROW COUNT
    $("#hidden_rowcount").val(parseInt($("#hidden_rowcount").val())+1);
    failed.currentTime = 0;
    failed.play();
    //CALCULATE FINAL TOTAL AND OTHER OPERATIONS
    final_total();
  }

  function update_price(row_id,item_cost){
    /*Input*/
    var sales_price=$("#sales_price_"+row_id).val().trim();
    if(sales_price!='' || sales_price==0) {sales_price = parseFloat(sales_price); }

    /*Default set from item master*/
    var item_price=parseFloat($("#tr_sales_price_temp_"+row_id).val().trim());

    if(sales_price<item_cost){
      //toastr["warning"]("Minimum Sales Price is "+item_cost);
      $("#sales_price_"+row_id).parent().addClass('has-error');
    }else{
      $("#sales_price_"+row_id).parent().removeClass('has-error');
    }

    make_subtotal($("#tr_item_id_"+row_id).val(),row_id);
  }

  function set_to_original(row_id,item_cost) {
    /*Input*/
    var sales_price=$("#sales_price_"+row_id).val().trim();
    if(sales_price!='' || sales_price==0) {sales_price = parseFloat(sales_price); }

    /*Default set from item master*/
    var item_price=parseFloat($("#tr_sales_price_temp_"+row_id).val().trim());

    if(sales_price<item_cost){
      toastr["success"]("Default Price Set "+item_price);
      $("#sales_price_"+row_id).parent().removeClass('has-error');
      $("#sales_price_"+row_id).val(item_price);
    }
    make_subtotal($("#tr_item_id_"+row_id).val(),row_id);
  }


  //INCREMENT ITEM
  function increment_qty(item_id,rowcount){
    var item_qty=$("#item_qty_"+item_id).val();
    var stock=$("#td_"+rowcount+"_1").html();
    if(parseInt(item_qty)<parseInt(stock)){
      item_qty=parseFloat(item_qty)+1;
      $("#item_qty_"+item_id).val(item_qty);
    }
    make_subtotal(item_id,rowcount);
  }
  //DECREMENT ITEM
  function decrement_qty(item_id,rowcount){
    var item_qty=$("#item_qty_"+item_id).val();
    if(item_qty<=1){
      $("#item_qty_"+item_id).val(1);
      return;
    }
    $("#item_qty_"+item_id).val(parseFloat(item_qty)-1);
    make_subtotal(item_id,rowcount);
  }
  //LEFT SIDE: IF ITEM QTY CHANGED MANUALLY
  function item_qty_input(item_id,rowcount){
    var item_qty=$("#item_qty_"+item_id).val();
    var stock=$("#td_"+rowcount+"_1").html();
    if(stock==0){
      toastr["warning"]("item Not Available in stock!");
      //return;
    }
    if(parseInt(item_qty)>parseInt(stock)){
      $("#item_qty_"+item_id).val(stock);
      toastr["warning"]("Oops! You have only "+stock+" items in Stock");
    // return;
    }
    if(item_qty==0){
      $("#item_qty_"+item_id).val(1);
      toastr["warning"]("You must have atlease one Quantity");
      //return;
    }
    make_subtotal(item_id,rowcount);
  }

  function zero_stock(){
    toastr["error"]("Out of Stock!");
    return;
  }
  //LEFT SIDE: REMOVE ROW
  function removerow(id){//id=Rowid
      $("#row_"+id).remove();
      failed.currentTime = 0;
      failed.play();
      final_total();
  }

  //MAKE SUBTOTAL
  function make_subtotal(item_id,rowcount){
    var sales_price     = $("#sales_price_"+rowcount).val();
    var item_qty        = $("#item_qty_"+item_id).val();
    // shahajahan 03-12-2024
    var mrp_price       = parseFloat($("#td_"+rowcount+"_8").html()).toFixed(2);
    var tot_mrp_price   = parseFloat(item_qty)*parseFloat(mrp_price).toFixed(2);
    var dis_hide        = $("#dis_hide_"+item_id).val();
    var profit_hide     = $("#profit_hide_"+item_id).val();
    var dis_ahide       = (isNaN(parseFloat($("#item_adis_"+item_id).val().trim()))) ? 0 :parseFloat($("#item_adis_"+item_id).val().trim());

    var total_discount  = parseFloat(item_qty * dis_hide).toFixed(2);
    $("#td_"+rowcount+"_6").html(parseFloat(total_discount).toFixed(2));
    $("#dis_row_"+item_id).val(total_discount);

    var total_profit    = parseFloat(item_qty * profit_hide).toFixed(2);
    $("#profit_row_"+item_id).val(total_profit);
    // shahajahan 03-12-2024


    //var gst_amt=(tot_sales_price * gst_per)/100;
    var tot_sales_price = parseFloat(item_qty)*parseFloat(sales_price).toFixed(2);
    var subtotal        = parseFloat(tot_sales_price).toFixed(2);

    final_sales_price = (parseFloat(dis_ahide) + parseFloat(total_discount) + parseFloat(subtotal)).toFixed(2);

    if (parseFloat(final_sales_price) > parseFloat(tot_mrp_price)) {
      $("#item_adis_"+item_id).val(0);
      return false;
    } else {
      $("#td_"+rowcount+"_4").html(parseFloat(subtotal).toFixed(2));
    }
    final_total();
  }

  function calulate_discount(discount_input, discount_type, total){
    if(discount_type == 'in_percentage'){
      return parseFloat((total*discount_input)/100);
    }else{//in_fixed
      return parseFloat(discount_input);
    }
  }
  //LEFT SIDE: FINAL TOTAL
  function final_total(){
    var total=0;
    var item_qty=0;
    var total_tax=0;
    var rowcount=$("#hidden_rowcount").val();
    var discount_input=$("#discount_input").val();
    var discount_type=$("#discount_type").val();
    var tot_profit = 0;
    var dis_row = 0;

    if($(".items_table tr").length > 1){
      for(i=0;i<rowcount;i++){
        if(document.getElementById('tr_item_id_'+i)){
          total = parseFloat(total)+ + +parseFloat($("#td_"+i+"_4").html()).toFixed(2);
          item_id = $("#tr_item_id_"+i).val();
          item_qty = parseFloat(item_qty)+ + +parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
          item_tem_qty = parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
          total_tax += (parseFloat($("#item_tax_"+item_id).val()).toFixed(2))*item_tem_qty;
          tot_profit += (parseFloat($("#profit_row_"+item_id).val()));
          dis_row += (parseFloat($("#dis_row_"+item_id).val()));
        }
      }//for end
    }//items_table

    total = parseFloat(total).toFixed(2);
    var discount_amt = calulate_discount(discount_input, discount_type, total);//return value
    // console.log(discount_amt + " " + discount_input + " " + total);
    set_total(item_qty, total, discount_amt, total-discount_amt, total_tax, tot_profit, dis_row);
  }

  function set_total(tot_qty=0, tot_amt=0, tot_disc=0, tot_grand=0, tot_tax = 0, tot_profit = 0, dis_row = 0){
    console.log(tot_amt + "  " + Math.round(tot_amt).toFixed(2));

    $(".tot_qty   ").html(tot_qty);
    $(".tot_amt   ").html(parseFloat(tot_amt).toFixed(2));
    $(".tot_disc  ").html(parseFloat(tot_disc).toFixed(2));
    $(".tot_tax  ").html(parseFloat(tot_tax).toFixed(2));
    $(".tot_grand ").html((parseFloat(tot_grand) + parseFloat(tot_tax)).toFixed(2));
    $("#tot_profit  ").val(parseFloat(tot_profit).toFixed(2));
    $("#item_tot_dis  ").val(parseFloat(dis_row).toFixed(2));
  }

  //LEFT SIDE: FINAL TOTAL
  function adjust_payments(){
    var total=0;
    var item_qty=0;
    var total_tax=0;
    var rowcount=$("#hidden_rowcount").val();
    var discount_input=$("#discount_input").val();
    var discount_type=$("#discount_type").val();
    var tot_profit = 0;
    var dis_row = 0;
    //var discount_amt = parseFloat($(".tot_disc").html());

    if($(".items_table tr").length>1){
      for(i=0;i<rowcount;i++){
        if(document.getElementById('tr_item_id_'+i)){
          total=parseFloat(total)+ + +parseFloat($("#td_"+i+"_4").html()).toFixed(2);
          item_id=$("#tr_item_id_"+i).val();
          item_qty=parseFloat(item_qty)+ + +parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
          item_tem_qty = parseFloat($("#item_qty_"+item_id).val()).toFixed(2)
          total_tax+=(parseFloat($("#item_tax_"+item_id).val()).toFixed(2))*item_tem_qty;
          tot_profit += (parseFloat($("#profit_row_"+item_id).val()));
          dis_row += (parseFloat($("#dis_row_"+item_id).val()));
        }
      }//for end
    }//items_table
    tem_amt = parseFloat(total).toFixed(2);
    total = parseFloat(total).toFixed(2);
    total = (parseFloat(total) + parseFloat(total_tax)).toFixed(2);
    //Find customers payment

    var payments_row = get_id_value("payment_row_count");
    // console.log(payments_row);

    var paid_amount = 0;
    for (var i = 1; i <= payments_row; i++) {
      if(document.getElementById("amount_"+i)){
        paid_amount = parseFloat(paid_amount)+parseFloat((get_id_value("amount_"+i)=='')? 0 : get_id_value("amount_"+i));
        //console.log((get_id_value("amount_"+i)=='')? 0 : get_id_value("amount_"+i));
      }
    }

    //RIGHT SIDE DIV
    var discount_amt = calulate_discount(discount_input, discount_type, tem_amt);//return value
    // console.log(discount_amt + " " + discount_input + " " + discount_type + " " + total);

    var change_return = 0;
    var balance = total - discount_amt - paid_amount;
    if(balance < 0){
      change_return = Math.abs(parseFloat(balance));
      // balance = 0;
    }
    // console.log(balance + " " + total + " " + discount_amt + " " + paid_amount + " " + change_return);


    balance = parseFloat(balance).toFixed(2);
    $(".sales_div_tot_qty  ").html(item_qty);
    $(".sales_div_tot_amt  ").html(parseFloat(total).toFixed(2));
    $(".sales_div_tot_discount ").html(parseFloat(discount_amt).toFixed(2));
    $(".sales_div_tot_payble ").html(parseFloat(total-discount_amt).toFixed(2));
    $(".sales_div_tot_paid ").html(parseFloat(paid_amount).toFixed(2));
    $(".sales_div_tot_balance ").html(balance);

    /**/
    $(".sales_div_change_return ").html((change_return).toFixed(2));
  }

  function check_same_item(item_id){

    if($(".items_table tr").length>1){
      var rowcount=$("#hidden_rowcount").val();
      for(i=0;i<=rowcount;i++){
              if($("#tr_item_id_"+i).val()==item_id){
                increment_qty(item_id,i);
                failed.currentTime = 0;
                failed.play();
                return false;
              }
        }//end for
    }
    return true;
  }

  $(document).ready(function(){
    //FIRST TIME: LOAD
    get_details();

    //FIRST TIME: SET TOTAL ZERO
    set_total();

    //RIGHT DIV: FILTER INPUT BOX
    $("#search_it").on("keyup",function(){
      search_it();
    });

    //CATEGORY WISE ITEM FETCH FROM SERVER
    $("#category_id").change(function () {
        get_details();
    });

    //DISCOUNT UPDATE
    $(".discount_update").click(function () {
        final_total();
        $('#discount-modal').modal('toggle');
    });

    //RIGHT SIDE: CLEAR SEARCH BOX
    $(".show_all").click(function(){
      $("#search_it").val('').trigger("keyup");
      $("#category_id").val('').trigger("change");
    });
    //UPDATE PROCESS START
    <?php if(isset($sales_id) && !empty($sales_id)){ ?>
      $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
      $.get("<?php echo $base_url ?>pos/fetch_sales/<?php echo $sales_id ?>",{},function(result){
        //console.log(result);
        result=result.split("<<<###>>>");
        $('#pos-form-tbody').append(result[0]);
        $('#discount_input').val(result[1]);
        $('#discount_type').val(result[2]);
        $('#customer_id').val(result[3]).select2();
        $('#temp_customer_id').val(result[3]);
        $("#hidden_rowcount").val(parseInt($(".items_table tr").length)-1);
        final_total();
        $(".overlay").remove();
        $("#customer_id").trigger("change");
        if(result[5]==1){
          $( "#binvoice" ).prop( "checked", true );
          $('#binvoice').parent('div').addClass('checked');
        }
      });
      //DISABLE THE HOLD BUTTON
      $("#hold_invoice,#show_cash_modal").attr('disabled',true).removeAttr('id');
    <?php } ?>
    //UPDATE PROCESS END
  });//ready() end

  /* DROP DOWN */
  <?php
    $json_array=array();
    $query1="select id,item_name,stock,item_code from db_items where (upper(item_name) like upper('%%') or upper(item_code) like upper('%%')) and status=1";
    //echo $query1;
    $q1=$this->db->query($query1);
    if($q1->num_rows()>0){
        foreach ($q1->result() as $value) {
            $details=$value->item_code."--".$value->item_name." (".$value->stock.")";
            $json_array[]=["id"=>(int)$value->id, "name"=>$details, "stock" => (int)$value->stock];
            //$json_array[]=$value->item_name;
        }
    }
    $json_array= json_encode($json_array);
  ?>
  $(document).ready(function() {
    $('#item_search').focus();
    $('#item_search').typeahead({
      source:<?php echo $json_array; ?>,
      scroll: true,
      items: 10,
      limit: 10,
      //showHintOnFocus: 10,
      autoSelect: true,
      updater: function (item) {
        return item;
        // console.log(this.map[item].id);
      },
      afterSelect: function (item) {
        if(item.stock==0){
          toastr["error"]("Out of Stock!");
          $("#item_search").val('');
          return;
        }
        if(item.stock < 0){
          toastr["error"]("Out of Stock!");
          $("#item_search").val('');
          return;
        }
        addrow(item.id);
        $("#item_search").val('');
      }
    });
    // hold_invoice_list();
  });

  //DATEPICKER INITIALIZATION
  $('#order_date,#delivery_date,#cheque_date').datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
    todayHighlight: true
  });
  $('#customer_dob,#birthday_person_dob').datepicker({
    calendarWeeks: true,
    todayHighlight: true,
    autoclose: true,
    format: 'dd-mm-yyyy',
    startView: 2
  });
</script>

  <script>
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
  <script type="text/javascript">
    Mousetrap.bind('ctrl+m', function(e) {
      e.preventDefault();
      $('#amount_1').val('').trigger('change');
      $(".show_payments_modal").trigger('click');
    });
    Mousetrap.bind('ctrl+h', function(e) {
      e.preventDefault();
      $("#hold_invoice").trigger('click');
    });
    Mousetrap.bind('ctrl+c', function(e) {
      e.preventDefault();
      $(".ctrl_c").trigger('click');
    });
  </script>
  <script type="text/javascript">

  </script>
</body>
</html>
