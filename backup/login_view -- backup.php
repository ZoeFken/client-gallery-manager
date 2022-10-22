    <div class="login-page">
    <div class="login-clean login-small">
            <h2 class="sr-only">Login Form</h2>
            <?php $attributes = array('class' => 'form-signin'); 
            echo form_open('login/authenticate', $attributes); ?>

            <?php if($this->session->tempdata('error')) : ?>
                <div class="alert alert-danger mt-2" role="alert">
                    <?php echo $this->session->tempdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="illustration"><img class="img-fluid" src="<?php echo base_url(); ?>assets/images/logo_horizontal.png" alt="Logo Fotografie Sandy"></div>

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
                    'type' => 'password',
                    'name' => 'password',
                    'placeholder' => 'Password'
                );
                echo form_password($data); 
                ?>
            </div>
            <div class="form_group">
                <?php 
                $data = array(
                    'class' => 'btn btn-primary btn-block p-2 mt-2',
                    'type' => 'submit',
                    'value' => 'Log in'
                );
                echo form_submit($data); 
                ?>
            </div>
          <?php echo form_close(); ?>   
    </div>
    </div>

<?php $this->session->unset_tempdata('error'); ?>
<?php $this->session->unset_tempdata('msg'); ?>