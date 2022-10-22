<?php // Modal voor het vastleggen van de foto's ?>
<div class="modal fade" id="lock<?php echo $gallery_id; ?>" tabindex="-1" role="dialog" aria-labelledby="centerTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo $this->lang->line('lock_selection'); ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body alert alert-warning" role="alert">
                <p><?php echo $this->lang->line('warning_selection'); ?></p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" href="<?php echo base_url(); ?>selection/lockImages/<?php  echo $gallery_id; ?>" role="button"><?php echo $this->lang->line('lock_selection'); ?></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>