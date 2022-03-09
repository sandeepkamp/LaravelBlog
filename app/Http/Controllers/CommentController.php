<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CommentController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    //on_post, from_user, body
    $input['from_user'] = $request->user()->id;
    $input['on_post'] = $request->input('on_post');
    $input['body'] = $request->input('body');
    $slug = $request->input('slug');
    Comments::create($input);

    return redirect($slug)->with('message', 'Comment published');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit(Request $request, $id)
  {
    $comment = Comments::where('id', $id)->first();
    if ($comment && ($request->user()->id == $comment->from_user || $request->user()->is_admin()))
      return view('comment.edit')->with('comment', $comment);
    else {
      return redirect('/')->withErrors('you have not sufficient permissions');
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request)
  {
    $comment_id = $request->input('comment_id');
   // dd($comment_id);
    $comment = Comments::find($comment_id);
    $data['message'] = 'Comment updated Successfully';
   // dd($comment);
    if ($comment && ($comment->from_user == $request->user()->id || $request->user()->is_admin())) {

      $comment->body = $request->input('body');
      $comment->save();
      //dd($comment);
     // return redirect()->back()->withMessage('Comment Updated Successfuly');
     return redirect('/')->with($data);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request,$id)
  {
    $comment = Comments::find($id);
    //dd($comment);
    if ($comment && ($comment->from_user == $request->user()->id || $request->user()->is_admin())) {
      $comment->delete();
      $data['message'] = 'Comment deleted Successfully';
    } else {
      $data['errors'] = 'Invalid Operation. You have not sufficient permissions';
    }

    return redirect('/')->with($data);
  }
}
