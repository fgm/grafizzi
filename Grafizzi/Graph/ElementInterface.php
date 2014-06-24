<?php

/**
 * @file
 * Grafizzi\Graph\ElementInterface: a component of the Grafizzi library.
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

interface ElementInterface extends NamedInterface {
  /**
   * Add a child to another element, usually a Graph.
   *
   * @todo decide what to do with duplicates.
   *
   * @throws ChildTypeException
   *
   * @param ElementInterface $child
   *
   * @return ElementInterface
   *   Return the object on which the method is invoked, to allow chaining.
   */
  public function addChild(ElementInterface $child);

  /**
   * Increment the depth of the object by $extra.
   *
   * @param integer $extra
   *
   * @return integer
   *   The new depth of the object.
   */
  public function adjustDepth($extra);

  /**
   * Nodes do not have children, only attributes.
   *
   * @return array
   */
  public static function getAllowedChildTypes();

  /**
   *
   * @param string $name
   *
   * @return AttributeInterface
   */
  public function getAttributeByName($name);

  /**
   *
   * @param string $name
   *
   * @return ElementInterface
   */
  public function getChildByName($name);

  /**
   * Return the parent element for the graph to which this element belongs.
   *
   * An unbound element returns null.
   *
   * @return ElementInterface
   */
  public function getParent();

  /**
   * Return the root element for the graph to which this element belongs.
   *
   * An unbound element returns itself.
   *
   * @return ElementInterface
   */
  public function getRoot();

  /**
   * A helper for removeAttributeByName().
   *
   * @param AttributeInterface $attribute
   *
   * @return void
   */
  public function removeAttribute(AttributeInterface $attribute);

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @param string $name
   *
   * @return void
   */
  public function removeAttributeByName($name);

  /**
   * A helper for removeChildByName().
   *
   * @throws ChildNameException
   *
   * @param ElementInterface $child
   *
   * @return ElementInterface
   */
  public function removeChild(ElementInterface $child);

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @param string $name
   *
   * @return ElementInterface
   */
  public function removeChildByName($name);

  /**
   * @todo decide what to do with duplicates.
   *
   * @throws ChildNameException
   *
   * @param AttributeInterface $attribute
   *
   * @return void
   */
  public function setAttribute(AttributeInterface $attribute);

  /**
   * @throws AttributeNameException
   *
   * @param array $attributes
   *   An array of objects implementing AttributeInterface
   *
   * @return void
   */
  public function setAttributes(array $attributes);

  /**
   * @param ElementInterface $parent
   *
   * @return void
   */
  public function setParent(ElementInterface $parent);
}
