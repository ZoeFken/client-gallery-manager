<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Betalingen
 * 
 * @author    Casteels Pieter-Jan
 * @copyright 2018-2019 Casteels Pieter-Jan / Fotografie Sandy
 * @version   1.8
 */

class Pay extends MY_Controller
{
    public function __construct() 
    {
        parent::__construct();
        if (!($this->checkAcces(KLANT, FALSE) || $this->checkAcces(ADMIN, FALSE)))
        {
            $this->myRedirect();
        }
    }

    /**
     * Basis betaal pagina
     */
    public function index($gallery_id = NULL)
    {
        $this->load->model('gallery_model');

        $info['title'] = $this->settings->getSiteValue('site_name') . " " . $this->lang->line('title_payment');
        $info['auth'] = ($this->checkAcces(ADMIN, FALSE)) ? 'admin' : 'klant';

        $user_id = $this->session->userdata('id');
        $gallerys = $this->gallery_model->getGallerys($user_id);

        if($gallery_id === NULL)
        {
            $gallery_id = $gallerys[0]['gallery_id'];
        }

        $data['gallery_id'] = $gallery_id;
        $data['gallerys'] = $gallerys;
        $data['user_id'] = $user_id;
        $data['ammount_included'] = $this->gallery_model->getAmmountIncluded($gallery_id);
        $data['ammount_selected'] = $this->gallery_model->getAmmountImagesSelected($gallery_id);
        $data['extra_images'] = $this->calculate($data['ammount_included'], $data['ammount_selected']);

        $this->load->view('templates/header', $info);
        $this->load->view('general/pay', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Zet de config van het betalings systeem
     */
    private function setConfig()
    {
        $this->config->load('pay_config', TRUE);
        $config = $this->config->item('pay_config');

        \Paynl\Config::setTokenCode($config['setTokenCode']);
        \Paynl\Config::setApiToken($config['setApiToken']);
        \Paynl\Config::setServiceId($config['setServiceId']);
    }

    /**
     * krijg een pdf van de vastgelegde fotos van een specifieke galerij
     * @param $gallery_id
     * @return een .pdf bestand
     */
    public function getPaymentMethods()
    {
        $this->setConfig();
        
        $paymentMethods = \Paynl\Paymentmethods::getList();
        var_dump($paymentMethods);
    }

    /**
     * Start een transactie met paynl
     */
    public function transaction($ammount)
    {
        $this->setConfig();

        $result = \Paynl\Transaction::start(array(
            // required vars
            'amount' => $ammount,
            'returnUrl' => \Paynl\Helper::getBaseUrl().'/pay/terug',
         
            // optional vars
            'exchangeUrl' => \Paynl\Helper::getBaseUrl().'/pay/exchange',
            'paymentMethod' => 10,
            'bank' => 1,
            'description' => 'Demo payment',
            'testmode' => 1,
            'extra1' => 'ext1',
            'extra2' => 'ext2',
            'extra3' => 'ext3',
            'products' => array(
                array(
                    'id' => 1,
                    'name' => 'Your product',
                    'price' => 5,
                    'tax' => 0.87,
                    'qty' => 1,
                ),
                array(
                    'id' => 2,
                    'name' => 'Other product',
                    'price' => 5,
                    'tax' => 0.87,
                    'qty' => 1,
                )
            ),
            'language' => 'EN',
            'enduser' => array(
                'initials' => 'M',
                'lastName' => 'Pay',
                'gender' => 'M',
                'dob' => '14-05-1999',
                'phoneNumber' => '0612345678',
                'emailAddress' => 'customer@pay.nl',
            ),
            'address' => array(
                'streetName' => 'Test',
                'houseNumber' => '10',
                'zipCode' => '1234AB',
                'city' => 'Test',
                'country' => 'NL',
            ),
            'invoiceAddress' => array(
                'initials' => 'IT',
                'lastName' => 'ITEST',
                'streetName' => 'Istreet',
                'houseNumber' => '70',
                'zipCode' => '5678CD',
                'city' => 'ITest',
                'country' => 'NL',
            ),
        ));
         
        # Save this transactionid and link it to your order
        $transactionId = $result->getTransactionId();
         
        # Redirect the customer to this url to complete the payment
        // $redirect = $result->getRedirectUrl();
        $this->payRedirect($result->getRedirectUrl());
    }

    /**
     * Betalings links redirect
     * @param $redirect de link gegeven door pay.nl
     */
    private function payRedirect($redirect)
    {
        $this->load->helper('url');
        echo anchor($redirect, 'Betalen"', array('target' => '_blank', 'class' => 'new_window'));
    }

    /**
     * Terug keren
     */
    private function terug()
    {
        $this->setConfig();

        $transaction = \Paynl\Transaction::getForReturn();

        //manual transfer transactions are always pending when the user is returned
        if( $transaction->isPaid() || $transaction->isPending())
        {
            echo "betaald, pending";
        } 
        elseif($transaction->isCanceled()) 
        {
            echo "geannuleerd";
        
        }
    }

    /**
     * verander keren
     */
    private function exchange()
    {
        $this->setConfig();

        $transaction = \Paynl\Transaction::getForExchange();

        if($transaction->isPaid() || $transaction->isAuthorized())
        {
            echo "betaald, pending";
        } 
        elseif($transaction->isCanceled())
        {
            echo "geannuleerd";
        }

        // always start your response with TRUE|
        echo "TRUE| ";

        // Optionally you can send a message after TRUE|, you can view these messages in the logs.
        // https://admin.pay.nl/logs/payment_state
        echo ($transaction->isPaid() || $transaction->isAuthorized())?'Paid':'Not paid';
    } 

    /**
     * Bereken het extra aantal foto's
     * @param $included
     * @param $selected
     */
    private function calculate($included, $selected)
    {
        if($included < $selected)
        {
            return (int)$selected - (int)$included;
        }
        return 0;        
    }
}
