<?php

namespace Grafizzi\Graph\Tests;

require 'vendor/autoload.php';

use Grafizzi\Graph\Renderer;
use Grafizzi\Graph\Filter\AbstractFilter;

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
  public function ZtestGetFormats() {
    // TODO Auto-generated RendererTest::testGetFormats()
    $this->markTestIncomplete("getFormats test not implemented");

    Renderer::getFormats(/* parameters */);
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

