<?php declare(strict_types=1);

/**
 * @file
 * Grafizzi\Graph\ElementInterface: a component of the Grafizzi library.
 *
 * (c) 2012-2024 Frédéric G. MARAND <fgm@osinet.fr>
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
  public function addChild(ElementInterface $child): ElementInterface;

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
   * @return array<string>
   */
  public static function getAllowedChildTypes(): array;

  /**
   *
   * @param string $name
   *
   * @return ?AttributeInterface
   */
  public function getAttributeByName($name): ?AttributeInterface;

  /**
   * @param mixed $name
   *   Will be stringified.
   *
   * @return \Grafizzi\Graph\ElementInterface|null
   */
  public function getChildByName($name): ?ElementInterface;

  /**
   * Return the parent element for the graph to which this element belongs.
   *
   * An unbound element returns null.
   *
   * @return ?ElementInterface
   */
  public function getParent(): ?ElementInterface;

  /**
   * Return the root element for the graph to which this element belongs.
   *
   * An unbound element returns itself.
   *
   * @return ?ElementInterface
   */
  public function getRoot(): ?ElementInterface;

  /**
   * A helper for removeAttributeByName().
   *
   * @param AttributeInterface $attribute
   *
   * @return void
   */
  public function removeAttribute(AttributeInterface $attribute): void;

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @param string $name
   *
   * @return void
   */
  public function removeAttributeByName(string $name): void;

  /**
   * A helper for removeChildByName().
   *
   * @throws ChildNameException
   *
   * @param ElementInterface $child
   *
   * @return ?ElementInterface
   */
  public function removeChild(ElementInterface $child): ?ElementInterface;

  /**
   * Silently fail, like unset, when removing an unassigned attribute.
   *
   * @param string $name
   *
   * @return ?ElementInterface
   */
  public function removeChildByName(string $name): ?ElementInterface;

  /**
   * @todo decide what to do with duplicates.
   *
   * @throws ChildNameException
   *
   * @param AttributeInterface $attribute
   *
   * @return void
   */
  public function setAttribute(AttributeInterface $attribute): void;

  /**
   * @throws AttributeNameException
   *
   * @param array<\Grafizzi\Graph\AttributeInterface> $attributes
   *   An array of objects implementing AttributeInterface
   *
   * @return void
   */
  public function setAttributes(array $attributes): void;

  /**
   * @param ElementInterface $parent
   *
   * @return void
   */
  public function setParent(ElementInterface $parent): void;
}
