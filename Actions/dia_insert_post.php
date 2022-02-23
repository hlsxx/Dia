<?php

  global $db, $dia;

  $data = \Core\Controllers\WebController::getPostParams();

  $tableName = array_pop($data);

  if (array_key_exists("password", $data)) {
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
  }

  if (!empty($_FILES) && $_FILES['size'] > 0) {
    $colNameFile = array_key_first($_FILES);
    $uploadToDir = $tableName;
    $data[$colNameFile] = $_FILES[$colNameFile]['name'];
    require ("{$this->rootDir}/Core/Actions/dia_upload_image.php");
  }

  /** 
   * INSERT request 
   * tableName
   * table_data
   * @return void
  */
  $db->insert_array([
    'table' => $tableName,
    'table_data' => $data
  ]);

  \Core\Controllers\WebController::redirect(
    $dia->convertTableNameToUrl($tableName) 
    . "?id_form=". 
    $db->getLastItemId($tableName)['id']
  );

?>