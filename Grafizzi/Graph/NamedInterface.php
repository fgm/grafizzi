<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\NamedInterface: a component of the Grafizzi library.
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

interface NamedInterface {
  public function build(?bool $directed = null): string;

  /**
   * Output a string in the default GraphViz format.
   *
   * - usual C/PHP double-quoting rules apply.
   * - strings containing a space or an escape are double-quoted
   * - strings remaining alphanumeric after this are not quoted
   *
   * @param mixed $value
   *
   * @return string
   */
  public static function escape($value): string;

  /**
   * The name of the function as used in the build process.
   *
   * May be different from the name used by other methods (clusters).
   *
   * @return string
   */
  public function getBuildName(): string;

  /**
   * The name of the object as used by all methods.
   *
   * @see getbuildName()
   *
   * @return string
   */
  public function getName(): string;

  /**
   * The name of the object type in GraphViz: attribute, node, edge, cluster...
   *
   * Sometimes used during building (graph, ...), sometimes not (attribute). May
   * vary per-instance, as graphs can have type graph or digraph depending on
   * their fDirected attribute.
   *
   * @return string
   */
  public function getType(): string;

  /**
   * @param mixed $name
   *   Will be stringified
   *
   * @return void
   */
  public function setName($name): void;
}
