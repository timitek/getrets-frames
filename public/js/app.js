/*
 elixir((mix) => {
 mix.webpack('../../themes/getrealt/assets/js/app.js', './public/themes/getrealt/js');
 });
 */

(function () {

    var listingService = function ($q, $http, restService) {

        this.index = function (customerKey, advancedSearch, keywords, extra, minPrice, maxPrice, includeResidential, includeLand, includeCommercial, beds, baths, sortBy, reverseSort) {
            var deferred = $q.defer();

            var params = {
                'keywords': keywords
            };

            if (advancedSearch) {
                if (customerKey) {
                    params.customerKey = customerKey;
                }
                if (extra) {
                    params.extra = extra;
                }
                if (advancedSearch) {
                    params.advancedSearch = advancedSearch;
                }
                if (minPrice) {
                    params.minPrice = minPrice;
                }
                if (maxPrice) {
                    params.maxPrice = maxPrice;
                }
                if (includeResidential) {
                    params.includeResidential = includeResidential;
                }
                if (includeLand) {
                    params.includeLand = includeLand;
                }
                if (includeCommercial) {
                    params.includeCommercial = includeCommercial;
                }
                if (beds) {
                    params.beds = beds;
                }
                if (baths) {
                    params.baths = baths;
                }
                if (sortBy) {
                    params.sortBy = sortBy;
                }
                if (reverseSort) {
                    params.reverseSort = reverseSort;
                }
            }

            restService.go({
                url: '/api/listings',
                method: 'POST',
                params: params
            }).then(function (data) {
                deferred.resolve(data.data);
            }, function (data) {
                deferred.reject(data.data);
                throw data;
            });

            return deferred.promise;
        };


        this.show = function (id) {
            var deferred = $q.defer();

            restService.go({
                url: '/api/listings' + id
            }).then(function (data) {
                deferred.resolve(data.data);
            }, function (data) {
                deferred.reject(data.data);
                throw data;
            });

            return deferred.promise;
        };

        this.sendLead = function (info) {
            var deferred = $q.defer();

            restService.go({
                url: '/api/listings/sendLead',
                method: 'POST',
                params: info
            }).then(function (data) {
                deferred.resolve(data.data);
            }, function (data) {
                deferred.reject(data.data);
                throw data;
            });

            return deferred.promise;
        };
    };

    var searchWidget = function ($scope, $timeout, eventFactory, listingService) {
        $scope.showSearchBox = true;

        // Input variables
        $scope.customerKey = null;
        $scope.advancedSearch = false;
        $scope.keywords = null;
        $scope.extra = null;
        $scope.minPrice = null;
        $scope.maxPrice = null;
        $scope.beds = null;
        $scope.baths = null;
        $scope.includeResidential = true;
        $scope.includeLand = true;
        $scope.includeCommercial = true;
        $scope.sortBy = null;
        $scope.reverseSort = false;

        $scope.beds = 0;
        $scope.baths = 0;

        $scope.listings = null;

        $scope.messageParent = function (listings) {
            $timeout(function () {
                window.parent.postMessage({
                    listings: listings, 
                    height: document.body.clientHeight
                }, '*');
            }, 10);
        };

        $scope.search = function () {
            $scope.listings = null;
            $scope.messageParent(null);
            if ($scope.advancedSearch || $scope.keywords) {
                eventFactory.searchingListings(true);
                listingService.index(
                        $scope.customerKey,
                        $scope.advancedSearch,
                        $scope.keywords,
                        $scope.extra,
                        $scope.minPrice,
                        $scope.maxPrice,
                        $scope.includeResidential,
                        $scope.includeLand,
                        $scope.includeCommercial,
                        $scope.beds,
                        $scope.baths,
                        $scope.sortBy,
                        $scope.reverseSort
                        ).then(function (data) {
                    $scope.listings = data;
                    eventFactory.searchingListings(false);
                    eventFactory.refreshListings($scope.listings);
                    $scope.messageParent(data);
                });
            }
        };

        $scope.start = function(params) {
            if (params) {
                $scope.customerKey = params.customerKey;
                $scope.showSearchBox = params.showSearchBox;
                $scope.advancedSearch = params.advancedSearch;
                $scope.keywords = params.keywords;
                $scope.extra = params.extra;
                $scope.minPrice = params.minPrice;
                $scope.maxPrice = params.maxPrice;
                $scope.includeResidential = params.includeResidential;
                $scope.includeLand = params.includeLand;
                $scope.includeCommercial = params.includeCommercial;
                $scope.beds = params.beds;
                $scope.baths = params.baths;
                $scope.sortBy = params.sortBy;
                $scope.reverseSort = params.reverseSort;
                $scope.search();
            }
        };
    };

    var listingsWidget = function ($scope, eventFactory) {
        $scope.listings = null;

        eventFactory.onSearchingListings($scope, function (searching) {
            $scope.searching = searching;
        });

        eventFactory.onRefreshListings($scope, function (listings) {
            $scope.listings = listings;
        });
    };

    var eventFactory = function ($rootScope) {
        var REFRESH_LISTINGS = 'refreshListings';
        var refreshListings = function (listings) {
            $rootScope.$broadcast(REFRESH_LISTINGS, listings);
        };
        var onRefreshListings = function ($scope, handler) {
            $scope.$on(REFRESH_LISTINGS, function (event, message) {
                handler(message);
            });
        };

        var SEARCHING_LISTINGS = 'searchingListings';
        var searchingListings = function (searching) {
            $rootScope.$broadcast(SEARCHING_LISTINGS, searching);
        };
        var onSearchingListings = function ($scope, handler) {
            $scope.$on(SEARCHING_LISTINGS, function (event, message) {
                handler(message);
            });
        };


        return {
            refreshListings: refreshListings,
            onRefreshListings: onRefreshListings,
            searchingListings: searchingListings,
            onSearchingListings: onSearchingListings
        };

    };

    var listingDetails = function ($scope, $uibModal, $timeout, listingService) {

        $scope.listingSource = null;
        $scope.listingType = null;
        $scope.listingID = null;
        $scope.address = null;

        $scope.messageParent = function () {
            $timeout(function () {
                window.parent.postMessage({
                    listingSource: $scope.listingSource,
                    listingType: $scope.listingType,
                    listingID: $scope.listingID,
                    address: $scope.address,
                    height: document.body.clientHeight
                }, '*');
            }, 10);
        };

        $scope.initMap = function () {

            var geocoder = new google.maps.Geocoder();

            if (geocoder) {
                geocoder.geocode({'address': $scope.address}, function (results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (status !== google.maps.GeocoderStatus.ZERO_RESULTS) {

                            var location = results[0].geometry.location;

                            var mapCanvas = document.getElementById('map');
                            mapCanvas.style.display = 'block';

                            var mapOptions = {
                                center: location,
                                zoom: 16,
                                panControl: false,
                                scrollwheel: false,
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            };

                            var map = new google.maps.Map(mapCanvas, mapOptions);


                            var marker = new google.maps.Marker({
                                position: location,
                                map: map,
                                icon: '/images/marker.png'
                            });

                            var directionsLink = 'http://www.google.com/maps/dir/current+position/' + encodeURI($scope.address);
                            var streetViewLink = 'http://maps.google.com/maps?q=&layer=c&cbll=' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();

                            var contentString = '<div class="info-window">' +
                                    '<h5><i class="fa fa-map-marker"></i> ' + results[0].formatted_address + '</h5>' +
                                    '<div class="info-content">' +
                                    '<p>' +
                                    '<i class="fa fa-car"></i>: <a href="' + directionsLink + '" target="_blank">Get Directions</a><br />' +
                                    '<i class="fa fa-street-view"></i>: <a href="' + streetViewLink + '" target="_blank">Street View</a><br />' +
                                    '</p>' +
                                    '</div>' +
                                    '</div>';

                            var infowindow = new google.maps.InfoWindow({
                                content: contentString,
                                maxWidth: 400
                            });

                            marker.addListener('click', function () {
                                infowindow.open(map, marker);
                            });

                            var styles = [{"featureType": "administrative", "stylers": [{"visibility": "off"}]}, {"featureType": "poi", "stylers": [{"visibility": "simplified"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "simplified"}]}, {"featureType": "water", "stylers": [{"visibility": "simplified"}]}, {"featureType": "transit", "stylers": [{"visibility": "simplified"}]}, {"featureType": "landscape", "stylers": [{"visibility": "simplified"}]}, {"featureType": "road.highway", "stylers": [{"visibility": "off"}]}, {"featureType": "road.local", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"visibility": "on"}]}, {"featureType": "water", "stylers": [{"color": "#84afa3"}, {"lightness": 52}]}, {"stylers": [{"saturation": -17}, {"gamma": 0.36}]}, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"color": "#3f518c"}]}];

                            map.set('styles', styles);

                            $scope.messageParent();

                        } else {
                            //alert("No results found");
                        }
                    } else {
                        //alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            }

        };

        $scope.initSliders = function () {
            // The slider being synced must be initialized first
            $('#carousel').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                itemWidth: 210,
                itemMargin: 5,
                smoothHeight: true,
                asNavFor: '#slider'
            });

            $('#slider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false,
                smoothHeight: true,
                sync: "#carousel"
            });
        };


        $scope.contactAgent = function () {
            var modalInstance = $uibModal.open({
                templateUrl: 'contactAgent.html',
                controller: 'contactAgentModal',
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                size: 'lg',
                resolve: {
                    parentController: function () {
                        return $scope;
                    }
                }
            });

            modalInstance.result.then(
                    function (results) {
                        var info = results.contactInfo;
                        // closed
                        if (info && (info.name || info.phone || info.email || info.message)) {
                            info.listingSource = $scope.listingSource;
                            info.listingType = $scope.listingType;
                            info.listingID = $scope.listingID;
                            
                            listingService.sendLead(info).then(function (data) {
                                $uibModal.open({
                                    templateUrl: 'messageConfirmation.html',
                                    controller: 'messageConfirmationModal',
                                    ariaLabelledBy: 'modal-title',
                                    ariaDescribedBy: 'modal-body',
                                    size: 'sm',
                                    resolve: {
                                        message: function () {
                                            return (data.success ? "Message Sent!" : "Oops! Our messaging is down right now.  Try contacting us directly please!");
                                        }
                                    }
                                });
                                
                            });
                        }
                    },
                    function () {
                        // dismissed
                    });

        };

        $scope.start = function (listingSource, listingType, listingID, address) {
            $scope.listingSource = listingSource;
            $scope.listingType = listingType;
            $scope.listingID = listingID;
            $scope.address = address;
            
            google.maps.event.addDomListener(window, 'load', $scope.initMap);
            
            $scope.initSliders();

            $scope.messageParent();
        };

    };
    
    var contactAgentModal = function ($scope, $uibModalInstance, parentController) {
        $scope.name = null;
        $scope.phone = null;
        $scope.email = null;
        $scope.message = null;
        
        $scope.send = function () {
            
            var contactInfo = {
                name: $scope.name,
                phone: $scope.phone,
                email: $scope.email,
                message: $scope.message
            };
            
            $uibModalInstance.close({
                contactInfo: contactInfo
            });
        };
        
        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
        
    };
    
    var messageConfirmationModal = function ($scope, $uibModalInstance, message) {
        $scope.message = message;

        $scope.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
        
    };

    angular.module('getrealt', ['getrealt.rest', 'ui.bootstrap'])
        .factory('eventFactory', ['$rootScope', eventFactory])
        .service('listingService', ['$q', '$http', 'restService', listingService])
        .controller('searchWidget', ['$scope', '$timeout', 'eventFactory', 'listingService', searchWidget])
        .controller('listingsWidget', ['$scope', 'eventFactory', listingsWidget])
        .controller('listingDetails', ['$scope', '$uibModal', '$timeout', 'listingService', listingDetails])
        .controller('contactAgentModal', ['$scope', '$uibModalInstance', 'parentController', contactAgentModal])
        .controller('messageConfirmationModal', ['$scope', '$uibModalInstance', 'message', messageConfirmationModal])
        .directive('ngEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if (event.which === 13) {
                        scope.$apply(function () {
                            scope.$eval(attrs.ngEnter, { 'event': event });
                        });

                        event.preventDefault();
                    }
                });
            };
        });

})();


