<?php
namespace OSInet\Graph;

use OSInet\Graph\Element;
use OSInet\Graph\GraphInterface;

class Graph extends Element implements GraphInterface {

  public static function getAllowedChildTypes() {
    $ret = array(
      'cluster',
      'edge',
      'node',
      'subgraph',
    );
    return $ret;
  }

  public function getDirected() {
    $ret = $this->directed;
    return $ret;
  }

  public static function getType() {
    $ret = 'graph';
    return $ret;
  }

  public function render() {
  }

  public function setDirected($directed) {
    $this->directed = $directed;
  }
}
