<?php
  global $db;

  try {
    $return['data'] = $db->dbSelect(
      \Core\Controllers\WebController::getParam("tableName"),
      (array)\Core\Controllers\WebController::getParam("conditions")
    );

    \Core\Controllers\WebController::getJson($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>