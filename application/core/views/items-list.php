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
            <?= form_open('#', array('class' => '', 'id' => 'table_form')); ?>
            <input type="hidden" id='base_url' value="<?=$base_url;?>">

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
                                    <a class="btn btn-block btn-info "
                                        href="<?php echo $base_url; ?>items/item_adjustment"><?= $this->lang->line('item_adjustment'); ?></a>

                                    <a class="btn btn-block btn-info " href="<?php echo $base_url; ?>items/add">
                                        <i class="fa fa-plus "></i> <?= $this->lang->line('new_item'); ?></a>
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
                                <table id="example2" class="table table-bordered table-striped" width="100%">
                                    <thead class="bg-primary ">
                                        <tr>
                                            <th class="text-center">
                                                <input type="checkbox" class="group_check checkbox">
                                            </th>
                                            <th><?= $this->lang->line('image'); ?></th>
                                            <th><?= $this->lang->line('item_code'); ?></th>
                                            <th><?= $this->lang->line('item_name'); ?></th>
                                            <th><?= $this->lang->line('brand'); ?></th>
                                            <th><?= $this->lang->line('category'); ?></th>
                                            <th><?= $this->lang->line('lot_number'); ?></th>
                                            <th><?= $this->lang->line('unit'); ?></th>
                                            <th><?= $this->lang->line('available_quantity'); ?></th>
                                            <th><?= $this->lang->line('alert_quantity'); ?></th>
                                            <th><?= $this->lang->line('sales_price'); ?></th>
                                            <th><?= $this->lang->line('tax'); ?></th>
                                            <th><?= $this->lang->line('status'); ?></th>
                                            <th><?= $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>

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
            <?= form_close();?>
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
    <script src="<?php echo $theme_link; ?>plugins/lightbox/ekko-lightbox.js"></script>
    <script type="text/javascript">
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        //datatables
        var table = $('#example2').DataTable({

            /* FOR EXPORT BUTTONS START*/
            dom: '<"row margin-bottom-12"<"col-sm-12"<"pull-left"l><"pull-right"fr><"text-center margin-left-10 "B>>>tip',
            /* dom:'<"row"<"col-sm-12"<"pull-left"B><"pull-right">>> <"row margin-bottom-12"<"col-sm-12"<"pull-left"l><"pull-right"fr>>>tip',*/
            buttons: {
                buttons: [{
                        className: 'btn bg-red color-palette btn-flat hidden delete_btn pull-left',
                        text: 'Delete',
                        action: function(e, dt, node, config) {
                            multi_delete();
                        }
                    },
                    {
                        extend: 'copy',
                        className: 'btn bg-teal color-palette btn-flat',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn bg-teal color-palette btn-flat',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn bg-teal color-palette btn-flat',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn bg-teal color-palette btn-flat',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn bg-teal color-palette btn-flat',
                        exportOptions: {
                            columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn bg-teal color-palette btn-flat',
                        text: 'Columns'
                    },

                ]
            },
            /* FOR EXPORT BUTTONS END */

            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.
            "responsive": true,
            language: {
                processing: '<div class="text-primary bg-primary" style="position: relative;z-index:100;overflow: visible;">Processing...</div>'
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('items/ajax_list')?>",
                "type": "POST",

                complete: function(data) {
                    $('.column_checkbox').iCheck({
                        checkboxClass: 'icheckbox_square-orange',
                        /*uncheckedClass: 'bg-white',*/
                        radioClass: 'iradio_square-orange',
                        increaseArea: '10%' // optional
                    });
                    call_code();
                    //$(".delete_btn").hide();
                },

            },

            //Set column definition initialisation properties.
            "columnDefs": [{
                    "targets": [0, 12], //first column / numbering column
                    "orderable": false, //set not orderable
                },
                {
                    "targets": [0],
                    "className": "text-center",
                },

            ],
        });
        new $.fn.dataTable.FixedHeader(table);
        
    });
    </script>
    <script>
      setInterval(() => {
        $('.red').closest('tr').css('background', '#e9caca');
      }, 1000);
    </script>


    <script src="<?php echo $theme_link; ?>js/items.js"></script>

    <!-- Make sidebar menu hughlighter/selector -->
    <script>
    $(".<?php echo basename(__FILE__,'.php');?>-active-li").addClass("active");
    </script>

</body>

</html>