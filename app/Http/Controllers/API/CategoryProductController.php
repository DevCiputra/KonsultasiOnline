<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    public function category (Request $request)
    {
        $id = $request->input('id');
        $name_category = $request->input('name_category');

        if($id) {
            $category = CategoryProduct::find($id);

            if($category) {
                return ResponseFormmater::success(
                    $category,
                    'Data Category Berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Data Category tidak ada',
                    404
                );
            }
        }

        $category = CategoryProduct::query();

        if($name_category) {
            $category->where('name_category', 'like', '%' . $name_category . '%');
        }

        return ResponseFormmater::success(
            $category->get(),
            'Data List Category Berhasil diambil'
        );
    }
}
