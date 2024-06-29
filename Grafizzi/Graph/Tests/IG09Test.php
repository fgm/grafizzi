<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\IG09Test: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
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
use Grafizzi\Graph\Node;
use Grafizzi\Graph\Subgraph;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz test9.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Test 9: "Unit test for graph with constrained rank clusters"
 *
 * Note: Order of instructions differs from Image_GraphViz: Grafizzi orders the
 * generated source according to the sequence of addChild() calls, whereas
 * Image_GraphViz orders subgraphs before edges whatever their insertion order.
 */
class IG09Test extends BaseGraphCase {

  public function setUp(): void {
    // not strict by default.
    parent::setUpExtended('asde91');
    $g = $this->Graph;
    $dic = $this->dic;
    $g->setDirected(TRUE);
    $g->setAttribute(new Attribute($dic, 'ranksep', .75));

    $rankSame = new Attribute($dic, 'rank', 'same');
    $sgAttrs = [
      $rankSame,
      new Attribute($dic, 'title', NULL),
    ];

    /* program types graph */
    $g->addChild($sgType = new Subgraph($dic, 'type', $sgAttrs));
    $sgType->addChild(new Node($dic, 'Software IS'));
    $sgType->addChild(new Node($dic, 'Configuration Mgt'));
    $sgType->addChild(new Node($dic, 'Architecture & Libraries'));
    $sgType->addChild(new Node($dic, 'Process'));

    /* time graphs */
    $g->addChild($sgPast = new Subgraph($dic, 'past', $sgAttrs));
    $g->addChild($sg1978 = new Subgraph($dic, '1978', $sgAttrs));
    $g->addChild($sg1980 = new Subgraph($dic, '1980', $sgAttrs));
    $g->addChild($sg1982 = new Subgraph($dic, '1982', $sgAttrs));
    $g->addChild($sg1983 = new Subgraph($dic, '1983', $sgAttrs));
    $g->addChild($sg1985 = new Subgraph($dic, '1985', $sgAttrs));
    $g->addChild($sg1986 = new Subgraph($dic, '1986', $sgAttrs));
    $g->addChild($sg1987 = new Subgraph($dic, '1987', $sgAttrs));
    $g->addChild($sg1988 = new Subgraph($dic, '1988', $sgAttrs));
    $g->addChild($sg1989 = new Subgraph($dic, '1989', $sgAttrs));
    $g->addChild($sg1990 = new Subgraph($dic, '1990', $sgAttrs));
    $g->addChild($sgFuture = new Subgraph($dic, 'future', $sgAttrs));

    /* programs */
    $sgPast->addChild($nBourneSh = new Node($dic, 'Bourne sh'));
    $sgPast->addChild($nMake = new Node($dic, 'make'));
    $sgPast->addChild($nSCCS = new Node($dic, 'SCCS'));
    $sgPast->addChild($nYacc = new Node($dic, 'yacc'));
    $sgPast->addChild($nCron = new Node($dic, 'cron'));

    $sg1978->addChild($nReiserCpp = new Node($dic, 'Reiser cpp'));
    $sg1978->addChild($nCshell = new Node($dic, 'Cshell'));

    $sg1980->addChild($nEmacs = new Node($dic, 'emacs'));
    $sg1980->addChild($nBuild = new Node($dic, 'build'));
    $sg1980->addChild($nVi = new Node($dic, 'vi'));

    $sg1982->addChild($nCurses = new Node($dic, '<curses>'));
    $sg1982->addChild($nRCS = new Node($dic, 'RCS'));
    $sg1982->addChild($nIMX = new Node($dic, 'IMX'));
    $sg1982->addChild($nSYNED = new Node($dic, 'SYNED'));

    $sg1983->addChild($nksh = new Node($dic, 'ksh'));
    $sg1983->addChild($nIFS = new Node($dic, 'IFS'));
    $sg1983->addChild($nTTU = new Node($dic, 'TTU'));

    $sg1985->addChild($nNmake = new Node($dic, 'nmake'));
    $sg1985->addChild($nPeggy = new Node($dic, 'Peggy'));

    $sg1986->addChild($nNcpp = new Node($dic, 'ncpp'));
    $sg1986->addChild($nKsh_i = new Node($dic, 'ksh-i'));
    $sg1986->addChild($nCurses_i = new Node($dic, '<curses-i>'));
    $sg1986->addChild($nPg2 = new Node($dic, 'PG2'));
    $sg1986->addChild($nCasterisk = new Node($dic, 'C*'));

    $sg1987->addChild($nAnsiCpp = new Node($dic, 'Ansi cpp'));
    $sg1987->addChild($nNmake20 = new Node($dic, 'nmake 2.0'));
    $sg1987->addChild($n3DFS = new Node($dic, '3D File System'));
    $sg1987->addChild($nFdelta = new Node($dic, 'fdelta'));
    $sg1987->addChild($nDAG = new Node($dic, 'DAG'));
    $sg1987->addChild($nCSAS = new Node($dic, 'CSAS'));

    $sg1988->addChild($nCia = new Node($dic, 'CIA'));
    $sg1988->addChild($nSBCS = new Node($dic, 'SBCS'));
    $sg1988->addChild($nKsh88 = new Node($dic, 'ksh-88'));
    $sg1988->addChild($nPegasusPml = new Node($dic, 'PEGASUS/PML'));
    $sg1988->addChild($nPAX = new Node($dic, 'PAX'));
    $sg1988->addChild($nBacktalk = new Node($dic, 'backtalk'));

    $sg1989->addChild($nCIApp = new Node($dic, 'CIA++'));
    $sg1989->addChild($nAPP = new Node($dic, 'APP'));
    $sg1989->addChild($nSHIP = new Node($dic, 'SHIP'));
    $sg1989->addChild($nDataShare = new Node($dic, 'DataShare'));
    $sg1989->addChild($nRyacc = new Node($dic, 'ryacc'));
    $sg1989->addChild($nMosaic = new Node($dic, 'Mosaic'));

    $sg1990->addChild($nLibft = new Node($dic, 'libft'));
    $sg1990->addChild($nCoShell = new Node($dic, 'CoShell'));
    $sg1990->addChild($nDIA = new Node($dic, 'DIA'));
    $sg1990->addChild($nIFS_i = new Node($dic, 'IFS-i'));
    $sg1990->addChild($nKyacc = new Node($dic, 'kyacc'));
    $sg1990->addChild($nSfio = new Node($dic, 'sfio'));
    $sg1990->addChild($nYeast = new Node($dic, 'yeast'));
    $sg1990->addChild($nML_X = new Node($dic, 'ML-X'));
    $sg1990->addChild($nDOT = new Node($dic, 'DOT'));

    $sgFuture->addChild($nAST = new Node($dic, 'Adv. Software Technology'));

    /* the time-line graph */
    $g->addChild(new Edge($dic, $yPast = new Node($dic, 'past'),
      $y1978 = new Node($dic, 1978)));
    $g->addChild(new Edge($dic, $y1978, $y1980 = new Node($dic, 1980)));
    $g->addChild(new Edge($dic, $y1980, $y1982 = new Node($dic, 1982)));
    $g->addChild(new Edge($dic, $y1982, $y1983 = new Node($dic, 1983)));
    $g->addChild(new Edge($dic, $y1983, $y1985 = new Node($dic, 1985)));
    $g->addChild(new Edge($dic, $y1985, $y1986 = new Node($dic, 1986)));
    $g->addChild(new Edge($dic, $y1986, $y1987 = new Node($dic, 1987)));
    $g->addChild(new Edge($dic, $y1987, $y1988 = new Node($dic, 1988)));
    $g->addChild(new Edge($dic, $y1988, $y1989 = new Node($dic, 1989)));
    $g->addChild(new Edge($dic, $y1989, $y1990 = new Node($dic, 1990)));
    $g->addChild(new Edge($dic, $y1990, $yFuture = new Node($dic, 'future')));

    /* hierarchy */
    $g->addChild(new Edge($dic, $nSCCS, $nRCS));
    $g->addChild(new Edge($dic, $nSCCS, $n3DFS));
    $g->addChild(new Edge($dic, $nSCCS, $nNmake));
    $g->addChild(new Edge($dic, $nMake, $nNmake));
    $g->addChild(new Edge($dic, $nMake, $nBuild));
    $g->addChild(new Edge($dic, $nBourneSh, $nCshell));
    $g->addChild(new Edge($dic, $nBourneSh, $nksh));
    $g->addChild(new Edge($dic, $nYacc, $nRyacc));
    $g->addChild(new Edge($dic, $nCron, $nYeast));

    $g->addChild(new Edge($dic, $nReiserCpp, $nNcpp));
    $g->addChild(new Edge($dic, $nCshell, $nksh));

    $g->addChild(new Edge($dic, $nBuild, $nNmake20));
    $g->addChild(new Edge($dic, $nVi, $nksh));
    $g->addChild(new Edge($dic, $nVi, $nCurses));
    $g->addChild(new Edge($dic, $nEmacs, $nksh));

    $g->addChild(new Edge($dic, $nRCS, $nSBCS));
    $g->addChild(new Edge($dic, $nRCS, $nFdelta));
    $g->addChild(new Edge($dic, $nCurses, $nCurses_i));
    $g->addChild(new Edge($dic, $nSYNED, $nPeggy));
    $g->addChild(new Edge($dic, $nIMX, $nTTU));

    $g->addChild(new Edge($dic, $nksh, $nNmake));
    $g->addChild(new Edge($dic, $nksh, $nKsh_i));
    $g->addChild(new Edge($dic, $nksh, $nKsh88));
    $g->addChild(new Edge($dic, $nIFS, $nCurses_i));
    $g->addChild(new Edge($dic, $nIFS, $nSfio));
    $g->addChild(new Edge($dic, $nIFS, $nIFS_i));
    $g->addChild(new Edge($dic, $nTTU, $nPg2));

    $g->addChild(new Edge($dic, $nNmake, $nksh));
    $g->addChild(new Edge($dic, $nNmake, $nNcpp));
    $g->addChild(new Edge($dic, $nNmake, $n3DFS));
    $g->addChild(new Edge($dic, $nNmake, $nNmake20));
    $g->addChild(new Edge($dic, $nPeggy, $nPegasusPml));
    $g->addChild(new Edge($dic, $nPeggy, $nRyacc));

    $g->addChild(new Edge($dic, $nCasterisk, $nCSAS));
    $g->addChild(new Edge($dic, $nNcpp, $nAnsiCpp));
    $g->addChild(new Edge($dic, $nCurses_i, $nFdelta));
    $g->addChild(new Edge($dic, $nKsh_i, $nKsh88));
    $g->addChild(new Edge($dic, $nPg2, $nBacktalk));

    $g->addChild(new Edge($dic, $nDAG,
      $nSoftwareIs = new Node($dic, 'Software IS')));
    $g->addChild(new Edge($dic, $nDAG, $nDOT));
    $g->addChild(new Edge($dic, $nDAG, $nDIA));
    $g->addChild(new Edge($dic, $nCSAS, $nCia));
    $g->addChild(new Edge($dic, $nAnsiCpp,
      $nConfigMgt = new Node($dic, 'Configuration Mgt')));
    $g->addChild(new Edge($dic, $nFdelta, $nSBCS));
    $g->addChild(new Edge($dic, $nFdelta, $nPAX));
    $g->addChild(new Edge($dic, $n3DFS, $nConfigMgt));
    $g->addChild(new Edge($dic, $nNmake20, $nConfigMgt));
    $g->addChild(new Edge($dic, $nNmake20, $nCoShell));

    $g->addChild(new Edge($dic, $nCia, $nCIApp));
    $g->addChild(new Edge($dic, $nCia, $nDIA));
    $g->addChild(new Edge($dic, $nSBCS, $nConfigMgt));
    $g->addChild(new Edge($dic, $nPAX, $nSHIP));
    $g->addChild(new Edge($dic, $nKsh88, $nConfigMgt));
    $g->addChild(new Edge($dic, $nKsh88,
      $nArchi = new Node($dic, 'Architecture & Libraries')));
    $g->addChild(new Edge($dic, $nKsh88, $nSfio));
    $g->addChild(new Edge($dic, $nPegasusPml, $nML_X));
    $g->addChild(new Edge($dic, $nPegasusPml, $nArchi));
    $g->addChild(new Edge($dic, $nBacktalk, $nDataShare));

    $g->addChild(new Edge($dic, $nCIApp, $nSoftwareIs));
    $g->addChild(new Edge($dic, $nAPP, $nDIA));
    $g->addChild(new Edge($dic, $nAPP, $nSoftwareIs));
    $g->addChild(new Edge($dic, $nSHIP, $nConfigMgt));
    $g->addChild(new Edge($dic, $nDataShare, $nArchi));
    $g->addChild(new Edge($dic, $nRyacc, $nKyacc));
    $g->addChild(new Edge($dic, $nMosaic,
      $nProcess = new Node($dic, 'Process')));

    $g->addChild(new Edge($dic, $nDOT, $nSoftwareIs));
    $g->addChild(new Edge($dic, $nDIA, $nSoftwareIs));
    $g->addChild(new Edge($dic, $nLibft, $nSoftwareIs));
    $g->addChild(new Edge($dic, $nCoShell, $nConfigMgt));
    $g->addChild(new Edge($dic, $nCoShell, $nArchi));
    $g->addChild(new Edge($dic, $nSfio, $nArchi));
    $g->addChild(new Edge($dic, $nIFS_i, $nArchi));
    $g->addChild(new Edge($dic, $nML_X, $nArchi));
    $g->addChild(new Edge($dic, $nKyacc, $nArchi));
    $g->addChild(new Edge($dic, $nYeast, $nProcess));

    $g->addChild(new Edge($dic, $nArchi, $nAST));
    $g->addChild(new Edge($dic, $nSoftwareIs, $nAST));
    $g->addChild(new Edge($dic, $nConfigMgt, $nAST));
    $g->addChild(new Edge($dic, $nProcess, $nAST));
  }

  /**
   * Tests Graph->build()
   */
  public function testBuild(): void {
    /* Test uses the alternate format for subgraphs:
        subgraph type {
          graph [ rank=same ];
          <nodes>
        }
      instead of:
        subgraph type {
          rank = same;
          <nodes>
        }
     */
    $expected = <<<EOT
digraph asde91 {
  ranksep=0.75;

  subgraph type {
    rank=same;

    "Software IS";
    "Configuration Mgt";
    "Architecture & Libraries";
    Process;
  } /* /subgraph type */
  subgraph past {
    rank=same;

    "Bourne sh";
    make;
    SCCS;
    yacc;
    cron;
  } /* /subgraph past */
  subgraph 1978 {
    rank=same;

    "Reiser cpp";
    Cshell;
  } /* /subgraph 1978 */
  subgraph 1980 {
    rank=same;

    emacs;
    build;
    vi;
  } /* /subgraph 1980 */
  subgraph 1982 {
    rank=same;

    "<curses>";
    RCS;
    IMX;
    SYNED;
  } /* /subgraph 1982 */
  subgraph 1983 {
    rank=same;

    ksh;
    IFS;
    TTU;
  } /* /subgraph 1983 */
  subgraph 1985 {
    rank=same;

    nmake;
    Peggy;
  } /* /subgraph 1985 */
  subgraph 1986 {
    rank=same;

    ncpp;
    "ksh-i";
    "<curses-i>";
    PG2;
    "C*";
  } /* /subgraph 1986 */
  subgraph 1987 {
    rank=same;

    "Ansi cpp";
    "nmake 2.0";
    "3D File System";
    fdelta;
    DAG;
    CSAS;
  } /* /subgraph 1987 */
  subgraph 1988 {
    rank=same;

    CIA;
    SBCS;
    "ksh-88";
    "PEGASUS/PML";
    PAX;
    backtalk;
  } /* /subgraph 1988 */
  subgraph 1989 {
    rank=same;

    "CIA++";
    APP;
    SHIP;
    DataShare;
    ryacc;
    Mosaic;
  } /* /subgraph 1989 */
  subgraph 1990 {
    rank=same;

    libft;
    CoShell;
    DIA;
    "IFS-i";
    kyacc;
    sfio;
    yeast;
    "ML-X";
    DOT;
  } /* /subgraph 1990 */
  subgraph future {
    rank=same;

    "Adv. Software Technology";
  } /* /subgraph future */
  past -> 1978;
  1978 -> 1980;
  1980 -> 1982;
  1982 -> 1983;
  1983 -> 1985;
  1985 -> 1986;
  1986 -> 1987;
  1987 -> 1988;
  1988 -> 1989;
  1989 -> 1990;
  1990 -> future;
  SCCS -> RCS;
  SCCS -> "3D File System";
  SCCS -> nmake;
  make -> nmake;
  make -> build;
  "Bourne sh" -> Cshell;
  "Bourne sh" -> ksh;
  yacc -> ryacc;
  cron -> yeast;
  "Reiser cpp" -> ncpp;
  Cshell -> ksh;
  build -> "nmake 2.0";
  vi -> ksh;
  vi -> "<curses>";
  emacs -> ksh;
  RCS -> SBCS;
  RCS -> fdelta;
  "<curses>" -> "<curses-i>";
  SYNED -> Peggy;
  IMX -> TTU;
  ksh -> nmake;
  ksh -> "ksh-i";
  ksh -> "ksh-88";
  IFS -> "<curses-i>";
  IFS -> sfio;
  IFS -> "IFS-i";
  TTU -> PG2;
  nmake -> ksh;
  nmake -> ncpp;
  nmake -> "3D File System";
  nmake -> "nmake 2.0";
  Peggy -> "PEGASUS/PML";
  Peggy -> ryacc;
  "C*" -> CSAS;
  ncpp -> "Ansi cpp";
  "<curses-i>" -> fdelta;
  "ksh-i" -> "ksh-88";
  PG2 -> backtalk;
  DAG -> "Software IS";
  DAG -> DOT;
  DAG -> DIA;
  CSAS -> CIA;
  "Ansi cpp" -> "Configuration Mgt";
  fdelta -> SBCS;
  fdelta -> PAX;
  "3D File System" -> "Configuration Mgt";
  "nmake 2.0" -> "Configuration Mgt";
  "nmake 2.0" -> CoShell;
  CIA -> "CIA++";
  CIA -> DIA;
  SBCS -> "Configuration Mgt";
  PAX -> SHIP;
  "ksh-88" -> "Configuration Mgt";
  "ksh-88" -> "Architecture & Libraries";
  "ksh-88" -> sfio;
  "PEGASUS/PML" -> "ML-X";
  "PEGASUS/PML" -> "Architecture & Libraries";
  backtalk -> DataShare;
  "CIA++" -> "Software IS";
  APP -> DIA;
  APP -> "Software IS";
  SHIP -> "Configuration Mgt";
  DataShare -> "Architecture & Libraries";
  ryacc -> kyacc;
  Mosaic -> Process;
  DOT -> "Software IS";
  DIA -> "Software IS";
  libft -> "Software IS";
  CoShell -> "Configuration Mgt";
  CoShell -> "Architecture & Libraries";
  sfio -> "Architecture & Libraries";
  "IFS-i" -> "Architecture & Libraries";
  "ML-X" -> "Architecture & Libraries";
  kyacc -> "Architecture & Libraries";
  yeast -> Process;
  "Architecture & Libraries" -> "Adv. Software Technology";
  "Software IS" -> "Adv. Software Technology";
  "Configuration Mgt" -> "Adv. Software Technology";
  Process -> "Adv. Software Technology";
} /* /digraph asde91 */

EOT;
    $this->check($expected, "Image_GraphViz test 9 passed.");
  }

}
