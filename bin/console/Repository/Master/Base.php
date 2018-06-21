<?php

namespace Console\Repository\Master;

use Kernel\Base\Repository\Write;

class Base extends Write
{
    protected function scope()
    {
        return [
            'alive' => ['alive']
        ];
    }

    protected function master(array $data = [], int $mode = REPOSITORY_MODE_CREATE)
    {
        return $data;
    }

    public function truncate(string $table)
    {
        $db = db();

        $query = 'TRUNCATE ' . $db->table($table);
        $db->executeNonQuery($query);
    }

    protected function data(string $table)
    {
        return load('config/master/db/' . $table . '.php', BIN);
    }

    public function release()
    {
        $data = $this->data($this->table);

        $basic = [
            'creator' => ACCOUNT_CONSOLE,
            'app_id' => MASTER_APP_ID,
        ];
        foreach ($data as $item) {
            $item = array_merge($item, $basic);
            if ($this->add($item)) {
                continue;
            }

            return false;
        }

        return true;
    }
}
