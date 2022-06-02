<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Graph: a component of the Grafizzi library.
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

class Graph extends AbstractElement implements GraphInterface {

  /**
   * Helper to simplify construction of strict graphs.
   *
   * @return array<string,bool>
   */
  public static function strict() {
    return ['strict' => TRUE];
  }

  /**
   * Generate non-strict graphs by default
   *
   * @var bool
   */
  public bool $fStrict = FALSE;

  /**
   * Generate digraphs by default.
   */
  public bool $fDirected = TRUE;

  /**
   * @param \Pimple\Container $dic
   * @param mixed $name
   * @param array<int|string,bool|\Grafizzi\Graph\AttributeInterface> $attributes
   */
  public function __construct(
    Container $dic,
    $name = 'G',
    array $attributes = []
  ) {
    if (!isset($dic['directed'])) {
      $dic['directed'] = TRUE;
    }
    parent::__construct($dic);
    $this->setName($name);
    if (!empty($attributes)) {
      if (isset($attributes['strict'])) {
        $this->fStrict = $attributes['strict'];
        unset($attributes['strict']);
      }
      $this->setAttributes($attributes);
    }
  }

  /**
   * @param ?bool $directed
   *
   * @return string
   */
  public function build(?bool $directed = NULL): string {
    // Allow overriding the build directed attribute.
    if (isset($directed)) {
      $savedDirected = $this->getDirected();
      $this->setDirected($directed);
    }
    else {
      $savedDirected = TRUE;
    }

    $actualDirected = $this->getDirected();
    $type = $this->getType();
    $buildName = $this->getBuildName();
    $elementIndent = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT);
    $childIndent = str_repeat(' ', ($this->fDepth + 1) * self::DEPTH_INDENT);

    $strict = $this->fStrict ? 'strict ' : '';
    $ret = "$elementIndent$strict$type $buildName {\n";

    $ret_attributes = $this->buildAttributesHelper($this->fAttributes,
      $actualDirected, $childIndent);
    $ret .= $ret_attributes;

    $ret_children = $this->buildChildrenHelper($this->fChildren,
      $actualDirected);

    if (!empty($ret_attributes) && !empty($ret_children)) {
      $ret .= "\n";
    }
    $ret .= "$ret_children$elementIndent} /* /$type $buildName */\n";

    // Restore the directed attribute if it was changed for build.
    if (isset($directed)) {
      $this->setDirected($savedDirected);
    }
    return $ret;
  }

  /**
   * Helper for Graph::build(): build attributes.
   *
   * Unrelated with AbstractElement::buildAttributes().
   *
   * @param \Grafizzi\Graph\AttributeInterface[] $attributes
   * @param bool $directed
   * @param string $childIndent
   *
   * @return string
   */
  protected function buildAttributesHelper(
    array $attributes,
    ?bool $directed,
    string $childIndent
  ) {
    $ret = '';
    /** @var \Grafizzi\Graph\AttributeInterface $attribute */
    foreach ($attributes as $attribute) {
      if ($built = $attribute->build($directed)) {
        $ret .= "$childIndent$built;\n";
      }
    }
    return $ret;
  }

  /**
   * Helper for Graph::build(): build children.
   *
   * @param \Grafizzi\Graph\ElementInterface[] $children
   * @param bool $directed
   *
   * @return string
   */
  public function buildChildrenHelper(array $children, $directed): string {
    $ret = '';
    /** @var \Grafizzi\Graph\AbstractElement $child */
    foreach ($children as $child) {
      $ret .= $child->build($directed);
    }
    return $ret;
  }

  /**
   * @return array<string>
   */
  public static function getAllowedChildTypes(): array {
    return [
      'cluster',
      'edge',
      'multiedge', // Grafizzi extension
      'node',
      'subgraph',
    ];
  }

  /**
   * @return bool
   */
  public function getDirected() {
    $ret = $this->fDirected;
    return $ret;
  }

  /**
   * @return string
   */
  public function getType(): string {
    $ret = $this->getDirected() ? 'digraph' : 'graph';
    return $ret;
  }

  /**
   * @param bool $directed
   */
  public function setDirected($directed) {
    $this->fDirected = $directed;
  }

}
