<h2 class="display-4 text-center pb-3">Registratie formulier</h2>
<div class="login-clean registration-big">
    <?php 
        $attributes = array('class' => 'form-signin'); 
        echo form_open('register/register'); 
        echo validation_errors();
        if($this->session->flashdata('msg'))
        {
            echo $this->session->flashdata('msg');
        }
    ?>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Email'
        );
        echo form_input($data); 
        ?>
    </div>
    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'name',
            'name' => 'name',
            'placeholder' => 'Naam'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'firstname',
            'name' => 'firstname',
            'placeholder' => 'Voornaam'
        );
        echo form_input($data); 
        ?>
    </div>
    
    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'telephone',
            'name' => 'telephone',
            'placeholder' => 'Telefoon'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'street',
            'name' => 'street',
            'placeholder' => 'Straat'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'number',
            'name' => 'number',
            'placeholder' => 'Nummer'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
         $data = array(
            'class' => 'form-control',
            'type' => 'appartment',
            'name' => 'appartment',
            'placeholder' => 'Bus'
        );
        echo form_input($data);
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'postalcode',
            'name' => 'postalcode',
            'placeholder' => 'Postcode'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'city',
            'name' => 'city',
            'placeholder' => 'Stad'
        );
        echo form_input($data); 
        ?>
    </div>

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'form-control',
            'type' => 'country',
            'name' => 'country',
            'placeholder' => 'Land'
        );
        echo form_input($data); 
        ?>
    </div>

    <!-- checkboxes -->
    <div class="form_group pr-4 pl-4">
        <?php
        $data = array(
            'name'          => 'clientadmin',
            'id'            => 'client',
            'value'         => 'KLANT',
            'checked'       => true,
            'style'         => 'margin:10px'
        );
        echo 'Klant';
        echo form_radio($data);
        ?>
    </div> 

    <?php
    if($this->authorize->checkAllow(CREATEADMIN))
    {
        // Admin
        echo "<div class='form_group pr-4 pl-4'>";
        $data = array(
            'name'          => 'clientadmin',
            'id'            => 'admin',
            'value'         => 'ADMIN',
            'checked'       => false,
            'style'         => 'margin:10px'
        );
        echo 'Admin';
        echo form_radio($data);

        // Create Admin
        $data = array(
            'name'          => 'createadmin',
            'id'            => 'createadmin',
            'value'         => 'createadmin',
            'checked'       => false,
            'style'         => 'margin:10px'
        );
        echo 'Create Admin';
        echo form_checkbox($data);
        echo "</div>";
    }
    ?>
    
    <!-- end checkboxes -->

    <div class="form_group pr-4 pl-4">
        <?php 
        $data = array(
            'class' => 'btn btn-primary btn-block',
            'type' => 'submit',
            'value' => 'Register'
        );
        echo form_submit($data); 
        ?>
    </div>
    <?php echo form_close(); ?>   
</div>