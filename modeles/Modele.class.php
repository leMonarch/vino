<?php
/**
 * Class Modele
 * Les modèles doivent hérités de cette classe.
 * 
 * @author Equipe de 4
 * @version 1.0
 * @update 2013-12-11
 * @license MIT
 * 
 */
class Modele {
	
    protected $_db;
	function __construct ()
	{
		$this->_db = MonSQL::getInstance();
	}
	
	function __destruct ()
	{
		
	}
}




?>