<?php

use Kernel\Lib\Collection;

function array_allow($check, $collection, $default = true)
{
    $check = array_intersect($check, $collection);
    if ($check) {
        return $check;
    }

    return $default ? $collection : [];
}

function array_append(array $target, array $data)
{
    foreach ($data as $key => $value) {
        $target = array_insert($target, $key, $value);
    }

    return $target;
}

function array_exist(array $data, string $key = '')
{
    return Collection::check($data, $key);
}

function array_expand(array $data, string $separator = '.')
{
    return Collection::expand($data, $separator);
}

function array_extract(array $data, string $path)
{
    return Collection::extract($data, $path);
}

function array_format(array $data, string $path, string $format = '')
{
    return Collection::format($data, $paths, $format);
}

function array_first(array $data, $default = null)
{
    if ($data) {
        reset($data);

        return current($data);
    }

    return $default;
}

function array_first_key(array $data, $default = null)
{
    if ($data) {
        reset($data);

        return key($data);
    }

    return $default;
}

function array_flatten(array $data, string $separator = '.')
{
    return Collection::flatten($data, $separator);
}

function array_get(array $data, string $path, $default = null)
{
    return Collection::get($data, $path, $default);
}

function array_insert(array $data, string $path, $value = null)
{
    return Collection::insert($data, $path, $value);
}

function array_last(array $data)
{
    return end($data);
}

function array_mask(array $data, array $mask = [])
{
    return array_intersect_key($data, array_flip($mask)) ?? [];
}

function array_remove(array $data, string $path)
{
    return Collection::remove($data, $path);
}

function array_pluck(array $data, string $keyPath, string $valuePath = null, string $groupPath = null)
{
    return Collection::combine($data, $keyPath, $valuePath, $groupPath);
}

function array_tree(array $data, $parentId = 0)
{
    $branch = [];

    foreach ($data as $item) {
        if ($item['parent_id'] == $parentId) {
            $children = array_tree($data, $item['id']);
            if ($children) {
                $item['children'] = $children;
            }
            $branch[] = $item;
        }
    }

    return $branch;
}
