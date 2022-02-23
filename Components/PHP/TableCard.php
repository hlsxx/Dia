<?php

namespace Component {

  class TableCard extends \Core\Component {

    public function __construct() {
      parent::__construct($this);
    }

    public function show(): string {
      return "
        <dia-table-card>
        </dia-table-card>
      ";
    }
  }
}