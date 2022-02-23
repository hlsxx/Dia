<?php
  global $db;
  global $client;

  // GET data from AXIOS POST
  $data = $db->request_data();

  // If search input is NOT empty
  if ($data->search != "") {
    // PARAMS for ELASTICSEARCH
    $params = [
      'index' => $data->index,
      'body'  => [
          'query' => [
            "query_string" => [
              "query" => "*". $data->search . "*",
              "fields" => json_decode($data->searchFields)
            ]
          ]
      ]
    ];
          
    // RESPONSE from localhost:9200
    $response = $client->search($params);

    $return['count'] = $response['hits']['total']['value'];
    $return['hits'] = $response['hits']['hits'];
  } else {
    $return['hits'] = [];
  }

  echo json_encode($return);