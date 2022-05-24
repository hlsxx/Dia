<?php

  global $db;

  $params = $db->getPostRequest();

  $searchItems = $db->dbSelect(
    "products",
    [
      "select" => "name as title, id as id, '' as link",
      "like" => [
        "name" => $params["search"]
      ]
    ]
  );

  $return['count'] = count($searchItems);
  $return['hits'] = $searchItems;

  echo json_encode($return);

?>