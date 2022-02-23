<?php

namespace Component {

  class Timer extends \Core\Component {
    
    public function __construct() {
      parent::__construct($this);
    }

    public function show() {
      return "<dia-timer></dia-timer>";
    }
  
  }

}