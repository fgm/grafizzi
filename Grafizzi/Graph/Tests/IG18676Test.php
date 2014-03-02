<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG18676Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz bug_18676.phpt
 *
 * Image_GraphViz version author: Frédéric G. Marand <fgm@osinet.fr>
 *
 * Request 18676: "Cluster doesn't show if there's no nodes inside."
 *
 * @link     http://pear.php.net/bugs/bug.php?id=18676
 */
class IG18676Test extends BaseGraphTest {

  public $expected = <<<EOT
strict digraph G {
  subgraph cluster_c1_id {
    label=c1_title;
  } /* /subgraph cluster_c1_id */
} /* /digraph G */

EOT;

  public function setUp($name = 'G', $attributes = array()) {
    parent::setUp('G', array('strict' => true));
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);

    $g->addChild($cluster = new Cluster($dic, 'c1_id', array(
      new Attribute($dic, 'label', 'c1_title'),
    )));
    $cluster->addChild(new Node($dic, 'n', array('implicit' => true)));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $this->check($this->expected, "Image_GraphViz bug test 18676 passed.");
  }
}
