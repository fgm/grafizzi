<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\AbstractLoggable;
use Grafizzi\Graph\AttributeInterface;

/**
 * An Element attribute.
 */
abstract class AbstractAttribute extends AbstractLoggable implements AttributeInterface {
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
  public function __construct(\Pimple $dic, $name, $value = NULL) {
    parent::__construct($dic);
    $this->setName($name);
    $this->setValue($value);
  }

  public function build() {
    $name = $this->getName();
    $value = $this->getValue();
    // TODO escape name, value more carefully
    $ret = "$name=\"$value\"";
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
    if (isset(self::$fDefaults[$name])) {
      $ret = self::$fDefaults[$name];
    }
    else {
      $ret = NULL;
    }
  }

  public function getValue() {
    return $this->fValue;
  }

  /**
   * In addition to basic behavior, validate name.
   *
   * (non-PHPdoc)
   * @see Grafizzi\Graph.AbstractLoggable::setName()
   *
   * @throws AttributeNameException
   */
  public function setName($name) {
    if (!empty(self::$fDefaults) && !array_key_exists($name, self::$fDefaults)) {
      throw new AttributeNameException("Invalid attribute $name");
    }
    parent::setName($name);
  }

  /**
   * Note: a NULL default value will work too: if $value is not NULL, the
   * default is ignored, and if value is NULL, isset() fails, and fValue is set
   * to $value, i.e. NULL too.
   *
   * @see AttributeInterface::setValue()
   */
  public function setValue($value = NULL) {
    if (!isset($value) && isset(self::$fDefaults[$this->fName])) {
      $value = self::$fDefaults[$this->fName];
    }
    $this->fValue = $value;
  }
}
