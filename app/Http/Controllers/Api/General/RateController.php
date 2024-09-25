<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rate'        => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'images.*'    => 'image|mimes:jpg,jpeg,png,bmp|max:2048',
        ]);
        $user = Auth::user();
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $ext = $image->getClientOriginalExtension();
                $name = 'rate-' . uniqid() . '.' . $ext;
                $image->move(public_path('uploads/rate'), $name);
                $imagePaths[] = asset('uploads/rate/' . $name);
            }
        }
        $rate = Rate::create([
            'rate'        => $request->rate,
            'title'       => $request->title,
            'description' => $request->description,
            'images'      => $imagePaths,
            'product_id'  => $productId,
            'user_id'     => $user->id,
        ]);
        return new RateResource($rate);
    }
    public function getRatesByProduct($productId)
    {
        $rates = Rate::where('product_id', $productId)->with('user')->get();
        return RateResource::collection($rates);
    }
}
