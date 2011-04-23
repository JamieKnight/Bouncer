<?php
//a handy curl function to return xml from pinboard API
function get_links($url, $username, $password){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
	$data = curl_exec($ch);
	curl_close($ch);
	return($data);
	
}

//Retrns links from the pinboard API for the user specific
function getPinboardLinks($username, $password){
	$pinboard = '';
	if($xmlstr = get_links("https://api.pinboard.in/v1/posts/recent?count=5", $username, $password)){
		$xml = simplexml_load_string($xmlstr);
		foreach($xml->post as $post){
			$pinboard .=  '<li><a href="'.$post->attributes()->href.'" target="_blank" on>'.$post->attributes()->description.'</a></li>';
		}
	}else{
		$pinboard = '<li>No links :( </li>';
	}
	return $pinboard;
}

//Returns the weathe for a given location. To find the BBC location for you weather, search for you location at http://news.bbc.co.uk/weather/ and then take the number from the url.
function getWeatherLinks($bbc_location_id){
	$weather = '';
	$xmlstr = file_get_contents('http://newsrss.bbc.co.uk/weather/forecast/'.$bbc_location_id.'/Next3DaysRSS.xml');
	$xml = simplexml_load_string($xmlstr);	
	foreach($xml->channel->item as $day){
		$weather .=  '<li><a href="'.$day->{'link'}.'">'.$day->title.'</a></li>';
	}
	return $weather;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Bouncer</title>
	<meta name="viewport" content="width=device-width, height=768, minimum-scale=1.0, maximum-scale=1.0" />
	<link href="basic.css" media="screen" rel="stylesheet" type="text/css" />
	<link rel="apple-touch-icon-precomposed" href="RSSFeed.png" />
	<script type="text/javascript">
	document.ontouchmove = function(e){
	     e.preventDefault();
	}
	</script>
</head>
<body>
	<div id="mainContent">
		<ul class="links">
			<li class="fireball">
				<a href="http://daringfireball.net" >
					Daring Fireball
				</a>
			</li>
			
			<li class="bbc">
				<a href="http://news.bbc.co.uk">
					BBC News
				</a>
			</li>
			
			<li class="engadget">
				<a href="http://engadget.com">
					engagdet
				</a>
			</li>
		</ul>
		
		<div id="weather">
			<h2>Taunton Whether</h2>
			<ul>
				<? echo getWeatherLinks(4189); ?>
			</ul>
		</div>
	</div>
	
	<div id="subContent">
		<h2>Recently added to pinboard.in</h2>
		<ul>
			<? echo getPinboardLinks("username", "password"); ?>
		</ul>
	</div>


</body>
</html>