    <h2 class="display-4 text-center"><?php echo $this->lang->line('contact_title'); ?></h2>
    <?php if($this->session->tempdata('error')) : ?>
    <table class="table border mb-2 col-8">
        <td colspan="5" class="table-danger justify-content-center"><?php echo $this->session->tempdata('error'); ?></td>
    </table>
    <?php endif; ?>
    <div class="d-flex justify-content-center">
    <?php 
        $attributes = array('class' => 'col-8'); 
        echo form_open('contact/sendEmail', $attributes);
        echo validation_errors();
    ?>

    <?php if($this->session->userdata()) : ?>
    <div class="form-group row">
        <?php 
        $data = array(
            'email' => $this->session->userdata('email'), 
            'name' => $this->session->userdata('username')
        );
        echo form_hidden($data); 
        ?>
    </div>
    <?php else : ?>
    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('name'); ?></label>
        <div class="col-sm-10">
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
    </div>

    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('email'); ?></label>
        <div class="col-sm-10">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'email',
            'name' => 'email',
            'placeholder' => $this->lang->line('email')
        );
        echo form_input($data); 
        ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="form_group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('message'); ?></label>
        <div class="col-sm-10">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'text',
            'rows' => '7',
            'name' => 'message',
            'placeholder' => $this->lang->line('message')
        );
        echo form_textarea($data); 
        ?>
        </div>
    </div>

    <div class="g-recaptcha pt-2" data-sitekey="<?php echo $this->config->item('google_key'); ?>"></div>

    <div class="form-group row">
        <?php 
        $data = array(
            'class' => 'btn btn-primary btn-block mt-2',
            'type' => 'submit',
            'value' => $this->lang->line('send')
        );
        echo form_submit($data); 
        ?>
    </div>
    <?php echo form_close(); ?>
</div>

