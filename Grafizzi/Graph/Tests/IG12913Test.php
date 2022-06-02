<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\Tests\IG12913Test: a component of the Grafizzi library.
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
use Grafizzi\Graph\Filter\DotFilter;
use Grafizzi\Graph\Filter\FilterInterface;
use Grafizzi\Graph\Node;
use Pimple\Container;

require 'vendor/autoload.php';

/**
 * A recreation of Image_GraphViz bug_12913.phpt
 *
 * Image_GraphViz version author: Philippe Jausions <jausions@php.net>
 *
 * Request 12913: "PEAR_Error on failure"
 *
 * Since Grafizzi is not a PEAR component, it can throw exceptions instead of
 * using PEAR errors, so the test applies to exceptions.
 */
class IG12913Test extends BaseGraphTest {

  /**
   *
   * @var \Grafizzi\Graph\Graph
   */
  public $Graph2;

  /**
   * @var \Pimple\Container
   */
  public $dic2;

  public function setUp(): void {
    parent::setUpExtended('G');
    $this->Graph2 = $this->Graph;
    $this->dic2 = $this->dic;
    $this->dic2['use_exceptions'] = false;
    unset($this->dic, $this->Graph);

    parent::setUpExtended('G');
    $this->dic['use_exceptions'] = TRUE;

    $this->Graph->addChild($cluster1 = new Cluster($this->dic, 1));
    $cluster1->addChild($node1 = new Node($this->dic, 'Node1', [
      new Attribute($this->dic, 'label', 'Node1'),
    ]));

    $this->Graph2->addChild($cluster2 = new Cluster($this->dic2, '2'));
    $cluster2->addChild($node2 = new Node($this->dic2, 'Node2', [
      new Attribute($this->dic2, 'label', 'Node2'),
    ]));
  }

  /**
   * @param DotFilter $filter
   * @param string $format
   */
  protected function withDicException(DotFilter $filter, string $format): void {
    try {
      $filter->image($format);
    } catch (\InvalidArgumentException $e) {
      $this->assertInstanceOf('InvalidArgumentException', $e,
        'Invalid argument for invalid format.');
    }
  }

  /**
   * @param DotFilter $filter
   * @param string $format
   */
  protected function withDicNoException(DotFilter $filter, string $format): void {
    $result = $filter->image($format);
    $this->assertFalse($result, 'Unavailable format image.');
  }

  /**
   * Tests Graph->image()
   */
  public function testImage(): void {
    $dotFilter = new DotFilter();
    $format = DotFilterTest::INVALID_FORMAT;
    $this->withDicException(
      $dotFilter->setDic(new Container(['use_exceptions' => true])), $format);
    $this->withDicNoException(
      $dotFilter->setDic(new Container(['use_exceptions' => false])), $format);
  }

}
