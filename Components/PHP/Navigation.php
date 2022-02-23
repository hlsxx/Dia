<?php

namespace Component {

  class Navigation extends \Core\Component {

    public function __construct() {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-navigation
        ></dia-navigation>
      ";
    }

  }

}