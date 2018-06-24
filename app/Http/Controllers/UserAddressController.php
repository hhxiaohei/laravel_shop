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

    public function edit(UserAddress $address)
    {
        $this->authorize('own',$address);

        return view('user_addresses.create_and_edit',[
            'address'=>$address
        ]);
    }

    public function update(UserAddress $address , UserAddressRequest $request)
    {
        $this->authorize('own',$address);

        $address->update($request->except('id','_token'));
        return redirect()->route('user_addresses.index');
    }

    public function destroy(UserAddress $address)
    {
        $this->authorize('own',$address);

        $address->delete();
        return [];
        //return redirect()->route('user_addresses.index');
    }
}
