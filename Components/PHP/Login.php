<?php

namespace Component {

  class Login extends \Core\Component {

    public bool $error = false;

    public function __construct(public String $tableName) {
      parent::__construct($this);
    }

    public function loginInput(string $loginInput) {
      $this->data['loginInput'] = $loginInput;
      $this->data['loginVal'] = "";
      return $this;
    }

    public function passwordInput(string $passwordInput) {
      $this->data['passwordInput'] = $passwordInput;
      $this->data['passwordVal'] = "";
      return $this;
    }

    public function error(bool $error) {
      $this->error = $error;
      return $this;
    }

    public function show() {
      return "
        <dia-login :params='{
          tableName: \"{$this->tableName}\",
          data: ".json_encode($this->data).",
          error: \"{$this->error}\"
        }'></dia-login>
      ";
    }

  }

}

?>