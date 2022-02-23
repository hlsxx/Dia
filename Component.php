<?php

namespace Core {

  class Component extends \Core\DB {

    public $uid = NULL;

    public $tableStructure = NULL;
    public $customComponentName = NULL;
    public $htmlComponent = NULL;

    public $render = FALSE;

    // Common component properties
    public string $tableName = "";
    public array $conditions = [];
    public array $data = [];

    /**
     * Specific component[e.g.: Alert] 
     * object push into Component constructor
     * @return void
     */
    public function __construct($_this) {
      parent::__construct();

      $this->uid = \Core\Bice::getuid();

      $this->VueComponentLoader($_this);
      $this->getTableStructure();
    }

    /**
     * Write created Component into GLOBALS
     * Use for Vue render()
     * @return void
     */
    public function VueComponentLoader($_this) {
      if (is_object($_this)) {
        if (!isset(\Core\Dia::$loadedComponents[$this->uid])) {
          \Core\Dia::$loadedComponents[$this->uid] = $_this;
        } else {
          \Core\Dia::$loadedComponents[\Core\Bice::getUID()] = $_this;
        }
      } else {
        $this->customComponentName = $_this;
        $this->VueComponentLoader($this);
      }
    }

    /**
     * OVERWRITE METHOD in Component
     * IF Component DONT have SHOW METHOD
     * THEN will call this one as Custom
     * Component
     * @return string (Vue component)
     */
    public function show() {
      return "<{$this->customComponentName}/>";
    }

    /**
     * Show again same Component
     * without create new object
     * @return void
     */
    public function view() {
      $this->VueComponentLoader($this);
    }

    /**
     * Set HTML for Component
     * @return void
     */
    public function setHtml($setHtml) {
      $this->htmlComponent = $setHtml;
    }

    /**
     * Render Vue Component
     * If Component is NOT Vue Component
     * but just HTML elements section
     */
    public function preRender() {
      if ($this->htmlComponent) {
        echo $this->htmlComponent;
      } else {
        echo $this->show();
      }
    }

    /**
     * If Component call render
     * $this->render set to TRUE
     * if $this->render is already set
     * it will call view method
     * @return void
     */
    public function render() {
      if ($this->render === TRUE) {
        $this->view();
      } else {
        $this->render = TRUE;
      }
    }

    /**
     * GET Component UID
     * @return string
     */
    public function getUID() {
      return $this->UID;
    }

    /**
     * GET JSON table structure for
     * required fields and else settings
     * @return
     */
    public function getTableStructure() {
      $diaTables = 
        $this->select(
          $this->config['dia_tables'],
          [],
          [],
          FALSE
        )
      ;

      $this->tableStructure = 
        json_decode(
          $diaTables[0]['structure']
        )
      ;

      //$this->tableName = $diaTables[0]['table_name'];
    }

    public function getTableRequiredFields(string $tableName) {
      $requiredFields = [];

      foreach ($this->tableStructure as $tableColumn => $val) {
        if ($this->tableName == $tableName) {
          if ($val->required === TRUE) {
            array_push($requiredFields, $tableColumn);
          }
        }
      }

      return $requiredFields;
    }

    public function getTableLookupFields(string $tableName) {
      foreach ($this->tableStructure as $column => $val) {
        if ($val->lookup) {
          var_dump($val->lookup->table);
        }
      }
    }

    public function vueJson($data) {
      return str_replace("\"", "\'", json_encode($data));
    }

    // Common components methods

    public function conditions(array $conditions) {
      $this->conditions = $conditions;
      return $this;
    }

    public function data(array $data) {
      $this->data = $data;
      return $this;
    }

  }

}