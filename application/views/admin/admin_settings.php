<h2 class="display-4 text-center pb-3"><?php echo $this->lang->line('settings_title'); ?></h2>
<div class="d-flex justify-content-center">
    <table class="table border mb-2 col-8">
        <?php if($this->session->tempdata('error')) : ?>
            <td colspan="5" class="table-danger justify-content-center"><?php echo $this->session->tempdata('error'); ?></td>
        <?php endif; ?>
    </table>
</div>

<div class="d-flex justify-content-center">
	<?php 
		$attributes = array('class' => 'col-8'); 
		echo form_open('setting/updateSiteSettings', $attributes); 
		echo validation_errors();
	?>

	<?php foreach($settings as $setting) : ?>
		<?php if($setting['setting_list_value'] == "1" || $setting['setting_list_value'] == "0") : ?>
			<div class="form-group row">
				<label class="col-sm-10 col-form-label dark-blue"><?php echo $this->lang->line('setting_' . $setting['setting_name']); ?></label>
				<div class='col-sm-2'>
					<?php
					$data = array(
						'name'          => $setting['setting_name'],
						'id'            => $setting['setting_id'],
						'value'			=> $setting['setting_name'],
						'checked'       => $setting['setting_list_value'],
						'style'         => 'margin:10px'
					);
					echo form_checkbox($data);
					?>
				</div>	
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php foreach($settings as $setting) : ?>
		<?php if($setting['setting_list_value'] != "1" && $setting['setting_list_value'] != "0") : ?>
			<div class="form-group row">
				<label class="col-sm-2 col-form-label"><?php echo $this->lang->line('setting_' . $setting['setting_name']); ?></label>
				<div class="col-sm-10">
					<?php 
					$data = array(
						'class' => 'form-control',
						'type' => 'text',
						'name' => $setting['setting_name'],
						'value' => $setting['setting_list_value']
					);
					echo form_input($data); 
					?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>

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
