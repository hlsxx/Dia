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
    'table' => $data->tableName,
    'table_data' => (array) $data->data
  ]);

   /** 
   * GET inserted item ID 
   * tableName
   * @return int
  */
  echo json_encode($db->getLastItemId($data->tableName));

?>