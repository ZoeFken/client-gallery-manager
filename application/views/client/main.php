<?php
    /**
     * Maak de galerij navigatie indien enkel een galerij maak geen navigatie
     * Indien meerdere galerijen maak een navigatie met een actieve knop
     */
?>

<div class="flex-column width-1275">
<?php if(count($gallerys) > 1) : ?>
<ul class="nav nav-tabs">
    <?php 
        $segments = $this->uri->segment_array(); 
        $selectedGallery = (isset($segments['2'])) ? $segments['2'] : $gallerys['0']['gallery_id']; 
    ?>
    <?php foreach($gallerys as $gallery) : ?>
        <?php
        $active = '';
        if($selectedGallery == $gallery['gallery_id'])
        {
            $active = ' active';
        }
        ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $active ?>" href="<?php echo base_url(); ?>client/<?php echo $gallery['gallery_id']; ?>"><?php echo str_replace( '_', ' ', $gallery['gallery_name']); ?></a>
        </li>
    <?php endforeach ?>
</ul>
<?php endif ?>
</div>
<?php $this->load->view('client/gallery'); ?>