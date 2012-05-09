<?php
namespace OSInet\Graph;

use Monolog\Logger;

use OSInet\Graph\ElementInterface;

class Element extends AbstractLoggable implements ElementInterface {

  public $fAttributes = array();

  public $fChildren = array();

  public function addChild(ElementInterface $child) {
  }

  public function build() {
  }

  public static function getAllowedChildTypes() {

  }

  public function getAttributeByName() {
  }

  public function getName() {
  }

  public static function getType() {

  }

  public function removeAttribute(AttributeInterface $attribute) {
    $name = $attribute->getName();
    if (!isset($name)) {
      throw new \InvalidArgumentException('Trying to remove unnamed attribute.');
    }
    $this->removeAttributeByName($name);
  }

  public function removeAttributeByName($name) {
    unset($this->fAttributes[$name]);
  }

  public function removeChild(ElementInterface $child) {
  }

  public function setAttribute(AttributeInterface $attribute) {
    $name = $attribute->getName();
    var_dump($name);
    if (!isset($name)) {
      $message = 'Trying to set unnamed attribute.';
      $this->logger->debug($message, debug_backtrace(FALSE));
      throw new \InvalidArgumentException($message);
    }
    $this->fAttributes[$name] = $attribute;
  }

  public function setAttributes(array $attributes) {
    foreach ($attributes as $attribute) {
      if (!in_array('AttributeInterface', class_implements($attribute))) {
        throw new \InvalidArgumentException('Trying to set non-attribute as an attribute.');
      }
      $this->setAttribute($attribute);
    }
  }

  public function setName() {
  }
}
