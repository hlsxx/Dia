<?php

global $db;

$tableName = \Core\Controllers\WebController::getParam("tableName");

/*$filePath = \Core\Controllers\WebController::getConfig()['dir']['web']
	. "/Tables/Navigations/"
	.ucfirst($tableName).".json"
;

if (is_file($filePath)) {
	$tableStructure = json_decode(file_get_contents($filePath), TRUE);
  $menuData = $tableStructure["menuData"];
}*/

$navbarList = $db->dbSelect(
  $tableName,
  [
    "order_by" => "order_index ASC"
  ]
);

foreach ($navbarList as $flatItem) {
  if ($flatItem['id_parent'] == 0) {
    $children = [];
    foreach ($navbarList as $flatItemSub) {
      if ($flatItemSub['id_parent'] == $flatItem['id']) {
        $children[] = [
          "name" => $flatItemSub['name'],
          "link" => $flatItemSub['link'],
          "icon" => $flatItemSub['icon'],
        ];
      }
    }

    $menuItems[] = [
      "name" => $flatItem['name'],
      "link" => $flatItem['link'],
      "icon" => $flatItem['icon'],
      "is_enabled" => $flatItem['is_enabled'],
      "childrens" => $children,
    ];
  }
}

echo json_encode($menuItems);
?>