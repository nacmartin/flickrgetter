<?php

require_once dirname(__FILE__).'/config.php';

ini_set('display_errors',true);

$urlbase = $_GET["url"]; 
//http://www.flickr.com/photos/pankcho/2908994735/

preg_match('/\/(?P<digit>\d+)\/$/', $urlbase, $matches);
$id = $matches['digit'];

$curl_handle=curl_init();
$url = "http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=".API_KEY."&photo_id=".$id;
curl_setopt($curl_handle,CURLOPT_URL,$url);
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

$info = new SimpleXMLElement($buffer);
$photo = $info->photo[0];
$license = "";
$licenseurl = "";

switch ($photo['license']){
  case '0':
    $license = "All Rights Reserved";
    $licenseurl = "";
    break;
  case '1':
    $license = "Attribution-NonCommercial-ShareAlike License";
    $licenseurl = "http://creativecommons.org/licenses/by-nc-sa/2.0/";
    break;
  case '2':
    $license = "Attribution-NonCommercial License";
    $licenseurl = "http://creativecommons.org/licenses/by-nc/2.0/";
    break;
  case '3':
    $license = "Attribution-NonCommercial-NoDerivs License";
    $licenseurl = "http://creativecommons.org/licenses/by-nc-nd/2.0/";
    break;
  case '4':
    $license = "Attribution License";
    $licenseurl = "http://creativecommons.org/licenses/by/2.0/";
    break;
  case '5':
    $license = "Attribution-ShareAlike License";
    $licenseurl = "http://creativecommons.org/licenses/by-sa/2.0/";
    break;
  case '6':
    $license = "Attribution-NoDerivs License";
    $licenseurl = "http://creativecommons.org/licenses/by-nd/2.0/";
    break;
  case '7':
    $license = "No known copyright restrictions";
    $licenseurl = "http://www.flickr.com/commons/usage/";
    break;
  case '8':
    $license = "United States Government Work";
    $licenseurl = "http://www.usa.gov/copyright.shtml";
    break;
}
$owner= $photo->owner['username'];
$title=$photo->title."\n";

//get sizes
$curl_handle=curl_init();
$url = "http://api.flickr.com/services/rest/?method=flickr.photos.getSizes&api_key=".API_KEY."&photo_id=".$id;
curl_setopt($curl_handle,CURLOPT_URL,$url);
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);

$sizes = new SimpleXMLElement($buffer);

$source = "";

foreach ($sizes->sizes->size as $size){
  switch ((string) $size['label']){
    case 'Medium':
      $source = $size['source'].PHP_EOL;
  }
}

//save file

$fp = fopen (DESTINATION_DIR . $id.'.jpg', 'w+');//This is the file where we save the information
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$source);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);
fclose($fp);

header ("Content-type: text/html\r\n\r\n");
echo htmlspecialchars("<div class='flickrphoto'>".
  "<img src='".IMG_DIR.$id.".jpg' alt='".$title."' title='".$title."'/>".
  "<div class='flickrcaption'><a rel='nofollow' href='".$licenseurl."'><img src='".IMG_DIR."cc.gif'></a><a rel='nofollow' href='".$urlbase."'> by ".$owner."</a></div>".
  "</div>");
