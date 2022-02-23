<?php

namespace Component {

  class Tiket extends \Core\Component {

    private string $title = '';
    private string $desc = '';

    public function __construct() {
      parent::__construct($this);
    }

    public function title(string $title): object {
      $this->title = $title;
      return $this;
    }

    public function desc(string $desc): object {
      $this->desc = $desc;
      return $this;
    }

    public function show() {
      return "
        <dia-tiket :params='{
          data: ".json_encode($this->data).",
          title: \"{$this->title}\",
          desc: \"{$this->desc}\"
        }'></dia-tiket>
      ";
    }

  }

}