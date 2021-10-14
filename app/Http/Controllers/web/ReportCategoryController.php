<?php

namespace App\Http\Controllers\web;

use App\ReportCategory;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ReportCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ReportCategory::orderBy('id', 'DESC')->get();
        ActivityLogLib::addLog('User has viewed report category list successfully.', 'success');
        return view('pages.report-category.index', ['categories' => $categories]);
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
            'name' => 'required | unique:report_categories'
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'Category name is already registered'
        ]);

        $category = new ReportCategory();

        if ($request->has('name')) {
            $category->name = $request->input('name');
        }

        if ($request->has('description')) {
            $category->description = $request->input('description');
        }

        if ($request->has('reference')) {
            $category->cwe_cve_reference = $request->input('reference');
        }

        $category->created_at = date('Y-m-d');
        $storeCategory = $category->save();

        if ($storeCategory) {
            ActivityLogLib::addLog('User has created a new category named ' . $category->name . ' successfully.', 'success');
            Toastr::success('New skill named ' . $category->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new category but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }

    /**
     * Show specific category details
     */
    public function show($id)
    {
        $category = ReportCategory::findOrFail($id);
        $createView = view('pages.report-category._show', ['category' => $category])->render();
        return (['showCategory' => $createView]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = ReportCategory::findOrFail($id);
        $createView = view('pages.report-category._edit', ['category' => $category])->render();
        return (['editCategory' => $createView]);
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
        $category = ReportCategory::findOrFail($id);

        $this->validate($request, [
            'name' => 'required | unique:report_categories,name,' . $category->id
        ], [
            'name.required' => 'Category name is required.',
            'name.unique' => 'Category name is already registered'
        ]);

        if ($request->has('name')) {
            $category->name = $request->input('name');
        }

        if ($request->has('description')) {
            $category->description = $request->input('description');
        }

        if ($request->has('reference')) {
            $category->cwe_cve_reference = $request->input('reference');
        }

        $category->updated_at = date('Y-m-d');
        $updateCategory = $category->save();

        if ($updateCategory) {
            ActivityLogLib::addLog('User has updated category named ' . $category->name . ' successfully.', 'success');
            Toastr::success('Category named ' . $category->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to updated category but failed.', 'error');
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
        //
    }
}
