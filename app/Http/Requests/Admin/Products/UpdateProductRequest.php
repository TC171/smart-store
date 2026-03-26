<?php

namespace App\Http\Requests\Admin\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Nếu có policy, có thể check ở đây
    }

    public function rules(): array
    {
        $productId = $this->route('product'); // lấy ID sản phẩm từ route

        return [
            // Thông tin cơ bản
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($productId),
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],

            // Mô tả
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],

            // Hình ảnh
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],

            // Thông số kỹ thuật
            'warranty_months' => ['nullable', 'integer', 'min:0', 'max:120'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],

            // Trạng thái
            'is_featured' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'status' => ['nullable', 'boolean'],

            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string'],

            // Biến thể (nếu muốn tạo sau cũng ok)
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', 'max:50'],
            'storages' => ['nullable', 'array'],
            'storages.*' => ['string', 'max:50'],
            'rams' => ['nullable', 'array'],
            'rams.*' => ['string', 'max:50'],

            // Giá & kho mặc định
            'default_price' => ['nullable', 'numeric', 'min:0'],
            'default_stock' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            'slug.unique' => 'Slug đã tồn tại, vui lòng đổi tên khác',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'brand_id.required' => 'Vui lòng chọn thương hiệu',
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif, webp',
            'default_price.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'default_stock.min' => 'Tồn kho phải lớn hơn hoặc bằng 0',
            'colors.*.max' => 'Tên màu quá dài',
            'storages.*.max' => 'Dung lượng quá dài',
            'rams.*.max' => 'RAM quá dài',
        ];
    }
}