<?php

namespace Grafizzi\Graph;

use Grafizzi\Graph\Graph;

class Subgraph extends Graph {

  public function getType() {
    $ret = 'subgraph';
    return $ret;
  }
}
