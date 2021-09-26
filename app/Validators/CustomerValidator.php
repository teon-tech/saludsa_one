<?php

namespace App\Validators;


/**
 * Class CustomerValidator
 * @package App\Validators
 */
class CustomerValidator extends BaseValidator
{

    /**
     * @param $input
     * @param null $id
     * @return bool
     */
    function validateSave($input, $id = null)
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
            'name' => 'required|max:128',
            'last_name' => 'max:45',
            'document_type' => 'nullable|in:CI,PASSPORT,RUC',
            'civil_status' => 'nullable|in:MARRIED,SINGLE,DIVORCED,FREE_UNION,WIDOWER',
            'gender' => 'nullable|in:MALE,FEMALE',
            'email' => 'nullable|email',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'firebase_uid' => 'required|unique:customer,firebase_uid' . ($id ? ",$id,id" : ''),
            'document' => $ruleDocument
        ];


        $this->validate($input, $rules, $messages);
    }

    function validateUpdate($input, $id = null)
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
            'name' => 'required|max:128',
            'last_name' => 'max:45',
            'document_type' => 'nullable|in:CI,PASSPORT,RUC',
            'civil_status' => 'nullable|in:MARRIED,SINGLE,DIVORCED,FREE_UNION,WIDOWER',
            'gender' => 'nullable|in:MALE,FEMALE',
            'email' => 'nullable|email',
            'birth_date' => 'nullable|date_format:Y-m-d',
            'document' => $ruleDocument
        ];


        $this->validate($input, $rules, $messages);
    }

}