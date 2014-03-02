<?php

/**
 * @file
 * Grafizzi\Graph\Filter\AbstractCommandFilter: a component of the Grafizzi library.
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

use Grafizzi\Graph\Filter\AbstractFilter;

/**
 * Filters implemented as an executable file
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

  public static $commandName;

  /**
   * Options passed on the command line.
   *
   * @var array
   */
  public $commandOptions = array();

  /**
   * An array of options passed on the call line of the filter command.
   *
   * @param array $args
   */
  public function __construct(array &$args = array()) {
    parent::__construct($args);
    $this->commandOptions = $args;
  }

  /**
   * @param string $input
   *
   * @return array
   * @throws \ErrorException
   */
  public function filter($input) {
    $args = '';
    foreach ($this->commandOptions as $k => $v) {
      $args .= " $k$v";
    }

    $command = static::$commandName . $args;

    $descriptorSpec = array(
      0 => array('pipe', 'r'),
      1 => array('pipe', 'w'),
      2 => array('pipe', 'w'),
    );
    $pipes = array();

    $process = proc_open($command, $descriptorSpec, $pipes, NULL, NULL);
    if (!is_resource($process)) {
      throw new \ErrorException('"$command" command could not be run.');
    }

    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $ret = array(
      'stdout' => stream_get_contents($pipes[1]),
      'stderr' => stream_get_contents($pipes[2]),
    );
    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);

    return $ret;
  }
}
