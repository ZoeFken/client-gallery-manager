<div class="d-flex justify-content-center">
    <h2 class="sr-only"><?php echo $this->lang->line('edit_password_title'); ?></h2>
    <?php 
        $attributes = array('class' => 'col-8'); 
        echo form_open('password/editPassword', $attributes); ?>
    <div class="text-center"><h3><?php echo $email; ?></h3></div>
    <?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>
    <div class="form-group row">
        <?php 
        $data = array(
            'email' => $email
        );
        echo form_hidden($data); 
        ?>
    </div>

    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('old_password'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'password',
                'name' => 'oldpassword',
                'placeholder' => $this->lang->line('old_password')
            );
            echo form_password($data); 
            ?>
        </div>
    </div>
    <hr>
    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('new_password'); ?></label>
        <div class="col-sm-10">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'password',
            'name' => 'password',
            'placeholder' => $this->lang->line('new_password')
        );
        echo form_password($data); 
        ?>
        </div>
    </div>

    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('check_password'); ?></label>
        <div class="col-sm-10">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'checkpassword',
            'name' => 'checkpassword',
            'placeholder' => $this->lang->line('check_password')
        );
        echo form_password($data); 
        ?>
        </div>
    </div>

    <div class="form-group row">
        <?php 
        $data = array(
            'class' => 'btn btn-primary btn-block mt-2',
            'type' => 'submit',
            'value' => $this->lang->line('save')
        );
        echo form_submit($data); 
        ?>
    </div>
    <?php echo form_close(); ?>   
</div>