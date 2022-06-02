<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Filter\FilterInterface: a component of the Grafizzi library.
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

/**
 * Any filter must accept an input and output 0 to 2 "streams".
 */
interface FilterInterface {

  /**
   * @param array<string,mixed> $args
   *
   * @return \Grafizzi\Graph\Filter\FilterInterface
   */
  public function __construct(array $args = []);

  /**
   *
   * @throws \ErrorException
   *
   * @param string $input
   *
   * @return string[]
   *   - 0: the data output of the filter, normally to be used as the input to
   *     the next chained filter.
   *   - 1: the info output of the filter, possibly used for error reporting.
   */
  public function filter(string $input): array;
}
