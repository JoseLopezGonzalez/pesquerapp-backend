<?php

namespace App\Sanctum;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use App\Traits\UsesTenantConnection;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use UsesTenantConnection;
}
