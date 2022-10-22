
<div class="flex-column">
    <h1><?php echo $this->session->userdata('username'); ?></h1>
    <h3><?php echo $selected_gallery . ' Galerij'; ?></h3>

    <div class="grid imageGallery" data-masonry='{ "itemSelector": ".grid-item", columnWidth: ".grid-sizer" }'>
    <div class="grid-sizer"></div>
    <?php foreach($images as $img) : ?>
        <div class="grid-item">
                <a href="<?php echo base_url() . '/gallerys/' . $folderName . '/' . $img['image_name'] ?>" data-lightbox="gallery">
                    <img src="<?php echo base_url() . '/gallerys/' . $folderName . '/' . $img['image_name'] ?>">
                </a>
                <div class="is selected"></div>
            </div>
    <?php endforeach; ?>
    </div>
</div>

<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>
<script>new SimpleLightbox({elements: '.imageGallery a'});</script>