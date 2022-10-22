        <!-- footer -->
        <footer class="footer page-footer font-small blue pt-4 text-center mt-auto">
            <p><?php echo $this->lang->line('copyright_1') . date("Y") . $this->lang->line('copyright_2'); ?>
            <a href="#"><?php echo $this->lang->line('name_site'); ?></a></p>
        </footer>
        <!-- end footer -->
    </div>
    <!-- end wrapper -->
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/feather-replace.js"></script>
    
    <?php $this->session->unset_tempdata('error'); ?>
    <?php $this->session->unset_tempdata('msg'); ?>

</body>
</html>