<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $posts = Post::all();
        return view('dashboard.pages.posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        
        $slug = \Str::slug($request->title);

        if($request->hasFile('thumbnail')){
            
            $file = $request->file('thumbnail');
            $newFileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $newFileName);
            
        }
        Post::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'thumbnail' => $newFileName
        ]);
        

        session()->flash('success', "Post created is successful");
        return redirect()->route('posts.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);
        return view('dashboard.pages.posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
                // Find the post
            $post = Post::findOrFail($id);

            // Validate input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $newFileName = time() . '.' . $file->getClientOriginalExtension();

                // Define upload path
                $uploadPath = public_path('uploads');

                // Create folder if missing
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Delete old image if exists
                if ($post->thumbnail && file_exists(public_path('uploads/' . $post->thumbnail))) {
                    unlink(public_path('uploads/' . $post->thumbnail));
                }

                // Move new file
                $file->move($uploadPath, $newFileName);

                // Save new filename
                $validated['thumbnail'] = $newFileName;
            } else {
                // Keep the old image if no new one is uploaded
                $validated['thumbnail'] = $post->thumbnail;
            }

            $post->update([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'thumbnail' => $validated['thumbnail'],
            ]);

            // Redirect with success
            return redirect()
                ->route('posts.index')
                ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the post
        $post = Post::findOrFail($id);

        // Delete the thumbnail file if it exists
        if ($post->thumbnail && file_exists(public_path('uploads/' . $post->thumbnail))) {
            unlink(public_path('uploads/' . $post->thumbnail));
        }

        // Delete the post record
        $post->delete();

        // Redirect with success message
        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully!');
        }
}
