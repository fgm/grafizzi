<?php
namespace Grafizzi\Graph;

class GraphAttribute extends AbstractAttribute {
  public static $fDefaults = array(
    'bgcolor' => NULL, // color|colorlist
    'fontcolor' => 'black', // color
    'fontsize' => 14.0, // double, >= 1.0
    'label' => '', // string ("\n" on nodes)
    'rankdir' => 'TB', // rankDir: "TB", "LR", "BT", "RL"; dot only
  );
}
