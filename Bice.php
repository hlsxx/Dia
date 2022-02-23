<?php

namespace Core {

  class Bice {

    /**
     * GET custom generated UID
     * @return string
     */
    static public function getUID() {
      return (string)rand();
    }

    static public function print_r($data) {
      $out = \print_r($data, true);
      $out = \preg_replace("#(?<!\r)\n#", PHP_EOL, $out);
      echo $out;
    }

    public static function pagination(
      int|string $currentPage = 1, 
      int|string $count = 10, 
      int|string $countTotal = 0
    ) {

      if ($countTotal != 0) {
        $offset = $currentPage > 1 ? $count * ($currentPage - 1) : 0;

        return [
          "pages" => ceil($countTotal / $count),
          "offset" => $offset
        ];
      }

    }

    public static function csv(array &$array) {
      if (count($array) == 0) {
        return null;
      }
      ob_start();
      $df = fopen("php://output", 'w');
      fputcsv($df, array_keys(reset($array)));
      foreach ($array as $row) {
        fputcsv($df, $row);
      }
      fclose($df);
      return ob_get_clean();
    }

    public static function download($filename) {
      $now = gmdate("D, d M Y H:i:s");
      header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
      header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
      header("Last-Modified: {$now} GMT");
  

      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
  
      header("Content-Disposition: attachment;filename={$filename}");
      header("Content-Transfer-Encoding: binary");
    }
    
  }

}