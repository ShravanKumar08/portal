<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Platform;
use App\Models\Grade;
use function GuzzleHttp\json_encode;
use App\DataTables\QuestionDataTable;
use PDF;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(QuestionDataTable $dataTable) {
        $dataTable->role = "admin";
        return $dataTable->addScope(new \App\DataTables\Scopes\BaseDataTableScope)->render('admin.questions.index');
        //return "Index Page";
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $this->_append_variables($data);
        return view('admin.questions.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Question $question) {
       $this->_validate($request);

        $this->_save($request, $question);

        return response()->json(['msg' => 'Question created successfully'],200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Question $question)
    {
        $Question = $question;

        return view('admin.questions.view', compact('Question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function  edit(Question $question) {
         $data = [];
         $this->_append_variables($data);
         $data['Model'] = $question;
         return view('admin.questions.edit',$data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $this->_validate($request, $question->id);

        $this->_save($request, $question);

        //flash('Question Updated successfully')->success();

        return response()->json(['msg' => 'Question Updated successfully', 'id' => $question->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question) {
        $question->delete();
    }

    private function _validate($request, $id = null, $uid = null) {
        $request['answer']= ($request['type']=="D") ? $request['description_ans'] : $request['answer'];
        $rules = [
            'name' => "required",
            'type' => "required",
            'grade_id' => "required",
            'answer' => "required",
            'platforms' => "required",
            'options' => function ($attribute, $value, $fail) use($request) {
                if($request['type']=="O"){
                    if(in_array('',$value)){
                        $fail('Fill all '.$attribute.' values');
                    }
                }
            },
        ];
        $this->validate($request, $rules);
    }

    private function _save($request, $question) {
        $data = $request->except(['_token']);
        $question->fill($data);
        $question->answer= ($data['type']=="D") ? $data['description_ans'] : $data['answer'];
        $question->options= ($data['type']=="O") ? json_encode($data['options']) : null;
        $question->save();
        $question->platforms()->sync($data['platforms']);
    }

    private function _append_variables(&$data)
    {
        $data['platforms'] = Platform::query()->latest('name')->pluck('name', 'id')->toArray();
        $data['grades'] = Grade::query()->latest('name')->pluck('name', 'id')->toArray();
        $data['question_types'] = Question::$question_types;
    }

    public function generate(Request $request)
    {
        if($request->isMethod('post')){
            $this->validate($request, [
               'grade_id' => 'required',
//                'platform' => [
//                    'required',
//                    function ($attribute, $value, $fail) {
//                        $array_1 = array_pluck($value, 'id');
//                        $array_2 = array_unique($array_1);
//
//                        if(count($array_1) != count($array_2)){
//                            $fail("Platform must be unique.");
//                        }
//                    },
//                ],
               'platform.*.id' => 'required',
               'platform.*.count' => 'required|integer|max:100',
            ]);

            $questions = collect();

            $existsIds = [];

            foreach ($request->platform as $platform) {
                $query = Question::query()->limit($platform['count'])->where('type', $platform['type']);

                $query->whereHas('grade', function ($q) use ($request){
                    $q->where('grades.id', $request->grade_id);
                });

                $query->whereHas('platforms', function ($q) use ($platform){
                    $q->where('platforms.id', $platform['id']);
                });

                $query->whereNotIn('id', $existsIds);

                $result = $query->inRandomOrder()->get();

                $existsIds = array_merge($result->pluck('id')->toArray(), $existsIds);

                $questions = $questions->merge($result);
            }

            $data['questions'] = $questions->shuffle()->all();

            return view('admin.questions.partials.questions', $data);
        }else{
            $data = [];
            $this->_append_variables($data);

            return view('admin.questions.generate', $data);
        }
    }

    public function download(Request $request)
    {
        $ids = $request['question'];

        $data['questions'] = Question::query()->whereIn('id', $ids)->orderByRaw("find_in_set(id, '".implode(',', $ids)."');")->get();

        $data['duration'] = $data['questions']->sum('duration');

//        return view('admin.questions.pdf', $data);
//        return PDF::loadView('admin.questions.pdf', $data)->stream();
        return PDF::loadView('admin.questions.pdf', $data)->download('questions.pdf');
    }
}
