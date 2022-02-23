<?php

  global $webController;

  try {
    echo json_encode(\Core\Controllers\WebController::destroyMemory());
  } catch(\Exception $e) {
    echo json_encode([
      "status" => "fail",
      "message" => $e->getMessage()
    ]);
  }

?>