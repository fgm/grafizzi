<?php

/**
 * @file
 * Grafizzi\Graph\Tests\RendererTest: a component of the Grafizzi library.
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

use Grafizzi\Graph\Renderer;
use \ErrorException;

/**
 * Renderer test case.
 *
 * Note: at least in PHP3.7, when passing, expectOutputString is not
 * included in the assertions count, but it is included when failing.
 */
class RendererTest extends BaseGraphTest {

  const ERASABLE = 'input should be overwritten';

  /**
   *
   * @var Renderer
   */
  private $renderer;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() : void {
    parent::setUpExtended();
    $this->renderer = new Renderer($this->dic);
    $this->renderer->pipe = $this->Graph->build();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() : void {
    $this->renderer = null;
    parent::tearDown();
  }

  /**
   * Tests Renderer::getFormats()
   */
  public function testGetFormats() {
    $dic = $this->dic;
    $dic['use_exceptions'] = false;
    $formats = Renderer::getFormats($dic);
    $this->assertTrue(is_array($formats), 'Renderer::getFormats() returns an array when exceptions are not used.');

    $dic['use_exceptions'] = true;
    try {
      $formats = Renderer::getFormats($dic);
      $this->assertTrue(is_array($formats) && !empty($formats), 'Renderer::getFormats() returns a non-empty array when exceptions are used.');
    }
    catch (ErrorException $e) {
      $this->fail('Renderer::getFormats() could not find dot and threw an ErrorException.');
    }
  }

  /**
   * Test use of magic __call() with non existent filter.
   *
   * - must throw DomainException
   * - must not overwrite source data
   */
  public function test__call() {
    $expected = $out = 'Some data';
    try {
      $out = call_user_func(array($this->renderer, 'nonexistent'));
    }
    catch (\DomainException $e) {
      $this->assertInstanceOf('\\DomainException', $e, 'Rendering non existent filter throws DomainException');
    }
    $this->assertEquals($expected, $out, 'Output is not overwritten by non existent filter.');
  }

  /**
   * Tests rendering with a single filter.
   */
  public function testRenderOneFilter() {
    // Callback can be a closure.
    $callback = function ($x) {
      return strrev($x);
    };

    // The value of this string should be overwritten by the filter.
    $output = self::ERASABLE;

    $expected = $callback($this->renderer->pipe);

    // Invoke filter via renderer, overwriting output and pipe.
    call_user_func(array($this->renderer, 'string'), array(
      'out' => &$output,
      'callback' => $callback,
    ));

    $pipe = $this->renderer->pipe;
    $this->assertIsString($pipe, "String filter returns string output");
    $this->assertEquals($expected, $this->renderer->pipe, "Filter with closure works on renderer pipe.");

    // No error output.
    $this->expectOutputString("");
  }

  /**
   * Test the fluent filter interface.
   */
  public function testRenderChainedFilters() {
    $callback = 'strrev';

    // The value of this string should be overwritten by the filter.
    $output1 = $output2 = $output3 = self::ERASABLE;

    $input = $this->renderer->pipe;
    $step1 = call_user_func(array($this->renderer, 'string'), array(
      'out' => &$output1,
      'callback' => $callback,
    ));

    $r = call_user_func(array($step1, 'string'), array(
      'out' => &$output2,
      'callback' => $callback,
    ));

    $this->assertEquals($input, $this->renderer->pipe, "Chained filters with strrev work.");

    // Test a filter chain: string, then sink.
    $pipe_input = $this->renderer->pipe;

    $r->string(array(
      'out' => &$output3,
      'callback' => $callback,
    ))->sink();

    $this->assertEquals($callback($pipe_input), $output3, "String filter updates argument.");
    $pipe = $this->renderer->pipe;
    $this->assertEmpty($pipe, 'Sink filter drops content from stdout.');

    // No error output from either filter.
    $this->expectOutputString("");
  }
}

