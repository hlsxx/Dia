<?php

namespace Component {

  class Memory extends \Core\Component {

    public function __construct() {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-memory :params='{
          tableName: \"{$this->tableName}\",
          data: ".json_encode($this->data)."
        }'></dia-memory>
      ";
    }

  }

}

?>