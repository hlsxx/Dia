<?php

$tableName = \Core\Controllers\WebController::getParam("tableName");
$filePath = \Core\Controllers\WebController::getConfig()['dir']['web']
	. "/Tables/".ucfirst($tableName).".json"
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
