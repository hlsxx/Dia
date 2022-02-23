<?php

$return['data'] = \Core\Controllers\UserController::getLogged();
\Core\Controllers\WebController::getJson($return);