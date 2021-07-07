<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Helper\Reply;
use App\Http\Requests\Question\StoreRequest;
use App\Http\Requests\Question\UpdateRequest;
use App\Question;
use App\AssessmentMultipleChoice;
use App\Assessment;
use App\AssessmentQuestion;
use App\MultipleChoice;
use Yajra\DataTables\Facades\DataTables;

class AdminQuestionController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.question');
        $this->pageIcon = 'icon-grid';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('view_question'), 403);

        return view('admin.question.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(! $this->user->can('add_question'), 403);

        return view('admin.question.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        abort_if(! $this->user->can('add_question'), 403);

        $question_count = Question::where('job_id',$request->job_id)->count();


        $question = new Question();
        $question->job_id = $request->job_id;
        $question->question = $request->question;
        $question->question_type = $request->question_type;
        $question->required = $request->required;
        $question->company_id= $this->user->company_id;
        $question->order_no = $question_count + 1;

        if (!empty($request->time_limit)) {
            $question->audio_video_length = $request->time_limit;
        }

        $question->save();

        $question_id = $question->id;
        if (!empty($request->multiple)) {
            foreach($request->multiple as $key=>$value){
                if (!empty($request->multiple[$key])) {
                    $multiplechoice = new MultipleChoice();
                    $multiplechoice->question_id = $question_id;
                    $multiplechoice->answer = $request->multiple[$key];
                    $multiplechoice->save();
                }
            }
        }

        $data = array(
            "title" => $question->id
          );

        return Reply::successWithData(__('menu.question').' '.__('messages.createdSuccessfully'), $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(! $this->user->can('edit_question'), 403);

        $this->question = Question::find($id);
        return view('admin.question.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        abort_if(! $this->user->can('edit_question'), 403);
        $aid=$_GET["id"];

        $question = Question::find($aid);
        $question->question = $request->question;
        $question->required = $request->required;
        $question->question_type = $request->question_type;

        if (!empty($request->time_limit)) {
            $question->audio_video_length = $request->time_limit;
        }

        $question->save();

        $mSql = 'delete from assessment_multiple_choices where question_id='.$aid;

        DB::delete($mSql);


        $question_id = $aid;
        if (!empty($request->multiple)) {
            if($request->question_type == "Multiple"){
                foreach($request->multiple as $key=>$value){
                    $multiplechoice = new MultipleChoice();
                    $multiplechoice->question_id = $question_id;
                    $multiplechoice->answer = $request->multiple[$key];
                    $multiplechoice->save();
                }
            }
        }

        return Reply::success(__('menu.question').' '.__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_question'), 403);

        Assessment::destroy($id);

        $assessmentsQuestions = AssessmentQuestion::where('assessment_id',$id)->get();

        foreach($assessmentsQuestions as $q){
            AssessmentMultipleChoice::where('question_id',$q->id)->delete();
        }

        AssessmentQuestion::where('assessment_id',$id)->delete();


        return Reply::success(__('messages.questionDeleted'));
    }

    public function destroyAssQuestion($id)
    {
        abort_if(! $this->user->can('delete_question'), 403);

        Question::destroy($id);

        return Reply::success(__('messages.questionDeleted'));
    }

    public function data() {
        abort_if(! $this->user->can('view_question'), 403);

        $questions = Question::where('company_id',$this->user->company_id)->get();

        return DataTables::of($questions)
            ->addColumn('action', function ($row) {
                $action = '';

                if( $this->user->can('edit_question')){
                    $action.= '<a href="' . route('admin.questions.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_question')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('required', function ($row) {
                return ucfirst($row->required);
            })
            ->editColumn('requ', function ($row) {
                return ucfirst($row->question);
            })
            ->addIndexColumn()
            ->make(true);
    }

}
