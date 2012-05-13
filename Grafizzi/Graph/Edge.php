<?php

namespace Grafizzi\Graph;

use Grafizzi\Graph\AbstractElement;

class Edge extends AbstractElement {

  function __construct() {}

  public function getType() {
    $ret = 'edge';
    return $ret;
  }

  function __destruct() {}
}
