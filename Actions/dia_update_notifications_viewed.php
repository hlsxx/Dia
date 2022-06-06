<?php

  global $db;

  $data = $db->request_data();

  try {
    $db->update(
      $data->tableName,
      $data->data->id,
      ["viewed" => 1]
    );
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>