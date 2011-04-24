<?php

class Geotools {
	
	private $defaultUnit = 'miles';
	private $kmPerMile = 1.609;
	private $earthRadius = array();
	private $directions = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW", "N");
	
	public function __construct() {
		$this->earthRadius['miles'] = 3963.19;
		$this->earthRadius['km'] = $this->earthRadius['miles'] * $this->kmPerMile;
	}
	
	public function distanceBetween(Geopoint $pointA, Geopoint $pointB, $algorithm = 'flat', $unit = null) {

		if (!$unit) $unit = $this->defaultUnit;

		switch ($algorithm) {
			case 'haversine':
				$theta = ($pointA->longitude - $pointB->longitude); 
				$dist = sin(deg2rad($pointA->latitude)) * sin(deg2rad($pointB->latitude)) +  cos(deg2rad($pointA->latitude)) * cos(deg2rad($pointB->latitude)) * cos(deg2rad($theta)); 
				$dist = acos($dist); 
				
				$distance = rad2deg($dist);
			break;
			case 'flat':
			default:	
				$distanceEW = ($pointB->longitude - $pointA->longitude) * cos($pointA->latitude);
				$distanceNS = $pointB->latitude - $pointA->latitude;
				
				$distance = sqrt( ($distanceEW * $distanceEW) + ($distanceNS * $distanceNS));
			break;
		}
		
		$distance *= 2 * pi() * $this->earthRadius[$unit] / 360.0;
		
		return $distance;
		
	}
	
	
	public function endpoint(Geopoint $startPoint, $bearing, $distance, $unit = null) {
		if (!$unit) $unit = $this->defaultUnit;
		
		$radius = $this->earthRadius[$unit];

		$lat = deg2rad($startPoint->latitude);
		$lng = deg2rad($startPoint->longitude);

		$bearing = deg2rad($bearing);
		
		$endLat = asin(sin($lat) * cos($distance / $radius) + cos($lat) * sin($distance / $radius) * cos($bearing));
		
		$endLon = $lng + atan2( sin($bearing) * sin($distance / $radius) * cos($lat), cos($distance / $radius) - sin($lat) * sin($endLat) );
		
		return $this->geopoint(rad2deg($endLat), rad2deg($endLon));
	}
	
	public function midpoint(Geopoint $pointA, Geopoint $pointB) {
		
		$bearing = $this->bearingFrom($pointA, $pointB);
		$distance = $this->distanceBetween($pointA, $pointB);
		
		return $this->endpoint($pointA, $bearing, $distance / 2);
	}

	public function bearingFrom(Geopoint $pointA, Geopoint $pointB) {
     $bearing = (rad2deg(atan2(sin(deg2rad($pointB->longitude) - deg2rad($pointA->longitude)) * cos(deg2rad($pointB->latitude)), cos(deg2rad($pointA->latitude)) * sin(deg2rad($pointB->latitude)) - sin(deg2rad($pointA->latitude)) * cos(deg2rad($pointB->latitude)) * cos(deg2rad($pointB->longitude) - deg2rad($pointA->longitude)))) + 360) % 360;
		return $bearing;
	}

	
	public function compassDirection(Geopoint $pointA, Geopoint $pointB) {
		$bearing = $this->bearingFrom($pointA, $pointB);
		
		$tmp = round($bearing / 22.5);
		
		return $this->directions[$tmp];
	}
	
	public function geopoint($latitude, $longitude) {
		include_once('geopoint.php');
		return new Geopoint((float)$latitude, (float)$longitude);
	}
	
}