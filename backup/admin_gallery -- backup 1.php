<div class="flex-column width-1275">
<?php
    // echo "<pre>";
    // var_dump($folderName);
    // var_dump($selected_gallery);
    // var_dump($gallery_id);
    // var_dump($gallerys);
    // var_dump($visitorLink);
    // var_dump($ammount_selected);
    // var_dump($images);
    // var_dump($owner);
    // echo "</pre>";
?>
<div class="d-flex justify-content-between sticky-top bg-light">
    <div class="p-2 col-4">
        <h1 class="dark-blue"><?php echo $owner['user_firstname'] . ' ' . $owner['user_name']; ?></h1>
        <h4><small class="text-muted"><?php echo str_replace( '_', ' ', $selected_gallery) . ' Galerij'; ?></small></h4>
        <?php echo $this->session->flashdata('gallerymsg'); ?>
    </div>
    <div class="pt-3 h-100 col-4">
        <h6>Geselecteerd<small class="text-muted"><br><?php echo $ammount_selected; ?></small></h6>
        <h6>Inclusief<small class="text-muted"><br><?php echo $ammount_included; ?></small></h6>
    </div>
    <div class="p-2 text-right col-4">
        <?php if($visitorLink == NULL) : ?>
        <a href="<?php echo base_url(); ?>client/createVisitorLink/<?php echo $gallery_id; ?>" class="btn btn btn-sharelink">
            Genereer deelbare link
        </a><br>
        <?php endif; ?>
        <?php if ($this->session->flashdata('clientmsg')) { echo $this->session->flashdata('clientmsg') . '</br>'; } ?>
        <?php if ($visitorLink != NULL) : ?>
            <button onclick="copyToClipboard('#visitor')" class="btn btn-info">Kopieer link</button>
            <a href="<?php echo base_url(); ?>client/deleteVisitorLink/<?php echo $gallery_id; ?>" class="btn btn-sm btn-danger p-2">
                <span data-feather="trash"></span>
            </a> 
            <br><br>
            <input class="form-control mb-2 mr-sm-2" type="text" value="<?php echo $visitorLink; ?>" readonly>
            <div id="visitor" style="display:none;"><?php echo $visitorLink; ?></div>
        <?php endif; ?>
    </div>
</div>
<div id="result" class="d-flex"></div>
    <div class="grid imageGallery" data-masonry='{ "itemSelector": ".grid-item", columnWidth: ".grid-sizer" }'>
        <div class="grid-sizer"></div>

        <?php if ($images != null) : ?>
            <?php
            $counter = 1;
            $sizeGallery = count($images);
            foreach ($images as $img) : 
                $imageLocation = 'img/jpg/' . $folderName . '/' . $img['image_name']; 
                $id = $selected_gallery . $counter;
            ?>
                <div class="grid-item">
                        <a href="#<?php echo $id; ?>">
                            <img src="<?php echo base_url($imageLocation) ?>" alt="<?php echo $selected_gallery . ' ' . $counter ?>">
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
                    <div id="imagelink" class="<?php echo $class ?> selected <?php echo "imagelink_" . $img['image_id']; ?>">
                    <?php if ($img['image_locked'] == '0') : ?>
                        <a href="<?php echo base_url(); ?>selection/selectionImage/<?php echo $img['image_id']; ?>" class="selection-link" ></a>
                    <?php endif; ?>
                    </div>
                </div>

                <div class="cssbox">
                    <a id="<?php echo $id; ?>" href="#<?php echo $id; ?>">
                        <img class="cssbox_thumb">
                        <span class="cssbox_full">
                            <img src="<?php echo base_url($imageLocation) ?>">
                        </span>
                    </a>
                    <div class="<?php echo $class ?> Lightbox-Selected <?php echo "imagelink_" . $img['image_id']; ?>">
                    <?php if ($img['image_locked'] == '0') : ?>
                        <a href="<?php echo base_url(); ?>selection/selectionImage/<?php echo $img['image_id']; ?>" class="selection-link" ></a>
                    <?php endif; ?>
                    </div>
                    <a class="cssbox_close" href="#void"></a>
                    <?php if ($counter < $sizeGallery) : ?>
                        <?php $next = $counter+1; ?>
                        <a class="cssbox_next" href="#<?php echo $selected_gallery . $next ?>">&gt;</a>
                    <?php endif; ?>
                    <?php if ($counter > 1) : ?>
                        <?php $prev = $counter-1; ?>
                        <a class="cssbox_prev" href="#<?php echo $selected_gallery . $prev ?>">&lt;</a>
                    <?php endif ?>
                </div>
            
            <?php 
                $counter++;
                endforeach; 
            ?>
        <?php else : ?>
            <p>Nog geen foto's in de gallerij</p>
        <?php endif; ?>
        </div>
</div>
<script src="<?php echo base_url('assets/js/gallery.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>