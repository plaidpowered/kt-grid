# Bootstrap grid for Kirbytext
Use the Bootstrap grid inside your Kirbytext-formatted content. 

The markup structure is based loosely on @dreikanter's [Markdown Grid](https://github.com/dreikanter/markdown-grid), with some additions for [Bootstrap](https://getbootstrap.com)'s grid features. 

## Installation

Either drop this whole repository inside your Kirby site/plugins folder, or if you don't care about updates, all you really need is kt-grid.php

## Examples

Create a row with multiple cells of various column widths
```
-- row md 6, 3, 3 --
This is the first column, it is 50% wide
---
This is the second column, it is 25% wide
---
This is a third column, it is 25% wide
-- end --
```

Create in a single cell, 6 columns wide, with a 3 column offset
```
-- col md 6+3 --
This cell will fill half the container, centered.
-- end --
```

## Syntax

### Create a multi-cell row:

```
-- row [xs|sm|md|lg] COLUMNS --
...
-- end --
```

Where *COLUMNS* is a comma separated list of column widths. For more information, see the [Bootstrap CSS Grid](http://getbootstrap.com/css/#grid) documentation

The media query breakpoint is optional, and defaults to *md* (Medium devices/Desktop)

Use `---` to divide cells. The number of cells between `-- row --` and `-- end --` must be equal to the number of sizes specified in *COLUMNS*, else the preprocessor will exit.

### Create a single cell of a particular column width:

```Markdown
-- col [xs|sm|md|lg] SIZE[+OFFSET] --
...
-- end --
```

Where *SIZE* is a column width between 1 and 12.

*OFFSET* is an optional column offset between 1 and 11. See [Bootstrap: Offset columns](http://getbootstrap.com/css/#grid-offsetting) for more information.
