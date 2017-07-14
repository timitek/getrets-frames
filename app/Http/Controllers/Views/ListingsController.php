<?php

namespace App\Http\Controllers\Views;

use GetRETS;
use Illuminate\Http\Request;
use App\Http\Controllers\Views\Models\PageParams;

class ListingsController extends ViewController
{
    private $request = null;
    private $theme = 'sandstone';
    private $linkTarget = '_blank';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->theme = (isset($this->request->theme) ? $this->request->theme : $this->theme);
        $this->linkTarget = (isset($this->request->linkTarget) ? $this->request->linkTarget : $this->linkTarget);
        // $this->middleware('subscribed');
    }

    /**
     * Returns an array of the search parameters specified on the request
     *
     * @return array
     */
    private function getParams() {
        $searchParams = [];

        if (isset($this->request->advancedSearch)) {
            $searchParams['advancedSearch'] = boolval($this->request->advancedSearch);
        }

        if (isset($this->request->keywords)) {
            $searchParams['keywords'] = $this->request->keywords;
        }

        if (isset($this->request->minPrice)) {
            $searchParams['minPrice'] = intval(str_replace(',', '', $this->request->minPrice));
        }

        if (isset($this->request->maxPrice)) {
            $searchParams['maxPrice'] = intval(str_replace(',', '', $this->request->maxPrice));
        }

        if (isset($this->request->includeResidential)) {
            $searchParams['includeResidential'] = boolval($this->request->includeResidential);
        }

        if (isset($this->request->includeLand)) {
            $searchParams['includeLand'] = boolval($this->request->includeLand);
        }

        if (isset($this->request->includeCommercial)) {
            $searchParams['includeCommercial'] = boolval($this->request->includeCommercial);
        }

        if (isset($this->request->propertyType)) {
            $searchParams['includeCommercial'] = false;
            $searchParams['includeLand'] = false;
            $searchParams['includeResidential'] = false;
            
            switch (strtolower($this->request->propertyType)) {
                case 'commercial':
                    $searchParams['includeCommercial'] = true;
                    break;
                case 'land':
                    $searchParams['includeLand'] = true;
                    break;
                case 'residential':
                default:
                    $searchParams['includeResidential'] = true;
                    break;
            }
        }

        if (isset($this->request->beds)) {
            $searchParams['beds'] = floatval($this->request->beds);
        }

        if (isset($this->request->baths)) {
            $searchParams['baths'] = floatval($this->request->baths);
        }
        
        $searchParams['showSearchBox'] = (count($searchParams) <= 0);

        return $searchParams;
    }

    /**
     * Display the default listings page.
     *
     * @return Response
     */
    public function all() {
        $params = new PageParams();
        $params->setStartupParameters([
                    $this->getParams(),
                ]);
                
        return view('listings.all', [
                'theme' => $this->theme, 
                'linkTarget' => $this->linkTarget,
                'params' => $params
            ]);
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
        
        return view('listings.show', [
                'theme' => $this->theme, 
                'linkTarget' => $this->linkTarget,
                'listing' => $listing, 
                'headerImage' => $headerImage
            ]);
    }
}
