
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $theme_link; ?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $theme_link; ?>dist/js/app.js"></script>
<!-- Select2 -->
<script src="<?php echo $theme_link; ?>plugins/select2/select2.full.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $theme_link; ?>dist/js/demo.js"></script>
<!--Toastr notification -->
<script src="<?php echo $theme_link; ?>toastr/toastr.js"></script>
<script src="<?php echo $theme_link; ?>toastr/toastr_custom.js"></script>
<!-- Custom JS -->
<script src="<?php echo $theme_link; ?>js/custom.js"></script>
<!-- sweet alert -->
<script src="<?php echo $theme_link; ?>js/sweetalert.min.js"></script>
<!-- Autocomplete -->
<script src="<?php echo $theme_link; ?>plugins/autocomplete/autocomplete.js"></script>


<!-- CSRF Token Protection -->
<script type="text/javascript" >
  $(function($) { // this script needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({ data: {'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' }  });
  });
</script>
<!-- Initialize Select2 Elements -->
<script type="text/javascript"> $(".select2").select2(); </script>

<script type="text/javascript">
  $(document).ready(function () { setTimeout(function() {$( ".alert-dismissable" ).fadeOut( 1000, function() {});}, 10000); });
</script>

