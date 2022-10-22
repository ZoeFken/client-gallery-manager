<div class="flex-column width-1275 p-2">
    <h1><?php echo $this->session->userdata('username'); ?></h1>
    <h3><?php echo str_replace( '_', ' ', $selected_gallery) . ' Galerij'; ?></h3>
    <div class="masonry masonry--h">
        <?php
        $counter = 1;
        $sizeGallery = count($images);
        foreach($images as $img) : 
            $imageLocation = 'img/jpg/' . $folderName . '/' . $img['image_name']; 
            $id = $selected_gallery . $counter;
        ?>
            <figure class="masonry-brick masonry-brick--h">
                <a href="#<?php echo $id; ?>">
                    <img src="<?php echo base_url($imageLocation) ?>" class="masonry-img" alt="<?php echo $selected_gallery . ' ' . $counter ?>">
                </a>
            </figure>
        
        <div id="<?php echo $id; ?>" class="perfundo__overlay fadeIn qa-overlay-img1">
            <figure class="perfundo__content perfundo__figure">
                <img src="<?php echo base_url($imageLocation) ?>" alt="Demo image">
                <div class="perfundo__image" style="width: 700px; height: 700px; padding-top: 66.25%; background-image: url(<?php echo base_url($imageLocation) ?>);"></div>
            </figure>
            <a href="#perfundo-untarget" class="perfundo__close perfundo__control">Close</a>
            <?php if($counter < $sizeGallery) : ?>
            <?php $next = $counter+1; ?>
            <a class="perfundo__next perfundo__control qa-next-img1" href="#<?php echo $selected_gallery . $next ?>">Next</a>
            <?php endif; ?>
            <?php if($counter > 1) : ?>
            <?php $prev = $counter-1; ?>
            <a class="perfundo__prev perfundo__control" href="#<?php echo $selected_gallery . $prev ?>">Prev</a>
            <?php endif ?>
        </div>
        <?php 
            $counter++;
            endforeach; 
        ?> 
    </div>
</div>