<?php

/**
 * @file
 * Grafizzi\Graph\Tests\AbstractCommandFilterTest: a component of the Grafizzi library.
 *
 * (c) 2016 Frédéric G. MARAND <fgm@osinet.fr>
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

use Grafizzi\Graph\Filter\AbstractCommandFilter;

require 'vendor/autoload.php';

class PseudoCommandFilter extends AbstractCommandFilter {}

/**
 * Class AbstractCommandFilterTest
 */
class AbstractCommandFilterTest extends \PHPUnit_Framework_TestCase {

  public function testFilterArguments() {
    // echo is both a Linux, MacOS, and Windows command.
    PseudoCommandFilter::$commandName = 'echo';
    $args = array('-' => 'n', 'foo' => '=bar');
    $filter = new PseudoCommandFilter($args);
    $output_lines = $filter->filter("");
    $output = implode("\n", $output_lines);
    $this->assertEquals("foo=bar\n", $output);
  }

  public function testUnavailableCommand() {
    // Since this is the name of this package, it is unlikely to exist.
    PseudoCommandFilter::$commandName = 'grafizzi';
    $filter = new PseudoCommandFilter();
    try {
      $filter->filter("");
      $this->fail("Nonexistent command did not trigger an exception.");
    }
    catch (\ErrorException $e) {
      // 'Caught expected exception for nonexistent command.
    }
    catch (\Exception $e) {
      $this->fail('Caught unexpected exception for nonexistent command.');
    }
  }
}
