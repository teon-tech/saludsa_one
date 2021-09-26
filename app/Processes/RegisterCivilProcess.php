<?php

namespace App\Processes;

use App\Services\RegisterCivilService;
use App\Validators\RegisterCivilValidator;

class RegisterCivilProcess
{

    /**
     * @var RegisterCivilService
     */
    private $registerCivilService;

    public function __construct(RegisterCivilValidator $registerCivilValidator,
        RegisterCivilService $registerCivilService) {

        $this->registerCivilValidator = $registerCivilValidator;
        $this->registerCivilService = $registerCivilService;
    }

    public function findUserByCI($request)
    {
        $input = $request->all();
        $this->registerCivilValidator->findUserByCI($input);
        $numeroCedula = $input['numeroCedula'];
        $data = $this->registerCivilService->findUserByCI($numeroCedula);
        $user = collect($data['Datos']);
        return $user;
    }

}
