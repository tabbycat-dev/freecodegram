<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function index(){
        //get all post of people we are following
        //we following profile, 
        //but posts are associated to user not profile
        //1. grab all user id we are following. pluck all user_id in profiles table
        $users = auth()->user()->following()->pluck('profiles.user_id');

        //2.grab posts that has user_id in our list of $users
        $posts = Post::whereIn('user_id', $users)->with('user')->latest()->paginate(5);//get 5 latest post
        //orderBy('created_at', 'DESC') = lastest()
        #dd($posts); 
        return view('posts.index', compact('posts'));

    }
    public function __construct()
    {
        $this->middleware('auth'); //check auth and require log in to add new post
    }
    public function create(){
        return view('posts.create') ;
    }
    public function store(){
        $data = request()->validate(
            [
                # 'another' => '', (when there is no rule for that field)
                'caption' => 'required',
                'image' => ['required', 'image'],
            ]
        );
        //*upload image
        $imagePath = request('image')->store('uploads', 'public'); //(dir, driver) while driver could be s3 or public is local driver
        //upload to upload dir under storage/app/public
        //to access: http://127.0.0.1:8000/storage/uploads/imagename.jpg

        //*fit image to square using Intervention\Image\Facades\Image;
        $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        $image->save();

        //*save new post to with user_id
        auth()->user()->posts()->create([
            'caption' =>$data['caption'],
            'image'=> $imagePath,
        ]); //this will include user_id to post
        //Post::create($data); //this not include user_id
        //dd(request()->all());

        return redirect ('/profile/' . auth()->user()->id );
    }
    public function show(Post $post){ //fetch Post model instead of just ID
        //dd($post);
        return view('posts.show', compact('post')); //pass $post to view with key 'post'
    }
}
