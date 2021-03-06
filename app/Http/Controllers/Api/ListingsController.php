<?php

namespace App\Http\Controllers\Api;

use Timitek\GetRETS\GetRETS;
use Illuminate\Http\Request;

class ListingsController extends ApiController
{

    private $getRETS = null;

    /**
     * Create a new ApiController reference
     * 
     * @param Request $request The request object is injected for use in 
     * determining constraints of the request, such as the format the be used 
     * for the response
     */
    public function __construct(Request $request) {
        parent::__construct($request);

        $customerKey = (isset($this->request->customerKey) ? $this->request->customerKey : config('getrets.customer_key'));

        $this->getRETS = new GetRETS($customerKey);
    }

    public function imageUrl($listingSource, $listingType, $listingId, $photoId, $width = null, $height = null) {
        $img = $this->getUrl() . '/api/' . $this->getCustomerKey() . '/' . $this->getSearchType() . '/Image/' . $listingSource . '/' . $listingType . '/' . $listingId . '/' . $photoId;
        if ($width) {
            $img .= '?newWidth=' . $width . '&maxHeight=' . $height;
        }
        return $img;
    }

    private function addThumbnails(array &$listings) {
        foreach ($listings as &$listing) {
            $listing->thumbnail = $this->getRETS->getListing()
                    ->imageUrl($listing->listingSourceURLSlug, $listing->listingTypeURLSlug, $listing->listingID, 0);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $output = $this->verifyProvidedInput(['keywords' => 'Keywords are required to perform a search']);

        if (empty($output)) {
            $preparedKeywords = htmlspecialchars($this->request->keywords);
            if (!empty($this->request->advancedSearch) && $this->request->advancedSearch) {
                $sortBy = (isset($this->request->sortBy) ? $this->request->sortBy : 'rawListPrice');
                $reverseSort = (isset($this->request->reverseSort) ? boolval($this->request->reverseSort) : false);
                $data = $this->getRETS->getListing()
                        ->setSortBy($sortBy)
                        ->setReverseSort($reverseSort)
                        ->search($preparedKeywords, 
                                 $this->request->extra, 
                                 $this->request->maxPrice, 
                                 $this->request->minPrice, 
                                 $this->request->beds, 
                                 $this->request->baths, 
                                 $this->request->includeResidential, 
                                 $this->request->includeLand, 
                                 $this->request->includeCommercial);
                
                $this->addThumbnails($data);
                $output = $this->respondData($data);
            } else {
                $preparedKeywords = htmlspecialchars($this->request->keywords);
                $data = $this->getRETS->getListing()->searchByKeyword($preparedKeywords);
                $this->addThumbnails($data);
                $output = $this->respondData($data);
            }
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }
}
