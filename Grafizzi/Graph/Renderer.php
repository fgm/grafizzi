<?php
/**
 * @file
 * Grafizzi\Graph\Renderer: a component of the Grafizzi library.
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

use Grafizzi\Graph\Filter\AbstractFilter;
use Pimple\Container;

/**
 * A Renderer builds a rendering pipeline by instantiating Filters and providing
 * a pipe to chain invokations of their filter() methods.
 *
 * @method dot()
 * @method string()
 * @method sink()
 */
class Renderer {

  /**
   * The dependency injection container.
   *
   * @var \Pimple\Container
   */
  public $dic;

  /**
   * The channel between filters.
   *
   * @var string
   */
  public $pipe = null;

  /**
   * Helper to enumerate GraphViz format filters on the current system.
   *
   * @throws \ErrorException
   *
   * @param \Pimple\container $dic
   *
   * @return array
   *   An array of format names or false if dot cannot be run.
   */
  public static function getFormats(Container $dic) {
    $dotCommand = 'dot -Tinvalid';
    $useExceptions = !empty($dic['use_exceptions']);
    $descriptorSpec = array(
        0 => array('pipe', 'r'),
        1 => array('pipe', 'w'),
        2 => array('pipe', 'w'),
    );

    $process = proc_open($dotCommand, $descriptorSpec, $pipes, NULL, NULL);
    if (!is_resource($process)) {
      if ($useExceptions) {
        throw new \ErrorException('GraphViz "dot" command could not be run.');
      }
      // No need to define $formats otherwise: it is always defined further down.
    }

    fclose($pipes[0]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    proc_close($process);

    $sts = preg_match('/(.+):( .* )*/', $stderr, $matches);
    if (!$sts || count($matches) != 3) {
      if ($useExceptions) {
        throw new \ErrorException('GraphViz did not return a usable formats list.');
      }
      else {
        $formats = array();
      }
    }
    else {
      $formats = explode(' ', trim($matches[2]));
    }

    return $formats;
  }

  /**
   * Constructor.
   *
   * @param \Pimple\Container $dic
   */
  public function __construct(Container $dic) {
    $this->dic = $dic;
  }

  /**
   * Magic method: apply filter methods by simple name.
   *
   * @see Grafizzi\Graph\Filter\AbstractFilter::create()
   *
   * @param string $name
   *   The simple name for a filter. Will be converted to an actual class name.
   * @param array $args
   *   An array of arguments to pass to the filter method.
   *
   * @throws \DomainException
   *   Throws exception if the filter name does not convert to a usable filter
   *   class.
   *
   * @return $this
   */
  public function __call($name, $args) {

    $filter = isset($args[0])
      ? AbstractFilter::create($name, $args[0])
      : AbstractFilter::create($name);

    if ($filter) {
      list($this->pipe, $err) = $filter->filter($this->pipe);
      if (!empty($err)) {
        $this->dic['logger']->debug($err);
      }
    }
    else {
      throw new \DomainException('Filter not found: ' . $name);
    }
    return $this;
  }
}
