<?php

  global $db;

  $data = $db->request_data();

  try {
    $db->dbUpdateRow(
      $data->params->tableName, 
      $data->params->tableId,
      $data->params->column,
      $data->params->data
    );
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>