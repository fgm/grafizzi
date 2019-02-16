<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG19286Test: a component of the Grafizzi library.
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

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Cluster;
use Grafizzi\Graph\Subgraph;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz bug_19286.phpt
 *
 * Image_GraphViz version author: Frédéric G. Marand <fgm@osinet.fr>
 *
 * Request 19286: "Loop error on empty clusters."
 *
 * @link     http://pear.php.net/bugs/bug.php?id=19286
 */
class IG19286Test extends BaseGraphTest {

  public $expected = <<<EOT
strict digraph G {
  subgraph cluster_c1_id {
    label=c1_title;
  } /* /subgraph cluster_c1_id */
  subgraph s1_id {
    label=s1_title;
  } /* /subgraph s1_id */
} /* /digraph G */

EOT;

  public function setUp() : void {
    parent::setUpExtended('G', array('strict' => true));
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);

    $g->addChild($c1_id = new Cluster($dic, 'c1_id', array(
      new Attribute($dic, 'label', 'c1_title'),
    )));
    $g->addChild($s1_id = new Subgraph($dic, 's1_id', array(
      new Attribute($dic, 'label', 's1_title'),
    )));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $this->check($this->expected, "Image_GraphViz bug test 19286 passed.");
  }
}
