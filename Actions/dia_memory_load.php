<?php

  global $webController;

  try {
    $return['pages'] = \Core\Controllers\WebController::getAllMemory();
    $return['currentPage'] = \Core\Controllers\WebController::getCurrentPage();
    echo json_encode($return);
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>