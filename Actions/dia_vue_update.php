<?php

  global $db;

  $data = $db->request_data();

  $db->update(
    (string)$data->params->tableName,
    (int)$data->params->rowId,
    (array)$data->params->data
  );

?>