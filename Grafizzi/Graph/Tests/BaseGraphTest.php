<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\BaseGraphTest: a component of the Grafizzi library.
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

require 'vendor/autoload.php';

use \Grafizzi\Graph\Graph;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * Base test case.
 *
 * Build a graph that all other test cases will need.
 */
abstract class BaseGraphTest extends TestCase {

  /**
   *
   * @var ?Graph
   */
  public ?Graph $Graph;

  /**
   * @var \Pimple\Container
   */
  public Container $dic;

  /**
   * Prepares the environment before running a test.
   *
   * @param string $name
   * @param array<string,bool> $attributes
   *
   * @return void
   */
  protected function setUpExtended(string $name = 'G', array $attributes = []): void {
    parent::setUp();

    $log = new Logger(basename(__FILE__, '.php'));
    $log->pushHandler(new StreamHandler('php://stderr', Logger::INFO));
    $this->dic = new Container([
      'logger' => $log,
    ]);
    $this->Graph = new Graph($this->dic, $name, $attributes);
  }

  /**
   * Helper function for testBuild() method.
   *
   * @param string $expected
   *   A DOT source.
   * @param string $message
   *   The assertion message.
   */
  public function check(string $expected, string $message): void {
    $build = $this->Graph->build();
    $this->Graph->logger->debug("\n\n$build\n\n");
    $this->assertEquals($expected, $build, $message);
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->Graph = NULL;
    parent::tearDown();
  }

}
