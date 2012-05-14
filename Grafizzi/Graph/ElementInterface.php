<?php
namespace Grafizzi\Graph;

interface ElementInterface extends NamedInterface {
  public function addChild(ElementInterface $child);
  public function adjustDepth($extra);

  public static function getAllowedChildTypes();
  public function getAttributeByName($name);
  public function getRoot();

  public function removeAttribute(AttributeInterface $attribute);
  public function removeAttributeByName($name);
  public function removeChild(ElementInterface $child);
  public function removeChildByName($name);

  public function setAttribute(AttributeInterface $attribute);
  public function setAttributes(array $attributes);
  public function setParent(ElementInterface $parent);
}
