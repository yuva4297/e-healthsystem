

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>e-Healthcare</b> 
        </div>
        <strong>Copyright &copy; 2018-2019 <a href="<?php echo base_url(); ?>">e-Healthcare</a>.</strong> All rights reserved.
    </footer>
    
    <!-- jQuery UI 1.11.2 -->
    <!-- <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.min.js" type="text/javascript"></script> -->
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/app.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.validate.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/validation.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/fastclick.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/demo.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/moment.js"  type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"  type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/global.js"  type="text/javascript"></script>
    <script src="<?php echo base_url()?>js/ajaxfileupload.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/moment.min.js"  type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/select2.min.js"  type="text/javascript"></script>
    
    <script type="text/javascript">
        var windowURL = window.location.href;
        pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
        var x= $('a[href="'+pageURL+'"]');
            x.addClass('active');
            x.parent().addClass('active');
        var y= $('a[href="'+windowURL+'"]');
            y.addClass('active');
            y.parent().addClass('active');
            $(document).ready(function() {
    $('.js-example-basic-multiple').select2({
    placeholder: "Select one"
});
});
    </script>
  </body>
</html>