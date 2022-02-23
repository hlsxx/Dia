<?php

namespace Component {

  class Gallery extends \Core\Component {

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-gallery :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data)."
        }'></dia-gallery>
      ";
    }

  }

}

?>