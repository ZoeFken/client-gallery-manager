<?php // Modal voor het editeren van een galerij $gallery['gallery_name'] 
?>
<div class="modal fade" id="editGallery<?php echo $gallery['gallery_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="centerTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="longTitle"><?php echo $this->lang->line('edit_gallery_1'); ?><?php echo $gallery['gallery_name'] ?><?php echo $this->lang->line('edit_gallery_2'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <?php
                $attributes = array('class' => 'col-12');
                echo form_open('gallery/editGallery/' . $gallery['gallery_id'], $attributes);
                ?>
                <div class="form-group row">
                    <?php
                    $data = array(
                        'gallery_id' => $gallery['gallery_id'],
                    );
                    echo form_hidden($data);
                    ?>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <?php
                        $data = array(
                            'class' => 'form-control',
                            'type' => 'text',
                            'name' => 'name',
                            'placeholder' => $this->lang->line('name'),
                            'readonly' => 'true',
                            'value' => $gallery['gallery_name']
                        );
                        echo form_input($data);
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $data = array(
                            'class' => 'form-control',
                            'type' => 'text',
                            'name' => 'includedImages',
                            'placeholder' => $this->lang->line('included_images'),
                            'value' => $gallery['gallery_included']
                        );
                        echo form_input($data);
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $data = array(
                            'class' => 'btn btn-primary btn-block',
                            'type' => 'submit',
                            'value' => $this->lang->line('edit')
                        );
                        echo form_submit($data);
                        ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>