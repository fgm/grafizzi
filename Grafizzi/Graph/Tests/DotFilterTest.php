<?php

/**
 * @file
 * Grafizzi\Graph\Tests\DotFilterTest: a component of the Grafizzi library.
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

require 'vendor/autoload.php';

use Grafizzi\Graph\Filter\DotFilter;
use Pimple\Container;

/**
 * DotFilter test case.
 */
class DotFilterTest extends BaseFilterTest {

  /**
   * File to copy to.
   *
   * @var string
   */
  private $out = null;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $dotFilter = new DotFilter();
    $dotFilter->setDic(new Container());
    $this->filters[] = $dotFilter;
  }

  /**
   * Tests DotFilter->filter()
   */
  public function testFilter() {
    $in = 'digraph G { foo -> bar ; }';
    list($out, ) = $this->filters[0]->filter($in);
    $this->assertInternalType('string', $out, 'Dot filter returns string output');
  }

  public function testImageHappy() {
    /** @var \Grafizzi\Graph\Filter\DotFilter $filter */
    $filter = reset($this->filters);
    $this->assertNull($filter->formats, "Filter formats is initially null");
    $this->assertEquals(0, count($filter->formats), "Filter formats list is initially empty");

    $image = $filter->image('svg');

    $this->assertInternalType('array', $filter->formats, "Filter formats is an array");
    $this->assertTrue(count($filter->formats) >= 1, "There is at least one format available from the DotFilter.");

    // TODO check actual image generation once it is actually performed.
    $this->assertTrue($image, 'Image generation succeeds.');
  }

  public function testImageSadFormatNoException() {
    /** @var \Grafizzi\Graph\Filter\DotFilter $filter */
    $filter = reset($this->filters);
    $this->assertNull($filter->formats, "Filter formats is initially null");
    $this->assertEquals(0, count($filter->formats), "Filter formats list is initially empty");

    $image = $filter->image('some unlikely to be valid format');

    $this->assertInternalType('array', $filter->formats, "Filter formats is an array");
    $this->assertTrue(count($filter->formats) >= 1, "There is at least one format available from the DotFilter.");

    $this->assertEmpty($image, "Image is not generated when the image format is invalid");
  }

  /**
   * @expectedException \InvalidArgumentException
   */
  public function testImageSadFormatException() {
    /** @var \Grafizzi\Graph\Filter\DotFilter $filter */
    $filter = new DotFilter();
    $dic = new Container();
    $dic['use_exceptions'] = TRUE;
    $filter->setDic($dic);

    $image = $filter->image('some unlikely to be valid format');
  }
}
