<?php

namespace App\Models\all_member;
use CodeIgniter\Model;

class allmemberModel extends Model
{
    protected $db;
    protected $table = 'mem_h_member';
    protected $primaryKey = 'mem_id';

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    // ดึงข้อมูล
    public function getMembersByMonthYear($limit = 100, $offset = 0, $month = null, $year = null)
    {
        $sql = "
            SELECT *
            FROM (
                SELECT 
                    ROW_NUMBER() OVER (ORDER BY m.mem_id ASC) AS RowNum,
                    m.mem_id,
                    m.empid,
                    p.ptitle_name + ' ' + m.fname + ' ' + m.lname AS fullname,
                    m.shortname,
                    m.tried_flg,
                    m.id_card,
                    m.country_code,
                    mt.memtype,
                    m.section_id,
                    m.subsection_id,
                    s.status_name,
                    m.mem_date,
                    SUBSTRING(m.mem_date, 3, 2) as month,
                    SUBSTRING(m.mem_date, 5, 4) as year,
                    m.kasean_date,
                    m.tried_date,
                    m.dmyretire,
                    v.shr_sum_bth,
                    v.shr_sum_bth / 10 AS shr_amount,
                    m.address,
                    m.tumbol,
                    d.district_name,
                    pr.province_name,
                    m.zip_code,
                    m.pager
                FROM mem_h_member m
                JOIN view_shr_mem_new v ON m.mem_id = v.mem_id
                JOIN mem_m_province pr ON m.province_id = pr.province_id
                JOIN mem_m_district d ON m.district_id = d.district_id 
                    AND d.province_id = pr.province_id
                JOIN mem_m_memtype mt ON m.memtype_id = mt.memtype_id
                JOIN mem_m_status s ON m.status_id = s.status_id
                JOIN mem_m_ptitle p ON m.ptitle_id = p.ptitle_id
                WHERE v.shr_sum_bth > 0
                AND SUBSTRING(m.mem_date, 3, 2) <= ?
                AND SUBSTRING(m.mem_date, 5, 4) <= ?
            ) AS RowConstrainedResult
            WHERE RowNum > $offset AND RowNum <= ($offset + $limit)
            ORDER BY RowNum
        ";
        try {
            return $this->db->query($sql, [$month, $year])->getResult();
        } catch (\Exception $e) {
            log_message('error', '[allmemberModel::getMembersByMonthYear] Error: ' . $e->getMessage());
            return null;
        }
    }

    public function countAllMembers($month = null, $year = null)
    {
        $sql = "SELECT COUNT(*) AS total FROM mem_h_member m
                JOIN view_shr_mem_new v ON m.mem_id = v.mem_id
                WHERE v.shr_sum_bth > 0
                AND SUBSTRING(m.mem_date, 3, 2) <= ?
                AND SUBSTRING(m.mem_date, 5, 4) <= ?";
        $result = $this->db->query($sql, [$month, $year])->getRowArray();
        return $result['total'] ?? 0;
    }
}