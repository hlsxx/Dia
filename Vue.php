<?php

namespace Core {

  class Vue {

    public static array $data = [];
    public static string $methods = "";

    public function setData(array $data = []) : void {
      array_push(self::$data, $data);
    }

    public function getData() : void {
      echo json_encode(reset(self::$data));
    }

    public function setMethods(string $methods) : void {
      self::$methods = $methods;
    }

    public function getMethods() : void {
      echo self::$methods;
    }

  }

}