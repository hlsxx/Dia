<?php

namespace Core\Controllers {

  class WebController extends \Core\Dia {

    /**
     * SET memory breacrumbs value
     * @return void
     */
    public static function setMemory(string $url) {
      $_SESSION['memory'][$url] = $url;
    }

    /**
     * GET memory breacrumbs values
     * @return array
     */
    public static function getAllMemory() {
      return isset($_SESSION['memory']) ? $_SESSION['memory'] : [];
    }

    /**
     * SET url for memory
     * @return void
     */
    public static function setUrlForMemory(string $url) {
      if (!isset($_SESSION['memory'][$url])) {
        self::setMemory($url);
      }

      self::setCurrentPage();
    }

    /**
     * SET current web page
     * if param webPage empty
     * default web from config home
     * @return void
     */
    public static function setCurrentPage() {
      global $config;
      $_SESSION['currentPage'] = isset($_GET['webPage']) ? $_GET['webPage'] : $config['web']['home'];
    }

    /**
     * GET current web page session
     * @return string
     */
    public static function getCurrentPageSession() {
      return isset($_SESSION['currentPage']) ? $_SESSION['currentPage'] : "";
    }

    /**
     * GET current web page
     * @return string
     */
    public static function getCurrentPage() {
      return isset($_GET['webPage']) ? $_GET['webPage'] : self::getCurrentPageSession();
    }

    /**
     * UNSET memory breadcrumbs
     * @return array
     */
    public static function destroyMemory() {
      unset($_SESSION['memory']);
      
      self::setUrlForMemory(self::getCurrentPage());

      return self::getAllMemory();
    }

    /**
     * GET web page dir 
     * @return string
     */
    public static function getPage() {
      global $config;

      $pageToInclude = $config['dir']['web'] . '/' . $config['web']['pages'] . '/' . self::getCurrentPage() . '.php';

      if (!is_file($pageToInclude)) {
        $pageToInclude = $config['dir']['web'] . '/' . $config['web']['includes'] . '/' . $config['web']['notfound'] . '.php';
      }

      return $pageToInclude;
    }

    /**
     * GET JSON from array
     * @return string
     */
    public static function getJson(array $data) {
      $returnData = $data;

      if (self::checkParamIfExists('reset')) {
        $returnData['data'] = reset($returnData['data']);
      }

      if (self::checkParamIfExists('unset')) {
        $returnData['data'] = $returnData['data'][self::getParam('unset')];
      }

      if (self::checkParamIfExists('json')) {
        $returnData['data'] = json_decode($returnData['data']);
      }

      if (self::checkParamIfExists('return-only-data')) {
        $returnData = $returnData['data'];
      }

      echo json_encode($returnData);
    }

    /**
     * CHECK $_GET param if exists
     * @return bool
     */
    public static function checkParamIfExists(string $param) {
      return isset($_GET[$param]) ? true : false;
    }

    /**
     * GET $_GET param
     * @return string
     */
    public static function getParam(string $param) {
      return isset($_GET[$param]) ? $_GET[$param] : "";
    }

    /**
     * get all POST params
     * @return array
     */
    public static function getPostParams() {
      return isset($_POST) ? $_POST : [];
    }

    /**
     * get specific POST param
     * @return string
     */
    public static function getPostParam(string $param) {
      return isset($_POST[$param]) ? $_POST[$param] : "";
    }

    /**
     * Redirect to URL
     * @return void
     */
    public static function redirect(string $redirectUrl) {
      if ($redirectUrl == "") throw new \Exception("Empty redirectUrl");

      header("Location: {$redirectUrl}");
    }

    public static function getConfig() {
      global $config;

      return $config;
    }

  }

}