<?php

/**
 * @file
 * Grafizzi\Graph\Graph: a component of the Grafizzi library.
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

use Pimple\Container;

class Graph extends AbstractElement implements GraphInterface {

  /**
   * Helper to simplify construction of strict graphs.
   *
   * @return array
   */
  public static function strict() {
    return array('strict' => true);
  }

  /**
   * Generate non-strict graphs by default
   *
   * @var boolean
   */
  public $fStrict = false;

  /**
   * Generate digraphs by default.
   */
  public $fDirected = true;

  /**
   * @param \Pimple\Container $dic
   * @param string $name
   * @param array $attributes
   */
  public function __construct(Container $dic, $name = 'G', array $attributes = array()) {
    if (!isset($dic['directed'])) {
      $dic['directed'] = true;
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
   * @param bool $directed
   *
   * @return string
   */
  public function build($directed = null) {
    // Allow overriding the build directed attribute.
    if (isset($directed)) {
      $savedDirected = $this->getDirected();
      $this->setDirected($directed);
    }
    else {
      $savedDirected = true;
    }

    $actualDirected = $this->getDirected();
    $type = $this->getType();
    $buildName = $this->getBuildName();
    $elementIndent = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT);
    $childIndent = str_repeat(' ', ($this->fDepth + 1) * self::DEPTH_INDENT);

    $strict = $this->fStrict ? 'strict ' : '';
    $ret = "$elementIndent$strict$type $buildName {\n";

    $builtCount = 0;
    foreach ($this->fAttributes as $attribute) {
      if ($built = $attribute->build($directed)) {
        $ret .= "$childIndent$built;\n";
        $builtCount++;
      }
    }

    $builtChildren = '';
    foreach ($this->fChildren as $child) {
      $builtChildren .= $child->build($actualDirected);
    }
    if ($builtCount && !empty($builtChildren)) {
      $ret .= "\n";
    }
    $ret .= "$builtChildren$elementIndent} /* /$type $buildName */\n";

    // Restore the directed attribute if it was changed for build.
    if (isset($directed)) {
      $this->setDirected($savedDirected);
    }
    return $ret;
  }

  public static function getAllowedChildTypes() {
    $ret = array(
      'cluster',
      'edge',
      'multiedge', // Grafizzi extension
      'node',
      'subgraph',
    );
    return $ret;
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
  public function getType() {
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
