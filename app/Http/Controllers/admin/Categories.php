<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Categories_model;
use App\Http\Controllers\Controller;

class Categories extends Controller
{
    public function index(){
        has_access(13);
        $this->data['rows']=Categories_model::orderBy('order_no', 'asc')->get();
        return view('admin.categories.index',$this->data);
    }
    public function add(Request $request){
        has_access(13);
        $input = $request->all();
        if($input){
            $data=array();
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            if(!empty($input['featured'])){
                $data['featured']=1;
            }
            else{
                $data['featured']=0;
            }
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/categories/');
                if(!empty(basename($image))){
                    generateThumbnail('categories',basename($image),'square','medium');
                    $data['image']=basename($image);
                }

            }
            $data['name']=$input['name'];
            $data['order_no']=nextOrder('categories');
            $data['slug'] = checkSlug(Str::slug($data['name'], '-'),'categories');
            // pr($data);
            $id = Categories_model::create($data);
            return redirect('admin/categories/')
                ->with('success','Content Updated Successfully');
        }

        return view('admin.categories.index',$this->data);
    }
    public function edit(Request $request, $id){
        has_access(13);
        $category=Categories_model::find($id);
        $input = $request->all();
        if($input){
            $data=array();
            if(!empty($input['status'])){
                $category->status=1;
            }
            else{
                $category->status=0;
            }
            if(!empty($input['featured'])){
                $category->featured=1;
            }
            else{
                $category->featured=0;
            }
            
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:100000'
                ]);
                $image=$request->file('image')->store('public/categories/');
                if(!empty($image)){
                    generateThumbnail('categories',basename($image),'square','medium');
                    $category->image=basename($image);
                }

            }
            $category->name=$input['name'];
            $category->slug = checkSlug(Str::slug($category->name, '-'),'categories',$category->id);

            $category->update();
            return redirect('admin/categories/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Categories_model::find($id);
        return view('admin.categories.index',$this->data);
    }
    public function delete($id){
        has_access(13);
        $category = Categories_model::find($id);
        removeImage("categories/".$category->image);
        $category->delete();
        return redirect('admin/categories/')
                ->with('error','Content deleted Successfully');
    }
    public function orderAll(Request $request)
    {
        $rows = Categories_model::all();
        foreach ($rows as $row) {
            $orderId = $request->input('orderid' . $row->id);
            if ($orderId !== null) {
                $row->order_no = $orderId;
                $row->save();
            }
        }
        
        return redirect('admin/categories/')
                ->with('success','Order updated Successfully');
    }
}