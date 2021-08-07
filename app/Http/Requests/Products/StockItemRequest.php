<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StockItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        } elseif ($this->isMethod('put')) {
            return $this->updateRules();
        }
    }
    /**
     * Define validation rules to store method for resource creation
     *
     * @return array
     */
    public function createRules(): array
    {
        return [
            'item_id' => 'required|string',
            'item_in' => 'required|integer',
            'item_out' => 'required|integer',
            'status' => 'required|integer',
            'expired_date' => 'required',
        ];
    }

    /**
     * Define validation rules to update method for resource update
     *
     * @return array
     */
    public function updateRules(): array
    {
        return [
          'item_id' => 'required|string',
          'item_in' => 'required|integer',
          'item_out' => 'required|integer',
          'status' => 'required|integer',
          'expired_date' => 'required',
        ];
    }
}
