<?php

namespace App\Http\Resources;

use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'age' => $this->age,
            'gender' => $this->gender,
            'is_verified' => $this->is_verified,
            'code' => EmailVerificationCode::where('email', $this->email)->latest()->first()?->code ?? null,
            'avatar' => $this->getFirstMediaUrl('avatar'),
        ];
    }
}
