<?php

namespace Console\Repository\Master;

use Console\Repository\Master\Code;

class Category extends Base
{
    protected $table = 'category';
    protected $fillable = [
        'title',
        'parent_id',
        'left',
        'right',
        'tree_id',
        'channel_id',
        'locale_id',
        'app_id',
        'creator'
    ];

    public function __construct(Code $model_code)
    {
        $this->model_code = $model_code;
    }

    public function release()
    {
        $data = $this->data($this->table);

        $last_id = 0;
        $basic = [
            'creator' => ACCOUNT_CONSOLE,
            'app_id' => MASTER_APP_ID,
        ];
        $flag = false;
        foreach ($data as $item) {
            $item = array_merge($item, $basic);
            $flag = $this->add($item, $last_id);
            if ($flag) {
                $flag = $this->update(['tree_id' => $last_id], $last_id);
                if (!empty($item['code'])) {
                    $code = array_merge($item['code'], $basic);
                    $flag = $this->saveCode($code, $last_id);
                }
                if ($flag) {
                    continue;
                }
            }

            return false;
        }

        return true;
    }

    private function saveCode(array $data, int $target_id)
    {
        $data['target_id'] = $target_id;

        return $this->model_code->add($data);
    }
}
