<?php
namespace Grafizzi\Graph;

/**
 * The basic interface of Attribute classes.
 */
interface AttributeInterface extends NamedInterface {
  /**
   * @return array
   *   An array of USASCII strings.
   */
  public static function getAllowedNames();

  /**
   * Return the default value for an attribute if not set.
   *
   * Note: null is not a valid default value.
   *
   * @param string $name
   */
  public static function getDefaultValue($name);

  public function getValue();
  public function setValue($value = null);
}
