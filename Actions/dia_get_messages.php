<?php

  global $db;

  $data = $db->request_data();

  try {
    $return['data'] = $db->dbSelect(
      $data->params->tableName,
      [
        "where" => [
          "recipient" => (\Core\Controllers\UserController::getLogged())['email']
        ],
        "order_by" => "viewed ASC"
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