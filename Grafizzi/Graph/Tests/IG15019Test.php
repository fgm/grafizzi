<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\IG15019Test: a component of the Grafizzi library.
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

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz bug_15019.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Bug 15019: "addCluster using attributes twice"
 *
 * Note 1: IG test does not actually test using attributes twice.
 * Note 2: IG test expects an abnormal result: attribute "0" being set to "".
 *   Since this is an incorrect behavior, Grafizzi does not reproduce it.
 */
class IG15019Test extends BaseGraphTest {

  const CLUSTER_NAME = 0;

  public string $expected = <<<EOT
strict digraph Bug {
  subgraph cluster_0 {
    fontcolor=black;
    style=filled;
    label=Cluster;

    "Node";
  } /* /subgraph cluster_0 */
} /* /digraph Bug */

EOT;

  public function setUp(): void {
    parent::setUpExtended('Bug', ['strict' => TRUE]);
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(TRUE);

    $g->addChild($cluster0 = new Cluster($dic, self::CLUSTER_NAME, [
      new Attribute($dic, 'fontcolor', 'black'),
      new Attribute($dic, 'style', 'filled'),
      new Attribute($dic, 'label', 'Cluster'),
    ]));
    $cluster0->addChild($node = new Node($dic, 'Node'));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild(): void {
    $this->check($this->expected, "Image_GraphViz bug test 15019 passed.");
  }

  /**
   * @depends testBuild
   */
  public function testBuild2(): void {
    $cluster0 = $this->Graph->getChildByName(self::CLUSTER_NAME);
    $this->assertNotNull($cluster0, "Numbered cluster found in graph.");
    // Add the same attribute a second time.
    $cluster0->setAttribute(new Attribute($this->dic, 'style', 'filled'));

    $this->check($this->expected,
      "Image_GraphViz bug test 15019 redone passed.");
  }

}
