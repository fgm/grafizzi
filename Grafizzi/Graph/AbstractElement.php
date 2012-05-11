<?php
namespace Grafizzi\Graph;

use Monolog\Logger;
use Grafizzi\Graph\AbstractLoggable;
use Grafizzi\Graph\AttributeInterface;
use Grafizzi\Graph\ElementInterface;

abstract class AbstractElement extends AbstractLoggable implements ElementInterface {
  public $fAttributes = array();

  public $fChildren = array();

  public function addChild(ElementInterface $child) {
  }

  public function build() {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name.");
    $attributes = array_map(function (AttributeInterface $attribute) {
      return $attribute->build();
    }, $this->fAttributes);
    $ret = "$type $name [ " . implode(', ', $attributes) . " ];\n";
    return $ret;
  }

  public static function getAllowedChildTypes() {
    return NULL;
  }

  public function getAttributeByName($name) {
    $ret = isset($this->fAttributes[$name]) ? $this->fAttributes[$name] : NULL;
    $this->logger->debug("Getting attribute [$name]: " . print_r($ret, TRUE) . ".");
    return $ret;
  }

  public function removeAttribute(AttributeInterface $attribute) {
    $name = $attribute->getName();
    if (!isset($name)) {
      $message = 'Trying to remove unnamed attribute.';
      $this->logger->warn($message);
      throw new \InvalidArgumentException($message);
    }
    $this->removeAttributeByName($name);
  }

  public function removeAttributeByName($name) {
    $this->logger->debug("Removing attribute [$name].");
    unset($this->fAttributes[$name]);
  }

  public function removeChild(ElementInterface $child) {
  }

  public function setAttribute(AttributeInterface $attribute) {
      $name = $attribute->getName();
      if (!isset($name)) {
        $message = 'Trying to set unnamed attribute.';
        $this->logger->warn($message, debug_backtrace(FALSE));
        throw new \InvalidArgumentException($message);
      }
    $this->fAttributes[$name] = $attribute;
  }

  public function setAttributes(array $attributes) {
    foreach ($attributes as $attribute) {
      if (!in_array('AttributeInterface', class_implements($attribute))) {
        $message = 'Trying to set non-attribute as an attribute.';
        $this->logger->warn($message);
        throw new \InvalidArgumentException($message);
      }
      $this->setAttribute($attribute);
    }
  }
}
