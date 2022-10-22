<h2 class="display-4 text-center pb-3">Registratie formulier</h2>
<div class="d-flex justify-content-center">
    <?php 
        $attributes = array('class' => 'col-8'); 
        echo form_open('register/register', $attributes); 
        echo validation_errors();
        if($this->session->flashdata('msg'))
        {
            echo $this->session->flashdata('msg');
        }
    ?>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue">Email</label>
        <div class="col-sm-10">
            <?php 
            $data = array(
                'class' => 'form-control',
                'type' => 'email',
                'name' => 'email',
                'placeholder' => 'Email',
            );
            echo form_input($data); 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue">Naam</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label dark-blue">Voornaam</label>
        <div class="col-sm-10">
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
    </div>
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Telefoon</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Straat</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Nummer</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Bus</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Postcode</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Stad</label>
        <div class="col-sm-10">
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
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Land</label>
        <div class="col-sm-10">
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
    </div>

    <?php
    // checkboxes
    if($this->authorize->checkAllow(CREATEADMIN))
    {
        // Klant
        echo "<div class='form-group'>";
        $data = array(
            'name'          => 'clientadmin',
            'id'            => 'client',
            'value'         => 'KLANT',
            'checked'       => true,
            'style'         => 'margin:10px'
        );
        echo 'Klant';
        echo form_radio($data);
        echo "</div>";

        // Admin
        echo "<div class='form-group'>";
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

    <div class="form-group row">
        <?php 
        $data = array(
            'class' => 'btn btn-purple btn-block dark-purple',
            'type' => 'submit',
            'value' => 'Register'
        );
        echo form_submit($data); 
        ?>
    </div>
    <?php echo form_close(); ?>   
</div>