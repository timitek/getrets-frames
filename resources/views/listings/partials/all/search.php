<div class="well well-lg" ng-controller="searchWidget">
    <form>
        <span ng-show="advancedSearch" class="label label-primary pull-right pointer ng-cloak" ng-click="advancedSearch = false"><i class="fa fa-arrow-circle-up"></i> Simple Search</span>
        <span ng-show="!advancedSearch" class="label label-primary pull-right pointer" ng-click="advancedSearch = true"><i class="fa fa-arrow-circle-down"></i> Advanced Search</span>

        <div class="form-group">
            <label for="keywords">Search</label>
            <div class="input-group add-on">
                <input class="form-control" id="keywords" name="keywords" ng-enter="search()" placeholder="Enter keywords (address, listing id, etc..)" ng-model="keywords">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit" ng-click="search()"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="animated ng-cloak" ng-class="{ pulse: advancedSearch }" ng-show="advancedSearch">
            <div class="row">
                <div class="col-xs-12 col-md-6 form-group">
                    <label for="minPrice">Min Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" class="form-control" id="minPrice" name="minPrice" placeholder="Min Price" ng-model="minPrice">
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 form-group">
                    <label for="maxPrice">Max Price</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" class="form-control" id="maxPrice" name="maxPrice" placeholder="Max Price" ng-model="maxPrice">
                    </div>
                </div>                        

                <div class="col-xs-12">
                    <fieldset class="search-section">
                        <legend>Property Type</legend>
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="includeResidential" ng-model="includeResidential"> 
                                        Include Residential
                                    </label>
                                </div>                                
                            </div>

                            <div class="col-xs-12 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="includeLand" ng-model="includeLand"> 
                                        Include Land
                                    </label>
                                </div>                                
                            </div>

                            <div class="col-xs-12 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="includeCommercial" ng-model="includeCommercial"> 
                                        Include Commercial
                                    </label>
                                </div>                                
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>
</div>
