@extends('layout.master')

@section('content')

<div class="container" ng-controller="listingDetails" ng-init='start("{{ $listing->listingSourceURLSlug }}", "{{ $listing->listingTypeURLSlug }}", "{{ $listing->listingID }}", "{!! str_replace("'", "\u0027", $listing->address) !!}" )'>

    <!-- Featured Listings -->
    <div class="row">


        @if ($listing->photoCount > 0)
        <!-- Images -->
        <div class="col-md-8">

            <!-- Flexslider: Carousel -->
            <div id="carousel" class="flexslider hidden-xs hidden-sm">
                <ul class="slides">
                    @for ($i = 0; $i < $listing->photoCount; $i++)
                    <li>
                        <img class="carousel-img" src="{{ GetRETS::getListing()->imageUrl($listing->listingSourceURLSlug, $listing->listingTypeURLSlug, $listing->listingID, $i) }}" />
                    </li>
                    @endfor
                </ul>
            </div>

            <!-- Flexslider: Slider -->
            <div id="slider" class="flexslider">
                <ul class="slides">
                    @for ($i = 0; $i < $listing->photoCount; $i++)
                    <li>
                        <img class="slider-img" src="{{ GetRETS::getListing()->imageUrl($listing->listingSourceURLSlug, $listing->listingTypeURLSlug, $listing->listingID, $i) }}" />
                    </li>
                    @endfor
                </ul>
            </div>

            <hr class="visible-xs" />
            <hr class="visible-sm" />
        </div>
        @endif

        <!-- Right Side -->
        <div class="{{ $listing->photoCount > 0 ? 'col-md-4' : 'col-xs-12' }}">

            <!--
            <h5 class="listing-heading"><i class="fa fa-link"></i> <strong>ID:</strong> {{ $listing->listingID }}</h5>
            <div class="listing-actions">
                <button class="btn btn-primary" ng-click="contactAgent()">
                    <i class="fa fa-envelope"></i> Contact Agent
                </button>
            </div>
            <hr />
            -->
            
            <div class="listing-result">
                <div class="listing-result-attributes">
                    <div>
                        <span class="label label-primary">
                            @if ( strcmp("Land", $listing->listingTypeURLSlug) === 0 )
                            <i class="fa fa-tree"></i>
                            @elseif ( strcmp("Commercial", $listing->listingTypeURLSlug) === 0 )
                            <i class="fa fa-building"></i>
                            @else
                            <i class="fa fa-home"></i>
                            @endif
                            {{ $listing->listingTypeURLSlug }}
                        </span> 
                        @if ($listing->beds) 
                        <span class="label label-primary"><i class="fa fa-bed"></i> {{ $listing->beds }} Bed</span> 
                        @endif
                        @if ($listing->baths) 
                        <span class="label label-primary"><i class="fa fa-bath"></i> {{ $listing->baths }} Bath</span> 
                        @endif
                    </div>
                    @if ($listing->squareFeet || $listing->lot || $listing->acres)
                    <div class="listing-result-dimensions">
                        @if ($listing->squareFeet) 
                        <span class="listing-result-dimension">
                            <strong><abbr title="Square Feet">Sqft.:</abbr></strong> {{ $listing->squareFeet }}
                        </span>
                        @endif
                        @if ($listing->lot) 
                        <span class="listing-result-dimension">
                            <strong>Lot:</strong> {{ $listing->lot }}
                        </span>
                        @endif
                        @if ($listing->acres) 
                        <span class="listing-result-dimension">
                            <strong>Acres:</strong> {{ $listing->acres }}
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="listing-result-price">{{ $listing->listPrice }}</div>
            </div>

            <hr />
            <h5 class="listing-heading"><i class="fa fa-list-alt"></i> <strong>Remarks</strong></h5>
            <p>
                {!! $listing->description !!}
            </p>
            <hr />

            @if (!empty($listing->features))
            <h5 class="listing-heading"><i class="fa fa-star"></i> <strong>Features</strong> / Facts</h5>
            <div class="listing-features">
                @foreach($listing->features as $feature)
                <span class="label label-primary feature-{{ rand(1,5) }}">{{ $feature }}</span>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    <div id="map" style="display: none"></div>

    <div class="row listing-result">
        <div class="listing-result-provider"><strong>Provided By:</strong> {{ $listing->providedBy }}</div>
    </div>

</div>

@endsection

@section('quarx')
@edit('getrealt')
@endsection

@section('pre-javascript')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_KEY') }}"></script>
@endsection

@section('javascript')
<script type="text/ng-template" id="contactAgent.html">
@include('listings.partials.show.contactAgent')
</script>

<script type="text/ng-template" id="messageConfirmation.html">
@include('listings.partials.show.messageConfirmation')
</script>

@endsection