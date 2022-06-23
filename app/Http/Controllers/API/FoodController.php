<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $types = $request->input('types');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $rate_from = $request->input('rate_from');
        $rate_to = $request->input('rate_to');

        if ($id) {
            $food = Food::find($id);

            if ($food)
                return ResponseFormatter::success(
                    $food,
                    'Data produk berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
        }

        $food = Food::query();

        if ($name)
            $food->where('name', 'like', '%' . $name . '%');

        if ($types)
            $food->where('types', 'like', '%' . $types . '%');

        if ($price_from)
            $food->where('price', '>=', $price_from);

        if ($price_to)
            $food->where('price', '<=', $price_to);

        if ($rate_from)
            $food->where('rate', '>=', $rate_from);

        if ($rate_to)
            $food->where('rate', '<=', $rate_to);

        return ResponseFormatter::success(
            $food->paginate($limit),
            'Data list produk berhasil diambil'
        );
    }

    // CRUD Food

    public function addFood(Request $request)
    {
        $newFood = new Food();
        $pesan = [
            'name.required'      => 'name is required',
            'description.required'    => 'description is required',
            'ingredients.required'      => 'ingredients is required',
            'price.required'      => 'price is required',
            'rate.required'      => 'rate is required',
            'types.required'      => 'types is required',
        ];
        // validasi
        $validasi = Validator::make($request->all(), [
            'name'      => "required",
            'description'    => "required",
            'ingredients'      => "required",
            'price'      => "required",
            'rate'      => "required",
            'types'      => "required",

        ], $pesan);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->responError(0, $val[0]);
        }
        // store data
        $newFood->name = $request->name;
        $newFood->description = $request->description;
        $newFood->ingredients = $request->ingredients;
        $newFood->price = $request->price;
        $newFood->rate = $request->rate;
        $newFood->types = $request->types;
        $newFood->save();

        return response()->json([
            'status' => 1,
            'pesan' => "data berhasil ditambahkan",
            'data' => $newFood,

        ], Response::HTTP_OK);
    }

    // kalo di update nya ada store image , methode nya pake post jangan pake put
    public function editFood(Request $request, $id)
    {

        $getFood = Food::where('id', $id)->first();
        if (!$getFood) {
            return $this->responError(0, "data tidak ditemukan");
        }

        // $pesan = [
        //     'name.required'      => 'name is required',
        //     'description.required'    => 'description is required',
        //     'ingredients.required'      => 'ingredients is required',
        //     'price.required'      => 'price is required',
        //     'rate.required'      => 'rate is required',
        //     'types.required'      => 'types is required',
        // ];

        // $validasi = Validator::make($request->all(), [
        //     'name'      => "required",
        //     'description'    => "required",
        //     'ingredients'      => "required",
        //     'price'      => "required",
        //     'rate'      => "required",
        //     'types'      => "required",
        // ], $pesan);

        // if ($validasi->fails()) {
        //     $val = $validasi->errors()->all();
        //     return $this->responError(0, $val[0]);
        // }

        $getFood->update([
            'name' => $request->name,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'price' => $request->price,
            'rate' => $request->rate,
            'types' => $request->types,
        ]);


        return response()->json([
            'status' => 1,
            'pesan' => "data berhasil diupdate",
            'data' => $getFood,
        ], Response::HTTP_OK);
    }


    public function deleteFood($id)
    {

        $getFood = Food::find($id);
        if (!$getFood) {
            return $this->responError(0, "data tidak ditemukan");
        }
        $getFood->delete();

        return response()->json([
            'status' => 1,
            'pesan' => "data berhasil dihapus",
        ], Response::HTTP_OK);
    }

    public function responError($status, $pesan)
    {

        return response()->json([
            'status' => $status,
            'pesan' => $pesan,
        ], Response::HTTP_NOT_FOUND);
    }
}
