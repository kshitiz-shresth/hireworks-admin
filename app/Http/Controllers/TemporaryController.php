<?php

namespace App\Http\Controllers;

use App\Models\DataRow;
use Illuminate\Http\Request;

class TemporaryController extends Controller
{
    //
    public function postData(Request $request){
        $data = new DataRow();
        $data->data_type_id = $request->data_type_id;
        $data->field = $request->field;
        $data->type = $request->type;
        $data->display_name = $request->display_name;
        $data->required = $request->required ? 1 : 0;
        $data->browse = $request->browse ? 1 : 0;
        $data->read = $request->read ? 1 : 0;
        $data->edit = $request->edit ? 1 : 0;
        $data->add = $request->add ? 1 : 0;
        $data->delete = $request->delete ? 1 : 0;
        $data->order = $request->order;
        $data->save();
        return 'done';
    }
}
