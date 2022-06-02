<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\EdgeTest: a component of the Grafizzi library.
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

/** @noinspection PhpIncludeInspection */
require 'vendor/autoload.php';

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

/**
 * Edge test case.
 */
class EdgeTest extends BaseGraphTest {

  /**
   * @var ?Edge
   */
  private ?Edge $Edge;

  /**
   * @var Attribute
   */
  private $Attribute;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp(): void {
    parent::setUpExtended();
    $src = new Node($this->dic, 'source');
    $dst = new Node($this->dic, 'destination');
    $this->Attribute = new Attribute($this->dic, 'label',
      'Source to Destination');
    $this->Edge = new Edge($this->dic, $src, $dst);
    $this->Edge->setAttribute($this->Attribute);
    foreach ([$src, $dst, $this->Edge] as $child) {
      $this->Graph->addChild($child);
    }
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown(): void {
    $this->Edge = NULL;
    parent::tearDown();
  }

  /**
   * Tests Edge->__construct()
   */
  public function test__construct(): void {
    $this->assertEquals($this->Attribute,
      $this->Edge->getAttributeByName('label'),
      'Edge is correctly labeled');
  }

  /**
   * Tests Edge->build()
   *
   * @TODO test graph build
   */
  public function testBuild(): void {
    $dot = $this->Edge->build($this->Graph->getDirected());
    $this->assertEquals(<<<EOT
  source -> destination [ label="Source to Destination" ];

EOT
      , $dot, "Edge builds correctly");
  }

  /**
   * Common logic for the testBuild* test methods.
   *
   * @param string $expected
   *   The expected GraphViz output.
   * @param array<array<string>> $edges
   *
   * @internal param array $toSet An array of edges to add.*   An array of
   *   edges to add.
   */
  public function buildTestHelper(string $expected, array $edges): void {
    $this->Edge->removeAttribute($this->Attribute);
    $toSet = [];
    foreach ($edges as $edge) {
      [$name, $value] = $edge;
      $toSet[] = new Attribute($this->dic, $name, $value);
    }
    $this->Edge->setAttributes($toSet);
    $actual = $this->Edge->build();
    $this->assertEquals($expected, $actual);
  }

  public function testBuildAttributesNormal(): void {
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;
    $this->buildTestHelper($expected, [
      ['foo', 'bar'],
      ['baz', 'quux'],
    ]);
  }

  // Attribute list with empty title in middle on edge bound to root graph.
  public function testBuildAttributesEmptyMiddle(): void {
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;

    $this->buildTestHelper($expected, [
      ['foo', 'bar'],
      ['title', ''],
      ['baz', 'quux'],
    ]);
  }

  // Attribute list with empty title as single attribute on edge bound to root graph.
  public function testBuildAttributesOnlyEmpty(): void {
    $expected = <<<EOT
  source -> destination;

EOT;

    $this->buildTestHelper($expected, [
      ['title', ''],
    ]);
  }

  // Attribute list with empty title as last attribute on edge bound to root graph.
  public function testBuildAttributesEmptyLast(): void {
    $expected = <<<EOT
  source -> destination [ foo=bar, baz=quux ];

EOT;

    $this->buildTestHelper($expected, [
      ['foo', 'bar'],
      ['baz', 'quux'],
      ['title', ''],
    ]);
  }

  /**
   * Tests Edge::getAllowedChildTypes()
   */
  public function testGetAllowedChildTypes(): void {
    $this->assertEmpty(Edge::getAllowedChildTypes());
  }

  /**
   * Tests Edge::getType()
   */
  public function testGetType(): void {
    $this->assertEquals('edge', $this->Edge->getType());
  }

}
