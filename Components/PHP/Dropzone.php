<?php

namespace Component {

  class Dropzone extends \Core\Component {
    
    public function __construct(string $tableName) {
      parent::__construct($this);

      $this->tableName = $tableName;
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