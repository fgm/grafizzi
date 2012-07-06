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
   * @param ElementInterface $child
   *
   * @return ElementInterface;
   *   Return the object on which the method is invoked, to allow chaining.
   */
  public function addChild(ElementInterface $child);

  public function adjustDepth($extra);

  public static function getAllowedChildTypes();
  /**
   *
   * @param string $name
   *
   * @return Attribute
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
   * Return the root element for the graph to which this element belongs.
   *
   * An unbound element returns itself.
   *
   * @return ElementInterface
   */
  public function getRoot();

  public function removeAttribute(AttributeInterface $attribute);
  public function removeAttributeByName($name);
  public function removeChild(ElementInterface $child);
  public function removeChildByName($name);

  public function setAttribute(AttributeInterface $attribute);
  public function setAttributes(array $attributes);
  public function setParent(ElementInterface $parent);
}
