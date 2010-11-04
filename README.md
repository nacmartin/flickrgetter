Flickrgetter
============

Flickrgetter is a simple PHP script to help wordpress users who want to put flickr content in their blogs.

Installation
------------

Copy config-sample.php into config.php and configure it.

Parameters:
 * API_KEY : Your Flickr API key
 * DESTINATION_DIR : Directory in the disk where images will be saved (http user must have write access).
 * IMG_DIR : Relative URL to find the images, used in <img src=''> tags.

Usage
-----

Go to flickr, search for free contents: http://www.flickr.com/search/?l=cc&mt=all&adv=1&w=all&q=br%C3%A4uhaus&m=text

Click in the image you like and then go to http://your.url.example.com?url= where the parameter is the url of the image that you want. For instance: http://your.url.example.com?url=http://www.flickr.com/photos/kyral210/5141931157/

You may want to use this weblett: javascript:q=(document.location.href);void(open('http://your.url.example.com?url='+q,'_self','resizable,location,menubar,toolbar,scrollbars,status'));

Flickrgetter will grab the image, store it in the specified directory and provide you with some html code. If you paste it in your post, you are done.


License
-------
Licensed under MIT License
