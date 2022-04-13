<?php

namespace Component {

  class TableLarge extends \Core\Component {

    private array $buttons = [];
    private array $tableButtons = [];
    private array $customColumns = [];
    private string $emptyDataMessage = "";
    private string $fileDir= "";

    private $showSaveButton = "true";
    private $showDeleteButton = "true";

    private string $structureName = "";

    public function __construct(string $tableName) {
      parent::__construct($this);

      $this->tableName = $tableName;
      $this->structureName = $tableName;
    }

    public function buttons(array $buttons) {
      $this->buttons = $buttons;
      return $this;
    }

    public function tableButtons(array $buttons) {
      $this->tableButtons = $buttons;
      return $this;
    }

    public function customColumns(array $columns) {
      $this->customColumns = $columns;
      return $this;
    }

    public function fileDir(string $fileDir) {
      $this->fileDir = $fileDir;
      return $this;
    }

    public function emptyDataMessage(string $message) {
      $this->emptyDataMessage = $message;
      return $this;
    }

    public function showSaveButton($show) {
      $this->showSaveButton = (string)$show;
      return $this;
    }

    public function showDeleteButton($show) {
      $this->showDeleteButton = (string)$show;
      return $this;
    }

    public function useStructure(string $structureName) {
      $this->structureName = $structureName;
      return $this;
    }

    public function show() {
      return "
        <dia-table-large :params='{
          tableName: \"{$this->tableName}\",
          conditions: ".json_encode($this->conditions).",
          data: ".json_encode($this->data).",
          buttons: ".json_encode($this->buttons).",
          tableButtons: ".json_encode($this->tableButtons).",
          customColumns: ".json_encode($this->customColumns).",
          emptyDataMessage: \"{$this->emptyDataMessage}\",
          fileDir: \"{$this->fileDir}\",
          showSaveButton: \"{$this->showSaveButton}\",
          showDeleteButton: \"{$this->showDeleteButton}\",
          structureName: \"{$this->structureName}\"
        }'></dia-table-large>
      ";
    }

  }

}

?>