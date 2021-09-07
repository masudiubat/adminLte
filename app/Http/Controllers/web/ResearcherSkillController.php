<?php

namespace App\Http\Controllers\web;

use App\Skill;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ResearcherSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = Skill::orderBy('id', 'DESC')->get();
        ActivityLogLib::addLog('User has viewed researcher skill list successfully.', 'success');
        return view('pages.skill.index', ['skills' => $skills]);
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
            'name' => 'required | unique:skills'
        ], [
            'name.required' => 'Skill name is required.',
            'name.unique' => 'Skill name is already registered'
        ]);

        $skill = new Skill();

        if ($request->has('name')) {
            $skill->name = strtolower($request->input('name'));
            $skill->slug = Str::slug($request->input('name'));
        }

        $skill->created_at = date('Y-m-d');
        $storeSkill = $skill->save();

        if ($storeSkill) {
            ActivityLogLib::addLog('User has created a new skill named ' . $skill->name . ' successfully.', 'success');
            Toastr::success('New skill named ' . $skill->name . ' has created successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to create new skill but failed.', 'error');
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
        $skill = Skill::findOrFail($id);
        $createView = view('pages.skill._edit', ['skill' => $skill])->render();
        return (['editSkill' => $createView]);
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
        $skill = Skill::findOrFail($id);

        $this->validate($request, [
            'name' => 'required | unique:skills,name,' . $skill->id
        ], [
            'name.required' => 'Skill name is required.',
            'name.unique' => 'Skill name is already registered'
        ]);

        if ($request->has('name')) {
            $skill->name = strtolower($request->input('name'));
            $skill->slug = Str::slug($request->input('name'));
        }

        $skill->updated_at = date('Y-m-d');
        $updateSkill = $skill->save();

        if ($updateSkill) {
            ActivityLogLib::addLog('User has updated skill named ' . $skill->name . ' successfully.', 'success');
            Toastr::success('Skill named ' . $skill->name . ' has updated successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to updated skill but failed.', 'error');
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
        $skill = Skill::findOrFail($id);
        $skillName = $skill->name;
        $delete = $skill->delete();

        if ($delete) {
            ActivityLogLib::addLog('User has deleted skill named ' . $skillName . ' successfully.', 'success');
            Toastr::success('Skill has deleted successfully.', 'success');
            return redirect()->back();
        } else {
            ActivityLogLib::addLog('User has tried to delete' . $skillName . ' skill but failed.', 'error');
            Toastr::error('W00ps! Something went wrong. Try again.', 'error');
            return redirect()->back();
        }
    }
}
