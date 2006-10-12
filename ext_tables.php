<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(Array('LLL:EXT:jsr170repo_demo/locallang_db.php:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');


t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/","JSR170Repo Document");

$tempColumns = Array (
	"tx_jsr170repodemo_select_jsr170repo" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:jsr170repo_demo/locallang_db.php:tt_content.tx_jsr170repodemo_select_jsr170repo",		
		"config" => Array (
			"type" => "group",	
			"internal_type" => "db",	
			"allowed" => "tx_jsr170support_repo",	
			"size" => 1,	
			"minitems" => 0,
			"maxitems" => 1,
		)
	),
	"tx_jsr170repodemo_repo_doc_config" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:jsr170repo_demo/locallang_db.php:tt_content.tx_jsr170repodemo_repo_doc_config",		
		"config" => Array (
			"type" => "text",
			"cols" => "30",	
			"rows" => "5",
		)
	),
);


t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);
//t3lib_extMgm::addToAllTCAtypes("tt_content","tx_jsr170repodemo_select_jsr170repo;;;;1-1-1, tx_jsr170repodemo_repo_doc_config");
t3lib_extMgm::addToAllTCAtypes("tt_content","tx_jsr170repodemo_select_jsr170repo;;;;1-1-1, tx_jsr170repodemo_repo_doc_config","list");


?>