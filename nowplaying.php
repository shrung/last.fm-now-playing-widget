<?php
/* The MIT License (MIT)
 * 
 * Copyright (c) 2015 David Southgate
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

?>
<?php
	//CONFIG
	$username = "david_southgate";
	$api_key = "b1e6e2f5bdc9eedcf6ad19f846a96cb2";
	
	//Construct the api url for this request
	$api_url = "https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=".$username."&api_key=".$api_key."&format=json";

	//Store the JSOn responce
	$json = file_get_contents($api_url); 
	
	//Parse the json into an array
	$most_recent_track_array = get_object_vars(get_object_vars(get_object_vars(json_decode($json))['recenttracks'])['track'][0]);
	
	//Get nowplaying status of latest track
	$nowplaying = get_object_vars($most_recent_track_array['@attr'])['nowplaying'];
	
	//If a song is playing currently
	if($nowplaying == "true") {
		
		//Get the artist, track name and album art
		$artist = get_object_vars($most_recent_track_array['artist'])['#text'];
		$name = $most_recent_track_array['name'];
		$art = get_object_vars($most_recent_track_array['image'][2])['#text'];
	}
	
	//Otherwise, the user is not listening to anything
	else {
		$notlistening = true;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			body {
				color: #fff;
				font-family:sans-serif,helvetica;
			}
		
			#art {
				background: url(<?php echo $art ?>) no-repeat;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				-o-background-size: cover;
				background-size: cover;
				width: 80px;
				height: 80px;
				position: absolute;
				top: 0px;
				left: 0px;
			}
			
			#details {
				position: absolute;
				left: 90px;
				
				
			}
			
				#details #nowplaying {
					color: #E3E3E3;
					font-style: italic;
					font-weight: bold;
					font-size: 1em;
				}
				
				#details #artist,
				#details #name {
					font-size: 0.9em;
				}
		</style>
	</head>
	<body>
		<?php
		//If the user is not listening to anything, display message
		if(isset($notlistening)) {
		?>
		<div id="notlistening">
			I am not listening to any music. Song I play will be shown live here.
		</div>
		<?php
		}
		
		//Else display what the user is playing
		else {
		?>
		<div id="art"></div>
		<div id="details">
			<span id="nowplaying">I'm Listening To:</span></br>
			<span id="artist"><?php echo $artist ?></span></br>
			<span id="name"><?php echo $name ?></span>
		</div>
		<?php
		}
		?>
	</body>
</html>