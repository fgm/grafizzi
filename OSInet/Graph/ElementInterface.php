<?php
namespace OSInet\Graph;

use OSInet\Graph\AttributeInterface;

/**
 *
 * @author marand
 */
interface ElementInterface extends LoggableInterface {
  public function setAttribute(AttributeInterface $attribute);
  public function setAttributes(array $attributes);
  public function addChild(ElementInterface $child);

  public static function getAllowedChildTypes();
  public function getAttributeByName();
  public function getName();
  public static function getType();

  public function removeAttribute(AttributeInterface $attribute);
  public function removeAttributeByName($name);
  public function removeChild(ElementInterface $child);

  public function setName();
}

