<?php

namespace App\Http\Controllers\Api\Address;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $addresses = Address::where('user_id', $user->id)->get();
        return response()->json([
            'status' => true,
            'message' => 'Addresses retrieved successfully',
            'data' => $addresses,
        ], 200);
    }
    public function store(Request $request)
    {
        $data              = $request->validate([
            'name'         => 'required|string',
            'phone'        => 'required|string',
            'street'       => 'required|string',
            'landmark'     => 'nullable|string',
            'area'         => 'required|string',
            'country'      => 'required|string',
            'flat_no'      => 'required|string',
            'address_type' => 'required|in:home,office',
        ]);
        $data['user_id'] = auth()->id();
        $data['is_default'] = 0;
        $address = Address::create($data);
        return response()->json([
            'status' => true,
            'message' => 'Address created successfully',
            'data' => $address,
        ], 201);
    }
    public function setDefault($id)
    {
        $user = auth()->user();
        Address::where('user_id', $user->id)->update(['is_default' => 0]);
        $address = Address::where('user_id', $user->id)->findOrFail($id);
        $address->update(['is_default' => 1]);
        return response()->json([
            'status' => true,
            'message' => 'Default address updated successfully',
            'data' => $address,
        ], 200);
    }
}
