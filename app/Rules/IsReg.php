<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Head;

class IsReg implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $head; 

    public function __construct($head)
    {
        $this->head = $head;
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
       $head = $this->head;
       $cc = Head::where('reg',$value);
       if($cc->exists())
       {
           $val = $cc->first();
            return $val->id == $head->id ? true : false;
       }
       else
       {
            return true;
       }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Field Sudah ada';
    }
}
