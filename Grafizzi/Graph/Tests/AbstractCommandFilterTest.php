<?php declare(strict_types=1);

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
use PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';

class PseudoCommandFilter extends AbstractCommandFilter {}

/**
 * Class AbstractCommandFilterTest
 */
class AbstractCommandFilterTest extends TestCase {

  public function testFilterArguments(): void {
    // echo is both a Linux, macOS, and Windows command.
    PseudoCommandFilter::$commandName = 'echo';
    $args = ['+' => 'x', 'foo' => '=bar'];
    $filter = new PseudoCommandFilter($args);
    $output_lines = $filter->filter("");
    $output = implode("\n", $output_lines);
    $this->assertEquals("+x foo=bar\n\n", $output);
  }

  public function testUnavailableCommand(): void {
    $this->expectException(\ErrorException::class);
    // Since this is the name of this package, it is unlikely to exist.
    PseudoCommandFilter::$commandName = 'grafizzi';
    $filter = new PseudoCommandFilter();
    $filter->filter("");
  }
}
