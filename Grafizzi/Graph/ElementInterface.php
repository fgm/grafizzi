<?php
namespace Grafizzi\Graph;

use Grafizzi\Graph\AttributeInterface;
use Grafizzi\Graph\LoggableInterface;

interface ElementInterface extends LoggableInterface {
  public function addChild(ElementInterface $child);

  public static function getAllowedChildTypes();
  public function getAttributeByName($name);
  public static function getType();

  public function removeAttribute(AttributeInterface $attribute);
  public function removeAttributeByName($name);
  public function removeChild(ElementInterface $child);

  public function setAttribute(AttributeInterface $attribute);
  public function setAttributes(array $attributes);
}
