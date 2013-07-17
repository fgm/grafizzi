<?php

/**
 * @file
 * Grafizzi\Graph\Edge: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

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
   * Edge need a unique id.
   *
   * This is because, multiple edges may exist between the same vertices,
   * port included.
   *
   * @var int
   */
  public static $sequence = 0;

  /**
   * @var boolean
   */
  public $fDirected = true;

  public function __construct(\Pimple $dic, Node $source, Node $destination,
    array $attributes = array(), $sourcePort = null, $destinationPort = null) {
    parent::__construct($dic);
    $this->sourceNode = $source;
    $this->destinationNode = $destination;
    $name = self::$sequence++ . '--' . $source->getName() . '--' . $destination->getName();
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
    $this->logger->debug("Building $type $name, depth {$this->fDepth}.");
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
