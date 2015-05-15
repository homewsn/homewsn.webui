### Setting Up the Web User Interface (WebUI)

This repository is a part of the [HomeWSN](http://homewsn.github.io) project.

Web user interface requires the web server, PHP and MySQL database. 
The web server may be any on any operating system, the main requirement is PHP support. 
The best choice is a simple small and easy to use standalone web server with PHP support like [civetweb](https://github.com/bel2125/civetweb). 
To setup WebUI just copy all the files and subfolders into your web site document root (or another) folder. 

[The MySQL database](https://github.com/homewsn/homewsn.mysql.settings) is required to collect and store data received from the sensors and actuators. 
Web user interface also uses the database to obtain the properties of the devices, and their collected data. 
These data are typically long values, but may be floats and strings. 
The devices initially send their properties to the [whsnbg](https://github.com/homewsn/whsnbg), which in turn stores them in the database:
- `Actuator #`, `Actuator IP` in the 'Actuators' table *(DB Tables -> Actuators -> Locations menu)*;
- `Param #`, `Unit` and `Data type` in the 'Actuators parameters' table *(DB Tables -> Actuators -> Device types menu)*;
- `Sensor #`, `Sensor IP` in the 'Sensors' table *(DB Tables -> Sensors -> Locations menu)*;
- `Param #`, `Unit` and `Data type` in the 'Sensors parameters' table *(DB Tables -> Sensors -> Measured parameters menu)*;

The end user complements this data via the web interface. 
Sensor and actuator location is entered into `Location` field in the 'Sensors' and 'Actuators' database tables respectively. 
Other visual properties can be modified in the 'Sensors parameters' and 'Actuators parameters' tables. 
The `Image type` field is used to define the graphical representation of the measured parameter. 
- The *ImageFile* option is used for the parameters which may have only two states, like on-off or open-close etc. It's needed to additionally enter icons urls to the `Image n/a`, `Image 0` and `Image 1` fields if this option is selected. `State 0` and `State 1` are used for verbal designation of the device status.
- The *LinearGauge* and *CircularGauge* options are used for the numerical parameters. Therefore `State 0` and `State 1` are used for minimal and maximum values of this numeric parameter. `Image n/a`, `Image 0` and `Image 1` are not required to fill in.

The `Type` field is used to create the appropriate element under the *Types of devices* in the *Actuators* drop-down menu or under the *Types of measured parameters* in the *Sensors* drop-down menu respectively.

When all the required fields in the database tables will be filled in correctly, the corresponding elements in the *Actuators* and *Sensors* drop-down menus appear.

#### External dependencies

WebUI depends on external javascript and css libraries and each has its own license. The total list of the dependencies is provided below:

- [Bootstrap](http://getbootstrap.com/) 3.2.0 ([license](https://github.com/twbs/bootstrap/blob/master/LICENSE))
- [Bootstrap slider](https://github.com/seiyria/bootstrap-slider) 4.8.1 ([license](https://github.com/seiyria/bootstrap-slider/blob/master/LICENSE.md))
- [Bootstrap switch](http://www.bootstrap-switch.org/) 3.3.2 ([license](https://github.com/nostalgiaz/bootstrap-switch/blob/master/LICENSE))
- [X-editable](http://vitalets.github.io/x-editable/) 1.5.1 ([license](https://github.com/vitalets/x-editable/blob/master/LICENSE-MIT))
- [Highstock](http://www.highcharts.com/products/highstock) 2.1.5 ([license](http://shop.highsoft.com/highstock.html))
- [JQuery](https://jquery.com/) 2.1.3 ([license](https://jquery.org/license/))
- [JQuery UI](http://jqueryui.com/) 1.11.4 ([license](https://jquery.org/license/))
- [JQuery cookie](https://github.com/carhartl/jquery-cookie) 1.4.1 ([license](https://github.com/carhartl/jquery-cookie/blob/master/MIT-LICENSE.txt))
- [JSPlumb](http://www.jsplumb.org) 1.7.5 ([license](https://github.com/sporritt/jsplumb/#license))
- [later.js](http://bunkat.github.io/later/) 1.1.6 ([license](https://github.com/bunkat/later/blob/master/LICENSE))
- [Moment.js](http://momentjs.com/) 2.9.0 ([license](https://github.com/moment/moment/blob/develop/LICENSE))
- [The Paho JavaScript Client](https://eclipse.org/paho/clients/js/) 3.1 ([public license](http://www.eclipse.org/legal/epl-v10.html), [distribution license](http://www.eclipse.org/org/documents/edl-v10.php))
- [Raphaël JavaScript Library](http://raphaeljs.com/) 2.1.4 ([license](http://raphaeljs.com/license.html))
- [Selectize.js](http://brianreavis.github.io/selectize.js/) 0.12.1 ([license](https://github.com/brianreavis/selectize.js/blob/master/LICENSE))

HomeWSN WebUI javascript code itself licensed under [GPLv2](http://www.gnu.org/licenses/gpl-2.0.html).
