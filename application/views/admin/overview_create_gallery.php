<?php if($user['level'] != ADMIN) : ?>
    <tr id="<?php echo str_replace(' ', '_', $user['user_name']); ?>" class="collapse">
        <td class="col-12">
            <?php 
                $attributes = array('class' => 'form-inline float-right'); 
                echo form_open('gallery/createGallery/' . $user['user_id'], $attributes);
            ?>
            <div class="form_group">
                <?php 
                $data = array(
                    'user_id' => $user['user_id'],
                );
                echo form_hidden($data); 
                ?>
            </div>
            <div class="form_group pr-2">
                <?php 
                $data = array(
                    'class' => 'form-control',
                    'type' => 'text',
                    'name' => 'name',
                    'placeholder' => $this->lang->line('name')
                );
                echo form_input($data); 
                ?>
            </div>
            <div class="form_group pr-2">
                <?php 
                $data = array(
                    'class' => 'form-control',
                    'type' => 'text',
                    'name' => 'includedImages',
                    'placeholder' => $this->lang->line('included_images')
                );
                echo form_input($data); 
                ?>
            </div>
            <div class="form_group">
                <?php 
                $data = array(
                    'class' => 'btn btn-primary btn-block',
                    'type' => 'submit',
                    'value' => $this->lang->line('create')
                );
                echo form_submit($data); 
                ?>
            </div>
            <?php echo form_close(); ?>
        </td>
    </tr>
<?php endif; ?>