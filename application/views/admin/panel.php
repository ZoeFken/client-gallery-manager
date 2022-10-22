<?php // het admin paneel ?>
<div class="panel panel-container text-center sticky-top">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6 no-padding">
                <?php if(isset($gallery_id)) : ?>
                    <a class="no-hover" href="<?php echo base_url('selection/unlockAllImages/' . $gallery_id) ?>">
                <?php endif; ?>
                <div id="gallery-name" class="panel panel-widget border-rechts">
                    <div class="row no-padding d-flex flex-column">
                        <div class="panel-icon">
                            <span data-feather="user"></span>
                        </div>
                        <div class="medium mt-2"><?php echo str_replace( '_', ' ', $selected_gallery) . ' Galerij'; ?></div>
                        <div class="medium"><?php echo $owner['user_firstname'] . ' ' . $owner['user_name']; ?></div>
                        <div class="medium">
                            <?php 
                            if (isset($gallery_download)) {
                                if ($gallery_download === true) 
                                echo $this->lang->line('downloaded');
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php if(isset($gallery_id)) : ?>
                    </a>  
                <?php endif; ?>
            </div>
            <div class="col-6 col-md-3 col-lg-3 no-padding">
            <?php if(isset($gallery_id)) : ?>
            <a class="no-hover" href="<?php echo base_url('selection/lockedList/' . $gallery_id) ?>">
            <?php endif; ?>
                <div id="gallery-selected" class="panel panel-widget border-rechts">
                    <div class="row no-padding d-flex flex-column">
                        <div class="panel-icon">
                            <span data-feather="image"></span>
                        </div>
                        <div class="large"><span id='ammount_selected'><?php echo $ammount_selected; ?></span></div>
                        <div class="text-muted"><?php echo $this->lang->line('selected'); ?></div>
                    </div>
                </div>
            <?php if(isset($gallery_id)) : ?>
            </a>  
            <?php endif; ?>
            </div>
            <div class="col-6 col-md-3 col-lg-3 no-padding">
                <div id="gallery-included" class="panel panel-widget">
                    <div class="row no-padding d-flex flex-column">
                        <div class="panel-icon">
                            <span data-feather="bookmark"></span>
                        </div>
                        <div class="large"><span id='ammount_included'><?php echo $ammount_included; ?></span></div>
                        <div class="text-muted"><?php echo $this->lang->line('included'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>