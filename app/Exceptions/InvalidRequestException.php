<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

//邮箱验证异常
class InvalidRequestException extends Exception
{
    public function __construct(string $message="" , int $code = 400)
    {
        parent::__construct($message , $code);
    }

    public function render(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'msg'=>$this->msg,
            ] , $this->code);
        }

        return view('pages.error',[
            'msg'=>$this->message,
        ]);
    }
}
