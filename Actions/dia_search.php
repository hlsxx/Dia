<?php

  global $db;

  $params = $db->getPostRequest();

  if ($params["search"] != "") {
    $searchItems = $db->dbSelect(
      "products",
      [
        "select" => "name as title, id as id, if(type = 1, CONCAT('produkty?id_form=', id), CONCAT('male-produkty?id_form=', id)) as link",
        "like" => [
          "name" => $params["search"]
        ]
      ]
    );

    $return['count'] = count($searchItems);
    $return['hits'] = $searchItems;
  } else {
    $return['count'] = 0;
    $return['hits'] = [];
  }

  echo json_encode($return);

?>