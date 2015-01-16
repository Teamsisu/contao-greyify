Greyify
=======================

[![Version](http://img.shields.io/packagist/v/teamsisu/contao-greyify.svg?style=flat-square)](https://github.com/Teamsisu/contao-greyify)
[![License](http://img.shields.io/packagist/l/teamsisu/contao-greyify.svg?style=flat-square)](https://github.com/Teamsisu/contao-greyify)
[![Downloads](http://img.shields.io/packagist/dt/teamsisu/contao-greyify.svg?style=flat-square)](https://github.com/Teamsisu/contao-greyify)

An contao extension that provides a small class to greyscale images with PHP or inserttag


How to use:
--------------

#### inserttag
```php

    Default:
    {{greyify::imagePath/UUID?width=x&height=x&mode=x}}  // returns an image tag with figure container
    
    SRC:
    {{greyify::src::imagePath/UUID?width=x&height=x&mode=x}}  // returns only the file path
    
    TAG:
    {{greyify::tag::imagePath/UUID?width=x&height=x&mode=x}}  // returns an image tag
    
    FIGURE:
    {{greyify::figure::imagePath/UUID?width=x&height=x&mode=x}}  // returns an image tag with figure container
    
```

#### php
```php

    $image = Greyify::convert(imagePath/UUID?width=x&height=x&mode=x);  // returns only the path of the image

    $image = Greyify::getHTML(imagePath/UUID?width=x&height=x&mode=x);  // returns an image tag

    $image = Greyify::getHTML(imagePath/UUID?width=x&height=x&mode=x, true);  // returns an image tag with figure container

```

For the getHTML method there is as third parameter the "alt" value available
The parameters width/height/mode are optional for both inserttag and php



Additional note
--------------

This module is provided "as is", without warranty of any kind.
It is still under development if you find any issues please use the github issue tracker.