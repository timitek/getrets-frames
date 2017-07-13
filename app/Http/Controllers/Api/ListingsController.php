<?php

namespace App\Http\Controllers\Api;

use GetRETS;
use Illuminate\Http\Request;

class ListingsController extends ApiController
{
    public function imageUrl($listingSource, $listingType, $listingId, $photoId, $width = null, $height = null) {
        $img = $this->getUrl() . '/api/' . $this->getCustomerKey() . '/' . $this->getSearchType() . '/Image/' . $listingSource . '/' . $listingType . '/' . $listingId . '/' . $photoId;
        if ($width) {
            $img .= '?newWidth=' . $width . '&maxHeight=' . $height;
        }
        return $img;
    }

    private function addThumbnails(array &$listings) {
        foreach ($listings as &$listing) {
            $listing->thumbnail = GetRETS::getListing()
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
                $data = GetRETS::getListing()
                        ->search($preparedKeywords, $this->request->maxPrice, $this->request->minPrice, $this->request->includeResidential, $this->request->includeLand, $this->request->includeCommercial);
                
                if ($this->request->beds) {
                    $beds = $this->request->beds;
                    $data = collect($data)->filter(function ($value, $key) use ($beds) {
                        return $value->beds >= $beds;
                    })->values()->all();
                }
                
                if ($this->request->baths) {
                    $baths = $this->request->baths;
                    $data = collect($data)->filter(function ($value, $key) use ($baths) {
                        return $value->baths >= $baths;
                    })->values()->all();
                }

                $this->addThumbnails($data);
                $output = $this->respondData($data);
            } else {
                $preparedKeywords = htmlspecialchars($this->request->keywords);
                $data = GetRETS::getListing()->searchByKeyword($preparedKeywords);
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
