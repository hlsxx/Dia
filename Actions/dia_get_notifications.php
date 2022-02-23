<?php

  global $db;

  $data = $db->request_data();

  try {
    $return['data'] = $db->dbSelect(
      $data->params->tableName,
      [
        "where" => [
          "id_user" => (\Core\Controllers\UserController::getLogged())['id']
        ]
      ]
    );

    \Core\Controllers\WebController::getJson($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>