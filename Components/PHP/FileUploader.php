<?php

namespace Component {

  class FileUploader extends \Core\Component {

    public string $uploadText = "";

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function uploadText(string $text) {
      $this->uploadText = $text;
      return $this;
    }

    public function show() {
      return "
        <dia-file-uploader :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data).",
          uploadText: \"{$this->uploadText}\"
        }'></dia-file-uploader>
      ";
    }

  }

}