<?php

namespace Component {

  class ProfileView extends \Core\Component {

    public int $showEdit = 1;

    public function __construct(string $tableName) {
      parent::__construct($this);

      $this->tableName = $tableName;
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