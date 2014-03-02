<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG16872Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz bug_16872.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Bug 16872: "Cluster IDs start with 'cluster'"
 */
class IG16872Test extends BaseGraphTest {

  public $expected = <<<EOT
digraph sp_d_rcp_001 {
  rankdir=LR;
  ranksep=0.75;

  courbe_rcp [ shape=box ];
  detail_rcp [ shape=box ];
  subgraph cluster_pck_courbe_rcp {
    color=green;
    label=pck_courbe_rcp;

    sp_d_rcp_001 [ shape=component ];
  } /* /subgraph cluster_pck_courbe_rcp */
  sp_d_rcp_001 -> courbe_rcp [ color=blue, label=S, id=Scourbe_rcp ];
  sp_d_rcp_001 -> courbe_rcp [ color=red, label=D, id=Dcourbe_rcp ];
  sp_d_rcp_001 -> detail_rcp [ color=blue, label=S, id=Sdetail_rcp ];
  sp_d_rcp_001 -> detail_rcp [ color=red, label=D, id=Ddetail_rcp ];
} /* /digraph sp_d_rcp_001 */

EOT;

  public function setUp($name = 'G', $attributes = array()) {
    parent::setUp('sp_d_rcp_001');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);
    $g->setAttributes(array(
      new Attribute($dic, 'rankdir', 'LR'),
      new Attribute($dic, 'ranksep', .75),
    ));

    // Grafizzi emits elements in insertion order, so if we want these nodes to
    // appear above the cluster, they must be created before it, unlike the way
    // the test is build in Image_GraphViz.
    $result = array(
      array('tab' => 'courbe_rcp', 'action' => 'S'),
      array('tab' => 'courbe_rcp', 'action' => 'D'),
      array('tab' => 'detail_rcp', 'action' => 'S'),
      array('tab' => 'detail_rcp', 'action' => 'D'),
    );

    $lst_tab = array();
    $boxShape = new Attribute($dic, 'shape', 'box');
    foreach ($result as $row) {
      $table = $row['tab'];
      if (array_key_exists($table, $lst_tab) == false){
        $g->addChild($$table = new Node($dic, $table, array($boxShape)));
        $lst_tab[] = $table;
      }
    }

    $g->addChild($pck_courbe_rcp = new Cluster($dic, 'pck_courbe_rcp', array(
      new Attribute($dic, 'color', 'green'),
      new Attribute($dic, 'label', 'pck_courbe_rcp'),
    )));
    $pck_courbe_rcp->addChild($sp_d_rcp_001 = new Node($dic, 'sp_d_rcp_001', array(
      new Attribute($dic, 'shape', 'component'),
    )));

    foreach ($result as $row) {
      $table = $row['tab'];
      $action = $row['action'];
      $color = ($action == 'D') ? 'red' : 'blue';
      $this->dic['logger']->debug("Edge source " . $sp_d_rcp_001->getBuildName() .", dest ". $$table->getBuildName());
      $g->addChild(new Edge($dic, $sp_d_rcp_001, $$table, array(
        new Attribute($dic, 'color', $color),
        new Attribute($dic, 'label', $action),
        new Attribute($dic, 'id', $action . $table),
      )));
    }
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $this->check($this->expected, "Image_GraphViz bug test 16872 passed.");
  }
}
