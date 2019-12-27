<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\IncomeResource;
use App\Http\Resources\BillResource;

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
            'bills' => BillResource::collection($this->whenLoaded('bills')),
            'amount' => $this->amount,
            'amount_project' => $this->amount_project,
            'paid_on' => $this->paid_on,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
