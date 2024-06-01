<?php

namespace App\Traits;


use Illuminate\Validation\Validator;

trait OpenModalValidation
{
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->any()) {
                    if ($this->modalName == 'edit') {
                        $validator->errors()->add(
                            'id',
                            request()->route('id')
                        );
                    }

                    $validator->errors()->add(
                        $this->modalName,
                        true
                    );
                }


            }
        ];
    }
}
