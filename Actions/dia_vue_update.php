<?php

  global $db;

  $data = $db->request_data();

  $db->update(
    tableName: (string)$data->params->tableName,
    rowId: (int)$data->params->rowId,
    data: (array)$data->params->data
  );

?>