<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG02Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test2.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 2: "HTML-like labels"
 */
class IG02Test extends BaseGraphTest {

  public function setUp() {
    parent::setUp('structs');
    $this->Graph->setDirected(true);
    $graph = &$this->Graph;
    $dic = $this->dic;

    $plainText = new Attribute($dic, 'shape', 'plaintext');

    $graph->addChild($nStruct1 = new Node($dic, 'struct1', array(
      $plainText,
      new Attribute($dic, 'label', '<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0">
   <TR><TD>left</TD><TD PORT="f1">mid dle</TD><TD PORT="f2">right</TD></TR>
</TABLE>')
    )));

    $graph->addChild($nStruct2 = new Node($dic, 'struct2', array(
      $plainText,
      new Attribute($dic, 'label', '<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0">
   <TR><TD PORT="f0">one</TD><TD>two</TD></TR>
</TABLE>'))));

    $graph->addChild($nStruct3 = new Node($dic, 'struct3', array(
      $plainText,
      new Attribute($dic, 'label', '<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0" CELLPADDING="4">
   <TR>
      <TD ROWSPAN="3">hello<BR/>world</TD>
      <TD COLSPAN="3">b</TD>
      <TD ROWSPAN="3">g</TD>
      <TD ROWSPAN="3">h</TD>
   </TR>
   <TR>
      <TD>c</TD><TD PORT="here">d</TD><TD>e</TD>
   </TR>
   <TR>
      <TD COLSPAN="3">f</TD>
   </TR>
</TABLE>'),
    )));

    $emptyTitle = new Attribute($dic, 'title', '');
    $graph->addChild($edge12 = new Edge($dic, $nStruct1, $nStruct2,
      array($emptyTitle), 'f1', 'f0'));
    $graph->addChild($edge13 = new Edge($dic, $nStruct1, $nStruct3,
      array($emptyTitle), 'f2', 'here'));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild() {
    $expected = <<<EOT
digraph structs {
  struct1 [ shape=plaintext, label=<<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0">
   <TR><TD>left</TD><TD PORT="f1">mid dle</TD><TD PORT="f2">right</TD></TR>
</TABLE>> ];
  struct2 [ shape=plaintext, label=<<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0">
   <TR><TD PORT="f0">one</TD><TD>two</TD></TR>
</TABLE>> ];
  struct3 [ shape=plaintext, label=<<TABLE BORDER="0" CELLBORDER="1" CELLSPACING="0" CELLPADDING="4">
   <TR>
      <TD ROWSPAN="3">hello<BR/>world</TD>
      <TD COLSPAN="3">b</TD>
      <TD ROWSPAN="3">g</TD>
      <TD ROWSPAN="3">h</TD>
   </TR>
   <TR>
      <TD>c</TD><TD PORT="here">d</TD><TD>e</TD>
   </TR>
   <TR>
      <TD COLSPAN="3">f</TD>
   </TR>
</TABLE>> ];
  struct1:f1 -> struct2:f0;
  struct1:f2 -> struct3:here;
} /* /digraph structs */

EOT;
    $this->check($expected, "Image_GraphViz test 2 passed.");
  }
}
