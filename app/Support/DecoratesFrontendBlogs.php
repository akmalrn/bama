<?php

namespace App\Support;

use Illuminate\Support\Str;

trait DecoratesFrontendBlogs
{
    protected function decorateBlogCollection($blogs)
    {
        return $blogs->map(function ($blog) {
            $blogCopy = strip_tags($blog->overview ?: $blog->description);
            $publishedAt = $blog->published_at ?: $blog->created_at;

            $blog->setAttribute('category_title', optional($blog->categoryBlog)->title ?? 'Tips Laundry');
            $blog->setAttribute('summary', Str::limit($blogCopy, 112));
            $blog->setAttribute('read_minutes', max(2, (int) ceil(str_word_count($blogCopy) / 160)));
            $blog->setAttribute('display_date', $publishedAt->translatedFormat('d M Y'));

            return $blog;
        });
    }

    protected function decorateBlogPaginator($blogs)
    {
        $blogs->setCollection($this->decorateBlogCollection($blogs->getCollection()));

        return $blogs;
    }
}
