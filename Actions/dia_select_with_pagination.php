<?php

  global $db, $webController;

  $data = $db->request_data();

  $conditions = array_merge((array)$data->params->conditions, ["select" => "count(*) as count"]);

  $totalCount = $db->dbSelect(
    $data->params->tableName, 
    $conditions
  );

  $pagination = \Core\Bice::pagination(
    $data->params->conditions->currentPage ?? 1,
    16,
    reset($totalCount)['count']
  );

  $conditions = array_merge(
    ["limit" => "16 OFFSET {$pagination['offset']}"],
    (array)$data->params->conditions ?? [],
  );

  try {
    $return['data'] = $db->dbSelect(
      $data->params->tableName,
      $conditions
    );
    $return['pages'] = $pagination['pages'];

    \Core\Controllers\WebController::getJson($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>