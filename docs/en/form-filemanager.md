# Filemanager Form Input Type

Showing a filemanager input type

> File Manager Responsive is a web-based file management tool written in PHP language that allows you to manage your files and folders on your server through a web browser. It provides an easy-to-use interface that allows you to upload, download, edit, copy, move, and delete files and folders (https://www.responsivefilemanager.com/).
> You can edit the (upload direction, current path, default view and premissions) by change the config values in your app/config/crudbooster.php folder
> You can change the type of file by clicking options in step3 and choose the type you want (image or file) or you can directly edit it in your controller

### Code Sample
```php
$this->form[] = ['label'=>'Image','name'=>'image','type'=>'filemanager', 'filemanager_type' => 'image'];
$this->form[] = ['label'=>'File','name'=>'file','type'=>'filemanager', 'filemanager_type' => 'file'];
```


![image](https://cloud.githubusercontent.com/assets/6733315/23845248/93bf74da-07f9-11e7-8ca5-1ae27cb67be7.png)

## What's Next
- [How To Make The Graded Select Boxes (Parent Select -> Child Select -> Etc..)](./how-make-graded-select-box.md)

## What's Next
- [Form Input Type: googlemaps](./form-googlemaps.md)

## Table Of Contents
- [Back To Index](./index.md)
