<?php

namespace Component {

  class Popover extends \Core\Component {

    private string $title = "";
    private string $icon = "";

    public function __construct(string $title) {
      parent::__construct($this);

      $this->title = $title;
    }

    public function icon(string $icon): object {
      $this->icon = $icon;
      return $this;
    }

    public function show() {
      return "
        <popover :params='{
          title: \"{$this->title}\",
          icon: \"{$this->icon}\",
          uid: \"{$this->uid}\",
          borderWidth: \"{$this->borderWidth}\",
          backgroundColor: ".json_encode($this->backgroundColor).",
          borderColor: ".json_encode($this->borderColor)."
        }'></popover>
      ";
    }

  }

}

?>