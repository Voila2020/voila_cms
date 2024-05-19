# How to Add Webp Image Dynamically

### Introduction to WebP

WebP is a modern image format developed by Google that provides superior lossless and lossy compression for images on the web. By using WebP, web developers can create smaller, richer images that make the web faster.

Why Use WebP?

> Reduced File Size: WebP images are often significantly smaller than their JPEG or PNG equivalents, leading to faster load times.
>
> High-Quality Images: WebP supports both lossy and lossless compression, providing flexibility for maintaining image quality.

### Code Sample

```php
$this->form[] = ['label' => 'Image', 'name' => 'image', 'type' => 'filemanager'];
$this->form[] = ['label' => 'Image Webp', 'name' => 'image_webp' , 'type'=>'hidden'];
```


---
**NOTE**

The name of the image in the WebP format typically follows a similar naming convention to the original image file, with the addition of the "_webp" suffix added to indicate the file format and placed below the original image.

---


## What's Next

- [How To Make The Graded Select Boxes (Parent Select -> Child Select -> Etc..)](./how-make-graded-select-box.md)

## What's Next

- [Form Input Type: googlemaps](./form-googlemaps.md)

## Table Of Contents

- [Back To Index](./index.md)
