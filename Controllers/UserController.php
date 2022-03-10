<?php

namespace Core\Controllers {

  class UserController {

    /**
     * SET user SESSION
     * @return void
     */
    public static function setUser(array $data) {
      $_SESSION['user'] = $data;
    }

    /**
     * UNSET user SESSION
     * @return void
     */
    public static function destroyLogged() {
      unset($_SESSION['user']);
    }

    /**
     * GET logged user data
     * @return array
     */
    public static function getLogged() {
      return isset($_SESSION['user']) ? $_SESSION['user'] : [];
    }

    /**
     * REDIRECT after login
     * @return void
     */
    public static function checkIfUserLogged(&$page) {
      if ($page == "login") {
        if (!empty(self::getLogged())) {
          $page = "moj-ucet";
        }
      } else if($page == "moj-ucet") {
        if (empty(self::getLogged())) {
          $page = "login";
        }
      }
    }

    public static function getCustomerUid() {
      global $db;

      if (empty($_COOKIE["customer_uid"])) {
        $_COOKIE["customer_uid"] = \Core\Bice::getuid();

        $db->insert_array([
          "table" => "customers_uids",
          "table_data" => [
            "uid" => $_COOKIE["customer_uid"]
          ]
        ]);
      }

      return $_COOKIE["customer_uid"];
    }

  }

}