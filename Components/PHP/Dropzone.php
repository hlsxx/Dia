<?php

namespace Component {

  class Dropzone extends \Core\Component {
    
    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function show() {
      return "
        <dia-dropzone
          tableName={$this->tableName}
          :conditions='".json_encode($this->conditions)."'
        ></dia-dropzone>";
    }
  
  }

}