<?php

class geography {
	
	protected $EARTH_R = 3956.09;

	protected function _deg2rad_multi() {
		// Grab all the arguments as an array & apply deg2rad to each element
		$arguments = func_get_args();
		return array_map('deg2rad', $arguments);
	}

	// Function: latlon_convert
	// Function: latlon_convert
	// Desc:  This is a conversion function to help transform more standard
	//   notation coordinates into the form needed by these functions.  This
	//   allows entry as separate Degree, Minute and Second.  It also accepts
	//   a 'N', 'S', 'E', or 'W'  All parameters (except for degree) are
	//   optional and can be floats.
	//   You might enter 77 34 45.5, or 75 54.45644
	public function latlon_convert($degrees, $minutes = 0, $seconds = 0, $dir = '') {
		// Prepare the final value and keep adding to it:
		$final = $degrees;

		// Add in the minutes & seconds, properly converted to decimal values:
		// Uses the fact that there are 60 minutes in a degree,
		//  and 3600 seconds in a degree.
		$final += $minutes / 60.0;
		$final += $seconds / 3600.0;

		// If the direction is West or South, make sure this is negative
		//  in case someone forgot and put a -degree and said South:
		if (($dir == 'W') || ($dir == 'S')) {
			$final = abs($final) * -1.0;
		}

		return $final;
	}

	// Function: latlon_distance_great_circle
	// Desc:  Calculate the shortest distance between two pairs of coordinates.
	//   This calculates a great arc around the Earth, assuming that the Earth
	//   is a sphere.  There is some error in this, as the earth is not
	//   perfectly a sphere, but it is fairly accurate.
	public function latlon_distance_great_circle($lat_a, $lon_a, $lat_b, $lon_b) {
		// Convert our degrees to radians:
		list($lat1, $lon1, $lat2, $lon2) =
			$this -> _deg2rad_multi($lat_a, $lon_a, $lat_b, $lon_b);

		// Perform the formula and return the value
		return acos(
				( sin($lat1) * sin($lat2) ) +
				( cos($lat1) * cos($lat2) * cos($lon2 - $lon1) )
				) * $this -> EARTH_R;
	}

	// Function: latlon_bearing_great_circle
	// Desc:  This function calculates the initial bearing you need to travel
	//   from Point A to Point B, along a great arc.  Repeated calls to this
	//   could calculate the bearing at each step of the way.
	public function latlon_bearing_great_circle($lat_a, $lon_a, $lat_b, $lon_b) {
		// Convert our degrees to radians:
		list($lat1, $lon1, $lat2, $lon2) =
			$this -> _deg2rad_multi($lat_a, $lon_a, $lat_b, $lon_b);

		// Run the formula and store the answer (in radians)
		$rads = atan2(
				sin($lon2 - $lon1) * cos($lat2),
				(cos($lat1) * sin($lat2)) -
					  (sin($lat1) * cos($lat2) * cos($lon2 - $lon1)) );

		// Convert this back to degrees to use with a compass
		$degrees = rad2deg($rads);

		// If negative subtract it from 360 to get the bearing we are used to.
		$degrees = ($degrees < 0) ? 360 + $degrees : $degrees;

		return $degrees;
	}

	// Function: latlon_distance_rhumb
	// Desc:  Calculates the distance between two points along a Rhumb line.
	//   Rhumb lines are a line between two points that uses a constant
	//   bearing.  They are slightly longer than a great circle path; however,
	//   much easier to navigate.
	public function latlon_distance_rhumb($lat_a, $lon_a, $lat_b, $lon_b) {
		// Convert our degrees to radians:
		list($lat1, $lon1, $lat2, $lon2) =
			_deg2rad_multi($lat_a, $lon_a, $lat_b, $lon_b);

		// First of all if this a true East/West line there is a special case:
		if ($lat1 == $lat2) {
			$mid = cos($lat1);
		} else {
			 $delta = log( tan(($lat2 / 2) + (M_PI / 4))
				/ tan(($lat1 / 2) + (M_PI / 4)) );
			$mid = ($lat2 - $lat1) / $delta;
		}

		// Calculate difference in longitudes, and if over 180, go the other
		//  direction around the Earth as it will be a shorter distance:
		$dlon = abs($lon2 - $lon1);
		$dlon = ($dlon > M_PI) ? (2 * M_PI - $dlon) : $dlon;
		$distance = sqrt( pow($lat2 - $lat1,2) +
						  (pow($mid, 2) * pow($dlon, 2)) ) * $this -> EARTH_R;

		return $distance;
	}

	// Function: latlon_bearing_rhumb
	// Desc:  Calculates the bearing for the Rhumb line between two points.
	public function latlon_bearing_rhumb($lat_a, $lon_a, $lat_b, $lon_b) {
		// Convert our degrees to radians:
		list($lat1, $lon1, $lat2, $lon2) =
			_deg2rad_multi($lat_a, $lon_a, $lat_b, $lon_b);

		// Perform the math & store the values in radians.
		$delta = log( tan(($lat2 / 2) + (M_PI / 4))
					/ tan(($lat1 / 2) + (M_PI / 4)) );
		$rads = atan2( ($lon2 - $lon1), $delta);

		// Convert this back to degrees to use with a compass
		$degrees = rad2deg($rads);

		// If negative subtract it from 360 to get the bearing we are used to.
		$degrees = ($degrees < 0) ? 360 + $degrees : $degrees;

		return $degrees;
	}
}
