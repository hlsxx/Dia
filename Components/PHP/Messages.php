<?php

namespace Component {

  class Messages extends \Core\Component {
    
    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-messages :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data)."
        }'></dia-messages>
      ";
    }
  
  }

}