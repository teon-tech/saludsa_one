<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class RegisterCivilValidator extends BaseValidator
{

    public function findUserByCI($input)
    {
        $messages = [
            'numeroCedula.ecuador' => 'Identificación no es válida.',
        ];

        $rules = [
            'numeroCedula' => "required|ecuador:ci|max:20",
        ];
        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        $this->validate($input, $rules, $messages);
    }

}
