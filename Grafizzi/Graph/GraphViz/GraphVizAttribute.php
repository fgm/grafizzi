<?php
namespace Grafizzi\Graph\GraphViz;

use Grafizzi\Graph\GraphAttribute;

class GraphVizAttribute extends GraphAttribute {
  public static $fDefaults = array(
    'Damping' => 0.99, // double; >= 0; neato only
    'K' => 0.3, // double; >= 0; [s]fdp only
    'URL' => null, // escString; svg, postscript, map only
    'aspect' => null, // aspectType: double[,passes]
    'bb' => null, // rect; write only
    'bgcolor' => null, // color|colorlist
    'center' => false, // bool
    'charset' => 'UTF-8', // string
    'clusterrank' => 'local', // clustermode: local,global,none; dot only
    'colorscheme' => '', // string
    'comment' => '', // string
    'compound' => false, // bool, dot only
    'concentrate' => false, // bool
    'defaultdist' => null, // 1+(avg. len)*sqrt(|V|); >= epsilon; neato only
    'dim' => 2, // int; >= 2; [s]fdp, neato only
    'dimen' => 2, // int; >= 2; [s]fdp, neato only
    'diredgeconstraints' => false, // string or bool; neato only
    'dpi' => 96.0, // double; svg, bitmap only
    'epsilon' => null, // double; .0001 * # nodes(mode == KK), .0001(mode == major); neato only
    'esep' => +3, // adddoublel, addpoint; not dot
    'fontcolor' => 'black', // color
    'fontname' => 'Times-Roman',
    'fontnames' => '', // svg only
    'fontpath' => null, // string, system-dependent
    'fontsize' => 14.0, // double, >= 1.0
    'forcelabel' => true, // bool
    'gradientangle' => 0, // int
    'href' => '', // escString; svg, postscript, map only
    'id' => '', // escString; svg, postscript, map only
    'imagepath' => '', // string
    'label' => '', // string ("\n" on nodes)
    'label_scheme' => 0, // int; sfdp only
    'labeljust' => 'c',
    'labelloc' => 'b', // string, 'b' on root graphs, 'c' on nodes, 't' on clusters
    'landscape' => false, // bool
    'layerlistsep' => ',', // string
    'layers' => '', // layerlist
    'layerselect' => '', // layerrange
    'layersep' => ":\t", // string
    'layout' => '', // string
    'levels' => 0, // int, < MAXINT; sfdp only
    'levelsgap' => 0.0, // double; neato only
    'lheight' => null, // double, write only
    'lp' => null, // point; write only
    'lwidth' => null, // double; write only
    'margin' => null, // double|point, device-dependent
    'maxiter' => null, // 100 * # nodes(mode == KK), 200(mode == major), 600(fdp); fdp, neato only
    'mclimit' => 1.0, // double
    'mindist' => 1.0, // >= 0.0; circo only
    'mode' => 'major', // string: major, KK, hier, ipsep; neato only
    'model' => 'shortpath', // string
    'mosek' => false, // neato only
    'nodesep' => 0.25, // double; >= 0.02
    'nojustify' => false, // bool
    'normalize' => false, // bool, not dot
    'nslimit' => null, // double
    'nslimit1' => null, // double
    'ordering' => '', // string, dot only
    'orientation' => 0.0, // double|string, >= 360 (?) if numeric
    'outputorder' => 'breadthfirst', // outputMode: "breadthfirst","nodesfirst","edgesfirst
    'overlap' => true, // string|bool; not dot
    'overlap_scaling' => -4, // double, >= -1.0e10; prism only
    'pack' => false, // bool|int; not dot
    'packmode' => 'node', // packMode; not dot
    'pad' => 0.0555, // double|point; default == 4 points
    'page' => null, // double|point
    'pagedir' => 'BL', // pagedir: "BL", "BR", "TL", "TR", "RB", "RT", "LB", "LT"
    'quadtree' => 'normal', // quadType|bool; sfdp only
    'quandtum' => 0.0, // double, >= 0.0
    'rankdir' => 'TB', // rankDir: "TB", "LR", "BT", "RL"; dot only
    'ranksep' => 0.05, // double[list]; default 0.05 on dot, 1.0 on twopi; dot, twopi only
    'ratio' => null, // double|string
    'remincross' => false, // bool; dot only
    'repulsiveforce' => 1.0, // double; >= 0.0
    'resolution' => 96.0, // double; svg, bitmap only
    'root' => null, // string|bool; null on graphs, false on nodes; circo, twopi only
    'rotate' => 0, // int
    'rotation' => 0.0, // double; sfdp only
    'scale' => null, // doube|point; twopi only
    'searchsize' => 30, // int; dot only
    'sep' => +4, // addDouble|addPoint; not dot
    'showboxes' => 0, // int; >= 0; dot only
    'size' => null, // double|point
    'smoothing' => 'none', // smoothType; sfdp only
    'sortv' => 0, // int; >= 0
    'splines' => null, // bool|string: false, true, '', 'line', 'spline', 'polyline', 'ortho', 'compoound'
    'start' => '', // startType; fdp, neato only
    'style' => null, // style
    'stylesheet' => null, // string (URL|pathname), SVG only
    'target' => null, // [esc]string; cf HTML target attribute
    'truecolor' => null, // bitmap output only
    'viewport' => '', // viewPort
    'voro_margin' => 0.05, // double; >= 0.0; not dot
  );
}
