# How To Add More Button At Top Of Grid Data

Some cases you need to add extra button at top of grid data. For example you need to add a custom print button. And you've created a method to print.

Open the module controller, and find `$this->index_button` . 

```php
$this->index_button[] = ['label'=>'Advanced Print','url'=>CRUDBooster::mainpath("print"),"icon"=>"fa fa-print"];
```
## What's Next
- [How To Add More Action Button In Grid Data](./how-add-more-action-button.md)

## Table Of Contents
- [Back To Index](./index.md)
