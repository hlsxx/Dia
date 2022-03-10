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

    //
    // CUSTOMER
    //

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
            "whereArray" => [
              ["id_customer_uid", "=", reset($customers)['id']],
              ["is_order", "=", 0]
            ]
          ]
        );
        
        if (empty($carts)) {
          $idCart = $db->insert_array([
            'table' => "carts",
            'table_data' => [
              "id_customer_uid" => reset($customers)['id']
            ]
          ]);
        } else {
          $idCart = reset($carts)['id'];
        }

        return $idCart;
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

    /**
     * GET logged customer data
     * @return array
     */
    public static function getLoggedCustomer() {
      return isset($_SESSION['customer']) ? $_SESSION['customer'] : [];
    }

    /**
     * GET logged customer id
     * @return int
     */
    public static function getLoggedCustomerId() {
      return !empty(self::getLoggedCustomer()) ? self::getLoggedCustomer()['id'] : 0;
    }

  }

}