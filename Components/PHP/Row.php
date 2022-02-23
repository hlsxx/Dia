<?php

namespace Component {

  class Row extends \Core\Component {

    private string $title = "";

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function title(string $title) {
      $this->title = $title;
      return $this;
    }

    public function show() {
      return "
        <dia-row :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data).",
          title: \"{$this->title}\"
        }'></dia-row>
      ";
    }

  }

}

?>