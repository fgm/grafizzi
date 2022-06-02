Grafizzi
========

Welcome to Grafizzi, a PHP wrapper for AT&T GraphViz.

[![Build Status](https://secure.travis-ci.org/fgm/grafizzi.png?branch=master)](http://travis-ci.org/fgm/grafizzi)
[![codecov](https://codecov.io/gh/fgm/grafizzi/branch/master/graph/badge.svg)](https://codecov.io/gh/fgm/grafizzi)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/fgm/grafizzi/badges/quality-score.png?s=95ce57b528611f1f89868672f04e3af65ba73801)](https://scrutinizer-ci.com/g/fgm/grafizzi/)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffgm%2Fgrafizzi.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Ffgm%2Fgrafizzi?ref=badge_shield)


## Using Grafizzi in your PHP GraphViz projects

### Installing it in your project.

```bash
$ composer require osinet/grafizzi
```

### Generating graphs with Grafizzi

1. Create a Pimple instance, passing it an instance for your PSR/3 logger of choice
  (e.g. [Monolog]) in the `logger` key, and possibly other arguments like 
  `directed` to specify if you want to build a directed graph.
1. Create a `Graph` instance, passing it the container.
1. Add `Subgraph`, `Node` and `Edge` instances to the graph using the 
  `addChild()` method. Each of these take the container and an array of 
  `Attribute` instances in their constructor, or you can add them using 
  `setAttribute()` after construction. Attribute instances are reusable on
  multiple elements.
1. Invoke the `build()` method on the graph instance to obtain a string 
   containing your Graphviz dot-file, which you can then output to a file or
   pipe to `dot`, `neato` or your Graphviz command of choice.
1. Optional: use a chain of `Filter` instances to filter the result, for example
   to run Graphviz from your PHP script (`DotFilter`), or "tee" it between a
   filter pipe and a string (`StringFilter`).
   
You can take inspiration from the examples provided in the `app/` directory:

* `app/hello_node.php` builds a minimal graph showing a lot of logging
* `app/grafizzi.php` builds a graph for the Grafizzi hierarchy of `Filter` 
  classes.
  
[Monolog]: https://github.com/Seldaek/monolog

## Working on Grafizzi itself

### Installing Grafizzi for development

#### Obtain the Grafizzi sources

With Grafizzi being very new, the easiest way to get started is to clone the
Git repository.

    git clone https://github.com/fgm/grafizzi.git


#### Download the dependencies

Once you have a clone of the Grafizzi repository, you need to install its
dependencies, using the Composer package dependency manager. Download Composer
following the instructions on http://getcomposer.org/ , typically like this:

    curl -s http://getcomposer.org/installer | php

Then run:

    php composer.phar install

Note that Grafizzi is available for PHP &ge; 7.4, including 8.1.x.


#### Check your System Configuration

Now make sure that your local system is properly configured for Grafizzi. To do
this, execute:

    php app/hello-node.php

You should see a detailed debug execution trace. On a POSIX system, you can get
just the resulting GraphViz source by redirecting _stderr_ to `/dev/null`:

    php app/hello-node.php 2> /dev/null

You should see a very basic GraphViz source:

    graph g {
      rankdir="TB";
      label="Some graph";

      n1 [ label="Some node" ];
      n2 [ label="Other node" ];
      n1 -- n2;
    } /* /graph g */

If you get any warnings or recommendations, or nothing at all, check your PHP
error log, and fix these now before moving on.

### Verifying the Grafizzi code

If your system includes the `make` command, after installing,
run:

    make lint

You should get a green bar with zero warnings.


###  Generating documentation

If your system includes the `make` and `doxygen' commands, and GraphViz itself,
you can generate a fully indexed source documentation by running:

    make docs

This will generate a HTML documentation with internal search engine in the
doxygen/ directory. Use it by browsing to `doxygen/html/index.html`.

The documentation and search engine are  even usable over `file:///` URLs, so
you do not need a web server to access it.


### Running tests

If you want to make sure that Grafizzi runs fine on your system, make sure
that PHPunit 4.6 or later is installed on your system, and run:

    make test

Note that the Composer installation in dev mode will have installed PHPunit in
your `vendor` folder.


### Cleaning up

You can remove php_error.log, the generated doxygen docs directory, the 
generated coverage reports, and many stray generated files by running:

    make clean


Have fun!


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Ffgm%2Fgrafizzi.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Ffgm%2Fgrafizzi?ref=badge_large)

## Changelog

- 0.0.3: 
  - Dropped PHP < 7.4 support, added PHP 8.1+ support
  - Upgraded to PHPunit 9.5.x
  - Clean to PHPstan level 6
- 0.0.2: 
  - Dropped PHP 5.x support, upgraded to PHP 7.2/7.3 
  - Upgraded to PHPUnit 8.x
- 0.0.1:
  - Removed runtime dependency on Monolog
  - Upgraded to PHP/PHPunit 5.x.
