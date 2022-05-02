<?php

// GET tableName paramater
$tableName = \Core\Controllers\WebController::getParam("tableName");

// GET structureType parameter for tpye of structure to load
$structureType = \Core\Controllers\WebController::getParam("structureType");

// File path of .json file e.g.: Products.json
$filePath = \Core\Controllers\WebController::getConfig()['dir']['web']
	. "/Tables/" // default folder
	.($structureType != "default" ? "{$structureType}/" : "") // If is custom type Tables/CustomType/Products.json
	.ucfirst($tableName).".json"
;

if (is_file($filePath)) {
	$stringJson = file_get_contents($filePath);
	echo $stringJson;
	exit();
} else {
	echo json_encode([
		"status" => "fail"
	]);
}
