<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index () {
        $data = Shift::all();
        return view('page.superadmin.shift.index',compact('data'));
    }

    public function manage($id = null){
        $data = $id ? Shift::findOrFail($id) : new Shift();
        return view('page.superadmin.shift.manage', compact('data'));
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            ]);
            $data = new Shift();
            $data->name = $request->name;
            $data->start_time = $request->start_time;
            $data->end_time = $request->end_time;
            $data->save();
            return redirect()->route('admin.shift')->with('success', 'Shift created successfully');
            }
    public function update(Request $request, $id){
    $request->validate([
                    'name' => 'required',
                    'start_time' => 'required',
                    'end_time' => 'required',
                    ]);
                    $data = Shift::findOrFail($id);
                    $data->name = $request->name;
                    $data->start_time = $request->start_time;
                    $data->end_time = $request->end_time;
                    $data->save();
                    return redirect()->route('admin.shift')->with('success', 'Shift updated successfully');
                    }
    public function destroy($id){
    $data = Shift::findOrFail($id);
    $data->delete();
    return redirect()->route('admin.shift')->with('success', 'Shift deleted successfully');

}

}
