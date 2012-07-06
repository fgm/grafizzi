<?php

/**
 * @file
 * Grafizzi\Graph\AbstractElement: a component of the Grafizzi library.
 *
 * (c) 2012 Frédéric G. MARAND <fgm@osinet.fr>
 *
 * Grafizzi is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * Grafizzi is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE.  See the GNU Lesser General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Grafizzi, in the COPYING.LESSER.txt file.  If not, see
 * <http://www.gnu.org/licenses/>
 */

namespace Grafizzi\Graph;

/**
 * Status: working, some possibly useless code. Handle error situations better.
 */
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
   * The parent element, when bound, or null otherwise.
   *
   * @var AbstractElement
   */
  public $fParent = null;

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
    $child->setParent($this);
    return $this;
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
    foreach ($this->fChildren as $child) {
      $child->adjustDepth($extra);
    }
    return $this->fDepth;
  }

  /**
   * Build the DOT string for this subtree.
   *
   * Ignores $directed.
   *
   * @see NamedInterface::build()
   *
   * @return string
   */
  public function build($directed = null) {
    $type = $this->getType();
    $name = $this->getName();
    $this->logger->debug("Building element $name.");
    $attributes = array_map(function (AttributeInterface $attribute) {
      return $attribute->build($directed);
    }, $this->fAttributes);
    $name = $this->escape($name);
    $ret = str_repeat(' ', $this->fDepth * self::DEPTH_INDENT);
    if (!empty($attributes)) {
      $builtAttributes = implode(', ', array_filter($attributes));
      if (!empty($builtAttributes)) {
        $ret .= "$type $name [ $builtAttributes ];\n";
      }
    }
    return $ret;
  }

  /**
   * Nodes do not have children, only attributes.
   *
   * @return null
   */
  public static function getAllowedChildTypes() {
    return null;
  }

  /**
   * @see ElementInterface::getAttributeByName()
   *
   * @return AttributeInterface
   */
  public function getAttributeByName($name) {
    $ret = isset($this->fAttributes[$name]) ? $this->fAttributes[$name] : null;
    $this->logger->debug("Getting attribute [$name]: " . print_r($ret, true) . ".");
    return $ret;
  }

  /**
   * @see \Grafizzi\Graph\ElementInterface::getChildByName()
   */
  public function getChildByName($name) {
    $ret = isset($this->fChildren[$name])
      ? $this->fChildren[$name]
      : null;
    return $ret;
  }

  /**
   * @see \Grafizzi\Graph\ElementInterface::getRoot()
   */
  public function getRoot() {
    $current = $this;
    while ($current->fParent instanceof ElementInterface) {
      $current = $current->fParent;
    }
    return $current;
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
      $ret = null;
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
        $this->logger->warn($message, debug_backtrace(false));
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
      if (!in_array('Grafizzi\\Graph\\AttributeInterface', class_implements($attribute))) {
        $message = 'Trying to set non-attribute as an attribute';
        $this->logger->warn($message);
        throw new AttributeNameException($message);
      }
      $this->setAttribute($attribute);
    }
  }

  /**
   * @see ElementInterface::setParent()
   */
  public function setParent(ElementInterface $parent) {
    $this->fParent = $parent;
  }
}
