<?php

global $db, $webController;

$data = $db->request_data();

if ($data->params->logout === true) {
  \Core\Controllers\UserController::destroyLogged();
}

echo "home";