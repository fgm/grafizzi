<?php

/**
 * @file
 * Grafizzi\Graph\MultiEdge: a component of the Grafizzi library.
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

  public function build($directed = null) {
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
    $ret = 'multiedge';
    return $ret;
  }

}
