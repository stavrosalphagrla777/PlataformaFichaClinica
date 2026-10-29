<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'rol' => $this->roles->first()?->name,
            'nivel_educativo' => $this->nivel_educativo,
            'curso' => $this->curso,
            'activo' => (bool) $this->activo,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
