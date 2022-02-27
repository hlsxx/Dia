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

  // Table structure
  $filePath = \Core\Controllers\WebController::getConfig()['dir']['web']
    . "/Tables/".ucfirst($tableName).".json"
  ;

  if (is_file($filePath)) {
    $stringJson = file_get_contents($filePath);
    $structure = (array)json_decode($stringJson);
    $columns = array_keys($structure);
    
    $insertData = [];
    foreach ($columns as $colName) {
      $insertData[$colName] = $structure[$colName]->type;
    }


    foreach ($data as $postName => $postVal) {
      if ($insertData[$postName] == "number") {
        $data[$colName] = (int)$data[$colName];
      } else if($insertData[$postName] == "checkbox") {
        $data[$colName] = (int)(bool)$data[$colName];
      } else if($insertData[$postName] == "lookup") {
        $data[$colName] = (int)$data[$colName];
      }
    }
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