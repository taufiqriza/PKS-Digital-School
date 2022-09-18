<?php

namespace App\Http\Controllers;

use App\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class rolescontroller extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get data from table roles
        $roles = roles::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Roles',
            'data'    => $roles  
        ], 200);

    }
    
     /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        //find roles by ID
        $roles = roles::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Roles',
            'data'    => $roles 
        ], 200);

    }
    
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //save to database
        $roles = roles::create([
            'name'     => $request->name,

        ]);

        //success save to database
        if($roles) {

            return response()->json([
                'success' => true,
                'message' => 'Roles Created',
                'data'    => $roles  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Roles Failed to Save',
        ], 409);

    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $roles
     * @return void
     */
    public function update(Request $request, $roles)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find roles by ID
        $roles = Roles::findOrFail($roles);

        if($roles) {

            //update roles
            $roles->update([
                'name' => $request->name,
                
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Roles Updated',
                'data'    => $roles 
            ], 200);

        }

        //data roles not found
        return response()->json([
            'success' => false,
            'message' => 'Roles Not Found',
        ], 404);

    }
    
    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find roles by ID
        $roles = Roles::findOrfail($id);

        if($roles) {

            //delete roles
            $roles->delete();

            return response()->json([
                'success' => true,
                'message' => 'Roles Deleted',
            ], 200);

        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Roles Not Found',
        ], 404);
    }
}

