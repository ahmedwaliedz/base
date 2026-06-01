<?php

namespace App\Http\Requests\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateSettingRequest extends FormRequest
{
    private const REQUIRED_STRING_RULE = 'required|string|max:255';

    public function rules(): array
    {
        return $this->getRules(columns:  array_keys($this->except(['_token', '_method'])));
    }

    public function columnsRules(): array
    {
        return [
            "name"              => 'required|array',
            "name.*"            => self::REQUIRED_STRING_RULE.'|min:3',
            "phone"             => self::REQUIRED_STRING_RULE.'|digits_between:8,15',
            "whatsapp"          => self::REQUIRED_STRING_RULE.'|digits_between:8,15',
            "email"             => 'required|email:rfc,dns|max:255',
            'logo'              => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'no_data_image'     => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'fav_icon'          => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

            // pricing data
            'app_commission'    => 'required|numeric|min:0|max:100',
            'vat_ratio'         => 'required|numeric|min:0|max:100',
            'coupon_max_ratio'  => 'required|numeric|min:0|max:99',

            // location data
            "map_desc"          => 'required|array',
            "map_desc.*"        => self::REQUIRED_STRING_RULE.'|min:3',
            'lat'               => 'required|numeric|between:-90,90',
            'lng'               => 'required|numeric|between:-180,180',

            // SMTP Settings
            'mail_mailer'       => 'nullable|string|max:255',
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|string|max:10',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|string|max:10',
            'mail_from_address' => 'nullable|email:rfc,dns|max:255',
            'mail_from_name'    => 'nullable|string|max:255',
        ];
    }

   public function getRules(array $columns): array
    {
        return array_intersect_key($this->columnsRules(), array_flip($columns));
    }
}
