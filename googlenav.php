<?php
/*
Plugin Name: Google Navigator
Plugin URI: http://googlenavigator.heefthetgemaakt.nl
Description: a plugin for a contact page. add the adres you want visitor to navigate to. Visitors type their address. They get direct driving directions in the browser's default.
Version: 4.5
Author: Robert van Bekkum
Author URI: http://www.bekcomp.nl
License: A "Slug" license name e.g. GPL2
*/
/*  Copyright 2011  Robert van Bekkum  (email : wordpress@bekcomp.nl)
	
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
	Gemaakt voor een schooloopdracht van INHolland Haarlem
*/


function add_googlenav_header(){
  echo "";
  $dir = WP_PLUGIN_URL.'/google-navigate/scripts/';
  echo <<<_end_
  <script src="{$dir}jquery.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>

 
_end_;
}

add_action('wp_head','add_googlenav_header');

function navigator_func($atts) {
	extract(shortcode_atts(array(
		'location' => '',
        'height' => '300px',
        'width' => 'auto',
        'style' => '',
        'to' => 'To',
        'from' => 'From',
        'show' => 'Show Route',
        
        
	), $atts));

	



  $google_navigator_html .= <<<_end_

    <!-- Begin googlenav-->
    
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script type="text/javascript">
		var Map = null;
		var Geocoder = null;
		var DirDisplay = null;
		var DirService = null;

		$(function(){
			Geocoder = new google.maps.Geocoder();
			DirDisplay = new google.maps.DirectionsRenderer();
			DirService = new google.maps.DirectionsService();
			
			Map = new google.maps.Map(document.getElementById("MapsDiv"), {
				zoom: 10,
				center: new google.maps.LatLng(-34.397, 150.644), 
				navigationControl: true,
				navigationControlOptions: {
					style: google.maps.NavigationControlStyle.DEFAULT
				},
				mapTypeControl: true,
				mapTypeControlOptions: {
					style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
				},
				scaleControl: true,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			DirDisplay.setMap(Map);
			DirDisplay.setPanel(document.getElementById("NavigationText"));
			setMyLocation();
		});
		
		function setMyLocation() {
			Geocoder.geocode({"address": "{$location}"}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					Map.setCenter(results[0].geometry.location);
					var marker = new google.maps.Marker({
						map: Map, 
						position: results[0].geometry.location
					});
				} else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
		}
		
		function calculateRoute() {
			var fromAddress = document.getElementById("mapsFromLocation").value;
			var toAddress = "{$location}";
			DirService.route({
				origin: fromAddress, 
				destination: toAddress,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			}, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					DirDisplay.setDirections(result);
					setTimeout(ResizeColumns, 100);
				}
			});
		}
	</script>
	
  <div style="margin-bottom:10px;">
  		<b>{$from}&nbsp;:&nbsp;&nbsp;</b>  
        <input id="mapsFromLocation" name="mapsFromLocation" type="text" size="50" value=""/>
        <input type="button" value="{$show}" onclick="calculateRoute()"/>
    	<b>{$to}&nbsp;:&nbsp;&nbsp;</b>{$location}	
      
    </div>
    <div id="MapsDiv" style="height:{$height}; width:{$width}; {$style} "></div>
        <script type="text/javascript"><!--
			google_ad_client = "pub-6941027754457514";
			/* 468x15, gemaakt 10-1-11 */
			google_ad_slot = "4101246797";
			google_ad_width = 468;
			google_ad_height = 15;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
		</script>
    <div id="NavigationText"></div>
  <!-- End googlenav -->

_end_;
 return $google_navigator_html;
}

 
 add_shortcode('navigator', 'navigator_func');
?>