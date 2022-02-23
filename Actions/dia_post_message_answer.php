<?php

  global $db;

  $data = (array)$db->request_data();
  $data['data'] = (array)$data['data'];

  $customData = [];
  $customData['recipient'] = $data['data']['sender'];
  $customData['sender'] = $data['data']['recipient'];
  $customData['subject'] = $data['data']['subject'];
  $customData['body'] = $data['data']['answer'];

  $db->insert_array([
    'table' => $data['tableName'],
    'table_data' => (array) $customData
  ]);

  $updateData['id_answer'] = $db->getLastItemId($data['tableName'])['id'];

  $db->update(
    tableName: $data['tableName'],
    rowId: $data['rowId'],
    data: $updateData
  );

  echo $updateData['id_answer'];

?>