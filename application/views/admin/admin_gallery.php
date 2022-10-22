<div class="flex-column width-1275">
<?php $this->load->view('admin/panel'); ?>
<?php if($this->settings->getSiteValue('select') || $this->settings->getSiteValue('download') || $this->settings->getSiteValue('visitor')) {
	$this->load->view('client/functionality');
} ?>

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
                $size = unserialize($img['additional']);
                if($size['height'] != 500 || $size['height'] != 750)
                {
                    $size['height'] = ($size['height'] <= 624) ? 500 : 750; 
                }
            ?>
                <div class="grid-item">
                    <img class="lazy" src="<?php echo base_url('assets/images/placeholder-'. $size['height'] . '.jpg'); ?>" data-src="<?php echo base_url($imageLocation); ?>" alt="<?php echo $selected_gallery . ' ' . $counter; ?>">
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
					

                    <div class="delete">
                        <a href="<?php echo base_url(); ?>gallery/deleteImage/<?php echo $img['image_id']; ?>/<?php echo $gallery_id; ?>" class="btn btn-danger">
                            <span data-feather="trash"></span>
                        </a>
                    </div>
                </div>
                
            <?php 
                $counter++;
                endforeach; 
            ?>
        <?php else : ?>
            <p><?php echo $this->lang->line('nothing'); ?></p>
        <?php endif; ?>
        </div>
</div>
<script src="<?php echo base_url('assets/js/gallery.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/imagesloaded.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.pkgd.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/masonry.js'); ?>"></script>
