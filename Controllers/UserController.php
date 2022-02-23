<?php

namespace Core\Controllers {

  class UserController extends \Core\DB {

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
          $page = "home";
        }
      }
    }

  }

}