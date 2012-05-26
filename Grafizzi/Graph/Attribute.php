<?php

/**
 * Status: working, improvements needed before release.
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
