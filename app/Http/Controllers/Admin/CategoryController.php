<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::all();

        return view('admin.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
       // dd($request);
       
        $image=$request->image;

        $imagename=time().'.'.$image->getClientOriginalExtension();

        $request->image->move('category_images',$imagename);

        Category::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'image'         => $imagename
        ]);

        return to_route('admin.categories.index')->with('success','New Category Created Successfully');
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
    public function edit(Category $category)
    {
        //dd($category);

         return view('admin.categories.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //dd($request);

        $request->validate([
            'name' => 'required',
            'description'=> 'required',      
                        ]);

        $image=$category->image;                                             //Old Image

        if($request->image)
        {

            $image=time().'.'.$request->image->getClientOriginalExtension(); //New Image

            $request->image->move('category_images',$image);

        }


        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
        ]);

        return to_route('admin.categories.index')->with('success',' Category Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
    //    $category->delete(); 

    $category->menus()->detach();
    $category->delete();
    
       return to_route('admin.categories.index')->with('danger','Category Deleted Successfully');

    }

}
