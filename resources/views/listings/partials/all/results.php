<div class="ng-cloak" ng-controller="listingsWidget">
    <div class="row" ng-if="listings && listings.length">
        <div class="col-xs-12 col-sm-6 col-lg-4" ng-repeat-start="listing in listings">
            <div class="thumbnail listing-result animated bounceInDown">
                <a ng-href="/listings/{{listing.listingTypeURLSlug}}_{{listing.id}}" target="_blank" alt="View Details">
                    <img class="listing-result-img" ng-src="{{listing.thumbnail}}?newWidth=242&maxHeight=200" alt="...">
                </a>
                <div class="caption">
                    <div class="listing-result-address"><i class="fa fa-map-marker"></i> <span ng-bind="listing.address"></span></div>
                    <div class="listing-result-attributes">
                        <div>
                            <span class="label label-primary"><i class="fa" ng-class="{ 'fa-tree' : listing.listingTypeURLSlug === 'Land', 'fa-building' : listing.listingTypeURLSlug === 'Commercial', 'fa-home' : listing.listingTypeURLSlug === 'Residential' }"></i> <span ng-bind="listing.listingTypeURLSlug"></span></span>
                            <span class="label label-primary" ng-if="listing.beds"><i class="fa fa-bed"></i> <span ng-bind="listing.beds"></span> Bed</span>
                            <span class="label label-primary" ng-if="listing.baths"><i class="fa fa-bath"></i> <span ng-bind="listing.baths"></span> Bath</span>
                        </div>
                        <div class="listing-result-dimensions" ng-if="listing.squareFeet || listing.lot || listing.acres">
                            <span class="listing-result-dimension" ng-if="listing.squareFeet">
                                <strong><abbr title="Square Feet">Sqft.:</abbr></strong> <span ng-bind="listing.squareFeet"></span>
                            </span>
                            <span class="listing-result-dimension" ng-if="listing.lot">
                                <strong>Lot:</strong> <span ng-bind="listing.lot"></span>
                            </span>
                            <span class="listing-result-dimension" ng-if="listing.acres">
                                <strong>Acres:</strong> <span ng-bind="listing.acres"></span>
                            </span>
                        </div>
                    </div>
                    <div class="listing-result-price"><span ng-bind="listing.listPrice"></span></div>
                    <div class="listing-result-provider"><strong>Provided By:</strong> <span ng-bind="listing.providedBy"></span></div>
                </div>
            </div>
        </div>
        <div class="clearfix visible-sm-block" ng-if="$index%2==1"></div>
        <div class="clearfix visible-lg-block" ng-if="$index%3==2"></div>
        <div ng-repeat-end=""></div>
    </div>
    <div class="row" ng-if="!searching && (listings && !listings.length)">
        <div class="col-xs-12">
            <div class="alert alert-warning animated bounceInDown" role="alert"> <strong>Nope...</strong> I couldn't find exactly what you were looking for.  Change a few things up and try searching again.</div>            
        </div>
    </div>
</div>
