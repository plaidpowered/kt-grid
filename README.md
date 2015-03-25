# Bootstrap grid for Kirbytext
Use the Bootstrap grid inside your Kirbytext-formatted content. 

The markup structure is based loosely on @dreikanter's [Markdown Grid](https://github.com/dreikanter/markdown-grid), with some additions for [Bootstrap](https://getbootstrap.com)'s grid features. 

## Examples

Create a row with multiple cells of various column widths
```
-- row md 6, 3, 3 --
This is the first column
---
This is the second column
---
This is a third column
-- end --
```

Drop in a single cell, 6 columns wide, with a 3 column offset
```
-- col md 6+3 --
This cell will fill half the container, centered.
-- end --
```
