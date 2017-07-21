# getrets-frames
A framed solution for consuming the GetRETS&reg; API from timitek (<http://www.timitek.com>).

**Live Demo at**: <http://frames.timitek.com/demo/simple.html>

**GetRETS&reg;** is a product / service developed by timitek that makes it possible to quickly build real estate related applications for pulling listing data from several MLS's without having to know anything about RETS or IDX or worry about the pains of mapping and storing listing data from these various sources. 

**GetRETS&reg;** as a service provides a RESTful API endpoint for consuming the data, and although it's not limited to only being used in PHP applications, and users aren't required to use our SDK, we have provided a simple PHP SDK for the API and set of documentation for it's use.

***

# Table of Contents
* [Frames Server Setup](#frames-server-setup)
  * [Installing](#installing)
  * [Setup](#setup)
  * [Testing](#testing)
* [Development](#development)
  * [iframes](#iframes)
  * [Form Fields](#form-fields)

***

# Frames Server Setup

A public frames server is available at <http://frames.timitek.com>, however, you may wish to set up and host your own frames server to support specific customizations.  The server side code is based on laravel, and the client side code uses AngularJS, and Bootstrap 3.

Since this project is based on laravel <https://www.laravel.com/>, it adheres to the the same requirements and setup which can be found at <https://laravel.com/docs/master/>.  You should first review the server requirements and configuration found at this page if you are unfamiliar with laravel before continuing.

## Installing

To get started with this getrets-frames, you may either download the project manually or clone it into the folder where it will be installed.

```
git clone https://github.com/timitek/getrets-frames
cd getrets-frames
```

Then install the server side PHP vendor packages with;

```
composer install
```

Then install and process the client side packages with;

```
npm install
gulp
```

## Setup

This project uses the DotEnv PHP library that comes with laravel and uses a .env file to store it's settings.  An example .env file is provided with the install for you to use as a starting point.  You can find more information about this at <https://laravel.com/docs/master/configuration>.

Initialize the custom settings via the .env file

```
cp .env.example .env
php artisan key:generate
```

You may now add the customer key provided to you by timitek.com by either modifying the config/getrets.php or by adding the following to your .env file.

```
GETRETS_CUSTOMER_KEY=your_customer_key_from_timitek
```

To view maps on your listing details page, you will need a google maps api key, which can be obtained at <https://developers.google.com/maps/documentation/javascript/get-api-key>.

Add your google maps API key by modifying your .env file.

```
MAPS_KEY=your_google_maps_api_key
```

## Testing

To test the installation locally, you can start up a simple built in php server by running

```
php artisan serve
```

And then browsing to <http://localhost:8000/>

This test will allow you to ensure that your settings are correct and that your frames server is running.

A sample page that uses the frames server can be found at <http://localhost:8000/demo/sample.html>.  However, it needs to be modified, since by default this page is set to work against <http://frames.timitek.com>.  You will need to modify the references to frames.timitek.com to poing to your frames server.  These references can be found on line 26 and 50 of the sample html file.


# Development

Creating a frames solution is as easy as creating a form on your existing website and and an iframe pointing to a frames server. The publicly available frames server is found at <http://frames.timitek.com>.  A sample html file can be found at <https://github.com/timitek/getrets-frames/blob/master/public/demo/simple.html>

## iframes

Create an iframe on your site that will be used to display the listing results returned from the getrets-frames server.

```
<iframe id="listingsFrame" name="listingsFrame" src="" style="display: block; border: none; width: 100%;"></iframe>
```

Then create a form that will post to the frames server and targets your iframe.

```
<form action="http://frames.timitek.com" target="listingsFrame" method="post">
```

There are several fields you can provide on your form as either hidden input fields or as visible fields for your users to select in order to filter the listings you wish to search for.

## Form Fields

***advancedSearch*** - A boolean field that needs to be set to true if you want to provide anything more than a keyword search

***keywords*** - Keywords to search on (address, listing id, etc...)

***extra*** - *(optional)* Comma seperated list of extra terms to search for (golf, lake, etc...)

***maxPrice*** - *(optional)* The maximum listing price

***minPrice*** - *(optional)* The minimum listing price

***beds*** - *(optional)* The minimum number of beds to require

***baths*** - *(optional)* The minimum number of baths to require

***includeResidential*** - *(optional)* Include residential listings

***includeLand*** - *(optional)* Include land listings

***includeCommercial*** - *(optional)* Include commercial listings

***propertyType*** - *(optional)* Instead of providing the 3 includes listed above, you may alternatively provide the property type as one of 3 values (residential, commercial, land)

*Note* - If you don't set any of the *include* parameters or a propertyType, all will be assumed as set.

***sortBy*** - *(optional)* You may request your results to be sorted by 1 of the following fields (id, listingSourceURLSlug, listingTypeURLSlug, listingID, listingSource, listingType, address, baths, beds, listPrice, rawListPrice, providedBy, squareFeet, lot, acres)

***reverseSort*** - *(optional)* A boolean that when set to true will revere the default order of the results. (Used in conjunction with sortBy).

***theme*** - *(optional)* Changes the colors of the results and details pages.  The themes that are available for use are (cerulean, cosmo, custom, cyborg, darkly, flatly, journal, lumen, paper, readable, sandstone, simplex, slate, spacelab, superhero, united, yeti) and are provided by <http://www.bootswatch.com/>

***linkTarget*** - *(optional)* Specified how the listing details links will be created.  Follows the same rules as all href target's.  Example options are (_blank, _self, _parent, _top).
