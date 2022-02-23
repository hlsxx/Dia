<?php

  global $db;

  $data = $db->request_data();

  try {
    echo json_encode(\Core\Controllers\UserController::getLogged());
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>