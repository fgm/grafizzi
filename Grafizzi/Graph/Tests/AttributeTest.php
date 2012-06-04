<?php

/**
 * @file
 * Grafizzi\Graph\Tests\AttributeTest: a component of the Grafizzi library.
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

use \Grafizzi\Graph\Attribute;

/**
 * Attribute test case.
 */
class AttributeTest extends BaseGraphTest {

  /**
   *
   * @var Attribute
   */
  private $Attribute;

  /**
   * Prepares the environment before running a test.
   */
  protected function setUp() {
    parent::setUp();
    $this->Attribute = new Attribute($this->dic, 'label', 'A plain label');
  }

  /**
   * Cleans up the environment after running a test.
   */
  protected function tearDown() {
    $this->Attribute = null;
    parent::tearDown();
  }

  /**
   * Tests Attribute->__construct()
   */
  public function test__construct() {
    $this->assertEquals('label', $this->Attribute->getName());
    $this->assertEquals('A plain label', $this->Attribute->getValue());
  }

  /**
   * Tests Attribute->build()
   */
  public function testBuild() {
    $ret = $this->Attribute->build($this->Graph->getDirected());
    $this->assertEquals('label="A plain label"', $ret);

    $title = new Attribute($this->dic, 'title', 'Non empty title');
    $ret = $title->build($this->Graph->getDirected());
    $this->assertEquals('title="Non empty title"', $ret, 'Non-empty title built like a normal attribute.');

    $title = new Attribute($this->dic, 'title', '');
    $ret = $title->build($this->Graph->getDirected());
    $this->assertNull($ret, 'Empty title built as null.');

  }

  /**
   * Tests Attribute::getAllowedNames()
   */
  public function testGetAllowedNames() {
    $this->assertEmpty(Attribute::getAllowedNames());
  }

  /**
   * Tests Attribute::getDefaultValue()
   */
  public function testGetDefaultValue() {
    $name = $this->Attribute->getName();
    $this->assertNull(Attribute::getDefaultValue($name));
  }

  /**
   * Tests Attribute::getType()
   */
  public function testGetType() {
    $this->assertEquals('attribute', Attribute::getType());
  }

  /**
   * Tests Attribute->getValue()
   */
  public function testGetValue() {
    $this->assertEquals('A plain label', $this->Attribute->getValue());
  }

  /**
   * Tests Attribute->setName()
   */
  public function testSetName() {
    $this->Attribute->setName('font');
    $this->assertEquals('font', $this->Attribute->getName());
  }

  /**
   * Tests Attribute->setValue()
   *
   * @depends testSetName
   */
  public function testSetValue() {
    $name = 'Times New Roman';
    $this->Attribute->setValue($name);
    $this->assertEquals($name, $this->Attribute->getValue());
  }
}

