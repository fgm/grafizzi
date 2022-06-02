<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Filter\AbstractFilter: a component of the Grafizzi library.
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

namespace Grafizzi\Graph\Filter;

abstract class AbstractFilter implements FilterInterface {

  /**
   * @var array<string,mixed>
   */
  protected array $args;

  /**
   * @param array<string,mixed> $args
   */
  public function __construct(array $args = []) {
    $this->args = $args;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function filter(string $input): array;

  /**
   * Factory method for concrete filters.
   *
   * @param string $name
   * @param array<string,mixed> $args
   *
   * @return FilterInterface
   *
   * @throws \DomainException
   */
  public static function create(string $name, array &$args = []) {
    $className = __NAMESPACE__ . '\\' . ucfirst($name) . 'Filter';
    if (class_exists($className, TRUE)) {
      $ret = new $className($args);
    }
    else {
      throw new \DomainException('Non existent filter "' . $name . '" (' . $className . ').');
    }
    return $ret;
  }

}
