<?php

/**
 * Status: working, some possibly useless code. Handle error situations better.
 */

namespace Grafizzi\Graph;

abstract class AbstractElement extends AbstractNamed implements ElementInterface {
  const DEPTH_INDENT = 2;

  public $fAttributes = array();

  public $fChildren = array();

  /**
   * The nesting level of the element.
   *
   * An unbound element, like the root graph, has depth 0.
   *
   * @var int
   */
  public $fDepth = 0;

  /**
   * Possibly not needed with an efficient garbage collector, but might help in
   * case of dependency loops.
   *
   * XXX 20120512 check if really useful.
   */
  public function __destruct() {
    $name = $this->getName();
    $type = $this->getType();
    $this->logger->debug("Destroying $type " . ($name ?: "(unnamed)"));
    foreach ($this->fAttributes as &$attribute) {
      unset($attribute);
    }
    foreach ($this->fChildren as &$child) {
      unset($child);
    }
  }

  /**
   * @todo TODO decide what to do with duplicates.
   *
   * @throws ChildTypeException
   *
   * @see ElementInterface::addChild()
   */
  public function addChild(ElementInterface $child) {
    $name = $this->getName();
    $childName = $child->getName();
    $childType = $child->getType();
    $this->logger->debug("Adding child $childName, type $childType, to $name, depth {$this->fDepth}.");
    if (!in_array($childType, $this->getAllowedChildTypes())) {
      $message = "Invalid child type $childType for element $name.";
      $this->logger->err($message);
      throw new ChildTypeException($message);
    }
    $this->fChildren[$childName] = $child;
    $child->adjustDepth($this->fDepth + 1);
  }

  /**
   * Build the DOT string for this subtree.
   *
   * @see NamedInterface::build()
   *
   * @return string
   */
  public function build() {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name.");
    $attributes = array_map(function (AttributeInterface $attribute) {
      return $attribute->build();
    }, $this->fAttributes);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT)
      . "$type $name [ " . implode(', ', $attributes) . " ];\n";
    return $ret;
  }

  /**
   * Nodes do not have children, only attributes.
   *
   * @return NULL
   */
  public static function getAllowedChildTypes() {
    return NULL;
  }

  /**
   * @see ElementInterface::getAttributeByName()
   *
   * @return AttributeInterface
   */
  public function getAttributeByName($name) {
    $ret = isset($this->fAttributes[$name]) ? $this->fAttributes[$name] : NULL;
    $this->logger->debug("Getting attribute [$name]: " . print_r($ret, TRUE) . ".");
    return $ret;
  }

  /**
   * Increment the depth of the object by $extra.
   *
   * @param int $extra
   *
   * @return int
   *   The new depth of the object.
   */
  public function adjustDepth($extra = 0) {
    $this->logger->debug("Adjusting depth {$this->fDepth} by $extra.");
    $this->fDepth += $extra;
    return $this->fDepth;
  }

  /**
   * A helper for removeAttributeByName().
   *
   * @throws AttributeNameException
   *
   * @see ElementInterface::removeAttribute()
   */
  public function removeAttribute(AttributeInterface $attribute) {
    $name = $attribute->getName();
    if (!isset($name)) {
      $message = 'Trying to remove unnamed attribute.';
      $this->logger->warn($message);
      throw new AttributeNameException($message);
    }
    $this->removeAttributeByName($name);
  }

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @see ElementInterface::removeAttributeByName()
   */
  public function removeAttributeByName($name) {
    $this->logger->debug("Removing attribute [$name].");
    unset($this->fAttributes[$name]);
  }

  /**
   * A helper for removeChildByName().
   *
   * @throws ChildNameException
   *
   * @see ElementInterface::removeChild()
   */
  public function removeChild(ElementInterface $child) {
    $name = $child->getName();
    if (!isset($name)) {
      $message = 'Trying to remove unnamed child.';
      $this->logger->warn($message);
      throw new ChildNameException($message);
    }
    $ret = $this->removeChildByName($name);
    return $ret;
  }

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @see ElementInterface::removeChildByName()
   */
  public function removeChildByName($name) {
    $this->logger->debug("Removing child [$name].");
    if (isset($this->fChildren[$name])) {
      $child = $this->fChildren[$name];
      $child->adjustDepth(- $this->fDepth - 1);
      unset($this->fChildren[$name]);
      $ret = $child;
    }
    else {
      $ret = NULL;
    }
    return $ret;
  }

  /**
   * @todo TODO decide what to do with duplicates.
   *
   * @throws ChildNameException
   *
   * @see ElementInterface::setAttribute()
   */
  public function setAttribute(AttributeInterface $attribute) {
      $name = $attribute->getName();
      if (!isset($name)) {
        $message = 'Trying to set unnamed attribute.';
        $this->logger->warn($message, debug_backtrace(FALSE));
        throw new ChildNameException($message);
      }
    $this->fAttributes[$name] = $attribute;
  }

  /**
   *
   * @see ElementInterface::setAttributes()
   *
   * @throws AttributeNameException
   *
   * @param array $attributes
   *   An array of objects implementing AttributeInterface
   */
  public function setAttributes(array $attributes) {
    foreach ($attributes as $attribute) {
      if (!in_array('AttributeInterface', class_implements($attribute))) {
        $message = 'Trying to set non-attribute as an attribute.';
        $this->logger->warn($message);
        throw new AttributeNameException($message);
      }
      $this->setAttribute($attribute);
    }
  }
}
