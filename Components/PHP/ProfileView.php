<?php

namespace Component {

  class ProfileView extends \Core\Component {

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-profile-view :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data)."
        }'></dia-profile-view>
      ";
    }

  }

}

?>