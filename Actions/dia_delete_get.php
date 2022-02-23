<?php

  global $db, $webController;

  $tableName = \Core\Controllers\WebController::getParam("table_name");

  // If is $id defined in custom action
  $id = isset($id) ? $id : \Core\Controllers\WebController::getParam("id");

  /** 
   * DELETE request 
   * tableName
   * itemID
   * @return TRUE
  */
  $db->delete(
    $tableName, 
    ['id' => $id]
  );

?>