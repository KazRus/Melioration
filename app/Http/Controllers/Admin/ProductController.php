<?php

namespace App\Http\Controllers\Admin;

use App\Http\Helpers;
use App\Models\Actions;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Faculty;
use App\Models\ProductPosition;
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

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
        View::share('menu', 'product');

        $categories = Category::where('parent_id',null)->orderBy('sort_num','asc')->get();
        View::share('categories', $categories);

        $brands = Brand::where('is_show',1)->orderBy('sort_num','asc')->get();
        View::share('brands', $brands);
        
    }

    public function index(Request $request)
    {
        $row = Product::leftJoin('brand','brand.brand_id','=','product.brand_id')
                       ->leftJoin('category','category.category_id','=','product.category_id')
                       ->orderBy('product.sort_num','asc')
                       ->groupBy('product.product_id')
                       ->select('*');

        if(isset($request->active))
            $row->where('product.is_show',$request->active);
        else $row->where('product.is_show','1');

      
        if(isset($request->product_name) && $request->product_name != ''){
            $row->where(function($query) use ($request){
                $query->where('product_name_ru','like','%' .$request->product_name .'%');
            });
        }

        if(isset($request->category_name) && $request->category_name != ''){
            $row->where(function($query) use ($request){
                $query->where('category_name_ru','like','%' .$request->category_name .'%');
            });
        }

        if(isset($request->brand_name) && $request->brand_name != ''){
            $row->where(function($query) use ($request){
                $query->where('brand_name_ru','like','%' .$request->brand_name .'%');
            });
        }


        $row = $row->paginate(20);

        return  view('admin.product.product',[
            'row' => $row,
            'request' => $request
        ]);
    }

    public function create()
    {
        $row = new Product();
        $row->product_image = '/media/default.jpg';

        return  view('admin.product.product-edit', [
            'title' => 'Добавить товары',
            'row' => $row
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name_ru' => 'required'
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return  view('admin.product.product-edit', [
                'title' => 'Добавить товары',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $product = new Product();
        $product->product_name_ru = $request->product_name_ru;
        $product->product_desc_ru = $request->product_desc_ru;
        $product->product_text_ru = $request->product_text_ru;

        $product->product_name_kz = $request->product_name_kz;
        $product->product_desc_kz = $request->product_desc_kz;
        $product->product_text_kz = $request->product_text_kz;

        $product->product_name_en = $request->product_name_en;
        $product->product_desc_en = $request->product_desc_en;
        $product->product_text_en = $request->product_text_en;

        $product->product_characteristic_ru = $request->product_characteristic_ru;
        $product->product_image = $request->product_image;
        $product->crop_image = $request->crop_image;
        $product->tag_ru = $request->tag_ru;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->is_new = $request->is_new;
        $product->is_show_main = $request->is_show_main;
        $product->product_discount = $request->product_discount?$request->product_discount:0;
        $product->product_price = $request->product_price?$request->product_price:100;
        $product->product_old_price = $request->product_old_price?$request->product_old_price:0;
        $product->product_meta_title_ru = $request->product_meta_title_ru;
        $product->product_meta_description_ru = $request->product_meta_description_ru;
        $product->product_meta_keywords_ru = $request->product_meta_keywords_ru;
        $product->sort_num = $request->sort_num?$request->sort_num:100;
        $product->is_show = 1;

        $product->is_sale = $request->is_sale;
        $timestamp = strtotime($request->sale_date);
        $product->sale_date = date("Y-m-d H:i", $timestamp);

        $product->save();

        $product->product_url_ru = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_ru);
        $product->product_url_kz = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_kz);
        $product->product_url_en = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_en);
        $product->save();
        
        $action = new Actions();
        $action->action_code_id = 2;
        $action->action_comment = 'product';
        $action->action_text_ru = 'добавил(а) товар "' .$product->product_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $product->product_id;
        $action->save();
        
        return redirect('/admin/product');
    }

    public function edit($id)
    {
        $row = Product::leftJoin('category','category.category_id','=','product.category_id')
                        ->leftJoin('category as parent','parent.category_id','=','category.parent_id')
                        ->leftJoin('category as most_parent','most_parent.category_id','=','parent.parent_id')
                        ->where('product_id',$id)
                        ->select('product.*',
                                'parent.category_id as parent_category_id',
                                'most_parent.category_id as most_parent_category_id',
                                DB::raw('DATE_FORMAT(product.sale_date,"%d.%m.%Y %H:%i") as sale_date')
                        )
                        ->first();
        
        return  view('admin.product.product-edit', [
            'title' => 'Редактировать данные товара',
            'row' => $row
        ]);
    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'product_name_ru' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            
            return  view('admin.product.product-edit', [
                'title' => 'Редактировать данные товара',
                'row' => (object) $request->all(),
                'error' => $error[0]
            ]);
        }

        $product = Product::find($id);
        $product->product_name_ru = $request->product_name_ru;
        $product->product_desc_ru = $request->product_desc_ru;
        $product->product_text_ru = $request->product_text_ru;

        $product->product_name_kz = $request->product_name_kz;
        $product->product_desc_kz = $request->product_desc_kz;
        $product->product_text_kz = $request->product_text_kz;

        $product->product_name_en = $request->product_name_en;
        $product->product_desc_en = $request->product_desc_en;
        $product->product_text_en = $request->product_text_en;

        $product->product_characteristic_ru = $request->product_characteristic_ru;
        $product->product_image = $request->product_image;
        $product->crop_image = $request->crop_image;
        $product->tag_ru = $request->tag_ru;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->is_new = $request->is_new;
        $product->is_show_main = $request->is_show_main;
        $product->product_discount = $request->product_discount?$request->product_discount:0;
        $product->product_price = $request->product_price?$request->product_price:100;
        $product->product_old_price = $request->product_old_price?$request->product_old_price:0;
        $product->product_meta_title_ru = $request->product_meta_title_ru;
        $product->product_meta_description_ru = $request->product_meta_description_ru;
        $product->product_meta_keywords_ru = $request->product_meta_keywords_ru;
        $product->sort_num = $request->sort_num?$request->sort_num:100;

        $product->product_url_ru = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_ru);
        $product->product_url_kz = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_kz);
        $product->product_url_en = '/product/'.$product->product_id.'-'.Helpers::getTranslatedSlugRu($product->product_name_en);

        $product->is_sale = $request->is_sale;
        $timestamp = strtotime($request->sale_date);
        $product->sale_date = date("Y-m-d H:i", $timestamp);

        $product->save();
        
        $action = new Actions();
        $action->action_code_id = 3;
        $action->action_comment = 'product';
        $action->action_text_ru = 'редактировал(а) данные товара "' .$product->product_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $product->product_id;
        $action->save();
        
        return redirect('/admin/product');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        $old_name = $product->product_name_ru;

        $product->delete();

        $action = new Actions();
        $action->action_code_id = 1;
        $action->action_comment = 'product';
        $action->action_text_ru = 'удалил(а) товар "' .$product->product_name_ru .'"';
        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $id;
        $action->save();

    }

    public function changeIsShow(Request $request){
        $product = Product::find($request->id);
        $product->is_show = $request->is_show;
        $product->save();

        $action = new Actions();
        $action->action_comment = 'product';

        if($request->is_show == 1){
            $action->action_text_ru = 'отметил(а) как активное - товар "' .$product->product_name_ru .'"';
            $action->action_code_id = 5;
        }
        else {
            $action->action_text_ru = 'отметил(а) как неактивное - товар "' .$product->product_name_ru .'"';
            $action->action_code_id = 4;
        }

        $action->user_id = Auth::user()->user_id;
        $action->universal_id = $product->product_id;
        $action->save();

    }

    public function getCategoryByParent(Request $request)
    {
        $categories = Category::where('parent_id',$request->category_id)->get();

        return  view('admin.product.category-list', [
            'categories' => $categories,
            'type' => $request->type,
            'category_id' => $request->category_id
        ]);
    }

    public function getCropImage(Request $request)
    {
        return  view('admin.product.crop-image', [
            'row' => $request
        ]);
    }
}
