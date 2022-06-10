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

    \Core\Controllers\UserController::setUser($selectUserIfExists);

    echo 1;
  } catch(\Exception $e) {
    echo $e->getMessage();
  }

?>