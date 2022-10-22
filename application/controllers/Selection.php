<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Het selecteren van foto's
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Selection extends MY_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        if (!($this->checkAcces(KLANT, FALSE) || $this->checkAcces(ADMIN, FALSE)))
        {
            $this->myRedirect();
        }
        $this->load->model('selection_model');
    }

    /**
     * Genereer een lijst weergave van de vastgelegde foto's 
     * van een specifieke galerij
     * @param $gallery_id
     */
    public function lockedList($gallery_id)
    {
        $this->checkAcces(ADMIN);

		$info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_locked');
        $info['auth'] = 'admin';

        $data = $this->getViewData($gallery_id);

        $this->loadLang('admin_gallery');

        $this->load->view('templates/header', $info);
        $this->load->view('admin/locked_images', $data);
        $this->load->view('templates/footer');
    }

    /**
     * krijg een pdf van de vastgelegde fotos van een specifieke galerij
     * @param $gallery_id
     * @return een .pdf bestand
     */
    public function gallery_mPDF($gallery_id)
    {
        $this->checkAcces(ADMIN);

        $mpdf = new \Mpdf\Mpdf();

        $data = $this->getViewData($gallery_id);
        $html = $this->load->view('admin/locked_images', $data, true);
        $mpdf->WriteHTML($html);

        $filename = $data['selected_gallery'] . '.pdf';
        // D staat voor download
        $mpdf->Output($filename, 'D');
    }

    /**
     * Krijg de data terug voor de lock data view
     * @param $gallery_id
     * @return $data voor de view van lockdata
     */
    private function getViewData($gallery_id)
    {
        $this->load->model('gallery_model');

        $data['gallery_id'] = $gallery_id;
        $data['lockedImages'] = $this->selection_model->getLockedImages($gallery_id);
        $data['selected_gallery'] = $this->gallery_model->getGalleryName($gallery_id);
        $data['ammount_selected'] = $this->gallery_model->getAmmountImagesSelected($gallery_id);
        $data['ammount_included'] = $this->gallery_model->getAmmountIncluded($gallery_id);
        $data['folderName'] = $this->gallery_model->getGalleryFolderName($gallery_id);
        $data['owner'] = $this->gallery_model->whoOwnsGallery($gallery_id);

        return $data;
    }

    /**
     * Selecteer of deselecteer een foto
     * @param $image_id
     */
    public function selectionImage($image_id)
    {
        $this->load->model('image_model');
        $gallery_id = $this->image_model->getImageGalleryID($image_id);

        if(!$this->checkAcces(ADMIN, FALSE))
        {
            $this->personalGalleryAccess($gallery_id);
        }

        if(!$this->selection_model->isImageLocked($image_id))
        {
            $is_selected = ($this->selection_model->isImageSelected($image_id)) ? false : true;
			$this->selection_model->changeSelection($image_id, $is_selected);
			$this->logging->Log($this->session->userdata('id'), '9999', 'Image ' . $image_id . ' adjusted for gallery ' . $gallery_id);
        }
    }

    /**
     * Leg de foto's vast
     * @param $gallery_id
     */
    public function lockImages($gallery_id)
    {
        $personal = FALSE;
        if(!$this->checkAcces(ADMIN, FALSE))
        {
            $this->personalGalleryAccess($gallery_id);
            $personal = TRUE;
        }
        
        $notLockedSelectedImages = $this->selection_model->getAllNotLockedSelectedImages($gallery_id);

        if(is_null($notLockedSelectedImages)) $this->myRedirect();
        
        $sizeSelectedImages = sizeof($notLockedSelectedImages);

        $this->loadLang(LOGS);

        if($notLockedSelectedImages != NULL)
        {
            for($i = 0; $i < $sizeSelectedImages; $i++)
            {
                $notLockedSelectedImages[$i]['image_locked'] = '1';
                $notLockedSelectedImages[$i]['image_locked_at'] = date('Y-m-d H:i:s');
            }

            if($this->selection_model->lockSelectedImages($notLockedSelectedImages))
            {
                if ($personal) 
                {
                    $this->load->model('gallery_model');

                    $emailData = [
                        'email' => $this->session->userdata('email'),
                        'name' => $this->session->userdata('username'),
                        'gallery_name' => $this->gallery_model->getGalleryName($gallery_id)
                    ];

                    $email = ($this->sendLockEmail($emailData)) ? 'email gestuurd' : 'email niet gestuurd';
                };

                $this->logging->Log($this->session->userdata('id'), '920', 'All images of ' . $gallery_id . ' are successfully locked.');
                $this->session->set_tempdata('msg', $this->lang->line('log_920'), 100);
                $this->myRedirect();
            }
            
            $this->logging->Log($this->session->userdata('id'), '930', 'Could not lock images from gallery ' . $gallery_id);
            $this->session->set_tempdata('error', $this->lang->line('log_930'), 100);
            $this->myRedirect();
        }

        $this->logging->Log($this->session->userdata('id'), '940', 'No images selected for gallery ' . $gallery_id);
        $this->session->set_tempdata('error', $this->lang->line('log_940'), 100);
        $this->myRedirect();
    }

    /**
     * Selecteer alle foto's van een specifieke galerij
     * @param $gallery_id
     */
    public function selectAllImages($gallery_id)
    {
        $this->load->model('gallery_model');
        $imagesData = $this->gallery_model->getGalleryImagesData($gallery_id);

        $this->loadLang(LOGS);
        foreach($imagesData as &$image)
        {
            $image['image_selected'] = '1';
        }

        if($this->selection_model->updateAllImages($imagesData))
        {
            $this->logging->Log($this->session->userdata('id'), '950', 'All images of selected for gallery ' . $gallery_id);
            $this->session->set_tempdata('msg', $this->lang->line('log_950'), 100);
            $this->myRedirect();
        }

        $this->logging->Log($this->session->userdata('id'), '960', 'Error with selecting all images of gallery ' . $gallery_id);
        $this->session->set_tempdata('error', $this->lang->line('log_960'), 100);
        $this->myRedirect();
    }

    /**
     * Open alle foto's van een specifieke galerij
     * @param $gallery_id
     */
    public function unlockAllImages($gallery_id)
    {
        $this->checkAcces(ADMIN);

        $this->load->model('gallery_model');
        $imagesData = $this->gallery_model->getGalleryImagesData($gallery_id);

        foreach($imagesData as &$image)
        {
            $image['image_locked'] = '0';
        }

        $this->loadLang(LOGS);
        if($this->selection_model->updateAllImages($imagesData))
        {
            $this->logging->Log($this->session->userdata('id'), '970', 'All images have been unlocked of gallery ' . $gallery_id);
            $this->session->set_tempdata('msg', $this->lang->line('log_970'), 100);
            $this->myRedirect();
        }

        $this->logging->Log($this->session->userdata('id'), '980', 'Could not unlock all images of gallery ' . $gallery_id);
        $this->session->set_tempdata('error', $this->lang->line('log_980'), 100);
        $this->myRedirect();
    }

    /**
     * Verstuur email als de foto's gelocked zijn
     * @param $emaildata
     */
    private function sendLockEmail($emailData)
    {
        $this->config->load('email_config', TRUE); // TRUE zorgt ervoor dat de array op zich staat.
        $this->load->library('email');
        $this->email->clear();

        $subject = 'Vastgelegde foto\'s Fotografie Sandy';
        $message = $this->load->view('emails/vastgelegd_email.php', $emailData, TRUE); // TRUE krijg de data terug zonder het naar de browser te sturen
        $config = $this->config->item('email_config'); // Laad de hele config in $config

        $this->email->initialize($config);
        $this->email->from($config['email_address'], $emailData['name']);
        $this->email->to($config['email_owner']);
        $this->email->subject($subject);
        $this->email->message($message);

        return $this->email->send();
    }
}
