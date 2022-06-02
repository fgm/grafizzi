<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\MultiEdge: a component of the Grafizzi library.
 *
 * (c) 2012-2022 Frédéric G. MARAND <fgm@osinet.fr>
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

use Pimple\Container;

/**
 * A MultiEdge is a graph object made up of several nodes linked by edges, with
 * only one set of attributes. In GraphViz format, this means something like:
 *
 * foo -> bar -> baz [ label=Quux ];
 *
 * It provides a simpler source representation for node chains than multiple
 * edges.
 */
class MultiEdge extends AbstractElement {

  /**
   * Array of Node objects
   *
   * @var array<\Grafizzi\Graph\Node>
   */
  public array $fNodes;

  /**
   * @var boolean
   */
  public $fDirected = TRUE;

  /**
   * @param \Pimple\Container $dic
   * @param array<\Grafizzi\Graph\Node> $nodes
   * @param array<\Grafizzi\Graph\AttributeInterface> $attributes
   */
  public function __construct(
    Container $dic,
    array $nodes,
    array $attributes = []
  ) {
    parent::__construct($dic);
    $this->fNodes = $nodes;
    $this->setName(implode('--', array_map(function (Node $node) {
      return $node->getName();
    }, $this->fNodes)));
    $this->setAttributes($attributes);
  }

  /**
   * @param ?bool $directed
   *
   * @return string
   */
  public function build(?bool $directed = NULL): string {
    $type = $this->getType();
    $name = $this->getName();
    if (!isset($directed)) {
      $root = $this->getRoot();
      if ($root instanceof GraphInterface) {
        $directed = $root->getDirected();
      }
    }
    $this->logger->debug("Building $type $name, depth {$this->fDepth}.");
    $joiner = ($directed ? ' -> ' : ' -- ');
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . implode($joiner, array_map(function (Node $node) {
        return $node->getBuildName();
      }, $this->fNodes));

    $attributes = array_map(function (AttributeInterface $attribute) use (
      $directed
    ) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $ret .= $this->buildAttributes($attributes, '', '');
    return $ret;
  }

  /**
   * @return array<string>
   */
  public static function getAllowedChildTypes(): array {
    return [];
  }

  public function getType(): string {
    return 'multiedge';
  }

}
