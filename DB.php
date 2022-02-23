<?php

namespace Core {
  class DB extends \Core\Dia {

    public $con;

    public function __construct() {
      parent::__construct();
      global $con;
      $this->con = $con;
    }

    public function select($table = "", $conditions = array(), $items = array(), $showTableName = NULL) {
      $select_fix = ""; $select = ""; $build = ""; $data = array();
      extract($conditions);

      if (isset($json)) $json = TRUE;
      else $json = FALSE;
      
      if (isset($join)) $build .= "left join {$join} ";
      if (isset($where)) $build .= "where {$where} ";
      if (isset($group_by)) $build .= "group by {$group_by} ";
      if (isset($having)) $build .= "having {$having} ";
      if (isset($order_by)) $build .= "order by {$order_by} ";
      else $build .= "order by id desc ";
      if (isset($limit)) $build .= "limit {$limit} ";

      if (empty($items)) {
        $select_fix = '*';
      } else {
        foreach ($items as $item) {
          $select .= $item . ", ";
        }
      }
      
      if ($select != "*" && $select_fix == "") {
        $select_fix = substr($select, 0, -2);
      }

      $query = "SELECT {$select_fix} FROM {$table} {$build}";

      $result = $this->con->query($query);

      if (!$result) {
        echo $this->con->error;
      } else {
          while ($row = $result->fetch_assoc()) {
            $data[] = $row;
          }
        if ($json) {
          return json_encode($data);
        } else if($showTableName == FALSE) {
          return $data;
        } else {
          $data['tableName'] = $table;
          return $data;
        }
      }
    }

    public function insert($params = array()) {
      
      extract($params); $error = ""; $columns = ""; $values = "";

      if (!isset($table)) $error .= "table => 'nazov_tabulky'";
      //if (!isset($redirect)) $error .= "<br>redirect => 'nazov_page'";

      if (!isset($vue)) {
        $url_params = $_SERVER['QUERY_STRING'];

        $form_data= explode("&", $url_params);
        $delete_token = array_shift($form_data);

        foreach ($form_data as $key => $value) {
          $table_data[] = explode("=", $value);
        }

        for ($i=0;$i<count($table_data);$i++) {
          $columns .= $table_data[$i][0] . ", ";
          $values .= "'" . $table_data[$i][1] . "', ";
        }
      } else {
        foreach ($vue_columns as $key => $value) {
          $columns .= $value .", ";
          $values .= $vue_values[$key] .", ";
        }
      }
      
      $columns = substr($columns, 0, -2);
      $values = substr($values, 0, -2);

      $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

      if (!$this->con->query($query)) { 
        echo $this->con->error;
        $this->error_function($error);
      } else {
        //header("Location: index.php?page=$redirect&success=1");
      }
    }

    public function insert_post($params = array()) {
      $form_data = $_POST;
      extract($params);
      $columns = ""; $values = ""; $error = "";

      if (!isset($table)) $error .= "table => 'nazov_tabulky'";
      if (!isset($redirect)) $error .= "<br>redirect => 'nazov_page'";

      foreach ($form_data as $key => $value) {
        $columns .= $key .", ";
        $values .= "'" . $value ."', ";
      }

      $columns .= "image, ";
      $values .= "'" . $_FILES['image']['name'] . "', ";


      $columns = substr($columns, 0, -2);
      $values = substr($values, 0, -2);

      $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

      if (!$this->con->query($query)) { 
        echo $this->con->error;
        $this->error_function($error);
      } else {
        header("Location: index.php?page=$redirect&success=1");
      }

    }

    public function insert_array($params = array()) {
      extract($params); $columns = ""; $values = "";

      if (isset($table_data)) {
        foreach ($table_data as $key => $value) {
          if ($key != 'id') {
            $columns .= $key .", ";
            $values .= "'" . $value ."', ";
          }
        }
        
        $columns = substr($columns, 0, -2);
        $values = substr($values, 0, -2);

        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";

      }

      if (!$this->con->query($query)) { 
        echo $this->con->error;
        $this->error_function($error);
      } else {
        return $this->con->insert_id;
      }
    }

    public function delete($table = "", $params = array()) {
      extract($params);

      // IF $id is array [5,7,4]
      // them implode to string 
      if (!is_array($id)) {
        if (isset($_GET['id'])) $id = $_GET['id'];
      } else {
        $id = implode(", ", $id);
      }

      $query = "DELETE from {$table} WHERE id IN($id)";

      if (!$this->con->query($query)) {
        echo $this->con->error;
      } else if(!empty($redirect)) {
        header("Location: index.php?page=$redirect&success=1");
      }

      return TRUE;
    }

    public function update(string $tableName = '', int $rowId = 0, array $data = []) {
      $update = "";

      if (!$tableName) throw new \Exception("TableName is empty or incorrect");
      //if (!$rowId) throw new \Exception("RowId is empty or incorrect");
      if (!$data) throw new \Exception("Data are empty or incorrect");

      // Ak nie je zadane rowId vytiahni si z tabulky
      if ($rowId > 0) {
        $id = $rowId;
      } else {
        $id = (int) array_shift($data);
      }

      foreach ($data as $key => $value) {
        if (is_numeric($value)) {
          $value = (int) $value;
          $update .= "$key=$value, ";
        } else if(is_bool($value)) {
          $update .= "$key=".(int)$value.", ";
        } else {
          $update .= "$key='$value', ";
        }
      }
  
      $update = substr($update, 0, -2);
    
      $query = "UPDATE {$tableName} SET {$update} WHERE id = $id";
      //var_dump($query);
      //exit();

      if (!$this->con->query($query)) { 
        echo $this->con->error;
        $this->error_function($this->con->errorr);
      } else {
        //$this->redirect('home');
      }
      
    }

    public function request_data() {
      return json_decode(file_get_contents("php://input"));
    }

    /**
     * GET table last item id
     * tableName
     * @return int
     */
    public function getLastItemId($tableName) {
      $query = "SELECT id FROM {$tableName} ORDER BY id desc LIMIT 1";

      try {
        $res = $this->con->query($query);

        if (!$res) {
          throw new \Exception("Query error");
        } else {
          return $res->fetch_assoc();
        }
      } catch(\Exception $e) {
        return $e;
      }
    }

    /**
     * SELECT from DB
     * $tableName
     * $conditions(where, order, group, joins)
     * @return array
     */
    public function dbSelect(
      string $tableName, 
      array $conditions = [], 
      bool $vueJson = false,
      array $mergeWith = []
    ) {
      $select = "*";

      /**
       * SELECT
       */
      if (array_key_exists("select", $conditions)) {
        $select = $conditions["select"];
      }

      $query = "SELECT {$select} FROM {$tableName}";

      /**
       * JOIN
       */
      if (array_key_exists("join", $conditions)) {
        foreach ($conditions['join'] as $joinTableName => $columns) {
          $query = $query . " LEFT JOIN {$joinTableName} ON {$tableName}.{$columns[0]} = {$joinTableName}.{$columns[1]}";
        }
      }

      /**
       * WHERE
       */
      if (array_key_exists("where", $conditions)) {
        $i = 0;
        foreach ($conditions['where'] as $column => $value) {
          $value = 
            is_string($value) ? "'".$this->con->real_escape_string(addslashes($value))."'" 
            : $value
          ;

          if ($i == 0) {
            $query .= " WHERE ".$this->con->real_escape_string(addslashes($column))." = {$value}";
            $i++;
          } else {
            $query .= " AND ".$this->con->real_escape_string(addslashes($column))." = {$value}";
          }
        }
      }

       /**
       * WHERE ARRAY syntax
       */
      if (array_key_exists("whereArray", $conditions)) {
        $i = 0;
        foreach ($conditions['whereArray'] as $whereCondition) {

          $fullValues = "";
          if (is_array($whereCondition[2])) {
            foreach ($whereCondition[2] as $val) {
              if ($val != end($whereCondition[2])) {
                $fullValues .= is_string($val) ? 
                  "'".$this->con->real_escape_string(addslashes($val))."'" 
                  : $val .","
                ;
              } else {
                $fullValues .= is_string($val) ? 
                  "'".$this->con->real_escape_string(addslashes($val))."'" 
                  : $val
                ;
              }
            }
            $value = $fullValues;
          } else if(is_string($whereCondition[2])) {
            $value = "'$whereCondition[2]'";
          } else if (is_numeric($whereCondition[2])) {
            $value = $whereCondition[2];
          }

          if ($i == 0) {
            $query = $query . " WHERE {$whereCondition[0]} ". strtoupper($whereCondition[1]) ."". ($whereCondition[1] == "in" ? "({$value})" : "{$value}");
            $i++;
          } else {
            $query = $query . " AND {$whereCondition[0]} ". strtoupper($whereCondition[1]) ."". ($whereCondition[1] == "in" ? "({$value})" : "{$value}");
          }
        }
      }

      /**
       * GROUP BY
       */
      if (array_key_exists("group_by", $conditions)) {
        $query .= " GROUP BY {$conditions['group_by']}";
      }

       /**
       * ORDER BY
       */
      if (array_key_exists("order_by", $conditions)) {
        $query .= " ORDER BY {$conditions['order_by']}";
      } else {
        $query .= " ORDER BY id DESC";
      }

       /**
       * LIMIT
       */
      if (array_key_exists("limit", $conditions)) {
        $query .= " LIMIT {$conditions['limit']}";
      }

      // DEBUG QUERY HERE
      //var_dump($query);
      //exit();
      $res = $this->con->query($query);

      if ($res) {
        if ($res->num_rows > 0) {
          while ($row = $res->fetch_assoc()) {
            
            if (!empty($mergeWith)) {
              $row = array_merge($row, $mergeWith);
            }

            $data[] = $row;
          }

          // JSON to vue component prop
          if ($vueJson === TRUE) {
            return str_replace("\"", "\'", json_encode($data));
          } else {
            return $data;
          }
        } else {
          throw new \Exception("empty");
        }
      } else {
        throw new \Exception($this->con->error);
      }
    }

    public function dbUpdateRow(string $tableName, int $rowId, string $column, $data) {
      if (!$tableName) throw new \Exception("TableName is empty or incorrect");
      if (!$rowId) throw new \Exception("RowId is empty or incorrect");
      if (!$column) throw new \Exception("Column is empty or incorrect");
      if (!$data) throw new \Exception("Data are empty or incorrect");

      switch (gettype($data)) {
        case "object":
          $data = json_encode($data);
        break;
        case "array":
          $data = json_encode($data);
        break;
      }

      $query = "UPDATE {$tableName} SET {$column}='{$data}'";

      if (!$this->con->query($query)) return $this->con->error;
    }
  }

}

?>