<?php

  global $dia;

  if (empty($_FILES['file'])) exit();

  $file_to_upload = $_FILES['file'];
  $upload_filename = $file_to_upload['name'];
  $upload_dir = $dia->config['dir']['root']."/Files/dropzone";

  $error = "";

  if (!empty($file_to_upload['error'])) {
    $error = $file_to_upload['error'];
  } else {
    ob_start();

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777);
    }

    $ok = @move_uploaded_file($file_to_upload['tmp_name'], $upload_dir."/".$upload_filename);
    @chmod($upload_dir."/".$upload_filename, 0775);

    $error_tmp = ob_get_contents();
    ob_end_clean();

    if (!$ok) {
      $error = "Upload error: '{$_GET['upload_dir']}{$file_to_upload['name']}'.\n\nError description: $error_tmp";
    }
  }

  echo $error;