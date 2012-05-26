<?php

/**
 * @file
 * A recreation of Image_GraphViz test2.phpt
 *
 * @author Frédéric G. Marand <fgm@osinet.fr>
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 2: "HTML-like labels"
 */

namespace Grafizzi\Graph\Tests;

use Monolog\Logger;

use Grafizzi\Graph\Attribute;
use Grafizzi\Graph\Edge;
use Grafizzi\Graph\Graph;
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

require 'vendor/autoload.php';

/**
 * Graph test case.
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
    $build = $this->Graph->build();
    // echo "\n\n$build\n\n";
    $this->assertEquals($expected, $build, "Image_GraphViz test 2 passed.");
  }
}
