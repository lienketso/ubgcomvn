<?php

namespace Botble\Stock\Http\Requests;

use Botble\Stock\Enums\ContractStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ContractRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required',
            // 'percentage'  => 'integer|min:0|max:100|required',
            // 'price_per_stock'  => 'integer|required',
            // 'qty_of_stock'  => 'integer|required',
            // 'cp_category_id'  => 'integer|required',
            // 'status' => Rule::in(PackageStatusEnum::values()),
        ];
    }
}
