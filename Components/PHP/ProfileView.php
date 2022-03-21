<?php

namespace Component {

  class ProfileView extends \Core\Component {

    public int $showEdit = 1;

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function showEdit(bool $showEdit) {
      $this->showEdit = (int)$showEdit;
      return $this;
    }

    public function show() {
      return "
        <dia-profile-view :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data).",
          showEdit: {$this->showEdit}
        }'></dia-profile-view>
      ";
    }

  }

}

?>