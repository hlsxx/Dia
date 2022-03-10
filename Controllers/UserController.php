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
          self::setCustomerId();
        }
      } else if($page == "moj-ucet") {
        if (empty(self::getLogged())) {
          $page = "login";
        }
      }
    }

    public static function getCustomerUid() {
      return isset($_COOKIE["customer_uid"]) ? $_COOKIE["customer_uid"] : "";
    }

    public static function getCustomerIdCart() {
      $customerUid = self::getCustomerUid();

      if ($customerUid != "") {
        global $db;

        $customers = $db->dbSelect(
          "customers_uids",
          [
            "where" => [
              "uid" => $customerUid
            ]
          ]
        );

        $carts = $db->dbSelect(
          "carts",
          [
            "where" => [
              "id_customer_uid" => reset($customers)['id']
            ]
          ]
        );

        return reset($carts)['id'];
      }
    }

    public static function createCustomerUid() {
      global $db;

      if (empty($_COOKIE["customer_uid"])) {
        $uid = \Core\Bice::getuid();

        setcookie(
          "customer_uid", 
          $uid, 
          time() + (3600 * 240), "/"
        );

        $idCustomerUid = $db->insert_array([
          "table" => "customers_uids",
          "table_data" => [
            "uid" => $uid
          ]
        ]);

        $db->insert_array([
          "table" => "carts",
          "table_data" => [
            "id_customer_uid" => $idCustomerUid
          ]
        ]);
      }
    }

    public static function setCustomerId() {
      global $db;

      $customer = $db->dbSelect(
        "customers_uids",
        [
          "where" => [
            "id_customer" => self::getLogged()['id']
          ]
        ]
      );

      var_dump($customer); exit();

      $db->insert_array([
        "table" => "customers_uids",
        "table_data" => [
          "uid" => $_COOKIE["customer_uid"]
        ]
      ]);
    }

  }

}