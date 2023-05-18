<?php

namespace App\Helpers;

class QueryHelper
{

    public function __construct()
    {
    }

    public function setFilter(&$query, $filter, $allowed, $allowed_map = [])
    {
        if (!$filter) {
            return $query;
        }
        $qresult = '';
        $condition = [];
        foreach ($filter as $key => $val) {
            if (is_numeric($key)) {continue;}
            if (in_array($key, $allowed)) {
                if (empty($val)) {continue;}
                if (substr($val,0,1) == '%') {
                    $condition[] = $val . '%';
                    $i = (!empty($allowed_map[$key])) ? $allowed_map[$key] . ' LIKE ?' : $key . ' LIKE ?';
                } else {
                    $condition[] = $val;
                    $i = (!empty($allowed_map[$key])) ? $allowed_map[$key] . ' = ?' : $key . ' = ?';
                }
                $qresult .= ' AND ' . $i;
            }
        }
        $query .= $qresult;
        return $condition;
    }

    public function setLimit(&$query, $page, $limit)
    {
        if (!$limit) {
            return $query;
        }
        $query .= ' LIMIT ' . $limit . ' OFFSET ' . ($limit * ($page - 1));
        return $query;
    }

    public function setOrder(&$query, $order)
    {
        $query .= ' ORDER BY ' . $order;
        return $query;
    }

    public function setInput($data, $allowed)
    {
        $result = [];
        // var_dump($data);
        foreach ($data as $key => $val) {
            if (in_array($key, $allowed)) {
                if (empty($val)) {
                    $val = null;
                }
                $result[$key] = $val;
            }
        }
        return $result;
    }
}
