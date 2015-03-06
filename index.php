<?php
function api_rewrite( $url )
{  
//  var_dump($url);
  if (strpos($url, "catalog") === 0)
  {
    $project = substr($url, strlen("catalog"));
    return "catalog/Reserve($project)";
  }
  
  return $url;
}

$query = str_replace("api=", "", $_SERVER['QUERY_STRING']);
$new_url = api_rewrite($query);
if ($new_url != $query)
{
  if (0) // redirect
  {
    header('HTTP/1.1 307 Moved Permanently');
    header("Location: /api/$new_url");
  }
  else
    $_GET['api'] = $new_url;
}

include_once('tools/phoxy/index.php');
