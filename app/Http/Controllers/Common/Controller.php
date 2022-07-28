<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests\FormRequest;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController implements FormRequest
{
    protected $params;
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->params = $request->all();
    }

    /**
    * Return the params of request
    *
    * @return []
    */
    public function getParams($params = null)
    {
        if ($params) {
            $params = $this->request->replace($this->params)->only($params);
        } else {
            $params = $this->request->replace($this->params)->all();
        }

        foreach ($params as &$value) {
            $value = empty($value) ? null : $value;
        }
        return $params;
    }
}
