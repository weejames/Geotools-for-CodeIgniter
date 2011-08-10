## Geotools for CodeIgniter
### by James Constable
me@jamesconstable.co.uk
@weejames

24/04/2011

### Intro:

A collection of tools for geographic calculations and geocoding for use in CodeIgniter based applications.

Currently the only functions offered are those to calculate the distance between points, specified by a Latitude, Longitude pair.

### Installation:

You can either use http://getsparks.org to install via the spark installer.  Visit http://getsparks.org/packages/geotools/versions/HEAD/show to install it.

or

1. Extract the archive you get from here.
2. Put libraries/geotools.php and libraries/geopoint.php into your applications libraries directory.


### Usage:

If you've installed via the spark then add the following to your controller.

`$this->load->spark('geotools');`

or 

`$this->load->library('geotools');`

if you've installed manually.


#### Distance.

To calculate the distance between two points create the two locations by calling the geopoint function.

    $startPoint = $this->geotools->geopoint(55.8333, -4.25);
    $endPoint = $this->geotools->geopoint(55.9464, -3.1991);

Then call the distanceBetween function to calculate the distance between the two locations.

    $this->geotools->distanceBetween($startPoint, $endPoint, $algorithm, $unit);

Two algorithms are available to calculate distance.

Pythagorean theory -  faster but less accurate over longer distances.
Haversine - fairly accurate but has a larger performance hit.  This is the default option.

Units supported are 'miles' and 'km'.  Miles are returned by default.


#### Bearing.

To calculate the bearing (in degrees 0-360) between two points

    $this->geotools->bearingFrom($startPoint, $endPoint)

You can also pull the rough compass direction using

    $this->geotools->compassDirection($startPoint, $endPoint)


#### Midpoint.

To calculate the midpoint of two points

    $this->geotools->midpoint($startPoint, $endPoint)


#### End point.

To calculate an endpoint given a startpoint, bearing (0-360 degrees) and distance.

    $this->geotools->endpoint($startPoint, $bearing, $distance, $unit);

The unit refers to 'miles' or 'km' and should match what you're passing in to $distance.  It'll default to 'miles'.


### Problems? Suggestions?

Twitter: @weejames
Email: me@jamesconstable.co.uk
Web: http://jamesconstable.co.uk
GitHub: http://github.com/weejames

