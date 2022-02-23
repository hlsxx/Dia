<?php

namespace Component {

  class TableList extends \Core\Component {

    private $list = NULL;
    private array $columns = [];
    private array $addedColumns = [];
    private string $actionButton = "";
    private string $hideValue = "";

    public function __construct(public string $tableName) {
      parent::__construct($this);
    }

    public function columns(array $columns) : object {
      $this->columns = $columns;
      return $this;
    }

    public function addColumn(array $column) : object {

      if (empty($this->addedColumns)) {
        $this->addedColumns = $column;
      } else {
        array_push($this->addedColumns, $column);
      }
      return $this;

    }

    public function actionButton(array $params) : object {
      $this->actionButton = json_encode($params);
      return $this;
    }

    public function checkActionButton() : void {
      if ($this->actionButton == "") {
        $this->actionButton = '{}';
      }
    }

    public function hide(string $string) : object {
      $this->hideValue = $string;
      return $this;
    }

    public function show() : string {

      $this->checkActionButton();

      $this->list = $this->dbSelect(
        tableName: $this->tableName, 
        conditions: $this->conditions,
        vueJson: TRUE,
        mergeWith: $this->addedColumns
      );

      return "
        <dia-table-list
          tableName={$this->tableName}
          :list=\"{$this->list}\"
          :columns=\"".$this->vueJson($this->columns)."\"
          :actionButton='{$this->actionButton}'
          hideValue='{$this->hideValue}'
        ></dia-table-list>
      ";
    }

  }

}

?>