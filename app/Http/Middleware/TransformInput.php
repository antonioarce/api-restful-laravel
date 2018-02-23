<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInputs = [];
        foreach ($request->request->all() as $key => $value) {
            $transformedInputs[$transformer::originalAttribute($key)] = $value;
        }
        $request->replace($transformedInputs);

        $response = $next($request);


        if(isset($response->exception) && $response->exception instanceof ValidationException){
            $data = $response->getData();

            $transformedErrors = [];

            foreach ($data->error as $item => $value) {
                $transformedFIeld = $transformer::transformedAttribute($item);
                $transformedErrors[$transformedFIeld] = str_replace($item, $transformedFIeld,$value);
            }

            $data->error  = $transformedErrors;
            $response->setData($data);
        }

        return $response;
    }
}
