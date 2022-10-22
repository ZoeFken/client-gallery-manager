<div id="main" class="flex-column width-1275">
<?php
    // echo "<pre>";
    // var_dump($folderName);
    // var_dump($selected_gallery);
    // var_dump($gallery_id);
    // var_dump($gallerys);
    // var_dump($visitorLink);
    // var_dump($ammount_selected);
    // var_dump($images);
    // var_dump($this->session->all_userdata());
    // var_dump($this->session->userdata('email'));
    // var_dump($this->session->userdata('username'));
	// var_dump($owner);
	// echo $this->settings->getSiteValue('download');
    // echo "</pre>";
?>
<?php $this->load->view('client/panel'); ?>
<?php if($this->settings->getSiteValue('select') || $this->settings->getSiteValue('download') || $this->settings->getSiteValue('visitor')) {
	$this->load->view('client/functionality');
} ?>


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

<div id="result" class="d-flex"></div>
    <div id="work-list" class="grid imageGallery" data-masonry='{ "itemSelector": ".grid-item", columnWidth: ".grid-sizer" }'>
        <div class="grid-sizer"></div>

        <?php if ($images != null) : ?>
            <?php
            $counter = 1;
            $sizeGallery = sizeof($images);
            foreach ($images as $img) : 
                $imageLocation = 'img/jpg/' . $folderName . '/' . $img['image_name']; 
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

					<div id="imagename"><span><?php echo $img['image_name'] ?></span></div>

					<?php // select knop op de foto van de galerij ?>
					<?php if($this->settings->getSiteValue('select')) : ?>
						<div id="imagelink" class="<?php echo $class; ?> selected <?php echo "imagelink_" . $img['image_id']; ?>">
						<?php if ($img['image_locked'] == '0') : ?>
							<a href="<?php echo base_url() . 'selection/selectionImage/' . $img['image_id']; ?>" class="selection-link" ></a>
						<?php endif; ?>
						</div>
					<?php endif; ?>

                </div>
                <div class="cssbox">
                    <a id="<?php echo $id; ?>" href="#<?php echo $id; ?>">
                        <img class="cssbox_thumb">
                        <span class="cssbox_full">
                            <img class="lazybox-<?php echo $counter; ?>" data-src="<?php echo base_url($imageLocation) ?>">
                        </span>
					</a>

					<?php // Selectie in lightbox zicht ?>
					<?php if($this->settings->getSiteValue('select')) : ?>
						<div class="<?php echo $class ?> Lightbox-Selected <?php echo "imagelink_" . $img['image_id']; ?>">
						<div id="lightbox-imagename"><span><?php echo $img['image_name'] ?></span></div>
							<?php if ($img['image_locked'] == '0') : ?>
								<a href="<?php echo base_url() . 'selection/selectionImage/' . $img['image_id']; ?>" class="selection-link" ></a>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<div class="noSelection Lightbox-Selected <?php echo "imagelink_" . $img['image_id']; ?>">
							<div id="lightbox-imagename"><span><?php echo $img['image_name'] ?></span></div>
						</div>
					<?php endif; ?>

                    <a class="cssbox_close" href="#void"></a>
                    <?php if ($counter < $sizeGallery) : ?>
                        <?php $next = $counter+1; ?>
                        <a class="cssbox_next image-link" data-target="<?php echo $next ?>" href="#<?php echo $selected_gallery . $next ?>">&gt;</a>
                    <?php endif; ?>
                    <?php if ($counter > 1) : ?>
                        <?php $prev = $counter-1; ?>
                        <a class="cssbox_prev image-link" data-target="<?php echo $prev ?>" href="#<?php echo $selected_gallery . $prev ?>">&lt;</a>
                    <?php endif ?>
                </div>
            
            <?php 
                $counter++;
                endforeach; 
            ?>
        <?php else : ?>
            <p><?php echo $this->lang->line('no_images'); ?></p>
        <?php endif; ?>
        </div>
</div>

<script src="<?php echo base_url('assets/js/gallery.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>

<?php $this->session->unset_tempdata('error'); ?>
<?php $this->session->unset_tempdata('msg'); ?>
