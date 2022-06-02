<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\BaseTestSuite: a component of the Grafizzi library.
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


namespace Grafizzi\Graph\Tests;

use PHPUnit\Framework\TestSuite;

require 'vendor/autoload.php';

/**
 * Static test suite.
 */
class BaseTestsSuite extends TestSuite {

  /**
   * Extend PHPUnit addTestSuite to add test classes from the current namespace.
   *
   * @see PHPUnit_Framework_TestSuite::addTestSuite()
   */
  public function addTestSuite($testClass): void {
    parent::addTestSuite(__NAMESPACE__ . "\\$testClass");
  }

  /**
   * Constructs the test suite handler.
   */
  public function __construct() {
    $this->setName('BaseTestsSuite');

    // Original unit tests for Grafizzi.
    $unitTests = [
      'Attribute', 'Cluster', 'Edge',     'Graph',
      'MultiEdge', 'Node',    'Subgraph', 'escape',
      'Renderer',
      'SinkFilter', 'StringFilter',
    ];
    foreach ($unitTests as $unit) {
      $this->addTestSuite("{$unit}Test");
    }

    // Image_GraphViz tests adapted for Grafizzi.
    // Note: Image_GraphViz tests skip many numbers between 7 and 20.
    $igTests = [
      // Base tests
      '01', '02', '03', '04', '05', '06', '09',
      '12', '14', '16', '17', '19', '20', '20b',
      // Bug fix tests
      '15019', '15943', '16872', '18676', '19286',
      // Feature request tests
      '12913',
    ];

    foreach ($igTests as $igTest) {
      $this->addTestSuite("IG{$igTest}Test");
    }
  }

  /**
   * Creates the suite.
   */
  public static function suite(): self {
    return new self();
  }
}
