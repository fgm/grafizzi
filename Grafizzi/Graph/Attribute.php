<?php

/**
 * @file
 * Grafizzi\Graph\Attribute: a component of the Grafizzi library.
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

namespace Grafizzi\Graph;

use Monolog\Logger;

/**
 * An Element attribute.
 */
class Attribute extends AbstractNamed implements AttributeInterface {
  /**
   * A hash of default values for allowed attributes.
   *
   * If it is empty, any attribute is allowed.
   *
   * @var array
   */
  public static $fDefaults = array();

  public $fValue;

  /**
   * @see AttributeInterface::__construct()
   */
  public function __construct(\Pimple $dic, $name, $value = null) {
    parent::__construct($dic);
    $this->setName($name);
    $this->setValue($value);
  }

  /**
   * @todo FIXME escape name, value more carefully
   *
   * Ignores $directed.
   */
  public function build($directed = null) {
    $name = $this->getName();
    $this->logger->debug("Building attribute " . $name);
    $value = $this->getValue();
    if ('title' == $name && empty($value)) {
      $ret = null;
    }
    else {
      $escape = in_array($name, array('label', 'headlabel', 'taillabel'));
      $ret = "$name=" . self::escape($value, $escape);
    }
    return $ret;
  }

  /**
   * @see AttributeInterface::getAllowedNames()
   *
   * @return array
   */
  public static function getAllowedNames() {
    $ret = self::$fDefaults;
    return $ret;
  }

  /**
   *
   * @param string $name
   *
   * @see AttributeInterface::getDefaultValue()
   *
   */
  public static function getDefaultValue($name) {
    $ret = isset(self::$fDefaults[$name])
      ? self::$fDefaults[$name]
      : null;
    return $ret;
  }

  public function getType() {
    $ret = 'attribute';
    return $ret;
  }

  public function getValue() {
    return $this->fValue;
  }

  /**
   * In addition to basic behavior, validate name.
   *
   * @see AbstractNamed::setName()
   *
   * @throws AttributeNameException
   */
  public function setName($name) {
    if (!empty(self::$fDefaults) && !array_key_exists($name, self::$fDefaults)) {
      $message = "Invalid attribute $name.";
      $this->logger->err($message);
      throw new AttributeNameException($message);
    }
    parent::setName($name);
  }

  /**
   * Note: a null default value will work too: if $value is not null, the
   * default is ignored, and if value is null, isset() fails, and fValue is set
   * to $value, i.e. null too.
   *
   * @see AttributeInterface::setValue()
   */
  public function setValue($value = null) {
    $this->logger->debug("{$this->fName}->value = "
      . print_r($value, true) . ".");
    if (!isset($value) && isset(self::$fDefaults[$this->fName])) {
      $value = self::$fDefaults[$this->fName];
    }
    $this->fValue = $value;
  }
}
