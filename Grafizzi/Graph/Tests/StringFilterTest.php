<?php

/**
 * @file
 * Grafizzi\Graph\Tests\StringFilterTest: a component of the Grafizzi library.
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

use Grafizzi\Graph\Filter\StringFilter;

/**
 * StringFilter test case.
 */
class StringFilterTest extends BaseFilterTest {

  /**
   * String to copy to.
   *
   * @var string
   */
  private $out = 'initial data';

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() : void {
    parent::setUp();
    $this->filters[] = new StringFilter();
    $args = array(
      'out' => &$this->out,
      'callback' => function ($x) { return strrev($x) ; },
    );
    $this->filters[] = new StringFilter($args);
  }

  /**
   * Tests StringFilter->filter()
   */
  public function testFilter() {
    $in = 'String test';
    list($stdout, $err) = $this->filters[0]->filter($in);
    $this->assertEquals($in, $stdout, 'String filter returns its input.');

    list($stdout, $err) = $this->filters[1]->filter($in);
    $expected = strrev($in);
    $this->assertEquals($expected, $stdout, 'String filter with callback applies it.');
    $this->assertEquals($expected, $this->out, 'String filter with out string assigns it.');
    $this->assertEmpty($err, 'String filter does not return errors');
  }
}
