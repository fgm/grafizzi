<?php

/**
 * @file
 * Grafizzi\Graph\Cluster: a component of the Grafizzi library.
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

class Cluster extends Subgraph {

  public function getBuildName() {
    $name = $this->getName();
    if (strpos($name, 'cluster') !== 0) {
      $name = "cluster_$name";
    }
    $this->dic['logger']->debug("Cluster name: [$name]");
    $ret = $this->escape($name);
    return $ret;
  }
}
