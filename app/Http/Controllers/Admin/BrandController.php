<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Brand;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use View;
use DB;
use Auth;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'brand');
    }

    public function index(Request $request)
    {
        $row = Brand::orderBy('brand.sort_num','asc')
                       ->select('*',
                                 DB::raw('DATE_FORMAT(brand.created_at,"%d.%m.%Y %H:%i") as date'));

        if(isset($request->active))
            $row->where('brand.is_show',$request->active);
        else $row->where('brand.is_show','1');

      
        if(isset($request->brand_name) && $request->brand_name != ''){
            $row->where(function($query) use ($request){
                $query->where('brand_name_ru','like','%' .$request->brand_name .'%');
            });
        }
        
        $row = $row->paginate(20);

        return  view('admin.brand.brand',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Brand();
        $row->brand_image = '/media/default.jpg';

        return  view('admin.brand.brand-edit', [
            'title' => 'Добавить бренд',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.brand.brand-edit', [
                'title' => 'Добавить бренд',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $brand = new Brand();
        $brand->brand_name_ru = $request->brand_name_ru;
        $brand->brand_meta_title_ru = $request->brand_meta_title_ru;
        $brand->brand_meta_description_ru = $request->brand_meta_description_ru;
        $brand->brand_meta_keywords_ru = $request->brand_meta_keywords_ru;
        $brand->brand_url_ru = Helpers::getTranslatedSlugRu($request->brand_name_ru);

        $brand->brand_name_en = $request->brand_name_en;
        $brand->brand_meta_title_en = $request->brand_meta_title_en;
        $brand->brand_meta_description_en = $request->brand_meta_description_en;
        $brand->brand_meta_keywords_en = $request->brand_meta_keywords_en;
        $brand->brand_url_en = Helpers::getTranslatedSlugRu($request->brand_name_en);

        $brand->brand_name_kz = $request->brand_name_kz;
        $brand->brand_meta_title_kz = $request->brand_meta_title_kz;
        $brand->brand_meta_description_kz = $request->brand_meta_description_kz;
        $brand->brand_meta_keywords_kz = $request->brand_meta_keywords_kz;
        $brand->brand_url_kz = Helpers::getTranslatedSlugRu($request->brand_name_kz);

        $brand->is_show_menu = $request->is_show_menu;
        $brand->brand_image = $request->brand_image;
        $brand->sort_num = $request->sort_num?$request->sort_num:100;
        $brand->save();


        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'brand';
        $action->action_text_ru = 'добавил(а) бренд "' .$brand->brand_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $brand->brand_id;
        $action->save();

        return redirect('/admin/brand');
    }

    public function edit($id)
    {
        $row = Brand::find($id);
     
        return  view('admin.brand.brand-edit', [
            'title' => 'Редактировать данные бренда',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'brand_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.brand.brand-edit', [
                'title' => 'Редактировать данные бренда',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $brand = Brand::find($id);
        $brand->brand_name_ru = $request->brand_name_ru;
        $brand->brand_meta_title_ru = $request->brand_meta_title_ru;
        $brand->brand_meta_description_ru = $request->brand_meta_description_ru;
        $brand->brand_meta_keywords_ru = $request->brand_meta_keywords_ru;
        $brand->brand_url_ru = Helpers::getTranslatedSlugRu($request->brand_name_ru);

        $brand->brand_name_en = $request->brand_name_en;
        $brand->brand_meta_title_en = $request->brand_meta_title_en;
        $brand->brand_meta_description_en = $request->brand_meta_description_en;
        $brand->brand_meta_keywords_en = $request->brand_meta_keywords_en;
        $brand->brand_url_en = Helpers::getTranslatedSlugRu($request->brand_name_en);

        $brand->brand_name_kz = $request->brand_name_kz;
        $brand->brand_meta_title_kz = $request->brand_meta_title_kz;
        $brand->brand_meta_description_kz = $request->brand_meta_description_kz;
        $brand->brand_meta_keywords_kz = $request->brand_meta_keywords_kz;
        $brand->brand_url_kz = Helpers::getTranslatedSlugRu($request->brand_name_kz);
        
        $brand->is_show_menu = $request->is_show_menu;
        $brand->brand_image = $request->brand_image;
        $brand->sort_num = $request->sort_num?$request->sort_num:100;
        $brand->save();

        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'brand';
        $action->action_text_ru = 'редактировал(а) данные бренда "' .$brand->brand_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $brand->brand_id;
        $action->save();
        
        return redirect('/admin/brand');
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);

        $old_name = $brand->brand_name_ru;

        $brand->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'brand';
        $action->action_text_ru = 'удалил(а) бренд "' .$brand->brand_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $brand = Brand::find($request->id);
        $brand->is_show = $request->is_show;
        $brand->save();

        $action = new Actions();
        $action->action_comment = 'brand';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - бренд "' .$brand->brand_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - бренд "' .$brand->brand_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $brand->brand_id;
        $action->save();

    }

}
