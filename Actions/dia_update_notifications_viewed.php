<?php

  global $db;

  $data = $db->request_data();

  try {
    $db->update(
      tableName: $data->tableName,
      rowId: $data->data->id,
      data: ["viewed" => 1]
    );
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>