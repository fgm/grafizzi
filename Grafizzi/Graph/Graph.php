<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\AbstractElement;
use Grafizzi\Graph\GraphInterface;

class Graph extends AbstractElement implements GraphInterface {

  public function build() {
    $type = $this->getType();
    $name = $this->getName();
    $attributes = array();
    foreach ($this->fAttributes as $attribute) {
      $attributes[] = $attribute->build();
    }
    $ret = "$type $name {\n" . implode(";\n", $attributes) . "\n}\n";
    return $ret;
  }

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
