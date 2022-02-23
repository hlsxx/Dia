<?php

  global $db;

  $data = $db->request_data();

  try {
    $db->dbUpdateRow(
      tableName: $data->params->tableName, 
      rowId: $data->params->tableId,
      column: $data->params->column,
      data: $data->params->data
    );
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>