@extends('layout.master')

@section('content')

<div class="container">

    <div class="section-pad"></div>


    @if (!empty($customerKey))
        <div class="row">
            <div class="col-xs-12">
                @include('listings.partials.all.search')
            </div>
        </div>
        
        @include('listings.partials.all.results')
    @else
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger" role="alert">
                    <h1>Customer Key Required!</h1>
                    <p class="lead">
                        If you do not have a customer key, you can obtain an evaluation key from <a href="http://www.timitek.com/" target="_blank">www.timitek.com</a><br />
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection
