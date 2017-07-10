<?php

namespace App\Http\Controllers\Views;

use GetRETS;
use Illuminate\Http\Request;

class ListingsController extends ViewController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('subscribed');
    }

    /**
     * Display the default listings page.
     *
     * @return Response
     */
    public function all() {
        return view('listings.all');
    }

    /**
     * Display the specified listing.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $values = explode("_", $id);
        $listingSource = $values[1];
        $listingType = $values[0];
        $listingId = $values[2];
        
        $listing = GetRETS::getListing()->details($listingSource, $listingType, $listingId);

        $headerImage = null;
        if ($listing->photoCount) {
            $randomPhoto = rand(0, $listing->photoCount - 1);
            $headerImage = GetRETS::getListing()->imageUrl($listingSource, $listingType, $listingId, $randomPhoto, 1400, 1400);
        }
                
        if (empty($listing)) {
            abort(404);
        }
        
        return view('listings.show', ['listing' => $listing, 'headerImage' => $headerImage]);
    }
}
