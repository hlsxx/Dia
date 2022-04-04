<?php

  global $db, $dia;

  $data = \Core\Controllers\WebController::getPostParams();

  // If is not using PHP POST try AJAX POST
  if (empty($data)) {
    $data = json_decode(json_encode($db->request_data()), true);
    $axiosPost = true;
  }

  $tableName = array_pop($data);

  if (array_key_exists("password", $data)) {
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
  }
;
  if (!empty($_FILES)) {
    if (isset($_FILES['size']) && $_FILES["size"] > 0) {
      $colNameFile = array_key_first($_FILES);
      $uploadToDir = $tableName;
      $data[$colNameFile] = $_FILES[$colNameFile]['name'];
      require ("{$this->rootDir}/Core/Actions/dia_upload_image.php");
    } else {
      $data["image"] = "default.jpg"; // Set default image
    }
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
      $insertData[$colName] = $structure[$colName]->type ?? "text";
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

  try {
    $insertedId = $db->insert_array([
      'table' => $tableName,
      'table_data' => $data
    ]);

    if (isset($axiosPost)) {
      echo $insertedId;
    } else {
      \Core\Controllers\WebController::redirect(
        $dia->convertTableNameToUrl($tableName) 
        . "?id_form=". 
        $db->getLastItemId($tableName)['id']
      );
    }
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>