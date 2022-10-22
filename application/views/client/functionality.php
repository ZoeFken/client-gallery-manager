<nav class="navbar navbar-expand-lg navbar-dark bg-info">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
			<?php if($this->settings->getSiteValue('select')) : ?>
            <li class="nav-item">
                <a class="nav-link text-light" href="<?php echo base_url('selection/selectAllImages/' . $gallery_id); ?>"><?php echo $this->lang->line('select_all'); ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#" data-toggle="modal" data-target="#lock<?php echo $gallery_id ?>"><strong><?php echo $this->lang->line('lock_selection'); ?></strong></a>
                <?php $this->load->view('client/client_modal_lock'); ?>
			</li>
			<?php endif ?>
			<?php if($this->settings->getSiteValue('download')) : ?>
			<li class="nav-item">
                <a class="nav-link text-light" href="<?php echo base_url('download/download/' . $gallery_id); ?>"><?php echo $this->lang->line('download'); ?></a>
            </li>
			<?php endif ?>
        </ul>
		<?php if($this->settings->getSiteValue('visitor')) : ?>
        <ul class="navbar-nav ml-auto">
            <?php if($visitorLink == NULL) : ?>
            <li class="nav-item">
                <a class="nav-link text-light" href="<?php echo base_url('client/createVisitorLink/' . $gallery_id); ?>"><?php echo $this->lang->line('create_visitor_link'); ?></a>
            </li>
            <?php endif; ?>
            <?php if ($visitorLink != NULL) : ?>
            <li class="nav-item">
            <table>
                <tr>
                    <td><a class="nav-link text-light" href="#" onclick="copyToClipboard('#visitor')"><?php echo $this->lang->line('copy_link'); ?> &nbsp;</a></td>
                    <td><a href="<?php echo base_url('client/deleteVisitorLink/'. $gallery_id); ?>" class="btn btn-sm btn-danger p-2">
                        <span data-feather="trash"></span>
                    </a></td>
                </tr>
            </table>
            </li>
            <div id="visitor" style="display:none;">
                <?php echo trim($visitorLink); ?>
            </div>
            <?php endif; ?>
		</ul>
		<?php endif; ?>
    </div>
</nav>
