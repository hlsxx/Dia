<?php

  global $db;

  $data = $db->request_data();

  $db->update($data->table_name, $data);

?>