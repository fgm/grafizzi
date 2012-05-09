<?php
namespace OSInet\Graph;

use OSInet\Graph\AttributeInterface;

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

  public $fName;
  public $fValue;

  /**
   * @see AttributeInterface::__construct()
   */
  public function __construct(\Pimple $dic, $name, $value = NULL) {
    //parent::__construct($dic);
    $this->setName($name);
    $this->setValue($value);
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

  public function getName() {
    if (!isset($this->fName)) {
      throw new \DomainException;
    }
  }

  public function getValue() {

  }

  /**
   * @see AttributeInterface::setName()
   */
  public function setName($name) {
    if (!empty(self::$fDefaults) && !array_key_exists($name, self::$fDefaults)) {
      throw new AttributeNameException("Invalid attribute $name");
    }
    $this->fName = $name;
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
