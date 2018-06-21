<?php

namespace Admin\Repository\Post;

use Kernel\Base\Repository\Write;

class Input extends Write
{
    protected $table = 'post';
    protected $fillable = [
        'category_id',
        'title',
        'content',
        'mode',
        'priority',
        'file_id',
        'seo_id'
    ];

    public function updateMode(array $id_list, int $mode)
    {
        $data = [
            'mode' => $mode
        ];

        return $this->with('in', 'id', $id_list)->update($data);
    }
}
