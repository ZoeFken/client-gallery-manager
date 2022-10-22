<?php // Modal ter verificatie voor het deactiveren van een gebruiker ?>
<div class="modal fade" id="deactivateUser<?php echo $user['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="centerTitle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?php echo $this->lang->line('deactivate_user'); ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body alert alert-warning" role="alert">
        <p><?php echo $this->lang->line('1_deactivate_user'); ?><?php echo '<strong>' . $user['user_name'] . ' ' . $user['user_firstname'] . '</strong>'; ?><?php echo $this->lang->line('2_deactivate_user'); ?></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" href="<?php echo base_url(); ?>user/deactivateClient/<?php echo $user['user_id']; ?>" role="button"><?php echo $this->lang->line('deactivate_user'); ?></a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
      </div>
    </div>

  </div>
</div>