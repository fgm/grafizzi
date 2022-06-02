<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Filter\StringFilter: a component of the Grafizzi library.
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
 * A Grafizzi filter that also outputs its result to the passed-in string.
 *
 * Since it has to be passed by reference, the argument to use is like:
 * ['out' => &$some_string].
 */
class StringFilter extends AbstractFilter implements FilterInterface {

  /**
   * The reference to the variable in which to copy the result.
   *
   * @var ?string
   */
  public ?string $string = NULL;

  /**
   * An optional callable to be invoked on the data.
   *
   * @var ?callable
   *   It receives the input and returns its after filtering.
   */
  public $callback = NULL;

  /**
   * @param array<string,mixed> $args
   *   Valid keys are 'args' and 'callback'.
   *
   * @throws \InvalidArgumentException
   */
  public function __construct(array $args = []) {
    if (isset($args['out'])) {
      $this->string = &$args['out'];
    }
    if (isset($args['callback'])) {
      if (is_callable($args['callback'])) {
        $this->callback = $args['callback'];
      }
      else {
        throw new \InvalidArgumentException("Invalid callback passed to " . __CLASS__);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function filter($input): array {
    /** @var string $stdout */
    $stdout = isset($this->callback)
      ? call_user_func($this->callback, $input)
      : $input;
    if (isset($this->string)) {
      $this->string = $stdout;
    }
    $ret = [$stdout, ''];
    return $ret;
  }

}
