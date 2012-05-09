<?php
namespace OSInet\Graph\GraphViz;

use OSInet\Graph\GraphAttribute;

class GraphVizAttribute extends GraphAttribute {
  public static $fDefaults = array(
    'Damping' => 0.99, // double; >= 0; neato only
    'K' => 0.3, // double; >= 0; [s]fdp only
    'URL' => NULL, // escString; svg, postscript, map only
    'aspect' => NULL, // aspectType: double[,passes]
    'bb' => NULL, // rect; write only
    'bgcolor' => NULL, // color|colorlist
    'center' => FALSE, // bool
    'charset' => 'UTF-8', // string
    'clusterrank' => 'local', // clustermode: local,global,none; dot only
    'colorscheme' => '', // string
    'comment' => '', // string
    'compound' => FALSE, // bool, dot only
    'concentrate' => FALSE, // bool
    'defaultdist' => NULL, // 1+(avg. len)*sqrt(|V|); >= epsilon; neato only
    'dim' => 2, // int; >= 2; [s]fdp, neato only
    'dimen' => 2, // int; >= 2; [s]fdp, neato only
    'diredgeconstraints' => FALSE, // string or bool; neato only
    'dpi' => 96.0, // double; svg, bitmap only
    'epsilon' => NULL, // double; .0001 * # nodes(mode == KK), .0001(mode == major); neato only
    'esep' => +3, // adddoublel, addpoint; not dot
    'fontcolor' => 'black', // color
    'fontname' => 'Times-Roman',
    'fontnames' => '', // svg only
    'fontpath' => NULL, // string, system-dependent
    'fontsize' => 14.0, // double, >= 1.0
    'forcelabel' => TRUE, // bool
    'gradientangle' => 0, // int
    'href' => '', // escString; svg, postscript, map only
    'id' => '', // escString; svg, postscript, map only
    'imagepath' => '', // string
    'label' => '', // string ("\n" on nodes)
    'label_scheme' => 0, // int; sfdp only
    'labeljust' => 'c',
    'labelloc' => 'b', // string, 'b' on root graphs, 'c' on nodes, 't' on clusters
    'landscape' => FALSE, // bool
    'layerlistsep' => ',', // string
    'layers' => '', // layerlist
    'layerselect' => '', // layerrange
    'layersep' => ":\t", // string
    'layout' => '', // string
    'levels' => 0, // int, < MAXINT; sfdp only
    'levelsgap' => 0.0, // double; neato only
    'lheight' => NULL, // double, write only
    'lp' => NULL, // point; write only
    'lwidth' => NULL, // double; write only
    'margin' => NULL, // double|point, device-dependent
    'maxiter' => NULL, // 100 * # nodes(mode == KK), 200(mode == major), 600(fdp); fdp, neato only
    'mclimit' => 1.0, // double
    'mindist' => 1.0, // >= 0.0; circo only
    'mode' => 'major', // string: major, KK, hier, ipsep; neato only
    'model' => 'shortpath', // string
    'mosek' => FALSE, // neato only
    'nodesep' => 0.25, // double; >= 0.02
    'nojustify' => FALSE, // bool
    'normalize' => FALSE, // bool, not dot
    'nslimit' => NULL, // double
    'nslimit1' => NULL, // double
    'ordering' => '', // string, dot only
    'orientation' => 0.0, // double|string, >= 360 (?) if numeric
    'outputorder' => 'breadthfirst', // outputMode: "breadthfirst","nodesfirst","edgesfirst
    'overlap' => TRUE, // string|bool; not dot
    'overlap_scaling' => -4, // double, >= -1.0e10; prism only
    'pack' => FALSE, // bool|int; not dot
    'packmode' => 'node', // packMode; not dot
    'pad' => 0.0555, // double|point; default == 4 points
    'page' => NULL, // double|point
    'pagedir' => 'BL', // pagedir: "BL", "BR", "TL", "TR", "RB", "RT", "LB", "LT"
    'quadtree' => 'normal', // quadType|bool; sfdp only
    'quandtum' => 0.0, // double, >= 0.0
    'rankdir' => 'TB', // rankDir: "TB", "LR", "BT", "RL"; dot only
    'ranksep' => 0.05, // double[list]; default 0.05 on dot, 1.0 on twopi; dot, twopi only
    'ratio' => NULL, // double|string
    'remincross' => FALSE, // bool; dot only
    'repulsiveforce' => 1.0, // double; >= 0.0
    'resolution' => 96.0, // double; svg, bitmap only
    'root' => NULL, // string|bool; NULL on graphs, FALSE on nodes; circo, twopi only
    'rotate' => 0, // int
    'rotation' => 0.0, // double; sfdp only
    'scale' => NULL, // doube|point; twopi only
    'searchsize' => 30, // int; dot only
    'sep' => +4, // addDouble|addPoint; not dot
    'showboxes' => 0, // int; >= 0; dot only
    'size' => NULL, // double|point
    'smoothing' => 'none', // smoothType; sfdp only
    'sortv' => 0, // int; >= 0
    'splines' => NULL, // bool|string: false, true, '', 'line', 'spline', 'polyline', 'ortho', 'compoound'
    'start' => '', // startType; fdp, neato only
    'style' => NULL, // style
    'stylesheet' => NULL, // string (URL|pathname), SVG only
    'target' => NULL, // [esc]string; cf HTML target attribute
    'truecolor' => NULL, // bitmap output only
    'viewport' => '', // viewPort
    'voro_margin' => 0.05, // double; >= 0.0; not dot
  );
}
