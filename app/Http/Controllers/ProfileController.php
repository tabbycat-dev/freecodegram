<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $follows = (auth()->user())? auth()->user()->following->contains($user->id) : false;
        #dd($follows);
        //is auth user's following contains current page user id

        $postCount = Cache::remember( //use Illuminate\Support\Facades\Cache;
            'count.post.' . $user->id,
            now()->addSeconds(30),
            function() use ($user)  {
                return $user->posts->count();
            });
        $followingCount = Cache::remember( //use Illuminate\Support\Facades\Cache;
            'count.following.' . $user->id,
            now()->addSeconds(30),
            function() use ($user)  {
                return $user->following->count();
            });
        $followersCount = Cache::remember( //use Illuminate\Support\Facades\Cache;
            'count.followers.' . $user->id,
            now()->addSeconds(30),
            function() use ($user)  {
                return $user->profile->followers->count();
            });
        return view('profile.index',compact('user', 'follows', 'postCount', 'followingCount', 'followersCount'));

        #$followingCount = $user->following->count();
        #$followersCount = $user->profile->followers->count();

    }
    public function edit(User $user)
    {
        #who can view/ edit form?
        //calling update method in ProfilePolicy
        $this->authorize('update', $user->profile); 
        return view('profile.edit',compact('user'));
    }
    public function update(User $user)
    {
        $data = request()->validate([
             # 'another' => '', (when there is no rule for that field)
             'title' => 'required',
             'description' => 'required',
             'url' => 'url',
             'image' => '',
             #'image' => ['required', 'image'],
        ]);
        if (request('image')){
            //*upload image
            $imagePath = request('image')->store('profile', 'public'); //(dir, driver) while driver could be s3 or public is local driver
            //upload to upload dir under storage/app/public
            //to access: http://127.0.0.1:8000/storage/uploads/imagename.jpg

            //*fit image to square using Intervention\Image\Facades\Image;
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();
            $imageArray =['image' =>$imagePath];
        }
        
        #dd($data);
        #dd(array_merge($data, ['image'=>$imagePath]));
        #$user->profile->update($data);//whoever user pass in, not safe
        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        ));//safety

        return redirect("/profile/{$user->id}");
    }
}
