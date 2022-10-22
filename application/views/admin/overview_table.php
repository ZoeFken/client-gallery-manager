
<div class="flex-column width-1275">
    <table class="table border mb-0">
        <thead>
            <tr class="d-flex">
                <th scope="col" class="col-1"><?php echo $this->lang->line('hashtag'); ?></th>
                <th scope="col" class="col-3"><?php echo $this->lang->line('name'); ?></th>
                <th scope="col" class="col-4"><?php echo $this->lang->line('email'); ?></th>
                <th scope="col" class="col-1"><?php echo $this->lang->line('selected'); ?></th>
                <th scope="col" class="col-1"><?php echo $this->lang->line('included'); ?></th>
                <th scope="col" class="col-2"><?php echo $this->lang->line('actions'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php if($this->session->tempdata('msg')) : ?>
            <td colspan="5" class="table-success justify-content-center"><?php echo $this->session->tempdata('msg'); ?></td>
        <?php elseif($this->session->tempdata('error')) : ?>
            <td colspan="5" class="table-danger justify-content-center"><?php echo $this->session->tempdata('error'); ?></td>
        <?php endif; ?>
        <?php foreach($users as $user)
        { 
            $this->load->view('admin/overview_users', Array('user' => $user));
            $this->load->view('admin/overview_create_gallery', Array('user' => $user));
            // kijk of er reeds galerijen zijn
            if(array_key_exists("gallery", $user))
            {
                foreach($user['gallery'] as $gallery)
                {
                    $this->load->view('admin/overview_gallerys', Array('gallery' => $gallery, 'user' => $user)); 
                }
            }
        } ?>
        </tbody>
    </table>
</div>