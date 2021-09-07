<?php

namespace App\Library;

use App\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as MethodRequest;

class ActivityLogLib
{

    public static function addLog($description, $status)
    {
        ActivityLog::create(array(
            'user_id' => (Auth::user()) ? Auth::user()->id : 'unknown',
            'user_name' => (Auth::user()) ? Auth::user()->name : 'unknown',
            'request_method' => MethodRequest::method(),
            'request_route' => MethodRequest::url(),
            'request_ip' => MethodRequest::ip(),
            'request_description' => $description,
            'request_status' => $status,
            'request_date' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ));
    }
}
