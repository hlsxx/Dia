<?php

namespace Component {

  class ProfileCard extends \Core\Component {

    public int $idUser = 0;
    public string $url = "";

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function idUser(int $idUser) {
      $this->idUser = $idUser;
      return $this;
    }

    public function url(string $url) {
      $this->url = $url;
      return $this;
    }

    public function show() {
      return "
        <dia-profile-card :params='{
          tableName: \"{$this->tableName}\",
          data: ".json_encode($this->data).",
          idUser: {$this->idUser},
          url: \"{$this->url}\"
        }'></dia-profile-card>
      ";
    }

  }

}

?>