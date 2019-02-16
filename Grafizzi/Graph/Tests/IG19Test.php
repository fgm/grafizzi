<?php

/**
 * @file
 * Grafizzi\Graph\Tests\IG19Test: a component of the Grafizzi library.
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
 * A recreation of Image_GraphViz test19.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 19: "Call graph with labeled clusters"
 *
 * "Graph definition taken from GraphViz documentation"
 *
 * Note: ordering of insertions differs from Image_GraphViz, since Grafizzi
 * orders output by insertion order to allow customizing output order.
 */
class IG19Test extends BaseGraphTest {

  public function setUp() : void {
    // not strict by default.
    parent::setUpExtended();
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(true);
    $g->setAttributes(array(
      new Attribute($dic, 'size', 8.6),
      new Attribute($dic, 'ratio', 'fill'),
    ));

    $nullTitle = array(new Attribute($dic, 'title', NULL));

    $g->addChild($nFan = new Node($dic, 'fan', array('implicit' => TRUE)));

    $g->addChild($error = new Cluster($dic, 'error.h', array(
      new Attribute($dic, 'label', 'error.h'),
    )));
    $error->addChild($nInterp_err = new Node($dic, 'interp_err', $nullTitle));

    $g->addChild($sfio = new Cluster($dic, 'sfio.h', array(
      new Attribute($dic, 'label', 'sfio.h')
    )));
    $sfio->addChild($nSfprintf = new Node($dic, 'sfprintf', $nullTitle));

    $g->addChild($ciafan = new Cluster($dic, 'ciafan.c', array(
      new Attribute($dic, 'label', 'ciafan.c'),
    )));
    $ciafan->addChild($nCiafan = new Node($dic, 'ciafan', $nullTitle));
    $ciafan->addChild($nComputefan = new Node($dic, 'computefan', $nullTitle));
    $ciafan->addChild($nIncrement = new Node($dic, 'increment', $nullTitle));

    $g->addChild($util = new Cluster($dic, 'util.c', array(
      new Attribute($dic, 'label', 'util.c'),
    )));
    $util->addChild($nStringdup = new Node($dic, 'stringdup', $nullTitle));
    $util->addChild($nFatal = new Node($dic, 'fatal', $nullTitle));
    $util->addChild($nDebug = new Node($dic, 'debug', $nullTitle));

    $g->addChild($query = new Cluster($dic, 'query.h', array(
      new Attribute($dic, 'label', 'query.h'),
    )));
    $query->addChild($nRef = new Node($dic, 'ref', $nullTitle));
    $query->addChild($nDef = new Node($dic, 'def', $nullTitle));

    // No label on this cluster.
    $g->addChild($field = new Cluster($dic, 'field.h'));
    $field->addChild($nGet_sym_fields = new Node($dic, 'get_sym_fields', $nullTitle));

    $g->addChild($stdio = new Cluster($dic, 'stdio.h', array(
      new Attribute($dic, 'label', 'stdio.h'),
    )));
    $stdio->addChild($nStdprintf = new Node($dic, 'stdprintf', $nullTitle));
    $stdio->addChild($nStdsprintf = new Node($dic, 'stdsprintf', $nullTitle));

    $g->addChild($libc = new Cluster($dic, '<libc.a>'));
    $libc->addChild($nGetopt = new Node($dic, 'getopt', $nullTitle));

    $g->addChild($stdlib = new Cluster($dic,'stdlib.h', array(
      new Attribute($dic, 'label', 'stdlib.h'),
    )));
    $stdlib->addChild($nExit = new Node($dic, 'exit', $nullTitle));
    $stdlib->addChild($nMalloc = new Node($dic, 'malloc', $nullTitle));
    $stdlib->addChild($nFree = new Node($dic, 'free', $nullTitle));
    $stdlib->addChild($nRealloc = new Node($dic, 'realloc', $nullTitle));

    $g->addChild($main = new Cluster($dic, 'main.c'));
    $main->addChild($nMain = new Node($dic, 'main', $nullTitle));

    $g->addChild($index = new Cluster($dic, 'index.h'));
    $index->addChild($nInit_index = new Node($dic, 'init_index', $nullTitle));

    $g->addChild($string = new Cluster($dic, 'string.h', array(
      new Attribute($dic, 'label', 'string.h'),
    )));
    $string->addChild($nStrcpy = new Node($dic, 'strcpy', $nullTitle));
    $string->addChild($nStrlen = new Node($dic, 'strlen', $nullTitle));
    $string->addChild($nStrcmp = new Node($dic, 'strcmp', $nullTitle));
    $string->addChild($nStrcat = new Node($dic, 'strcat', $nullTitle));

    $g->addChild(new Edge($dic, $nCiafan, $nComputefan));
    $g->addChild(new Edge($dic, $nCiafan, $nDef));

    $g->addChild(new Edge($dic, $nFan, $nIncrement));
    $g->addChild(new Edge($dic, $nFan, $nFatal));
    $g->addChild(new Edge($dic, $nFan, $nRef));
    $g->addChild(new Edge($dic, $nFan, $nInterp_err));
    $g->addChild(new Edge($dic, $nFan, $nFree));
    $g->addChild(new Edge($dic, $nFan, $nExit));
    $g->addChild(new Edge($dic, $nFan, $nMalloc));
    $g->addChild(new Edge($dic, $nFan, $nStdsprintf));
    $g->addChild(new Edge($dic, $nFan, $nStrlen));

    $g->addChild(new Edge($dic, $nComputefan, $nFan));
    $g->addChild(new Edge($dic, $nComputefan, $nStdprintf));
    $g->addChild(new Edge($dic, $nComputefan, $nGet_sym_fields));
    $g->addChild(new Edge($dic, $nComputefan, $nMalloc));
    $g->addChild(new Edge($dic, $nComputefan, $nStrcmp));
    $g->addChild(new Edge($dic, $nComputefan, $nRealloc));
    $g->addChild(new Edge($dic, $nComputefan, $nStrlen));

    $g->addChild(new Edge($dic, $nStringdup, $nFatal));
    $g->addChild(new Edge($dic, $nStringdup, $nMalloc));
    $g->addChild(new Edge($dic, $nStringdup, $nStrcpy));
    $g->addChild(new Edge($dic, $nStringdup, $nStrlen));

    $g->addChild(new Edge($dic, $nMain, $nExit));
    $g->addChild(new Edge($dic, $nMain, $nInterp_err));
    $g->addChild(new Edge($dic, $nMain, $nCiafan));
    $g->addChild(new Edge($dic, $nMain, $nFatal));
    $g->addChild(new Edge($dic, $nMain, $nMalloc));
    $g->addChild(new Edge($dic, $nMain, $nStrcpy));
    $g->addChild(new Edge($dic, $nMain, $nGetopt));
    $g->addChild(new Edge($dic, $nMain, $nInit_index));
    $g->addChild(new Edge($dic, $nMain, $nStrlen));
    $g->addChild(new Edge($dic, $nIncrement, $nStrcmp));
    $g->addChild(new Edge($dic, $nDebug, $nSfprintf));
    $g->addChild(new Edge($dic, $nDebug, $nStrcat));
    $g->addChild(new Edge($dic, $nFatal, $nSfprintf));
    $g->addChild(new Edge($dic, $nFatal, $nExit));
  }

  /**
   * Tests g->build()
   */
  public function testBuild() {
    $expected = <<<'EOT'
digraph G {
  size=8.6;
  ratio=fill;

  subgraph "cluster_error.h" {
    label="error.h";

    interp_err;
  } /* /subgraph "cluster_error.h" */
  subgraph "cluster_sfio.h" {
    label="sfio.h";

    sfprintf;
  } /* /subgraph "cluster_sfio.h" */
  subgraph "cluster_ciafan.c" {
    label="ciafan.c";

    ciafan;
    computefan;
    increment;
  } /* /subgraph "cluster_ciafan.c" */
  subgraph "cluster_util.c" {
    label="util.c";

    stringdup;
    fatal;
    debug;
  } /* /subgraph "cluster_util.c" */
  subgraph "cluster_query.h" {
    label="query.h";

    ref;
    def;
  } /* /subgraph "cluster_query.h" */
  subgraph "cluster_field.h" {
    get_sym_fields;
  } /* /subgraph "cluster_field.h" */
  subgraph "cluster_stdio.h" {
    label="stdio.h";

    stdprintf;
    stdsprintf;
  } /* /subgraph "cluster_stdio.h" */
  subgraph "cluster_<libc.a>" {
    getopt;
  } /* /subgraph "cluster_<libc.a>" */
  subgraph "cluster_stdlib.h" {
    label="stdlib.h";

    exit;
    malloc;
    free;
    realloc;
  } /* /subgraph "cluster_stdlib.h" */
  subgraph "cluster_main.c" {
    main;
  } /* /subgraph "cluster_main.c" */
  subgraph "cluster_index.h" {
    init_index;
  } /* /subgraph "cluster_index.h" */
  subgraph "cluster_string.h" {
    label="string.h";

    strcpy;
    strlen;
    strcmp;
    strcat;
  } /* /subgraph "cluster_string.h" */
  ciafan -> computefan;
  ciafan -> def;
  fan -> increment;
  fan -> fatal;
  fan -> ref;
  fan -> interp_err;
  fan -> free;
  fan -> exit;
  fan -> malloc;
  fan -> stdsprintf;
  fan -> strlen;
  computefan -> fan;
  computefan -> stdprintf;
  computefan -> get_sym_fields;
  computefan -> malloc;
  computefan -> strcmp;
  computefan -> realloc;
  computefan -> strlen;
  stringdup -> fatal;
  stringdup -> malloc;
  stringdup -> strcpy;
  stringdup -> strlen;
  main -> exit;
  main -> interp_err;
  main -> ciafan;
  main -> fatal;
  main -> malloc;
  main -> strcpy;
  main -> getopt;
  main -> init_index;
  main -> strlen;
  increment -> strcmp;
  debug -> sfprintf;
  debug -> strcat;
  fatal -> sfprintf;
  fatal -> exit;
} /* /digraph G */

EOT;
    $this->check($expected, "Image_graphViz test 19 passed.");
  }
}
