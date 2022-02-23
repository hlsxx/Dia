<?php

namespace Component {

  class Elasticsearch extends \Core\Component {
    public $con;

    private $index;
    private $columns;

    public function __construct($index) {
      parent::__construct($this);

      $this->index = $index;
    }

    private function checkElasticConnection() {
      try {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $connection =  @socket_connect($socket, 'localhost', 9200);
      } catch(\Exception $e) {
        $this->index = "offline";
      }
    }

    public function searchFields($params = array()) {
      $this->columns = $params;
      return $this;
    }

    public function show() {
      $columnsJSON = $this->columns ? json_encode($this->columns) : [];

      $this->checkElasticConnection();

      return "
        <dia-elasticsearch 
          index=\"{$this->index}\"
          searchFields='$columnsJSON'
        ></dia-elasticsearch>
      ";
    }

  }

}

?>