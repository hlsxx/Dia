<?php

namespace Component {

  class JsonEditor extends \Core\Component {

    public array $conditions = ["select" => "structure"];

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function show() {
      return "
        <json-editor :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data)."
        }'></json-editor>
      ";
    }

  }

}

?>