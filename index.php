<?php

function get_links($url, $username, $password){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	// add delicious.com username and password below
	curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
	$data = curl_exec($ch);
	curl_close($ch);
	return($data);
}



if($xmlstr = get_links("https://api.pinboard.in/v1/posts/recent?count=5", "<your username>", "<your password>")){

	$xml = simplexml_load_string($xmlstr);
	
	$pinboard = '';
	
	
	foreach($xml->post as $post){
		$pinboard .=  "<li>";
		$pinboard .=  '<a href="'.$post->attributes()->href.'" target="_blank" on>'.$post->attributes()->description.'</a>';
		$pinboard .= "</li>";
	}


}else{

	$pinboard = '<li>No links :( </li>';

}

//bbc weather

	$bbc = '';
	$xmlstr = file_get_contents('http://newsrss.bbc.co.uk/weather/forecast/4189/Next3DaysRSS.xml');
	$xml = simplexml_load_string($xmlstr);	
	foreach($xml->channel->item as $day){
		$bbc .=  "<li>";
		$bbc .=  '<a href="'.$day->{'link'}.'">'.$day->title.'</a>';
		$bbc .= "</li>";
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
			<? echo $bbc; ?>
			<!--
<li>
				<a href="link-url">Saturday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
			</li>
			
			<li>
				<a href="link-url">Sunday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
			</li>
			
			<li>
				<a href="link-url">Monday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
			</li>
-->
		</ul>
	
	</div>
</div>

<div id="subContent">
	<h2>Recently added to pinboard</h2>
	<ul>
		<? echo $pinboard ?>
		<!--
<li>
			<a href="link-url">Saturday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
		</li>
		
		<li>
			<a href="link-url">Sunday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
		</li>
		
		<li>
			<a href="link-url">Monday: white cloud, Max Temp: 7°C (45°F), Min Temp: 3°C (37°F)</a>
		</li>
-->
	</ul>
</div>


</body>
</html>