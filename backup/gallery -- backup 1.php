<?php
    // echo "<pre>";
    // var_dump($this->session->all_userdata());
    // var_dump($gallerys);
    // var_dump($images);
    // var_dump($folderName);
    // var_dump($selected_gallery);
    // echo "</pre>";
?>

<div class="flex-column width-1275">
    <h1><?php echo $this->session->userdata('username'); ?></h1>
    <h3><?php echo str_replace( '_', ' ', $selected_gallery) . ' Galerij'; ?></h3>

    <div class="grid imageGallery" data-masonry='{ "itemSelector": ".grid-item", columnWidth: ".grid-sizer" }'>
    <div class="grid-sizer"></div>
    <?php foreach($images as $img) : ?>
    <div class="grid-item">
            <?php $imageLocation = 'img/jpg/' . $folderName . '/' . $img['image_name']; ?>
                <a href="<?php echo base_url($imageLocation) ?>" data-lightbox="gallery">
                    <img src="<?php echo base_url($imageLocation) ?>">
                </a>
                <?php
                $class = '';
                $selected = $img['image_selected'];
                switch($selected)
                {
                    case '0';
                        $class = 'isNot';
                        break;
                    case '1';
                        $class = 'is';
                        break;
                    default;
                        $class = 'isNot';
                        break;
                }
                if($img['image_locked'] == '1')
                {
                    $class = 'locked';
                }
                ?>
            <div class="<?php echo $class ?> selected"></div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>
<script>new SimpleLightbox({elements: '.imageGallery a'});</script>