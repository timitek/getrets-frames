(function () {
    'use strict';


    var restService = function ($q, $http) {

        var self = this;

        self.getOverride = function (defaultValue, overrideValue) {
            var newValue = ((typeof overrideValue === 'undefined') ? defaultValue : overrideValue);
            return newValue; // Leave the meaningless newValue in here for breakpoint / watch purposes
        };

        self.applyOptions = function (options) {
            var params = {
                maxRetries: 3,
                enableCache: true,
                method: 'GET',
                responseType: 'json'
            };

            if (options) {
                params.maxRetries = self.getOverride(params.maxRetries, options.maxRetries);
                params.enableCache = self.getOverride(params.enableCache, options.enableCache);
                params.method = self.getOverride(params.method, options.method);
                params.responseType = self.getOverride(params.responseType, options.responseType);
                params.headers = self.getOverride(params.headers, options.headers);
                params.transformRequest = self.getOverride(params.transformRequest, options.transformRequest);
            }

            // GET uses params and POST uses data
            if (params.method === 'GET') {
                params.params = options.params;
            } else {
                params.data = options.params;
            }

            params.url = options.url;

            return params;
        };

        self.allowCaching = function (params) {
            // TODO: We haven't implemented this yet, but we will do some client side caching
            var allow = params.enableCache;

            if (allow) {
                // Check to see if this is a cacheable verb (GET) that returns results that can be cached              
                // Check to see if local storage / caching is available on this client

                allow = false; // Right now disable it until we have implemented caching
            }

            return allow;
        };

        self.cacheResults = function (params, results) {
            // TODO: We haven't implemented this yet, but we will do some client side caching

            var deferred = $q.defer();

            if (self.allowCaching(params)) {
                // TODO: Finish this                
            } else {
                deferred.resolve(null);
            }

            return deferred.promise;
        };

        self.fetchFromCache = function (params) {
            // TODO: We haven't implemented this yet, but we will do some client side caching

            var deferred = $q.defer();

            if (self.allowCaching(params)) {
                // TODO: Finish this                
            } else {
                deferred.resolve(null);
            }

            return deferred.promise;
        };


        self.process = function (params) {
            var deferred = $q.defer();

            self.fetchFromCache(params)
                    .then(function (results) {
                        if (results) {
                            // We got cached results, we are done
                            deferred.resolve(results);
                        } else {
                            var httpOptions = {
                                url: params.url,
                                method: params.method,
                                responseType: params.responseType,
                                data: params.data,
                                params: params.params
                            };
                            
                            if (params.headers) {
                                httpOptions.headers = params.headers;
                            }
                            
                            if (params.transformRequest) {
                                httpOptions.transformRequest = params.transformRequest;
                            }
                            
                            $http(httpOptions)
                            .then(function (results) {
                                if (results && params.enableCache) {
                                    self.cacheResults(params, results.data);
                                }                                
                                deferred.resolve(results.data);
                            }, function (data, status, headers, config) {
                                deferred.reject({data: data, status: status, headers: headers, config: config});
                            });
                        }
                    });

            return deferred.promise;
        };

        // Main entry point and should typically be the only method called externally
        self.go = function (options) {

            var deferred = $q.defer();

            var params = self.applyOptions(options);

            // Polly is used for transient exception handling
            // We will make this more sophisticated
            polly()
                .retry(self.maxRetries)
                .executeForPromise(function () {
                    return self.process(params);
                })
                .then(function (results) {
                    deferred.resolve(results);
                }, function (err) {
                    deferred.reject(err.data);
                    if (err.data && err.data.status === 401) {
                        var r = confirm("You are not authorized to perform this action.  It's possible your session has timed out.  Would you like to try logging back in?");
                        if (r === true) {
                            window.location.reload(true);
                        }                                    
                    }
                    else {
                        alert("There was an error!");
                    }
                });

            return deferred.promise;
        };

    };
    
    angular.module('getrealt.rest', [])
            .service('restService', ['$q', '$http', restService]);

})();