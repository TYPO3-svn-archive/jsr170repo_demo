<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2004 Dimitri Ebert (dimitri.ebert@dkd.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Plugin 'JSR170Repo Document' for the 'jsr170repo_demo' extension.
 *
 * @author	Dimitri Ebert <dimitri.ebert@dkd.de>
 */

/**
 * Diese Erweiterung dient als Beispiel für die Entwicklung von Plugins mit JSR170-Repository-Unterstützung
 *
 */

require_once(PATH_tslib."class.tslib_pibase.php");

/**
 * Einbindung von JSR170 kompatiblen Klassen
 *
 */
require_once(t3lib_extMgm::extPath("jsr170support")."class.jsr170support_connect.php");
require_once(t3lib_extMgm::extPath("jsr170support")."class.jsr170support_div.php");


class tx_jsr170repodemo_pi1 extends tslib_pibase {
	var $prefixId = "tx_jsr170repodemo_pi1";		// Same as class name
	var $scriptRelPath = "pi1/class.tx_jsr170repodemo_pi1.php";	// Path to this script relative to the extension dir.
	var $extKey = "jsr170repo_demo";	// The extension key.
	
	/**
	 * [Put your description here]
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		

		if($this->cObj->data['tx_jsr170repodemo_select_jsr170repo'] > 0){

	   		//lokale Konfiguration für Repository aus DB auslesen
			$this->repoConfId = $this->cObj->data['tx_jsr170repodemo_select_jsr170repo'];
			$query = "SELECT  * FROM  tx_jsr170support_repo WHERE hidden=0 AND deleted=0 AND uid='".$this->repoConfId."'";
			$res = mysql(TYPO3_db,$query);
			$row = mysql_fetch_assoc($res);
	
			//Konfigurationstext parsen
			$this->TSdataArray = t3lib_TSparser::checkIncludeLines_array(explode("\n",$row['connectconfig']));
			$RepoTS = implode(chr(10).'[GLOBAL]'.chr(10),$this->TSdataArray);
			$parseObj = t3lib_div::makeInstance('t3lib_TSparser');
			$parseObj->parse($RepoTS);
			$repoconf = $parseObj->setup;
	
		}
		else {
			//sonst default über TS Plugin-Konfiguration
			$repoconf = $conf;	

		}




	 	// Konfiguration für Contentelement parsen	
		$this->TSdataArray = t3lib_TSparser::checkIncludeLines_array(explode("\n",$this->cObj->data['tx_jsr170repodemo_repo_doc_config']));
		$RepoTS = implode(chr(10).'[GLOBAL]'.chr(10),$this->TSdataArray);
		$parseObj = t3lib_div::makeInstance('t3lib_TSparser');
		$parseObj->parse($RepoTS);
		$contentconf = $parseObj->setup;


		// Objekte für JSR170-Unterstützung bilden und Verbindung zur Repository aufbauen 
		$JSR170Helper = t3lib_div::makeInstance('jsr170support_div');		
		$JSR170Object = t3lib_div::makeInstance('jsr170support_connect');
		$JSR170Object -> connect($repoconf);			

		// Knoten nach Konfiguration finden und Properties abarbeiten und zur Ausgabe vorbereiten
		$node = $JSR170Object -> rootNode  -> getNode($contentconf['path']); 
		foreach($contentconf['property.'] as $number => $propconf ){
			$property = $node -> getProperty($propconf['name']);
			$value = $property -> getString();  
			
			$content .= str_replace("|",$value,$propconf['wrap']);

		}

	
		return $this->pi_wrapInBaseClass($content);
	}
	
}



if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/jsr170repo_demo/pi1/class.tx_jsr170repodemo_pi1.php"])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/jsr170repo_demo/pi1/class.tx_jsr170repodemo_pi1.php"]);
}

?>