<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\CategoryPolyclinic;
use Illuminate\Http\Request;

class CategoryPolyclinicController extends Controller
{
    public function getCategoryPolyclinic(Request $request)
    {
        $id = $request->input('id');
        $category_polyclinic = $request->input('category_polyclinic');

        if($id) {
            $categoryPoly = CategoryPolyclinic::find($id);

            if($categoryPoly) {
                return ResponseFormmater::success(
                    $categoryPoly,
                    'Data Category Polyclinic Berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Data Category Polyclinic tidak ada',
                    404
                );
            }
        }

        $categoryPoly = CategoryPolyclinic::query();

        if($category_polyclinic) {
            $categoryPoly->where('category_polyclinic', 'like', '%' . $category_polyclinic . '%');
        }

        return ResponseFormmater::success(
            $categoryPoly->paginate(10),
            'Data List Category Polyclinic Berhasil diambil'
        );
    }

}
