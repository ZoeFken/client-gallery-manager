<div class="flex-column width-1275">
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
        
            <div class="cssbox">
                <a id="<?php echo $id; ?>" href="#<?php echo $id; ?>">
                    <img class="cssbox_thumb">
                    <span class="cssbox_full">
                        <img src="<?php echo base_url($imageLocation) ?>">
                    </span>
                </a>
                <a class="cssbox_close" href="#void"></a>
                <?php if($counter < $sizeGallery) : ?>
                    <?php $next = $counter+1; ?>
                    <a class="cssbox_next" href="#<?php echo $selected_gallery . $next ?>">&gt;</a>
                <?php endif; ?>
                <?php if($counter > 1) : ?>
                    <?php $prev = $counter-1; ?>
                    <a class="cssbox_prev" href="#<?php echo $selected_gallery . $prev ?>">&lt;</a>
                <?php endif ?>
            </div>
        
        <?php 
            $counter++;
            endforeach; 
        ?> 
    </div>
</div>