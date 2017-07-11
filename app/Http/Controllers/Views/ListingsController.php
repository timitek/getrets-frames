<?php

namespace App\Http\Controllers\Views;

use GetRETS;
use Illuminate\Http\Request;
use App\Http\Controllers\Views\Models\PageParams;

class ListingsController extends ViewController
{
    private $request = null;
    private $theme = 'sandstone';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->theme = (isset($this->request->theme) ? $this->request->theme : $this->theme);
        // $this->middleware('subscribed');
    }

    /**
     * Display the default listings page.
     *
     * @return Response
     */
    public function all() {
        $searchParams = [
                    'showSearchBox' => true,
                ];

        $params = new PageParams();
        $params->setStartupParameters([
                    $searchParams,
                ]);
                
        return view('listings.all', ['theme' => $this->theme, 'params' => $params]);
    }

    /**
     * Display the default listings page.
     *
     * @return Response
     */
    public function search() {
        $searchParams = [
                    'showSearchBox' => false,
                    'advancedSearch' => boolval($this->request->advancedSearch),
                    'keywords' => $this->request->keywords,
                    'minPrice' => intval(str_replace(',', '', $this->request->minPrice)),
                    'maxPrice' => intval(str_replace(',', '', $this->request->maxPrice)),
                ];

        $params = new PageParams();
        $params->setStartupParameters([
                    $searchParams,
                ]);
                
        return view('listings.all', ['theme' => $this->theme, 'params' => $params]);
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
        
        return view('listings.show', ['theme' => $this->theme, 'listing' => $listing, 'headerImage' => $headerImage]);
    }
}
