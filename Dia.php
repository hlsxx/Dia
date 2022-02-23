<?php

namespace Core {
  class Dia {

    public static $loadedComponents = [];
    public static $loadedWebComponents = [];

    public $config, $con, $installed;
    public $pages = array();
    public $components = array();
    //public $showedComponents = array();

    private string $rootDir;

    public $script = "";

    public function __construct() {
      global $config, $con;

      $this->config = $config;
      $this->con = $con;

      $this->rootDir = $config['dir']['root'];

      $this->najdi_podstranky();
      $this->check_install();
      $this->najdi_vue_componenty();
    }

    // ZAKLADNE FUNKCIE
    private function najdi_podstranky() {
      $pages = scandir($this->config['dir']['web'] . '/' . $this->config['web']['pages']);

      foreach ($pages as $page) {
      $this->pages[] = substr($page, 0, -4);
      }

    }

    private function najdi_vue_componenty() {
      $pages = scandir(__DIR__ . "/../web/" . $this->config['web']['vue_components']);

      foreach ($pages as $page) {
      $this->components[] = substr($page, 0, -4);
      }

    }

    /**
     * Call custom Component from
     * web/components directory
     * @return void
     */
    public function vue($name) {
     //$component = new \Core\Component($name);

      array_push(self::$loadedWebComponents, $name);

      //return $component;
    }

    /**
     * Create custom Vue Component
     * from HTML in .php file
     */
    public function html(string $html) {
      $htmlComponent = new \Component\Custom("html");
      $htmlComponent->setHtml($html);

      return $htmlComponent;
    }

    /**
     * Create custom Vue Component
     * from HTML in .php file
     */
    public function createVue(string $html) {
      $htmlComponent = new \Component\Custom($html);

      return $htmlComponent;
    }

    /**
     * TEMPLATE METHOD
     * Create template with will 
     * render Vue components
     * @return object
     */
    public function template(string $html) {
      return $this->html($html);
    }

    public function card(string $html = "") {
      return "
        <div class='card'>
          <div class='card-body'>
            {$html}
          </div>
        </div>
      ";
    }

    public function cardBook(string $html, string $redirectType = "id_form") {
      $params = $_GET;
      $newParams = [];
      $returnHtml = "";

      array_shift($params);
      
      foreach ($params as $key => $param) {
        if ($key != 'previous_page_id_form' && $key != 'id') {
          $newParams[$key] = $param;
        }
      }

      $paramsLength = count($newParams);

      if (isset($params['previous_page_id_form'])) {
        $href = "{$newParams['previous_page']}?{$redirectType}={$params['previous_page_id_form']}";
      } else {
        $href = "{$newParams['previous_page']}";
      }
     
      if ($paramsLength > 0) {
        $i = 0;
        for ($j=0;$j<$paramsLength+1;$j++) {
          $i++;
          $returnHtml .= "
            <div style='
              padding-left:10px;
              border:1px solid #cccccc;
              border-radius:5px;
              ".($i != $paramsLength + 1 ? 'background:#ebebeb' : 'background:#f8f9fa;').";
            '>
          ";

          if ($i == $paramsLength + 1) {
            $returnHtml .= "
              <div class='row'>
                <a href='{$href}' style='margin:5px;margin-left:15px'>
                  <button class='btn btn-light'>
                    <i class='fas fa-times color-red'></i>
                  </button>
                </a>
              </div>
            ";
          }
        }

        $returnHtml .= $html;

        foreach ($newParams as $param) {
          $returnHtml .= "
          </div>
          ";
        }
      } else {
        $returnHtml = $html;
      }

      return $returnHtml;
    }

    public function setScript(string $script) {
      $this->script .= $script;
    }

    public function getScript() {
      echo $this->script;
    }

    public function daj_zahlavie() {
      $css_files = ""; $script_files = "";  $bootstrap = ""; $vue = "";

      foreach ((array)$this->config['head']['css'] as $value) {
        $css_files .= "<link rel='stylesheet' href='Assets/css/$value'>"; 
      }

      foreach ((array)$this->config['head']['script'] as $value) {
        $script_files .= "<script src='Assets/js/$value'></script>"; 
      }

      if ($this->config['web']['bootstrap']) {
        $bootstrap .= "<link rel='stylesheet' href='../../Core/Assets/css/style.css'>";
      }

      if ($this->config['web']['dropzone']) {
        $dropzone = "
          <link rel='stylesheet' type='text/css' href='../../node_modules/dropzone/dist/basic.css'>
          <link rel='stylesheet' type='text/css' href='../../node_modules/dropzone/dist/dropzone.css'>
          <script src='../../node_modules/dropzone/dist/dropzone-min.js'></script>
        ";
      }

      if ($this->config['web']['vue']) {
        $vue = "
          <script src='{$this->config['host']}/node_modules/vue/dist/vue.global.prod.js'></script>
          <script src='{$this->config['host']}/node_modules/axios/dist/axios.min.js'></script>
          <script src='{$this->config['host']}/node_modules/chart.js/dist/chart.min.js'></script>
          <script src='{$this->config['host']}/node_modules/jquery/dist/jquery.min.js'></script>
          <script src='{$this->config['host']}/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js'></script>
          <script src='{$this->config['host']}/node_modules/vue3-sfc-loader/dist/vue3-sfc-loader.js'></script>
          <script src='{$this->config['host']}/node_modules/mitt/dist/mitt.umd.js'></script>
          <script src='{$this->config['host']}/Core/Assets/js/CustomFunctions.js'></script>
          <script src='{$this->config['host']}/Core/Assets/js/Functions.js'></script>
          <script src='{$this->config['host']}/Core/Assets/js/dia.js'></script>
          <script src='{$this->config['host']}/Core/Assets/js/diaTables.js'></script>
          <script src='{$this->config['host']}/Core/Assets/js/fontawesome.js'></script>
        ";
      }

      echo "
        <!DOCTYPE html>
        <html lang='{$this->config['head']['lang']}'>
        <meta charset='{$this->config['head']['charset']}'>
        <head>
          {$bootstrap}
          {$dropzone}
          {$css_files}
          {$vue}
          {$script_files}
        </head>
        <body> 
      ";
    }

    public function getPageHeader() : void {
      @include "{$this->config['dir']['admin']}/{$this->config['web']['includes']}/header.php";
    }

    public function getPageNavigation() : void {
      @include "{$this->config['dir']['admin']}/{$this->config['web']['includes']}/navigation.php";
    }

    public function getPageFooter() : void {
      @include "{$this->config['dir']['admin']}/{$this->config['web']['includes']}/footer.php";
    }

    public function daj_zapaticku() {
      //echo "<script src='Core/Assets/js/dia.js'></script>";
    }

    public function action($action) {
      return "<input type='hidden' name='token' value=$action>";
    }

    public function find_alert() {
      if (isset($_GET['alert'])) {
        return $_GET;
      }
    }

    protected function check_install() {
      global $con;

      if (!$con->query("SELECT * FROM admin WHERE id = 1")) {
        $this->installed = FALSE;
      } else {
        $this->installed = TRUE;
      }
    }

    public function autoload() {
      spl_autoload_register(function ($className) {
        $className = str_replace("\\", "/", $className);

        if (strpos($className, "/")) $parts = explode("/", $className);

        if (file_exists($this->config['dir']['root'] . '/' . $className . '.php')) {
          require_once $this->config['dir']['root'] . '/' . $className . '.php';
        } else if (file_exists($this->config['dir']['root']. "/Core/Components/PHP/{$parts[1]}.php")) {
          require_once $this->config['dir']['root'] . "/Core/Components/PHP/{$parts[1]}.php";
        }

      });
    }

    public function action_find() {
      $url_params = $_SERVER['QUERY_STRING'];

      $form_data= explode("&", $url_params);
      $token = array_shift($form_data);
      $action_name = explode("=", $token);

      $token_check = strrpos($url_params, "token");

      if ($token_check === 0) {
      $explode_1 = explode("=", $url_params);
      $explode_2 = explode("&", $explode_1[1]);

      // AK SA NACHDADZA SLOVO DIA SMERUJE TO NA CORE AKCIU
      if ((strstr($action_name[1], '_', true)) == "dia") {
        return include ("{$this->rootDir}\\Core\\public\\web\\actions\\" . $action_name[1] . ".php");
      } else {
        return include ("web/{$this->config['web']['actions']}/" . $action_name[1] . ".php");
      }

      }
    }

    public function AjaxAction() {
      $action = isset($_GET['action']) ? $_GET['action'] : '';

      if ((strstr($action, '_', true)) == "dia") {
        return include ("{$this->rootDir}/Core/Actions/" . $action . ".php");
      } else {
        return include ($this->getWebDir() . "/{$this->config['web']['actions']}/" . $action . ".php");
      }
    }

    public function convertTableNameToUrl($tableName) : string {
      return isset($this->config['urls'][$tableName]) ? $this->config['urls'][$tableName] : $tableName;
    }

    protected function error_function($error) {
      echo "<h1>Nezadany parameter:</h1>";
      echo "<h2>{$error}</h2>";
    }

    // SUBOROVE FUNKCIE

    public function read_folder($folder) {
      $files= scandir("web/" . $this->config['web']['files'] . "/{$folder}");
      unset($files[0]);
      unset($files[1]);

      foreach ($files as $file) {
        ${substr($file, 0, -4)} = readfile("web/" . $this->config['web']['files'] . "/{$folder}/{$file}");

        echo ${substr($file, 0, -4)};
        echo "<br><br>";
      }
    }

    // CONFIG FUNKCIE
    public function web_home() {
      return $this->config['web']['home'];
    }

    public function web_actions() {
      return $this->config['web']['actions'];
    }

    public function getWebIncludes() {
      return $this->config['web']['includes'];
    }

    public function getWebPages() {
      return $this->config['web']['pages'];
    }

    public function webFiles() {
      return $this->dirRoot() . '/'. $this->config['web']['files'];
    }

    public function dirRoot() {
      return $this->config['dir']['root'];
    }

    public function getWebDir() {
      return $this->config['dir']['web'];
    }

    public function getNotFound() {
      return $this->config['web']['notfound'];
    }

    // TESTOVACIE FUNKCIE
    public function get_config() {
      print_r($this->config);
    }

    public function get_con() {
      print_r($this->con);
    }

  }
}

?>