<tr class="d-flex">
    <th scope="row" class="col-1"><?php echo $user['user_id']; ?></th>
    <td class="col-3"><?php echo $user['user_name'] . ' ' . $user['user_firstname']; ?></td>
    <td class="col-4"><?php echo $user['user_email']; ?></td>
    <td class="col-1"></td>
    <td class="col-1"></td>
    <td class="col-2">
        <div class="btn-group" role="group" aria-label="Group action for a Client">
            <a href="<?php echo base_url(); ?>User/edit/<?php echo $user['user_id']; ?>" class="btn btn-sm btn-info">
                <span data-feather="edit"></span>
            </a>
            <?php if($user['level'] != ADMIN) : ?>
                <a href="#" class="btn btn-sm btn-warning" data-toggle="collapse" data-target="#<?php echo str_replace(' ', '_', $user['user_name']); ?>">
                    <span data-feather="image"></span>
                </a>
            <?php endif; ?>
            <a href="<?php echo base_url(); ?>activation/activateUser/<?php echo $user['user_id']; ?>" class="btn btn-sm btn-secondary">
                <span data-feather="send"></span>
            </a>
            <?php if($type != 'inactive') : ?>
                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deactivateUser<?php echo $user['user_id'] ?>">
                    <span data-feather="trash"></span>
                </a>
                <?php $this->load->view('admin/overview_modal_deactivate_user'); ?>
            <?php endif; ?>
            <?php if($type == 'inactive') : ?>
                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteUser<?php echo $user['user_id'] ?>">
                    <span data-feather="trash"></span>
                </a>
                <?php $this->load->view('admin/overview_modal_delete_user'); ?>
            <?php endif; ?>
        </div>
    </td>
</tr>