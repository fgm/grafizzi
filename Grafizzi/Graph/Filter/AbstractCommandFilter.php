<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Filter\AbstractCommandFilter: a component of the Grafizzi library.
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
 * Filters implemented as an executable file.
 *
 * - nop -p <file>+ : check and optionally pretty-print graph files
 *   - p : just check, no output
 *   - concatenate them on stdout
 * - acyclic -n -v -o <outfile> <file>
 *   - -n : no output, just return value for acyclic or not
 *   - -v - pretty-print on stdout + 1 sentence on stderr saying whether the graph is acyclic
 *   - -o <file> : only output the acyclicity sentence on stderr
 * - gc -<many options> <file>+: count graph components: nodes, edges...
 * - gvpr : "awk" for GraphViz.
 * - renderers: dot, neato, twopi, circo, fdp, sfdp
 */
abstract class AbstractCommandFilter extends AbstractFilter {

  public static string $commandName;

  /**
   * Options passed on the command line.
   *
   * @var array<string,mixed>
   */
  public array $commandOptions = [];

  /**
   * An array of options passed on the call line of the filter command.
   *
   * @param array<string,mixed> $args
   */
  public function __construct(array $args = []) {
    $this->commandOptions = $args;
  }

  /**
   * {@inheritdoc}
   */
  public function filter(string $input): array {
    $args = '';
    foreach ($this->commandOptions as $k => $v) {
      $args .= ' ' . escapeshellarg("${k}${v}");
    }

    $command = static::$commandName . $args;

    $descriptorSpec = [
      0 => ['pipe', 'r'],
      1 => ['pipe', 'w'],
      2 => ['pipe', 'w'],
    ];
    $pipes = [];

    // Option "bypass_shell" only works on Windows.
    $process = proc_open($command, $descriptorSpec, $pipes, NULL, NULL, ['bypass_shell' => true]);

    // Highly unlikely to happen outside Windows unless /bin/sh is missing.
    // Look for /bin/sh in this file (near line 810 in PHP 7.x):
    // https://github.com/php/php-src/blob/master/ext/standard/proc_open.c
    if (!is_resource($process)) {
      throw new \ErrorException('"$command" command could not be run (no resource).');
    }

    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $ret = [
      stream_get_contents($pipes[1]),
      stream_get_contents($pipes[2]),
    ];
    fclose($pipes[1]);
    fclose($pipes[2]);
    $status = proc_close($process);
    // http://tldp.org/LDP/abs/html/exitcodes.html#EXITCODESREF
    if ($status === 127) {
      throw new \ErrorException("''$command' command could not be run (exit 127)'.");
    }

    return $ret;
  }
}
