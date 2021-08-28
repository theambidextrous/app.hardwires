<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

class QuestionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function manage_q($questionId)
    {
        $thisItem = Question::find($questionId);
        return view('manage_q', [
            'thisItem' => $thisItem,
            'options' => $this->findOptions($questionId),
            'sectionId' => $thisItem->section,
        ]);
    }
    public function save_q(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string',],
                // 'section' => ['required', 'string', 'max:10'],
                'number' => ['required', 'string'],
                'is_check' => ['required', 'string'],
                'option' => ['required', 'array'],
                'value' => ['required', 'array'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            // throw new \Exception("Error Processing Request", 1);
            
            $data = $request->all();
            $questionId = $data['questionId'];
            $optionArray = $data['option'];
            $valueArray = $data['value'];
            if(count($optionArray) < 2 || count($valueArray) < 2)
            {
                throw new \Exception("Question options cannot be less than 2");
            }
            if( count($optionArray) != count($valueArray) )
            {
                throw new \Exception("Question options mismatch error");
            }
            if( !$this->is_unique($optionArray) ) //|| !$this->is_unique($valueArray)
            {
                throw new \Exception("Duplicate data error. Some options have duplicate labels.");
            }
            // $optionData = array_combine($valueArray, $optionArray);
            $optionData = $valueArray;
            DB::beginTransaction();
            /** rm options */
            Option::where('question', $questionId)->delete();
            /** update question */
            $questionMeta = Question::find($questionId);
            $questionMeta->name = $data['name'];
            $questionMeta->number = $data['number'];
            $questionMeta->is_check = $data['is_check'];
            $questionMeta->save();
            /** create new options */
            $cloop = 0;
            foreach($optionData as $v )
            {
                $fillable = [
                    'question' => $questionId,
                    'option' => ucwords(strtolower($optionArray[$cloop])),
                    'value' => intval($v),
                ];
                Option::create($fillable);
                $cloop++;
            }
            DB::commit();
            return redirect()->back()->with(['suc' => 'Updated successfully!']);
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    protected function findOptions($questionId)
    {
        $data = Option::where('question', $questionId)->get();
        if(is_null($data)) { return []; }
        return $data->toArray();
    }
    public function questions($sectionId = null)
    {
        return view('questions', [
            'questions' => $this->findQuestionsBySection($sectionId),
            'thisItem' => Section::find($sectionId),
            'parentItem' => $this->findParentItem($sectionId),
            'next' => Question::max('id') + 1,
        ]);
    }
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string',],
                'section' => ['required', 'string', 'max:10'],
                'number' => ['required', 'string'],
                'is_check' => ['required', 'string'],
                'option' => ['required', 'array'],
                'value' => ['required', 'array'],
            ]);
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator);
            }
            $data = $request->all();
            if(!$data['section'])
            {
                throw new \Exception("Section ID cannot be set to zero");
            }
            $optionArray = $data['option'];
            $valueArray = $data['value'];
            if(count($optionArray) < 2 || count($valueArray) < 2)
            {
                throw new \Exception("Question options cannot be less than 2");
            }
            if( count($optionArray) != count($valueArray) )
            {
                throw new \Exception("Question options mismatch error");
            }
            if( !$this->is_unique($optionArray) ) // || !$this->is_unique($valueArray) )
            {
                throw new \Exception("Duplicate data error. Some options have duplicates.");
            }
            // $optionData = array_combine($valueArray, $optionArray);
            $optionData = $valueArray;
            DB::beginTransaction();
            $questionId = Question::create($data)->id;
            /** options */
            $cloop = 0;
            foreach($optionData as $v )
            {
                $fillable = [
                    'question' => $questionId,
                    'option' => ucwords(strtolower($optionArray[$cloop])),
                    'value' => intval($v),
                ];
                Option::create($fillable);
                $cloop++;
            }
            DB::commit();
            return redirect()->back()->with(['suc' => 'Created successfully!']);
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with(['mess' => $e->getMessage() ]);
        }
    }
    protected function findQuestionsBySection($sectionId)
    {
        $data = Question::where('section', $sectionId)->get();
        if(is_null($data)) { return []; }
        return $this->formatQuestions($data->toArray());
    }
    protected function findQuestionOptions($id)
    {
        $data = Option::where('question', $id)->orderBy('id', 'asc')->get();
        if( is_null($data) ) { return []; }
        return $data->toArray();
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
    protected function findParentItem($sectionId)
    {
        $sectionMeta = Section::find($sectionId);
        if(is_null($sectionMeta))
        {
            return [
                'id' => null,
                'name' => null,
                'sections' => null,
            ];
        }
        return Series::find($sectionMeta->series);
    }
    protected function is_unique(array $input_array) {
        return count($input_array) === count(array_flip($input_array));
    }
}
