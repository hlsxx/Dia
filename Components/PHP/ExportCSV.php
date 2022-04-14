<?php

namespace Component;

class ExportCSV extends \Core\Component {
  
  public function __construct(string $tableName) {
    parent::__construct($this);

    $this->tableName = $tableName;
  }

  public function show() {
    return "
      <dia-export-csv :params='{
        tableName: \"{$this->tableName}\",
        conditions: ".json_encode($this->conditions).",
        data: ".json_encode($this->data)."
      }'></dia-export-csv>
    ";
  }

}
