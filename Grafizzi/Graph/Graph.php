<?php
namespace Grafizzi\Graph;

class Graph extends AbstractElement implements GraphInterface {

  /**
   * Generate digraphs by default.
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic) {
    if (!isset($dic['name'])) {
      $dic['name'] = 'G';
    }
    if (!isset($dic['directed'])) {
      $dic['directed'] = true;
    }
    parent::__construct($dic);
  }

  public function build() {
    $type = $this->getType();
    if ($type == 'graph') {
      if ($this->getDirected()) {
        $type = 'digraph';
      }
    }
    $name = $this->getName();
    $ret = "$type $name {\n";
    $indent = str_repeat(' ', ($this->fDepth + 1) * self::DEPTH_INDENT);

    foreach ($this->fAttributes as $attribute) {
      $ret .= $indent . $attribute->build() . ";\n";
    }
    if (count($this->fAttributes)) {
      $ret .= "\n";
    }
    foreach ($this->fChildren as $child) {
      $ret .= $child->build() . "\n";
    }
    $ret .= "}\n";
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
    $ret = $this->fDirected;
    return $ret;
  }

  public static function getType() {
    $ret = 'graph';
    return $ret;
  }

  public function setDirected($directed) {
    $this->fDirected = $directed;
  }
}
