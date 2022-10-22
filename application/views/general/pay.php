<div class="flex-column width-1275">
<?php
    echo "<pre>";
    var_dump($gallery_id);
    var_dump($gallerys);
    var_dump($user_id);
    var_dump($ammount_included);
    var_dump($ammount_selected);
    var_dump($extra_images);
    echo "</pre>";
?>
<?php if($this->session->tempdata('msg')) : ?>
    <div class="alert alert-success mt-2" role="alert">
        <?php echo $this->session->tempdata('msg'); ?>
    </div>
<?php endif; ?>
<?php if($this->session->tempdata('error')) : ?>
    <div class="alert alert-danger mt-2" role="alert">
        <?php echo $this->session->tempdata('error'); ?>
    </div>
<?php endif; ?>
<h1>Betalingen</h1>
</div>