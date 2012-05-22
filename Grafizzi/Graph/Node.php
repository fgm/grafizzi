<?php

namespace Grafizzi\Graph;

class Node extends AbstractElement {

  /**
   * Helper to simplify construction of implicit nodes.
   *
   * @return array
   */
  public static function implicit() {
    return array('implicit' => true);
  }

  /**
   * Node is implicit: it can be used in edge creations, but has no entry of its own.
   *
   * @var boolean
   */
  public $fImplicit = FALSE;

  public function __construct(\Pimple $dic, $name, array $attributes = array()) {
    parent::__construct($dic);
    if (isset($attributes['implicit'])) {
      $this->fImplicit = $attributes['implicit'];
      unset($attributes['implicit']);
    }
    $this->setAttributes($attributes);
    if (!isset($attributes['name'])) {
      $this->setName($name);
    }
  }

  /**
   * @see AbstractElement::build()
   */
  public function build($directed = NULL) {
    // Implicit nodes have no entry of their own.
    if ($this->fImplicit) {
      return;
    }
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building node $name, depth {$this->fDepth}.");
    $attributes = array_map(function (AttributeInterface $attribute) use ($directed) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT) . $this->escape($name);
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
