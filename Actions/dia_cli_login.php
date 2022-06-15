<?php

  global $db, $webController;

  $data = \Core\Controllers\WebController::getPostRequest();

  try {
    $selectUserIfExists = [];
    $selectUserIfExists = $db->dbSelect(
      $data['tableName'],
      [
        "where" => [
          "email" => $data['email']
        ]
      ]
    );

    $selectUserIfExists = reset($selectUserIfExists);

    if (empty($selectUserIfExists)) {
      throw new \Exception("User doesnt exists");
    }

    if (!password_verify($data['password'], $selectUserIfExists['password'])) {
      throw new \Exception("Password not valid");
    }

    //\Core\Controllers\UserController::setUser($selectUserIfExists);

    echo json_encode($selectUserIfExists);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>