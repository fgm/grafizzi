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
   * Optional port name on source Node.
   *
   * @var string
   */
  public $sourcePort = null;

  /**
   * Optional port name on destination Node.
   *
   * @var string
   */
  public $destinationPort = null;

  /**
   * @var boolean
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic, Node $source, Node $destination,
    array $attributes = array(), $sourcePort = null, $destinationPort = null) {
    parent::__construct($dic);
    $this->sourceNode = $source;
    $this->destinationNode = $destination;
    $name = $source->getName() . '--' . $destination->getName();
    if ($sourcePort && $destinationPort) {
      $this->sourcePort = $sourcePort;
      $this->destinationPort = $destinationPort;
      $name .= "--$sourcePort--$destinationPort";
    } elseif ($sourcePort || $destinationPort) {
      throw new \InvalidArgumentException('Both ports must be set if one is set, but you only set one.');
    }
    $this->setName($name);
    $this->setAttributes($attributes);
  }

  public function build($directed = null) {
    $type = $this->getType();
    $name = $this->getName();
    if (!isset($directed)) {
      $directed = true;
    }
    $this->logger->debug("Building edge $name, depth {$this->fDepth}.");
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . $this->escape($this->sourceNode->getName())
      . (isset($this->sourcePort) ? ":$this->sourcePort" : null)
      . ($directed ? ' -> ' : ' -- ')
      . $this->escape($this->destinationNode->getName())
      . (isset($this->destinationPort) ? ":$this->destinationPort" : null);

    $attributes = array_map(function (AttributeInterface $attribute) use ($directed) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    if (!empty($attributes)) {
      $builtAttributes = implode(', ', array_filter($attributes));
      if (!empty($builtAttributes)) {
        $ret .= " [ $builtAttributes ]";
      }
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
