<?php

  global $db;

  $data = $db->request_data();

  if (isset($data->params->table_id)) {
    echo json_encode(
      $db->select(
        $data->params->tableName,
        [
          "where" => "id = {$data->params->table_id}"
        ],
        $data->params->form_inputs,
      )
    );
  } else echo json_encode($db->select($data->params->tableName));

?>