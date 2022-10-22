<tr class="d-flex">
    <th class="col-1"></th>
    <td class="col-3"></td>
    <td class="col-4 gallery-color"><?php echo str_replace('_', ' ', $gallery['gallery_name']); ?></td>
    <td class="col-1 gallery-color"><?php echo $gallery['ammount_selected']; ?></td>
    <td class="col-1 gallery-color"><?php echo $gallery['gallery_included']; ?></td>
    <td class="col-2 gallery-color">
        <div class="btn-group" role="group" aria-label="Group action for a Client">
            <a href="<?php echo base_url(); ?>gallery/visitGallery/<?php echo $gallery['gallery_id']; ?>" class="btn btn-sm btn-info">
                <span data-feather="eye"></span>
            </a>
            <a href="<?php echo base_url(); ?>image_upload/upload_page/<?php echo $gallery['gallery_id']; ?>" class="btn btn-sm btn-warning">
                <span data-feather="upload"></span>
            </a>
            <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#editGallery<?php echo $gallery['gallery_id'] ?>">
                <span data-feather="edit"></span>
            </a>

            <?php
            $this->load->view('admin/overview_modal_gallery', array('gallery' => $gallery));
            ?>

            <a href="<?php echo base_url(); ?>gallery/deleteGallery/<?php echo $gallery['gallery_id']; ?>" class="btn btn-sm btn-danger">
                <span data-feather="trash"></span>
            </a>
        </div>
    </td>
</tr>