<?php 
$class = ' active';
$active = $admin = $inactive = '';

$segments = $this->uri->segment_array(); 
$selection = $this->session->userdata('location');

switch ($selection)
{
    case 'active':
        $active = $class;
        break;
    case 'admin':
        $admin = $class;
        break;
    case 'inactive':
        $inactive = $class;
        break;
    default:
        $active = $class;
        break;
}
?>

<div class="flex-column width-1275 pb-1">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link<?php echo $active ?>" href="<?php echo base_url(); ?>overview/active"><?php echo $this->lang->line('active'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo $admin ?>" href="<?php echo base_url(); ?>overview/admin"><?php echo $this->lang->line('admin'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php echo $inactive ?>" href="<?php echo base_url(); ?>overview/inactive"><?php echo $this->lang->line('inactive'); ?></a>
        </li>
    </ul>
</div>
<?php $this->load->view('admin/overview_table'); ?>