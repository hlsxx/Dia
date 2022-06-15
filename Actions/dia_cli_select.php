<?php

  global $db, $webController;

  $data = \Core\Controllers\WebController::getParams();

  try {
    $return['data'] = $db->dbSelect(
      $data["tableName"],
      isset($data["conditions"]) ? $data["conditions"] : []
    );

    \Core\Controllers\WebController::getJson($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>