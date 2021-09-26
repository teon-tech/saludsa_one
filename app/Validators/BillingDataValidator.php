<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * Class ShippingAddressValidator
 * @package App\Validators
 */
class BillingDataValidator extends BaseValidator
{

    /**
     * @param $input
     * @param null $id
     * @return bool
     */
    function validateStore($input, $id = null)
    {
        $messages = [
            'document.ecuador' => 'Identificación no es válida.'
        ];
        $document_type = isset($input['document_type']) ? $input['document_type'] : 'CI';
        switch ($document_type) {
            case 'CI':
                $ruleDocument = "nullable|ecuador:ci|max:20";
                break;
            case 'RUC':
                $ruleDocument = "nullable|ecuador:ruc,ruc_spub,ruc_spriv|max:20";
                break;
            default:
                $ruleDocument = "nullable|max:20";
                break;
        }
        $rules = [
            'nickname' => 'required|max:45|unique:billing_data,nickname,' . ($id ? "$id,id" : 'null,id') . ',customer_id,'
                . (isset($input['customer_id']) ? $input['customer_id'] : '') . ',deleted_at,NULL',
            'name' => 'required|max:128',
            'email' => 'nullable|email',
            'document_type' => 'required|in:CI,PASSPORT,RUC',
            'cellphone_number' => 'required|max:20',
            'address' => 'required|max:128',
            'birth_date' => 'required|date_format:Y-m-d',
            'default' => 'required|in:YES,NO',
            'customer_id' => 'required|exists:customer,id',
            'document' => $ruleDocument
        ];
        $validator = Validator::make(
            $input,
            $rules,
            $messages
        );

        if ($validator->fails()) {
            $this->responseFail($validator->errors()->toArray());
        }
        return true;
    }

}