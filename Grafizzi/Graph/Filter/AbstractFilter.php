<?php

/**
 * @file
 * Grafizzi\Graph\Filter\AbstractFilter: a component of the Grafizzi library.
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

namespace Grafizzi\Graph\Filter;

abstract class AbstractFilter implements FilterInterface {

  /**
   * Configuration of the filter instance.
   *
   * @var array
   */
  public $config;

  public function __construct(array &$args = array()) {}

  /**
   * Apply a configurable process to an input string and return the result.
   *
   * @param string $input
   *
   * @return string
   */
  public function filter($input) {}

  /**
   * Factory method for concrete filters.
   *
   * @param string $name
   * @param array $args
   * @throws DomainException
   * @return FilterInterface
   */
  public static function create($name, array &$args = array()) {
    $className = __NAMESPACE__ . '\\' . ucfirst($name) . 'Filter';
    if (class_exists($className, true)) {
      $ret = new $className($args);
    }
    else {
      throw new \DomainException('Non existent filter "' . $name . '" (' . $className . ').');
    }
    return $ret;
  }
}
