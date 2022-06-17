<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuStoreRequest;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus=Menu::all();

        return view('admin.menus.index',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();
        
        return view('admin.menus.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuStoreRequest $request)
    {
        $image=$request->image;

        $imagename=time().'.'.$image->getClientOriginalExtension();

        $request->image->move('menu_images',$imagename);

        $menu=Menu::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'price'         => $request->price,
            'image'         => $imagename,
        ]);
       
        if($request->has('categories'))
        {
            $menu->categories()->attach($request->categories);
        }

        return to_route('admin.menus.index')->with('success','New Menu Created Successfully');
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
    public function edit(Menu $menu)
    {
        $categories=Category::all();

        return view('admin.menus.edit', compact('menu','categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'description'=> 'required', 
            'price'     => 'required',     
                        ]);

        $image=$menu->image;                                             //Old Image

        if($request->image)
        {

            $image=time().'.'.$request->image->getClientOriginalExtension(); //New Image

            $request->image->move('menu_images',$image);

        }


        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $image,
        ]);

        if($request->has('categories'))
        {
            $menu->categories()->sync($request->categories);
        }

        return to_route('admin.menus.index')->with('success','Menu Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $menu->categories()->detach();
        $menu->delete(); 

       return to_route('admin.menus.index')->with('danger','Menu Deleted Successfully');
    }
}
