<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge($this->article->toArrayAssoc() , [
            'species' => $this->species->toArrayAssoc(),
            'captureZone' => $this->captureZone->toArrayAssoc(),
            'articleGtin' => $this->article_gtin,
            'boxGtin' => $this->box_gtin,
            'palletGtin' => $this->pallet_gtin,
            'fixedWeight' => $this->fixed_weight,
        ]);
    }

    /**
     * Customize the response before sending.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\JsonResponse  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setData($this->resource->toArray($request));
    }
}
