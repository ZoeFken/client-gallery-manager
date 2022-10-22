<h2 class="display-4 text-center"><?php echo $this->lang->line('edit_title'); ?></h2>
<?php if ($reset) 
    echo '<p class="text-center">' . base_url('Password/savePassword/'. $reset . '</p>');
?>


<?php if($this->session->tempdata('error')) : ?>
    <table class="table border mb-2 col-8">
        <td colspan="5" class="table-danger justify-content-center"><?php echo $this->session->tempdata('error'); ?></td>
    </table>
<?php endif; ?>
<div class="d-flex justify-content-center">
    <?php 
        $attributes = array('class' => 'col-8'); 
        echo form_open('user/editSpecificUser', $attributes); 
        echo validation_errors();
    ?>
    <div class="form-group row">
        <?php 
        $data = array(
            'id' => $user['user_id'],
            'auth' => $user['user_auth']
        );
        echo form_hidden($data); 
        ?>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue"><?php echo $this->lang->line('email'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'email',
                'name' => 'email',
                'placeholder' => $this->lang->line('email'),
                'value' => $user['user_email']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue"><?php echo $this->lang->line('name'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'name',
                'placeholder' => $this->lang->line('name'),
                'value' => $user['user_name']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue"><?php echo $this->lang->line('firstname'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'firstname',
                'placeholder' => $this->lang->line('firstname'),
                'value' => $user['user_firstname']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('telephone'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'telephone',
                'placeholder' => $this->lang->line('telephone'),
                'value' => $user['user_telephone']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('language'); ?></label>
        <div class="col-sm-10">
            <?php
            $directory = dirname(dirname(dirname(__FILE__))) . '/language';
            $optionsNoVlaue = array_diff(scandir($directory), array('..', '.', 'index.html'));
            $options = array_combine($optionsNoVlaue, $optionsNoVlaue);

            $data = array(
                'class' => 'form-control',
                'name' => 'language'
            );
            echo form_dropdown($data, $options, $user['user_language']); 
            ?>
        </div>
    </div>

    <h4 class="display-5 text-center pb-3"><?php echo $this->lang->line('address'); ?></h4>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('street'); ?></label>
        <div class="col-sm-4">
            <?php
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'street',
                'placeholder' => $this->lang->line('street'),
                'value' => $address['address_street']
            );
            echo form_input($data); 
            ?>
        </div>

        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('number'); ?></label>
        <div class="col-sm-1">
            <?php
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'number',
                'placeholder' => $this->lang->line('number'),
                'value' => $address['address_number']
            );
            echo form_input($data); 
            ?>
        </div>

        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('appartment'); ?></label>
        <div class="col-sm-1">
            <?php
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'appartment',
                'placeholder' => $this->lang->line('appartment'),
                'value' => $address['address_appartment']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('postalcode'); ?></label>
        <div class="col-sm-4">
            <?php
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'postalcode',
                'placeholder' => $this->lang->line('postalcode'),
                'value' => $address['address_postalcode']
            );
            echo form_input($data); 
            ?>
        </div>

        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('city'); ?></label>
        <div class="col-sm-4">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'city',
                'placeholder' => $this->lang->line('city'),
                'value' => $address['address_city']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo $this->lang->line('country'); ?></label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'text',
                'name' => 'country',
                'placeholder' => $this->lang->line('country'),
                'value' => $address['address_country']
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <?php
    if($this->authorize->checkAllow(CREATEADMIN))
    {
        // klant
        echo "<div class='form_group pl-2'>";
        $data = array(
            'name'          => 'clientadmin',
            'id'            => 'client',
            'value'         => 'KLANT',
            'checked'       => $user['klant'],
            'style'         => 'margin:10px'
        );
        echo $this->lang->line('client');
        echo form_radio($data);
        echo "</div>";
    
        // Admin
        echo "<div class='form_group pl-2'>";
        $data = array(
            'name'          => 'clientadmin',
            'id'            => 'admin',
            'value'         => 'ADMIN',
            'checked'       => $user['admin'],
            'style'         => 'margin:10px'
        );
        echo $this->lang->line('admin');
        echo form_radio($data);

        // Create Admin
        $data = array(
            'name'          => 'createadmin',
            'id'            => 'createadmin',
            'value'         => 'createadmin',
            'checked'       => $user['createadmin'],
            'style'         => 'margin:10px'
        );
        echo $this->lang->line('create_admin');
        echo form_checkbox($data);
        echo "</div>";
    }
    ?>

    <div class="form-group row">
        <?php
        $data = array(
            'class' => 'btn btn-primary btn-block',
            'type' => 'submit',
            'value' => $this->lang->line('edit')
        );
        echo form_submit($data); 
        ?>
    </div>
    <?php echo form_close(); ?>   
</div>