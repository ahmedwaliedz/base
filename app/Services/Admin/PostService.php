<?php

namespace App\Services\Admin;

use App\Models\Post;
use App\Services\Admin\Base\CrudBaseService;

class PostService extends CrudBaseService
{
    public function __construct()
    {
        parent::__construct(Post::class);
    }

    public function index($request, $where = [])
    {
        return parent::index($request, $where)->with('translations');
    }

    public function switchIsActive(int|string $id): bool
    {
        $post = Post::query()->findOrFail($id);
        $post->update(['is_active' => ! $post->is_active]);

        return (bool) $post->fresh()->is_active;
    }
}