<?php

namespace App\Rules\Core;

use App\Http\Resources\Core\ErrorResource;
use App\Models\Organization;
use Illuminate\Contracts\Validation\Rule;

class LoginRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = explode("@",$value);
        if(count($value)<2){
            return false;
        }
        $org = Organization::whereNamespace($value[1])->first();
        if(!$org)
            return false;
        else
            return $org->users()->whereUsername($value[0])->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User not found';
    }
}
