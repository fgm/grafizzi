<?php

/**
 * @file
 * Grafizzi\Graph\Filter\DotFilter: a component of the Grafizzi library.
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

use Grafizzi\Graph\Filter\AbstractCommandFilter;
use Grafizzi\Graph\Renderer;

/**
 * A CommandFilter for the dot main commands: dot, neato, (s)fdp, circo, twopi.
 *
 * These filters share a common option regarding image format, as well as many
 * others not exposed here.
 */
class DotFilter extends AbstractCommandFilter {

  /**
   * Valid output formats.
   *
   * @var array
   */
  public $formats = null;

  public static $commandName = 'dot';

<<<<<<< HEAD
=======
  public function __construct(array &$args = array()) {
    parent::__construct($args);
  }

>>>>>>> 313b54c... Backup version for safety only: work in progress, do not use.
  /**
   * @throws \InvalidArgumentException
   *
   * @param string $format
   *
   * @return boolean
   */
  public function image($format) {
    if (!isset($this->formats)) {
      // In case of failure, this will hold an empty array, not null.
      $this->formats = Renderer::getFormats($this->dic);
    }

    if (!in_array($format, $this->formats)) {
      $ret = false;
      if (!empty($this->dic['use_exceptions'])) {
        throw new \InvalidArgumentException('Invalid image format');
      }
    }

    // TODO perform rendering.
    return $ret;
  }
<<<<<<< HEAD
=======

>>>>>>> 313b54c... Backup version for safety only: work in progress, do not use.
}