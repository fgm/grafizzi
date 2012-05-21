<?php

namespace Grafizzi\Graph;

class Node extends AbstractElement {

  public function __construct(\Pimple $dic, $name, array $attributes = array()) {
    parent::__construct($dic);
    $this->setName($name);
    $this->setAttributes($attributes);
  }

  public function build($directed = NULL) {
    if ($this->fDepth <= 0) {
      throw new ChildTypeException("Cannot build unbound node.");
    }
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building node $name, depth {$this->fDepth}.");
    $attributes = array_map(function (AttributeInterface $attribute) use ($directed) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT) . "$name";
    if (!empty($attributes)) {
      $ret .= " [ " . implode(', ', $attributes) . " ]";
    }
    $ret .= ";\n";
    return $ret;
  }

  public static function getAllowedChildTypes() {
    return array();
  }

  public function getType() {
    $ret = 'node';
    return $ret;
  }
}
