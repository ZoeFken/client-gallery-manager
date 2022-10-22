<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Download de betaalde foto's
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   2
 */
class Download extends MY_Controller 
{
    function __construct()
    {
		parent::__construct();
		$this->checkAcces(LOGIN);
		$this->load->model('download_model');
		$this->load->model('gallery_model');
		if(!$this->settings->getSiteValue('download'))
		{
			$this->myRedirect();
		}
	}

	/**
	 * Download de vastgelegde inclusieve foto's
	 * 
	 * @param $gallery_id
	 */
	public function download($gallery_id)
	{
		if(!$this->checkAcces(ADMIN, FALSE))
        {
            $this->personalGalleryAccess($gallery_id);
        }
		$this->loadLang(LOGS);
		
		$selectedImages = $this->download_model->getLockedImages($gallery_id);
		if($selectedImages == NULL)
		{
			$this->logging->Log($this->session->userdata('id'), '1030', 'Download fail no images locked for ' . $gallery_id);
            $this->session->set_tempdata('error', $this->lang->line('log_1030'), 100);
            $this->myRedirect();
		}
		if($this->validateAmmountLocked($gallery_id, count($selectedImages)))
		{
			$foldername = $this->gallery_model->getGalleryFolderName($gallery_id);
			$this->createZip($selectedImages, $foldername);
			// Update db met downloaded als de downloader geen admin is
			if(!$this->checkAcces(ADMIN, FALSE))
			{
				$this->download_model->setDownloaded($gallery_id);
			}
		}
		else
		{
            $this->logging->Log($this->session->userdata('id'), '1010', 'Downloading not possible, more locked then included from ' . $gallery_id);
            $this->session->set_tempdata('error', $this->lang->line('log_1010'), 100);
            $this->myRedirect();
		}
	}

	/**
	 * Controle van aantal vastgelegde foto's
	 * 
	 * @param $gallery_id, $ammountLockedImages
	 * @return true of false
	 */
	private function validateAmmountLocked($gallery_id, $ammountLockedImages)
	{
		$included = $this->gallery_model->getAmmountIncluded($gallery_id);
		return ($included >= $ammountLockedImages);
	}

	/**
	 * Genereer een zip bestand en download deze
	 * https://www.codeproject.com/Articles/3839889/Streaming-ZIP-File-in-PHP-Without-Temp-File
	 * 
	 * @param $selectedImages
	 * @param $foldername
	 */
	private function createZip($selectedImages, $foldername)
	{
		$directory = DOCROOT . GALLERYS . $foldername . '/original/';
		$selectedDirectory = $directory . 'selected/';

		// check if directory exist if so delete all content
		if(is_dir($selectedDirectory)) 
		{
			$files = glob($selectedDirectory.'*');
			foreach($files as $file)
			{
				if(is_file($file)) 
				{
				  unlink($file);
				}
			}
		} 
		else mkdir($selectedDirectory, 0777);

		foreach($selectedImages as $image)
		{
			$fileName = $directory . $image['image_name'];
			copy($fileName, $selectedDirectory . $image['image_name']);
		}

		// load BjSZipper library file
		$this->load->library('BjSZipper');

		$zip = new BjSZipper($foldername .'.zip');
		$zip->AddDir($selectedDirectory, true, '/\.(jpg|jpeg)/i'); // All JPEGs recursively
		$zip->Send();
	}	
}
