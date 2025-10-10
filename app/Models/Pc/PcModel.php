<?php
namespace App\Models\Pc;
use CodeIgniter\Model;

class PcModel extends Model{
     protected $table = 'pc_detail_master'; 
    protected $primaryKey = 'pc_id';        
    protected $allowedFields = ['pc_id','user_name', 'computer_name', 'login_user', 'terminal_server', 'terminal_login', 'location', 'band', 'model', 'ip_address', 'ram', 'harddisk', 'cpu', 'os', 'office', 'solfware', 'printer', 'printer_share_name', 'outlet_port', 'use_status', 'remark', 'br_no', 'serial_no', 'buy_date', 'property_code', 'property_type', 'monitor'];
    protected $useAutoIncrement = false;


public function getAllDetails($limit =17, int $offset = 0)
{
$sql = "SELECT * 
        FROM pc_detail_master 
        ORDER BY pc_id
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
return $this->db->query($sql, [$offset, $limit])->getResultArray();
}
public function countAllDetails()
{
    $sql = "SELECT COUNT(*) AS total FROM pc_detail_master";
    $row = $this->db->query($sql)->getRowArray();
    return $row['total'] ?? 0;
}

public function getFilteredDetails($ip = null, $ips = null, $limit = 17, int $offset = 0)
{
    $sql = "SELECT * FROM pc_detail_master WHERE 1=1";
    $params = [];

    if ($ip) {
        $sql .= " AND ip_address = ?";
        $params[] = trim($ip);
    }

    if ($ips) {
        $ipList = array_map('trim', explode(',', $ips));
        $placeholders = implode(',', array_fill(0, count($ipList), '?'));
        $sql .= " AND ip_address IN ($placeholders)";
        $params = array_merge($params, $ipList);
    }

    $sql .= " ORDER BY pc_id OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params[] = $offset;
    $params[] = $limit;

    return $this->db->query($sql, $params)->getResultArray();
}

public function countFilteredDetails($ip = null, $ips = null)
{
    $sql = "SELECT COUNT(*) AS total FROM pc_detail_master WHERE 1=1";
    $params = [];

    if ($ip) {
        $sql .= " AND ip_address = ?";
        $params[] = trim($ip);
    }

    if ($ips) {
        $ipList = array_map('trim', explode(',', $ips));
        $placeholders = implode(',', array_fill(0, count($ipList), '?'));
        $sql .= " AND ip_address IN ($placeholders)";
        $params = array_merge($params, $ipList);
    }

    $row = $this->db->query($sql, $params)->getRowArray();
    return $row['total'] ?? 0;
}



public function getDetailById($id){
$query = $this->db->query("
SELECT * FROM pc_detail_master WHERE pc_id = ?", [$id]);
return $query->getRow();
}

   public function getHistoryById($id)
    {
        $query = $this->db->query("
            SELECT 
                s.pc_id,
                m.date_update,
                m.detail,
                m.userid,
                m.last_update
            FROM pc_detail_master AS s
            JOIN pc_t_detail AS m 
                ON s.pc_id = m.pc_id
            WHERE s.pc_id = ?
            ORDER BY m.date_update DESC
        ", [$id]);

        return $query->getResult(); 
    }

    public function updateDataById($id, $data){
    // Convert empty string or '1900-01-01 00:00:00' to null for all fields
    $fields = [
        'user_name', 'computer_name', 'login_user', 'terminal_server', 'terminal_login',
        'location', 'band', 'model', 'ip_address', 'ram', 'harddisk', 'cpu', 'os', 'office',
        'solfware', 'printer', 'printer_share_name', 'outlet_port', 'use_status', 'remark',
        'br_no', 'serial_no', 'buy_date', 'property_code', 'property_type', 'monitor'
    ];
    $params = [];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || $data[$field] === '' || $data[$field] === '1900-01-01 00:00:00') {
            $params[] = null;
        } else {
            $params[] = $data[$field];
        }
    }
    $sql = "UPDATE pc_detail_master SET 
        user_name = ?, 
        computer_name = ?, 
        login_user = ?, 
        terminal_server = ?, 
        terminal_login = ?,
        location = ?, 
        band = ?, 
        model = ?, 
        ip_address = ?, 
        ram = ?, 
        harddisk = ?, 
        cpu = ?, 
        os = ?, 
        office = ?, 
        solfware = ?, 
        printer = ?, 
        printer_share_name = ?, 
        outlet_port = ?, 
        use_status = ?, 
        remark = ?,
        br_no = ?, 
        serial_no = ?, 
        buy_date = ?, 
        property_code = ?, 
        property_type = ?, 
        monitor = ?
        WHERE pc_id = ?";
    $params[] = $id;
    return $this->db->query($sql, $params);
}


public function insertData()
{
    $data = $this->request->getJSON(true) ?? $this->request->getPost();

    if (!$data) {
        return $this->fail('No data provided');
    }

    // หาค่า max(PC_ID)
    $maxIdResult = $this->db->query("SELECT MAX(pc_id) AS max_id FROM pc_detail_master")->getRowArray();
    $newId = ($maxIdResult['max_id'] ?? 0) + 1;

    $sql = "INSERT INTO pc_detail_master (
                pc_id, user_name, computer_name, login_user, terminal_server, terminal_login, 
                location, band, model, ip_address, ram, harddisk, cpu, os, office, solfware, 
                printer, printer_share_name, outlet_port, use_status, remark, br_no, serial_no, 
                buy_date, property_code, property_type, monitor
            ) VALUES (
                :pc_id:, :user_name:, :computer_name:, :login_user:, :terminal_server:, :terminal_login:,
                :location:, :band:, :model:, :ip_address:, :ram:, :harddisk:, :cpu:, :os:, :office:, :solfware:,
                :printer:, :printer_share_name:, :outlet_port:, :use_status:, :remark:, :br_no:, :serial_no:,
                :buy_date:, :property_code:, :property_type:, :monitor:
            )";

   $params = [
    'pc_id' => $newId,
    'user_name' => !empty($data['user_name']) ? $data['user_name'] : null,
    'computer_name' => !empty($data['computer_name']) ? $data['computer_name'] : null,
    'login_user' => !empty($data['login_user']) ? $data['login_user'] : null,
    'terminal_server' => !empty($data['terminal_server']) ? $data['terminal_server'] : null,
    'terminal_login' => !empty($data['terminal_login']) ? $data['terminal_login'] : null,
    'location' => !empty($data['location']) ? $data['location'] : null,
    'band' => !empty($data['band']) ? $data['band'] : null,
    'model' => !empty($data['model']) ? $data['model'] : null,
    'ip_address' => !empty($data['ip_address']) ? $data['ip_address'] : null,
    'ram' => !empty($data['ram']) ? $data['ram'] : null,
    'harddisk' => !empty($data['harddisk']) ? $data['harddisk'] : null,
    'cpu' => !empty($data['cpu']) ? $data['cpu'] : null,
    'os' => !empty($data['os']) ? $data['os'] : null,
    'office' => !empty($data['office']) ? $data['office'] : null,
    'solfware' => !empty($data['solfware']) ? $data['solfware'] : null,
    'printer' => !empty($data['printer']) ? $data['printer'] : null,
    'printer_share_name' => !empty($data['printer_share_name']) ? $data['printer_share_name'] : null,
    'outlet_port' => !empty($data['outlet_port']) ? $data['outlet_port'] : null,
    'use_status' => !empty($data['use_status']) ? $data['use_status'] : null,
    'remark' => !empty($data['remark']) ? $data['remark'] : null,
    'br_no' => !empty($data['br_no']) ? $data['br_no'] : null,
    'serial_no' => !empty($data['serial_no']) ? $data['serial_no'] : null,
    'buy_date' => !empty($data['buy_date']) ? $data['buy_date'] : null,
    'property_code' => !empty($data['property_code']) ? $data['property_code'] : null,
    'property_type' => !empty($data['property_type']) ? $data['property_type'] : null,
    'monitor' => !empty($data['monitor']) ? $data['monitor'] : null,
];


    $result = $this->db->query($sql, $params);

    if ($result) {
        return $this->respondCreated([
            'status' => 'success',
            'message' => 'PC added successfully',
            'PC_ID' => $newId
        ]);
    }

    return $this->fail('Failed to add PC');
}


public function getSearchStatus(string $property_type = '' ,string $br_no = '', string $status = '', string $keyword = '', int $limit = 17, int $offset = 0)
{
    $sql = "SELECT * FROM pc_detail_master WHERE 1=1";
    $params = [];

 // Filter br_no
    if (!empty($property_type)) {
        $sql .= " AND property_type = ?";
        $params[] = $property_type;
    }

     // Filter br_no
    if (!empty($br_no)) {
        $sql .= " AND br_no = ?";
        $params[] = $br_no;
    }

    // Filter status
    if (!empty($status)) {
        $sql .= " AND use_status = ?";
        $params[] = $status;
    }

    // Filter keyword
    if (!empty($keyword)) {
        $sql .= " AND (
            pc_id LIKE ? OR
            user_name LIKE ? OR
            computer_name LIKE ? OR
            login_user LIKE ? OR
            terminal_server LIKE ? OR
            terminal_login LIKE ? OR
            location LIKE ? OR
            band LIKE ? OR
            model LIKE ? OR
            ip_address LIKE ? OR
            ram LIKE ? OR
            harddisk LIKE ? OR
            cpu LIKE ? OR
            os LIKE ? OR
            office LIKE ? OR
            solfware LIKE ? OR
            printer LIKE ? OR
            printer_share_name LIKE ? OR
            outlet_port LIKE ? OR
            remark LIKE ? OR
            br_no LIKE ? OR
            serial_no LIKE ? OR
            buy_date LIKE ? OR
            property_code LIKE ? OR
            property_type LIKE ? OR
            monitor LIKE ?
        )";
        $keywordParam = array_fill(0, 26, "%{$keyword}%");
        $params = array_merge($params, $keywordParam);
    }

    // Pagination
    $sql .= " ORDER BY pc_id OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params[] = (int)$offset;
    $params[] = (int)$limit;

    return $this->db->query($sql, $params)->getResultArray();
}

public function countSearchStatus(string $property_type = '' , string $br_no = '', string $status = '', string $keyword = '')
{
    $sql = "SELECT COUNT(*) as total FROM pc_detail_master WHERE 1=1";
    $params = [];

    
     if (!empty($property_type)) {
        $sql .= " AND property_type = ?";
        $params[] = $property_type;
    }

     if (!empty($br_no)) {
        $sql .= " AND br_no = ?";
        $params[] = $br_no;
    }

    if (!empty($status)) {
        $sql .= " AND use_status = ?";
        $params[] = $status;
    }

    if (!empty($keyword)) {
        $sql .= " AND (
            pc_id LIKE ? OR
            user_name LIKE ? OR
            computer_name LIKE ? OR
            login_user LIKE ? OR
            terminal_server LIKE ? OR
            terminal_login LIKE ? OR
            location LIKE ? OR
            band LIKE ? OR
            model LIKE ? OR
            ip_address LIKE ? OR
            ram LIKE ? OR
            harddisk LIKE ? OR
            cpu LIKE ? OR
            os LIKE ? OR
            office LIKE ? OR
            solfware LIKE ? OR
            printer LIKE ? OR
            printer_share_name LIKE ? OR
            outlet_port LIKE ? OR
            remark LIKE ? OR
            br_no LIKE ? OR
            serial_no LIKE ? OR
            buy_date LIKE ? OR
            property_code LIKE ? OR
            property_type LIKE ? OR
            monitor LIKE ?
        )";
        $keywordParam = array_fill(0, 26, "%{$keyword}%");
        $params = array_merge($params, $keywordParam);
    }

    $row = $this->db->query($sql, $params)->getRowArray();
    return $row['total'] ?? 0;
}

// LogPC ต้องการเรียง last_update
public function getAllDetailsLog($limit = 17, int $offset = 0)
{
$sql = "SELECT * 
        FROM pc_t_detail
        ORDER BY last_update DESC
        OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
return $this->db->query($sql, [$offset, $limit])->getResultArray();
}
public function countAllDetailsLog()
{
    $sql = "SELECT COUNT(*) AS total FROM pc_t_detail";
    $row = $this->db->query($sql)->getRowArray();
    return $row['total'] ?? 0;
}

public function getSearchStatusLog(string $keyword = '', int $limit = 17, int $offset = 0)
{
    $sql = "SELECT * FROM pc_t_detail WHERE 1=1";
    $params = [];

    // Filter keyword
    if (!empty($keyword)) {
        $sql .= " AND (
            pc_id LIKE ? OR
            date_update LIKE ? OR
            detail LIKE ? OR
            userid LIKE ? OR
            last_update LIKE ?
        )";
        $keywordParam = array_fill(0, 5, "%{$keyword}%");
        $params = array_merge($params, $keywordParam);
    }

    // Pagination
    $sql .= " ORDER BY pc_id OFFSET ? ROWS FETCH NEXT ? ROWS ONLY";
    $params[] = (int)$offset;
    $params[] = (int)$limit;

    return $this->db->query($sql, $params)->getResultArray();
}

public function countSearchStatusLog(string $keyword = '')
{
    $sql = "SELECT COUNT(*) as total FROM pc_t_detail WHERE 1=1";
    $params = [];
    if (!empty($keyword)) {
        $sql .= " AND (
            pc_id LIKE ? OR
            date_update LIKE ? OR
            detail LIKE ? OR
            userid LIKE ? OR
            last_update LIKE ?

        )";
        $keywordParam = array_fill(0, 5, "%{$keyword}%");
        $params = array_merge($params, $keywordParam);
    }

    $row = $this->db->query($sql, $params)->getRowArray();
    return $row['total'] ?? 0;
}


public function countPc()
{
    $sql = "SELECT COUNT(pc_id) AS total FROM pc_detail_master WHERE property_type = 'PC'";
    $row = $this->db->query($sql)->getRowArray();
    return $row['total'] ?? 0;
}
public function countPcStatusN()
{
    $sql = "SELECT COUNT(pc_id) AS total FROM pc_detail_master WHERE use_status = 'N' and property_type = 'PC'";
    $row = $this->db->query($sql)->getRowArray();
    return $row['total'] ?? 0;
}
public function countPcStatusY()
{
    $sql = "SELECT COUNT(use_status) AS total FROM pc_detail_master WHERE use_status = 'A' and property_type = 'PC'";
    $row = $this->db->query($sql)->getRowArray();
    return $row['total'] ?? 0;
}



  // ฟังก์ชันนับจำนวนเครื่องตาม location
    public function getPcCountByLocation()
    {
        return $this->select('location, COUNT(*) as total')
        ->where('use_status', 'A')  
        ->where('property_type', 'PC')
                    ->groupBy('location')
                    ->orderBy('total', 'DESC')
                    ->findAll();
    }

     public function getPcCountByBranch()
    {
        $sql = "
            SELECT 
                br_no,
                CASE br_no
                    WHEN '001' THEN 'ลาดพร้าว'
                    WHEN '002' THEN 'ดอนเมือง'
                    WHEN '003' THEN 'สุวรรณภูมิ'
                    ELSE 'อื่น ๆ'
                END AS branch_name,
                COUNT(*) AS total
            FROM pc_detail_master
            WHERE use_status = 'A'and property_type = 'PC'
            GROUP BY br_no
            ORDER BY br_no
        ";

        return $this->db->query($sql)->getResultArray();
    }

    // ฟังก์ชันนรายการเพิ่มเครื่องคอมพิวเตอร์ล่าสุด
    public function getRecentPcAdditions(int $limit = 3)
{
    $sql = "SELECT TOP($limit) *
            FROM pc_detail_master
            WHERE use_status = 'A'
            ORDER BY pc_id DESC";
    return $this->db->query($sql)->getResultArray();
}

// ฟังก์ชันนรายการแก้ไขเครื่องคอมพิวเตอร์ล่าสุด
    public function getRecentPcEditions(int $limit = 3)
{
    $sql = "SELECT TOP($limit) *
            FROM pc_t_detail
            ORDER BY last_update DESC";
    return $this->db->query($sql)->getResultArray();
}


 // ฟังก์ชันดึง location ไม่ซ้ำ
    public function getDistinctLocations()
    {
        return $this->select('location')
                    ->distinct()
                    ->orderBy('location', 'ASC')
                    ->findAll();
    }
 // ฟังก์ชันดึง os ไม่ซ้ำ
    public function getDistinctOs()
    {
        return $this->select('os')
                    ->distinct()
                    ->orderBy('os', 'ASC')
                    ->findAll();
    }
    
 // ฟังก์ชันดึง property_type ไม่ซ้ำ
     public function getDistinctPropertyType()
    {
        return $this->select('property_type')
                    ->distinct()
                    ->orderBy('property_type', 'ASC')
                    ->findAll();
    }
}