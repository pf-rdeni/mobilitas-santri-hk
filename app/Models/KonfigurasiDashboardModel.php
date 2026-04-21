<?php

namespace App\Models;

use CodeIgniter\Model;

class KonfigurasiDashboardModel extends Model
{
    protected $table            = 'konfigurasi_dashboard';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['slug', 'nilai', 'keterangan', 'updated_at'];
    protected $useTimestamps    = true;
    protected $updatedField     = 'updated_at';

    public function getRangeGroups()
    {
        $data = $this->whereIn('slug', ['range_grup_1', 'range_grup_2', 'range_grup_3', 'range_grup_4'])->findAll();
        $groups = [];
        foreach ($data as $row) {
            $groups[$row->slug] = $row->nilai;
        }
        return $groups;
    }

    public function updateRange($slug, $nilai)
    {
        return $this->where('slug', $slug)->set(['nilai' => $nilai])->update();
    }
}
