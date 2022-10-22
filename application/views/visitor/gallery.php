<div class="flex-column width-1275">
    <h1>Galerij <?php echo str_replace( '_', ' ', $selected_gallery); ?></h1>
    <h3><?php echo $owner['user_firstname'] . ' ' . $owner['user_name']; ?></h3>

    <div class="grid imageGallery" data-masonry='{ "itemSelector": ".grid-item", columnWidth: ".grid-sizer" }'>
        <div class="grid-sizer"></div>

        <?php
        $counter = 1;
        $sizeGallery = count($images);
        foreach($images as $img) : 
            $imageLocation = 'img/jpg/' . $folderName . '/' . $img['image_name'] . '/' . $link; 
            $id = $selected_gallery . $counter;
            $size = unserialize($img['additional']);
            if($size['height'] != 500 || $size['height'] != 750)
            {
                $size['height'] = ($size['height'] <= 624) ? 500 : 750; 
            }
        ?>
            <div class="grid-item">
                <a class="image-link" href="#<?php echo $id; ?>" data-target="<?php echo $counter; ?>">
                    <img class="lazy" src="<?php echo base_url('assets/images/placeholder-'. $size['height'] . '.jpg'); ?>" data-src="<?php echo base_url($imageLocation); ?>" alt="<?php echo $selected_gallery . ' ' . $counter; ?>">
                </a>
                <div id="imagename"><span><?php echo $img['image_name'] ?></span></div>
            </div>

            <div class="cssbox">
                <a id="<?php echo $id; ?>" href="#<?php echo $id; ?>">
                    <img class="cssbox_thumb">
                    <span class="cssbox_full">
                        <img class="lazybox-<?php echo $counter; ?>" data-src="<?php echo base_url($imageLocation) ?>">
                    </span>
                </a>
                
                <a class="cssbox_close" href="#void"></a>
                <?php if($counter < $sizeGallery) : ?>
                    <?php $next = $counter+1; ?>
                    <a class="cssbox_next image-link" data-target="<?php echo $next ?>" href="#<?php echo $selected_gallery . $next ?>">&gt;</a>
                <?php endif; ?>
                <?php if($counter > 1) : ?>
                    <?php $prev = $counter-1; ?>
                    <a class="cssbox_prev image-link" data-target="<?php echo $prev ?>" href="#<?php echo $selected_gallery . $prev ?>">&lt;</a>
                <?php endif ?>
            </div>
        
        <?php 
            $counter++;
            endforeach; 
        ?> 
        </div>
</div>

<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>
<!-- <script>new SimpleLightbox({elements: '.imageGallery a'});</script> -->