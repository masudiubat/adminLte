<?php

namespace App\Http\Controllers\web;

use App\Scope;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ScopeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scopes = Scope::all();
        return view('pages.scope.index', ['scopes' => $scopes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required | unique:scopes'
        ], [
            'name.required' => 'Scope name is required.',
            'name.unique' => 'Scope name is already registered'
        ]);

        $scope = new Scope();

        if ($request->has('name')) {
            $scope->name = strtolower($request->input('name'));
        }

        $scope->created_at = date('Y-m-d');
        $storeScope = $scope->save();

        if ($storeScope) {
            ActivityLogLib::addLog('User has created a new scope named ' . $scope->name . ' successfully.', 'success');
            Toastr::success('New scope named ' . $scope->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new scope but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $scope = Scope::findOrFail($id);
        $createView = view('pages.scope._edit', ['scope' => $scope])->render();
        return (['editScope' => $createView]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $scope = Scope::findOrFail($id);

        $this->validate($request, [
            'name' => 'required | unique:scopes,name,' . $scope->id
        ], [
            'name.required' => 'Scope name is required.',
            'name.unique' => 'Scope name is already registered'
        ]);

        if ($request->has('name')) {
            $scope->name = strtolower($request->input('name'));
        }

        $scope->updated_at = date('Y-m-d');
        $updateScope = $scope->save();

        if ($updateScope) {
            ActivityLogLib::addLog('User has updated scope named ' . $scope->name . ' successfully.', 'success');
            Toastr::success('Scope named ' . $scope->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to update scope but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $scope = Scope::findOrFail($id);
        $scopeName = $scope->name;
        $deleteScope = $scope->delete();

        if ($deleteScope) {
            ActivityLogLib::addLog('User has deleted scope named ' . $scopeName . ' successfully.', 'success');
            Toastr::success('Scope named ' . $scopeName . ' has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete scope but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
