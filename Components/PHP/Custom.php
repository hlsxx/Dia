<?php

namespace Component {

  class Custom extends \Core\Component {
    public $con;

    public $html;

    public function __construct(string $html) {
      parent::__construct($this);

      $this->html = $html;
    }

    public function show() {
      return "
        <dia-custom>
          {$this->html} 
        </dia-custom>
      ";
    }

  }

}

?>