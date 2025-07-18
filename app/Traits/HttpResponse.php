<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait HttpResponse
{
    /**
     * Success message http response json
     *
     * @param  array|string  $data
     * @param  int  $code
     * @return JsonResponse
     */
    protected function success($data = [], $message = '', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'result' => $data,
        ], $code);
    }

    /**
     * Error message http response json
     *
     * @param  array|string  $data
     * @param  int  $code
     * @return JsonResponse
     */
    protected function error($data = [], $message = '', $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'result' => $data,
        ], $code);
    }

    /**
     * Error message http response json
     *
     * @param  \Exception  $e
     * @return JsonResponse
     */
    /*protected function log($e)
    {
        DB::rollBack();
        Log::error($e->getMessage()."\n".$e->getTraceAsString());

        return response()->json([
            'success' => false,
            'result' => $e->getMessage(),
        ], 500);
    }*/
}
