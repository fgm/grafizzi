<?php

namespace Grafizzi\Graph;

class Node extends AbstractElement {

  public function __construct(\Pimple $dic, $name, array $attributes = array()) {
    parent::__construct($dic);
    $this->setAttributes($attributes);
    if (!isset($attributes['name'])) {
      $this->setName($name);
    }
  }

  /**
   * @see AbstractElement::build()
   */
  public function build($directed = NULL) {
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
