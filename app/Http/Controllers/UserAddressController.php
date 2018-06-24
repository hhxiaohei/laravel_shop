<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    public function create()
    {
        return view('user_addresses.create_and_edit',[
            'address'=>new UserAddress()
        ]);
    }

    public function store(UserAddressRequest $request)
    {
        $request->user()->addresses()->create($request->all());
        return redirect()->route('user_addresses.index');
    }
}
