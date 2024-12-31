<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;


class PostService
{
    public function getAllPosts(int $perPage = 10): LengthAwarePaginator
    {
        return Post::paginate($perPage);
    }
    public function createPost(array $data)
    {
        return Post::create($data);
    }

    public function updatePost(Post $post, array $data)
    {
        $post->update($data);
        return $post;
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return true;
    }

    public function getPostById(int $id): ?Post
    {
        return Post::find($id);
    }
}
