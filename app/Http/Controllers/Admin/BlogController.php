<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Faculty;
use App\Models\BlogPosition;
use App\Models\Position;
use App\Models\Rubric;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'blog');

        $categories = Category::where('is_show',1)->orderBy('sort_num','asc')->get();
        View::share('categories', $categories);
    }

    public function index(Request $request)
    {
        $row = Blog::leftJoin('users','users.user_id','=','blog.user_id')
                        ->leftJoin('category','category.category_id','=','blog.category_id')
                       ->orderBy('blog.blog_date','desc')
                       ->select('*',
                                 DB::raw('DATE_FORMAT(blog.blog_date,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('blog.is_show',$request->active);
        else $row->where('blog.is_show','1');

      
        if(isset($request->blog_name) && $request->blog_name != ''){
            $row->where(function($query) use ($request){
                $query->where('blog_name_ru','like','%' .$request->blog_name .'%');
            });
        }
        
        if(isset($request->user_name) && $request->user_name != ''){
            $row->where(function($query) use ($request){
                $query->where('name','like','%' .$request->user_name .'%');
            });
        }

        $row = $row->paginate(20);

        return  view('admin.blog.blog',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Blog();
        $row->blog_image = '/media/default.jpg';

        return  view('admin.blog.blog-edit', [
            'title' => 'Добавить фото',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.blog.blog-edit', [
                'title' => 'Добавить фото',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $blog = new Blog();
        $blog->blog_name_ru = $request->blog_name_ru;
        $blog->blog_desc_ru = $request->blog_desc_ru;
        $blog->blog_text_ru = $request->blog_text_ru;
        $blog->blog_image = $request->blog_image;
        $blog->category_id = $request->category_id;
        $blog->user_id = Auth::user()->user_id;
        $blog->blog_meta_description_ru = $request->blog_meta_description_ru;
        $blog->blog_meta_keywords_ru = $request->blog_meta_keywords_ru;
        $blog->is_show = 1;

        $timestamp = strtotime($request->blog_date);
        $blog->blog_date = date("Y-m-d H:i", $timestamp);

        $blog->save();


        $blog->blog_url_ru = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_ru);
        $blog->blog_url_kz = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_kz);
        $blog->blog_url_en = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_en);
        $blog->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'blog';
        $action->action_text_ru = 'добавил(а) фото "' .$blog->blog_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $blog->blog_id;
        $action->save();
        
        return redirect('/admin/blog');
    }

    public function edit($id)
    {
        $row = Blog::find($id);

        return  view('admin.blog.blog-edit', [
            'title' => 'Редактировать данные статьи',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'blog_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            
            return  view('admin.blog.blog-edit', [
                'title' => 'Редактировать данные статьи',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $blog = Blog::find($id);
        $blog->blog_name_ru = $request->blog_name_ru;
        $blog->blog_desc_ru = $request->blog_desc_ru;
        $blog->blog_text_ru = $request->blog_text_ru;
        $blog->blog_image = $request->blog_image;
        $blog->category_id = $request->category_id;
        $blog->blog_meta_description_ru = $request->blog_meta_description_ru;
        $blog->blog_meta_keywords_ru = $request->blog_meta_keywords_ru;
        
        $timestamp = strtotime($request->blog_date);
        $blog->blog_date = date("Y-m-d H:i", $timestamp);

        $blog->blog_url_ru = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_ru);
        $blog->blog_url_kz = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_kz);
        $blog->blog_url_en = '/blog/'.$blog->blog_id.'-'.Helpers::getTranslatedSlugRu($blog->blog_name_en);
        $blog->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'blog';
        $action->action_text_ru = 'редактировал(а) данные статьи "' .$blog->blog_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $blog->blog_id;
        $action->save();
        
        return redirect('/admin/blog');
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);

        $old_name = $blog->blog_name_ru;

        $blog->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'blog';
        $action->action_text_ru = 'удалил(а) фото "' .$blog->blog_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $blog = Blog::find($request->id);
        $blog->is_show = $request->is_show;
        $blog->save();

        $action = new Actions();
        $action->action_comment = 'blog';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - статья "' .$blog->blog_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - статья "' .$blog->blog_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $blog->blog_id;
        $action->save();

    }
}
