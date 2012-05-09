<?php
namespace OSInet\Graph;

/**
 * The basic interface of Attribute classes.
 */
interface AttributeInterface {
  /**
   * @return array
   *   An array of USASCII strings.
   */
  public static function getAllowedNames();

  /**
   * Return the default value for an attribute if not set.
   *
   * Note: NULL is not a valid default value.
   *
   * @param string $name
   */
  public static function getDefaultValue($name);

  public function getName();
  public function getValue();

  public function setName($name);
  public function setValue($value);
}
