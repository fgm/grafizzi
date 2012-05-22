<?php

namespace Grafizzi\Graph;

class MultiEdge extends AbstractElement {

  /**
   * Array of Node objects
   *
   * @var array
   */
  public $fNodes;

  /**
   * @var boolean
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic, array $nodes, array $attributes = array()) {
    parent::__construct($dic);
    $nonNodes = array_filter($nodes, function ($node) {
      return !($node instanceof Node);
    });
    if (!empty($nonNodes)) {
      throw new ChildTypeException("Trying to add non-nodes to a multi-edge element.");
    }
    $this->fNodes = $nodes;
    $this->setName(implode('--', array_map(function (Node $node) {
      return $node->getName();
    }, $this->fNodes)));
    $this->setAttributes($attributes);
  }

  public function build($directed = NULL) {
    $type = $this->getType();
    $name = $this->getName();
    if (!isset($directed)) {
      $directed = $this->getRoot()->getDirected();
    }
    $this->logger->debug("Building edge $name, depth {$this->fDepth}.");
    $joiner = ($directed ? ' -> ' : ' -- ');
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . implode($joiner, array_map(function (Node $node) {
        return $node->getBuildName();
      }, $this->fNodes));

    $attributes = array_map(function (AttributeInterface $attribute) use ($directed) {
      return $attribute->build($directed);
    }, $this->fAttributes);
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
    $ret = 'multiedge';
    return $ret;
  }

}
