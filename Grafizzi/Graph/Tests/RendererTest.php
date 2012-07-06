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
use Grafizzi\Graph\Filter\AbstractFilter;
use \ErrorException;

/**
 * Renderer test case.
 */
class RendererTest extends BaseGraphTest {

  /**
   *
   * @var Renderer
   */
  private $renderer;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp($name = 'G', $attributes = array()) {
    parent::setUp($name, $attributes);
    $this->renderer = new Renderer($this->dic);
    $this->renderer->pipe = $this->Graph->build();
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
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
      $this->pass('Renderer::getFormats() could not find dot and threw an ErrorException.');
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
      $out = $this->renderer->nonexistent();
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
    // Callback can be a closure
    $callback = function ($x) { return strrev($x); };

    // The value of this string should be overwritten by the filter.
    $output = 'input';

    $expected = $callback($this->renderer->pipe);
    $this->renderer->string(array(
      'out' => &$output,
      'callback' => $callback,
    ));

    $this->assertEquals($expected, $this->renderer->pipe, "Filter with closure works on renderer pipe.");
    $this->assertEquals($expected, $output, "Filter to string updates argument.");
  }

  /**
   * Test the fluent filter interface.
   */
  public function testRenderChainedFilters() {
    $callback = 'strrev';

    // The value of this string should be overwritten by the filter.
    $output = 'input';

    $expected = $this->renderer->pipe;
    $r = $this->renderer->string(array(
      'out' => &$output,
      'callback' => $callback,
    ))->string(array(
      'out' => &$output,
      'callback' => $callback,
    ));

    $this->assertEquals($expected, $this->renderer->pipe, "Chained filters with strrev work.");

    $r->string(array(
      'out' => &$output,
      'callback' => $callback,
    ))->sink();
    $this->assertEquals($callback($expected), $output, "Filter to string updates argument.");
    $this->assertNull($this->renderer->pipe, 'Sink filter drops content.');
  }
}

