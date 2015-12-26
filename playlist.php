<?php
header('Content-Type: text/html; charset=utf-8');
//Constants
define("API_URL", "https://meta.wikimedia.org/w/api.php");
define("PLAYLIST_PAGE", "Wikiradio_(tool)/playlist/");

function getWikiPageContent($url)
{
  $url str_replace(' ','_',$url);
  $options = array(
    'http'=>array(
      'method'=>"GET",
      'header'=>"Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                "User-Agent: <wikiradio> by The_Photographer [[es:User:The_Photographer]]" // i.e. An iPad 
    )
  );
  $context = stream_context_create($options);
  
  return json_decode(file_get_contents($url, false, $context),true);
}

function getPlaylist($name)
{
  $url = API_URL.'?action=query&titles='.PLAYLIST_PAGE.$name.'&rvprop=content&formatversion=2&format=json';
  $file = getWikiPageContent($url);
  if (!is_null($file))
    return $file['query']['pages'][0]['revisions'][0]['content'];
  else
    echo "Playlist is null"
}

function getFileUrl($filename)
{
  $url = API_URL.'?action=query&prop=revisions&titles='.$filename.'&prop=imageinfo&iiprop=url&format=json';
  $file = getWikiPageContent($url);
  //return $file['query']['pages'];//['imageinfo'][0]['url'];
  var_dump($file['query']['pages']);
  //var_dump($file);
}

if (isset($_GET['name']))
  echo getPlaylist($_GET['name']);
if (isset($_GET['filename']))
  getFileUrl($_GET['filename']);
?>
