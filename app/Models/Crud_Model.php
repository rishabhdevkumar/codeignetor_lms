<?php

namespace App\Models;

use CodeIgniter\Model;

class Crud_Model extends Model
{
    protected $DBGroup = 'default';

    public function selectData($table, $where = [], $orderField = 'id', $order = 'ASC', $start = 0, $limit = 10000000000)
    {
        return $this->db->table($table)
            ->where($where)
            ->orderBy($orderField, $order)
            ->limit($limit, $start)
            ->get()
            ->getResultArray();
    }

    public function selectWhereIn($table, $field, $values = [], $orderField = 'id', $order = 'ASC', $start = 0, $limit = 10000000000)
    {
        return $this->db->table($table)
            ->whereIn($field, $values)
            ->orderBy($orderField, $order)
            ->limit($limit, $start)
            ->get()
            ->getResultArray();
    }

    public function selectWhereLikeOr($table, $data = [], $orderField = 'id', $order = 'ASC', $start = 0, $limit = 10000000000)
    {
        $builder = $this->db->table($table);

        $first = true;
        foreach ($data as $field => $value) {
            if ($first) {
                $builder->like($field, $value);
                $first = false;
            } else {
                $builder->orLike($field, $value);
            }
        }

        return $builder->orderBy($orderField, $order)
            ->limit($limit, $start)
            ->get()
            ->getResultArray();
    }

    public function saveData($table, $data)
    {
        $this->db->table($table)->insert($data);

        return $this->db->insertID();
    }

    public function updateData($table, $data, $condition)
    {
        return $this->db->table($table)
            ->where($condition)
            ->update($data);
    }

    public function deleteData($table, $condition)
    {
        return $this->db->table($table)
            ->where($condition)
            ->delete();
    }

    public function addRow($table, $data)
    {
        return $this->db->table($table)->insert($data);
    }

    public function selectWhereWithColumns($columns, $table, $where = [], $orderField = 'id', $order = 'ASC', $start = 0, $limit = 10000000000)
    {
        return $this->db->table($table)
            ->select($columns)
            ->where($where)
            ->orderBy($orderField, $order)
            ->limit($limit, $start)
            ->get()
            ->getResultArray();
    }

    public function select(
        string $table,
        array $where,
        string $orderField,
        string $order,
        int $start = 0,
        int $limit = 1000000000
    ) {
        $builder = $this->db->table($table);

        $builder->where($where)
            ->orderBy($orderField, $order)
            ->limit($limit, $start);

        $query = $builder->get();

        $result = $query->getResultArray();

        return ! empty($result) ? $result : false;
    }

    // public function select(
    //     string $table,
    //     array $where = [],
    //     array $whereRaw = [],
    //     string $orderField = 'id',
    //     string $order = 'ASC',
    //     int $start = 0,
    //     int $limit = 1000000000
    // ) {
    //     $builder = $this->db->table($table);

    //     if ($where) {
    //         $builder->where($where);
    //     }

    //     foreach ($whereRaw as $condition) {
    //         $builder->where($condition, null, false);
    //     }

    //     $builder->orderBy($orderField, $order)
    //         ->limit($limit, $start);

    //     $result = $builder->get()->getResultArray();

    //     return $result ?: false;
    // }
}
