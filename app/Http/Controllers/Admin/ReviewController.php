<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Review;
use App\Models\Rubric;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'review');

       
    }

    public function index(Request $request)
    {
        $row = Review::orderBy('review.sort_num','asc')
                       ->select('*',
                                 DB::raw('DATE_FORMAT(review.created_at,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('review.is_show',$request->active);
        else $row->where('review.is_show','1');

      
        if(isset($request->review_name) && $request->review_name != ''){
            $row->where(function($query) use ($request){
                $query->where('review_name_ru','like','%' .$request->review_name .'%');
            });
        }
        
        $row = $row->paginate(20);

        return  view('admin.review.review',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Review();
        $row->review_image = '/img/review/review.jpg';

        return  view('admin.review.review-edit', [
            'title' => 'Добавить отзыв',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.review.review-edit', [
                'title' => 'Добавить отзыв',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $review = new Review();
        $review->review_name_ru = $request->review_name_ru;
        $review->review_desc_ru = $request->review_desc_ru;
        $review->review_text_ru = $request->review_text_ru;
        $review->review_image = $request->review_image;
        $review->sort_num = $request->sort_num?$request->sort_num:100;
        $review->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'review';
        $action->action_text_ru = 'добавил(а) отзыв "' .$review->review_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $review->review_id;
        $action->save();

        return redirect('/admin/review');
    }

    public function edit($id)
    {
        $row = Review::find($id);
        
        return  view('admin.review.review-edit', [
            'title' => 'Редактировать данные отзыва',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'review_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $row = Review::find($id);

            return  view('admin.review.review-edit', [
                'title' => 'Редактировать данные отзыва',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $review = Review::find($id);
        $review->review_name_ru = $request->review_name_ru;
        $review->review_desc_ru = $request->review_desc_ru;
        $review->review_text_ru = $request->review_text_ru;
        $review->review_image = $request->review_image;
        $review->sort_num = $request->sort_num?$request->sort_num:100;
        $review->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'review';
        $action->action_text_ru = 'редактировал(а) данные отзыва "' .$review->review_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $review->review_id;
        $action->save();
        
        return redirect('/admin/review');
    }

    public function destroy($id)
    {
        $review = Review::find($id);

        $old_name = $review->review_name_ru;

        $review->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'review';
        $action->action_text_ru = 'удалил(а) отзыв "' .$review->review_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $review = Review::find($request->id);
        $review->is_show = $request->is_show;
        $review->save();

        $action = new Actions();
        $action->action_comment = 'review';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - отзыв "' .$review->review_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - отзыв "' .$review->review_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $review->review_id;
        $action->save();
    }
    
}
