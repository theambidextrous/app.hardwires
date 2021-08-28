<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Series;
use App\Models\Section;
use App\Models\Question;
use App\Models\Option;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Config;

class SectionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function section_prev($sectionId)
    {
        try {
            $sectionMeta = Section::find($sectionId);
            if(is_null($sectionMeta))
            {
                return response([
                    'error' => 'none of this even exists',
                ],404);
            }
            return view('section_prev', [
                'sectionMeta' => $sectionMeta,
                'customerHash' => 'ghghghghghghgh',
                'questions' => $this->findSectionQuestions($sectionId),
            ]);
        } catch (\Throwable $th) {
            return response([
                'error' => 'System could not load your assessment. Try again later or contact ' . Config::get('mail.from.address') . ' for assistance',
            ],404);
        }
    }
    protected function findSectionQuestions($sectionId)
    {
        $data = Question::selectRaw("id, name, description, section, number, is_check, is_active, created_at, updated_at, CAST(number as SIGNED) AS q_number")
        ->where('section', $sectionId)
        ->orderBy('q_number', 'asc')
        ->orderBy('number', 'asc')
        ->get();
        if( is_null($data) ) { return []; }
        return $this->formatQuestions($data->toArray());
    }
    protected function formatQuestions($data)
    {
        $rtn = [];
        foreach ($data as $_data) {
           $_data['options'] = $this->findQuestionOptions($_data['id']);
           array_push($rtn, $_data);
        }
        return $rtn;
    }
    protected function findQuestionOptions($id)
    {
        $data = Option::where('question', $id)->orderBy('id', 'asc')->get();
        if( is_null($data) ) { return []; }
        return $data->toArray();
    }
    public function sections($seriesId = null)
    {
        return view('sections', [
            'sections' => $this->findSectionsBySeries($seriesId),
            'thisItem' => Series::find($seriesId),
        ]);
    }
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:55'],
                'series' => ['required', 'string', 'max:10'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            $data = $request->all();
            if(!$data['series'])
            {
                throw new \Exception("Series ID cannot be set to zero");
            }
            Section::create($data);
            return redirect()->back()->with(['suc' => 'Created successfully!']);
        } catch (\Throwable $e) {
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    protected function findSectionsBySeries($seriesId)
    {
        $data = Section::where('series', $seriesId)->get();
        if(is_null($data)) { return []; }
        return $data->toArray();
    }

}
