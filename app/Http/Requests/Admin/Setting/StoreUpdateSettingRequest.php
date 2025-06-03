<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateSettingRequest extends FormRequest
{
    private const REQUIRED_STRING_RULE = 'required|string|max:255';

    public function rules(): array
    {
        $requestKeys = array_keys($this->except(['_token', '_method']));
        return $this->getRules(columns:  $requestKeys);
    }

    public function columnsRules(): array
    {
        return [
            "name"      => 'required|array',
            "name.*"    => self::REQUIRED_STRING_RULE.'|min:3',
            "phone"     => self::REQUIRED_STRING_RULE.'|digits_between:8,15',
            "whatsapp"  => self::REQUIRED_STRING_RULE.'|digits_between:8,15',
            "email"     => 'required|email:rfc,dns|max:255',
            'logo'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'no_data_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

   public function getRules(array $columns): array
    {
        $allRules = $this->columnsRules();
        return array_intersect_key($allRules, array_flip($columns));
    }


}
