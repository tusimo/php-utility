<?php

if (!function_exists('uuid')) {
    function uuid()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime()*10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid = chr(123)
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid, 12, 4).$hyphen
                .substr($charid, 16, 4).$hyphen
                .substr($charid, 20, 12)
                .chr(125);
            return $uuid;
        }
    }
}

if (!function_exists('is_cli')) {
    /**
     * detect if run in cli mode
     *
     * @return bool
     */
    function is_cli()
    {
        if (defined('STDIN')) {
            return true;
        }

        if (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0) {
            return true;
        }

        return false;
    }
}

if (! function_exists('class_constants')) {
    /**
     * get class constants as array and filter by a prefix
     * @param $class
     * @param null|string $prefix
     * @return array
     */
    function class_constants($class, $prefix = null)
    {
        $constants = (new \ReflectionClass($class))->getConstants();

        if (!empty($prefix)) {
            return array_filter($constants, function ($key) use ($prefix) {
                return strpos($key, $prefix) === 0;
            }, ARRAY_FILTER_USE_KEY);
        }

        return $constants;
    }
}

if (!function_exists('distance')) {
    /**
     * calculate distance between two point
     * @param $lon1
     * @param $lat1
     * @param $lon2
     * @param $lat2
     * @return float
     */
    function distance($lon1, $lat1, $lon2, $lat2)
    {
        $EARTH_RADIUS = 6378.137;
        $radLat1 = deg2rad($lat1);
        $radLat2 = deg2rad($lat2);
        $a = $radLat1 - $radLat2;
        $b = deg2rad($lon1) - deg2rad($lon2);
        $s = 2 * asin(sqrt(pow(sin($a/2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2), 2)));
        $s = $s *$EARTH_RADIUS;
        $s = round($s * 10000) / 10000;
        return $s;
    }
}


if (!function_exists('rename_array_keys')) {
    /**
     * rename array keys recursive
     * @param $array
     * @param string $callback
     */
    function rename_array_keys(&$array, $callback = 'snake_case')
    {
        foreach (array_keys($array) as $key) {
            $value = &$array[$key];
            unset($array[$key]);
            $transformedKey = call_user_func($callback, $key);
            if (is_array($value)) {
                rename_array_keys($value, $callback);
            }
            $array[$transformedKey] = $value;
            unset($value);
        }
    }
}

if (!function_exists('replace_array_keys')) {
    /**
     * replace array keys by a key map
     * key will be ignored when not exists
     * @param $target
     * @param $maps
     * @return array
     */
    function replace_array_keys($target, $maps)
    {
        if (empty($maps)) {
            return $target;
        }
        foreach ($maps as $key => $castMap) {
            if (is_numeric($key)) {
                $maps[$castMap] = $castMap;
                unset($maps[$key]);
            }
        }
        $items = [];
        $unFindKey = uuid();
        foreach ($maps as $key => $item) {
            if ($unFindKey === ($value = data_get($target, $key, $unFindKey))) {
                continue;
            } else {
                data_set($items, $item, $value);
            }
        }
        return $items;
    }
}


if (!function_exists('get_array_by_keys')) {
    /**
     * get array items by key map recursive
     * @param $target
     * @param $array
     * @return array
     */
    function get_array_by_keys($target, $array)
    {
        if (!is_array($array)) {
            return [];
        }
        $result = [];
        foreach ($array as $key => $item) {
            if (array_key_exists($key, $target)) {
                if (is_array($target[$key]) && !array_key_exists(0, $target[$key])) {
                    $result[$key] = get_array_by_keys($target[$key], $item);
                } else {
                    $result[$key] = $target[$key];
                }
            }
        }
        return $result;
    }
}

if (!function_exists('is_mobile')) {
    /**
     * detect mobile is chinese valid mobile
     * @param $mobile
     * @return bool
     */
    function is_mobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match(
            '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,1,3,6,7,8]{1}\d{8}$|^18[\d]{9}$#',
            $mobile
        ) ? true : false;
    }
}

if (!function_exists('is_email')) {
    /** detect email is valid
     * @param $email
     * @return bool
     */
    function is_email($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('array_to_tree')) {
    /**
     * covert a array to tree
     * @param array $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param string $root
     * @return array
     */
    function array_to_tree(array $list, $pk = 'key', $pid = 'parent_key', $child = 'value', $root = '')
    {
        if (empty($list)) {
            return [];
        }
        $tree = [];
        if (!empty($list)) {
            $newList = [];
            foreach ($list as $item) {
                $newList[$item[$pk]] = $item;
            }
            foreach ($newList as $value) {
                if ($root == $value[$pid]) {
                    $tree[] = &$newList[$value[$pk]];
                } elseif (isset($newList[$value[$pid]])) {
                    $newList[$value[$pid]][$child][] = &$newList[$value[$pk]];
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('object_to_array')) {
    /**
     * covert object to array
     * @param $object
     * @return mixed
     */
    function object_to_array($object)
    {
        return json_decode(json_encode($object), true);
    }
}

if (!function_exists('string_to_array')) {
    /**
     * covert a string to array
     * @param $string
     * @param string $option json|JSON as covert a json string to array and else as delimiter for a string to explode
     * @param null $callback covert array value ,use intval strval floatval boolval and custom callback
     * @return array|mixed
     */
    function string_to_array($string, $option = ',', $callback = null)
    {
        $item = [];
        if ($string != '' && !is_null($string)) {
            if (strtoupper($option) == 'JSON') {
                $item = json_decode($string, true);
                if (!is_array($item)) {
                    $item = [];
                }
            } else {
                $item = explode($option, $string);
            }
        }
        if ($callback) {
            return array_map($callback, $item);
        }
        return $item;
    }
}

if (!function_exists('integer_disassemble')) {
    function integer_disassemble ($integer)
    {
        $result = [];
        if (!is_integer($integer) || $integer <= 0) {
            return $result;
        }
        foreach (array_reverse(str_split(decbin($integer))) as $key => $item) {
            $item && $result[] = pow(2, $key);
        }
        return $result;
    }
}
