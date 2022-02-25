<?php

  global $db;

  $data = $db->request_data();

  /** 
   * INSERT request 
   * tableName
   * table_data
   * @return void
  */
  $db->insert_array([
    'table' => $data->params->tableName,
    'table_data' => (array) $data->params->data
  ]);

   /** 
   * GET inserted item ID 
   * tableName
   * @return int
  */
  echo json_encode($db->getLastItemId($data->params->tableName)['id']);

?>