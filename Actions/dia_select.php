<?php

  global $db, $webController;

  $data = $db->request_data();

  try {
    $return['data'] = $db->dbSelect(
      $data->params->tableName,
      (array)$data->params->conditions
    );

    \Core\Controllers\WebController::getJson($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>