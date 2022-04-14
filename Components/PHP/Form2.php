<?php

namespace Component {

  class Form2 extends \Core\Component {

    private array $defaultValues = [];

    public function __construct(string $tableName) {
      parent::__construct($this);

      $this->tableName = $tableName;
    }

    public function defaultValues(array $defaultValues = []) {
      $this->defaultValues = $defaultValues;
      return $this;
    }

    public function show() {
      return "
        <dia-form-new :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data).",
          defaultValues: ".json_encode($this->defaultValues)."
        }'></dia-form-new>
      ";
    }

  }

}

?>