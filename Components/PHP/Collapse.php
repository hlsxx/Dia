<?php

namespace Component {

  class Collapse extends \Core\Component {

    private array $data = [];
    private string $title = "";

    public function __construct(array $data) {
      parent::__construct($this);

      $this->data = $data;
    }

    public function data(array $data): object {
      $this->data = $data;
      return $this;
    }

    public function title(string $title): object {
      $this->title = $title;
      return $this;
    }

    public function show() {
      return "
        <dia-collapse :params='{
          data: ".json_encode($this->data).",
          title: \"{$this->title}\"
        }'></dia-collapse>
      ";
    }
  
  }

}