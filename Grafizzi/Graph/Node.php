<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Node: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
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

class Node extends AbstractElement {

  /**
   * Node is implicit: it can be used in edge creations, but has no entry of
   * its own.
   *
   * @var bool
   */
  public bool $fImplicit = false;

  /**
   * Helper to simplify construction of implicit nodes.
   *
   * @return array<string,bool>
   */
  public static function implicit(): array {
    return ['implicit' => true];
  }

  /**
   * @param \Pimple\Container $dic
   * @param mixed $name
   * @param array<int|string,bool|\Grafizzi\Graph\AttributeInterface> $attributes
   */
  public function __construct(
    Container $dic,
    $name,
    array $attributes = []
  ) {
    parent::__construct($dic);
    if (isset($attributes['implicit'])) {
      $this->fImplicit = !!$attributes['implicit'];
      unset($attributes['implicit']);
    }
    // Implicit is the only key in Node $attributes that may contain a bool,
    // instead of an AttributeInterface, and we now removed it.
    /** @var array<\Grafizzi\Graph\AttributeInterface> $attributes */
    $this->setAttributes($attributes);
    if (!isset($attributes['name'])) {
      $this->setName($name);
    }
  }

  /**
   * @see AbstractElement::build()
   */
  public function build(?bool $directed = NULL): string {
    // Implicit nodes have no entry of their own.
    if ($this->fImplicit) {
      return '';
    }
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building $type $name, depth {$this->fDepth}.");
    $attributes = array_map(function (AttributeInterface $attribute) use (
      $directed
    ) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $ret = str_repeat(' ',
        $this->fDepth * self::DEPTH_INDENT) . $this->escape($name);
    if (!empty($attributes)) {
      $builtAttributes = implode(', ', array_filter($attributes));
      if (!empty($builtAttributes)) {
        $ret .= " [ " . implode(', ', array_filter($attributes)) . " ]";
      }
    }
    $ret .= ";\n";
    return $ret;
  }

  /**
   * @return array<string>
   */
  public static function getAllowedChildTypes(): array {
    return [];
  }

  public function getType(): string {
    return 'node';
  }

}
