<?php

/**
 * @file
 * Grafizzi\Graph\Tests\BaseFilterTest: a component of the Grafizzi library.
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

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

/**
 *
 * @author marand
 */
abstract class BaseFilterTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   *   Array of filters, implementing FilterInterface.
   */
  protected $filters = array();

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->filters = array();
    parent::tearDown();
  }
}