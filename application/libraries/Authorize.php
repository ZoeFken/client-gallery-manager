<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Autorisatie systeem
 * gebaseerd op binair rekenen
 * constanten gedefineerd in application/config/constants.php
 */
class Authorize
{
	private $rights;
	
	public function __construct()
	{
		$this->setBaseRights(0);
	}

	/**
     * Zet de rechten op de basis (0)
     * @param $base de basis rechten meegegeven in constructor
     */
	private function setBaseRights($base)
	{
		$this->rights = $base;
	}

	/**
     * Zet de intiger waarde van de rechten
     * @param $numeric de specifieke rechten intiger
     */
	public function setRights($numeric)
	{
		if (is_integer($numeric) && $numeric >= 0) {
			$this->rights = $numeric;
		}
	}

	/**
     * Krijg de intiger waarde van de rechten
     * @return $rights de intiger waarde van de rechten
     */
	public function getRightsDecimal()
	{
		return $this->rights;
	}
    
    /**
     * Mag de gebruiker een bepaalde actie doen
     * @param $rightbit de specifieke rechten bit
     */
	public function checkAllow($rightbit)
	{
		return (bool) ($this->rights & $rightbit);
	}
    
    /**
     * Geef de gebruiker een specifiek recht
     * @param $rightbit de specifieke rechten bit
     */
	public function grant($rightbit)
	{
		$this->rights = $this->rights | $rightbit;
	}
    
    /**
     * Verwijder een specifiek recht
     * @param $rightbit de specifieke rechten bit
     */
	public function revoke($rightbit)
	{
		$this->rights = $this->rights & ~$rightbit;
	}
}