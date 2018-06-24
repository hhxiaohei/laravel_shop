<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

//500 错误 不需要展示给用户
class InternalException extends Exception
{
    protected $msgForUser;

    public function __construct(string $msg , string $msgForUser="系统内部错误" ,int $code = 500)
    {
        parent::__construct($msg , $code);
        $this->msgForUser = $msgForUser;
    }

    public function render(Request $request)
    {
        if($request->expectsJson()){
            return response()->json([
                'msg'=>$this->msgForUser
            ] , $this->code);
        }

        return view('pages.error',[
            'msg'=>$this->msgForUser
        ]);
    }
}
