<?php

/**
 * Filters
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

namespace Grafizzi\Graph\Filter;

abstract class AbstractFilter {

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
  public static function create($name) {
    $className = __NAMESPACE__ . '\\' . ucfirst($name) . 'Filter';
    if (class_exists($className, true)) {
      $args = func_get_args();
      array_shift($args);
      // echo "In " . __METHOD__ . "class: $className (". var_export($args, true) . ")\n";
      $ret = new $className($args);
    }
    else {
      throw new \DomainException('Non existent filter "' . $name . '" (' . $className . ').');
    }
    return $ret;
  }
}
