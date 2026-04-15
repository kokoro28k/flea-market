<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
        return [
            'name' => 'required|string',
            'brand' => 'nullable|string',
            'description' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png',
            'category_id' => 'required|array',
            'category_id.*' => 'integer|exists:categories,id',
            'condition' => 'required',
            'price' => 'required|integer|min:1',
        ];
    }

    
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image_path.required' => '商品画像を登録してください',
            'image_path.mimes' => '商品画像は、jpegもしくはpng形式で登録してください',
            'category_id.required' => '商品のカテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は整数で入力してください',
            'price.min' => '商品価格は0円以上で入力してください'
        ];
    }
}
