<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\IncomeResource;

class PaycheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'income_id' => $this->income_id,
            'income' => new IncomeResource($this->whenLoaded('income')),
            'amount' => $this->amount,
            'paid_on' => $this->paid_on,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
