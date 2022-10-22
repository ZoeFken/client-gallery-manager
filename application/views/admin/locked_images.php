
<div class="flex-column width-1275 pb-1">
<?php $this->load->view('admin/panel'); ?>
<?php if(isset($lockedImages)) : ?>
    <table class="table table-striped border mb-0" style="width: 100%;">
        <thead>
            <tr class="d-flex">
                <th scope="col" class="col-1"><?php echo $this->lang->line('hashtag'); ?></th>
                <th scope="col" class="col-4"><?php echo $this->lang->line('thumb'); ?></th>
                <th scope="col" class="col-3"><?php echo $this->lang->line('photo_name'); ?></th>
                <th scope="col" class="col-4"><?php echo $this->lang->line('photo_locked_on'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if($this->session->tempdata('msg')) : ?>
            <td colspan="5" class="table-success justify-content-center"><?php echo $this->session->tempdata('msg'); ?></td>
        <?php elseif($this->session->tempdata('error')) : ?>
            <td colspan="5" class="table-danger justify-content-center"><?php echo $this->session->tempdata('error'); ?></td>
        <?php endif; ?>
        <?php foreach($lockedImages as $image) : ?>
        <?php $imageLocation = 'img/jpg/' . $folderName . '/' . $image['image_name']; ?>
        <tr class='d-flex'>
            <td scope="row" class="col-1"><?php echo $image['image_id']; ?></td>
            <?php // inline style voor mpdf ?>
            <td class="col-4"><img src="<?php echo base_url($imageLocation); ?>" style="max-height: 100px; max-width: 100px;" alt="<?php $image['image_name']?>"></td>
            <td class="col-3"><?php echo $image['image_name']; ?></td>
            <td class="col-4"><?php echo $image['image_locked_at']; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div>
    <!-- <a href="<?php // echo base_url('selection/gallery_mPDF/' . $gallery_id); ?>" class="mt-2 btn btn-purple btn-lg btn-block"><?php // echo $this->lang->line('generate_pdf'); ?></a> -->
    </div>
<?php else : ?>
    <h4><?php echo $this->lang->line('nothing_locked'); ?></h4>
<?php endif; ?>
</div>
