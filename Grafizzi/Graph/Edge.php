<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Edge: a component of the Grafizzi library.
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
   * @var ?string
   */
  public ?string $sourcePort = NULL;

  /**
   * Optional port name on destination Node.
   *
   * @var ?string
   */
  public ?string $destinationPort = NULL;

  /**
   * Edge need a unique id.
   *
   * This is because, multiple edges may exist between the same vertices,
   * port included.
   *
   * @var int
   */
  public static int $sequence = 0;

  /**
   * @var boolean
   */
  public bool $fDirected = TRUE;

  /**
   * @param \Pimple\Container $dic
   * @param \Grafizzi\Graph\Node $source
   * @param \Grafizzi\Graph\Node $destination
   * @param array<\Grafizzi\Graph\AttributeInterface> $attributes
   * @param string|null $sourcePort
   * @param string|null $destinationPort
   *
   * @throws \InvalidArgumentException
   */
  public function __construct(
    Container $dic,
    Node $source,
    Node $destination,
    array $attributes = [],
    ?string $sourcePort = NULL,
    ?string $destinationPort = NULL
  ) {
    parent::__construct($dic);
    $this->sourceNode = $source;
    $this->destinationNode = $destination;
    $name = self::$sequence++ . '--' . $source->getName() . '--' . $destination->getName();
    if ($sourcePort && $destinationPort) {
      $this->sourcePort = $sourcePort;
      $this->destinationPort = $destinationPort;
      $name .= "--$sourcePort--$destinationPort";
    }
    elseif ($sourcePort || $destinationPort) {
      throw new \InvalidArgumentException('Both ports must be set if one is set, but you only set one.');
    }
    $this->setName($name);
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
      $directed = TRUE;
    }
    $this->logger->debug("Building $type $name, depth {$this->fDepth}.");
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . $this->escape($this->sourceNode->getName())
      . (isset($this->sourcePort) ? ":$this->sourcePort" : NULL)
      . ($directed ? ' -> ' : ' -- ')
      . $this->escape($this->destinationNode->getName())
      . (isset($this->destinationPort) ? ":$this->destinationPort" : NULL);

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
    return 'edge';
  }

}
