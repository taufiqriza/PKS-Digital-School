<?php

namespace App\Http\Controllers;


use App\Comments;
use App\Mail\PostAuthorMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class commentscontroller extends Controller
{
    /**
     * index
     *
     * @return void
     */

    public function __construct()
    {
        return $this->middleware('auth:api')->only(['store' , 'update' , 'delete']);
    }

    public function index()
    {
        //get data from table comments
        $comments = Comments::latest()->get();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Data Comments',
            'data'    => $comments  
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
        //find post by ID
        $comments = Comments::findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Post',
            'data'    => $comments 
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
            'content'   => 'required',
            'post_id' => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = auth()->user();

        //save to database
        $comments = Comments::create([
            'content'     => $request->content,
            'post_id'   => $request->post_id,
            'user_id' => $user->id
        ]);

        dd($comments->posts);
        Mail::to($comments->posts->users->email)->send(new PostAuthorMail($comments));

        //success save to database
        if($comments) {

            return response()->json([
                'success' => true,
                'message' => 'Post Created',
                'data'    => $comments  
            ], 201);

        } 

        //failed save to database
        return response()->json([
            'success' => false,
            'message' => 'Comments Failed to Save',
        ], 409);

    }
    
    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $comments
     * @return void
     */
    public function update(Request $request, $comments)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'content'   => 'required',
            'post_id' => 'required',
        ]);
        
        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find post by ID
        $comments = Comments::findOrFail($comments);

        if($comments) {

            $user = auth()->user();

            if($comments->user_id != $user->id)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Post Bukan Milik Anda',
                ], 403);
            }

            //update comments
            $comments->update([
                'content'     => $request->content,
                'post_id'   => $request->post_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comments Updated',
                'data'    => $comments  
            ], 200);

        }

        //data comments not found
        return response()->json([
            'success' => false,
            'message' => 'Comments Not Found',
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
        //find comments by ID
        $comments = Comments::findOrfail($id);

        if($comments) {

            $user = auth()->user();

            if($comments->user_id != $user->id)
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Post Bukan Milik Anda',
                ], 403);
            }

            //delete comments
            $comments->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comments Deleted',
            ], 200);

        }

        //data comments not found
        return response()->json([
            'success' => false,
            'message' => 'Comments Not Found',
        ], 404);
    }
}
