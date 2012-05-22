<?php

namespace Grafizzi\Graph;

class Edge extends AbstractElement {

  /**
   * @var Node
   */
  public $sourceNode;

  /**
   * @var Node
   */
  public $destinationNode;

  /**
   * @var boolean
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic, Node $source, Node $destination, array $attributes = array()) {
    parent::__construct($dic);
    $this->sourceNode = $source;
    $this->destinationNode = $destination;
    $this->setName($source->getName() . '--' . $destination->getName());
    $this->setAttributes($attributes);
  }

  public function build($directed = NULL) {
    $type = $this->getType();
    $name = $this->getName();
    if (!isset($directed)) {
      $directed = TRUE;
    }
    $this->logger->debug("Building edge $name, depth {$this->fDepth}.");
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . $this->escape($this->sourceNode->getName())
      . ($directed ? ' -> ' : ' -- ')
      . $this->escape($this->destinationNode->getName());

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
    $ret = 'edge';
    return $ret;
  }

}
