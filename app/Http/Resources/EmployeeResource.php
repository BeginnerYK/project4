<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName'=> $this->last_name,
            'address'=> $this->address,
            'countryId'=> $this->country_id,
            'stateId'=> $this->state_id,
            'cityId'=> $this->city_id,
            'departmentId'=> $this->department_id,
            'zip_code'=> $this->zip_code,
            'birthDate'=> $this->birth_date,
        ];
    }
}
