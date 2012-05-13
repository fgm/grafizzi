<?php

namespace Grafizzi\Graph;

class Node extends AbstractElement {

  public function __construct(\Pimple $dic, $name, array $attributes = array()) {
    parent::__construct($dic);
    $this->setName($name);
    $this->setAttributes($attributes);
  }

//  public function addChild(ElementInterface $child) {}

  public function build() {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name, depth {$this->fDepth}.");
    $attributes = array_map(function (AttributeInterface $attribute) {
      return $attribute->build();
    }, $this->fAttributes);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . "$name [ " . implode(', ', $attributes) . " ];\n";
    return $ret;
  }

//  public function getAttributeByName($name) {}

  public static function getAllowedChildTypes() {
    return array();
  }

  public static function getType() {
    $ret = 'node';
    return $ret;
  }

//   public function removeAttribute(AttributeInterface $attribute) {}

//   public function removeAttributeByName($name) {}

//   public function removeChild(ElementInterface $child) {}

//   public function setAttribute(AttributeInterface $attribute) {}

//   public function setAttributes(array $attributes) {}
}
