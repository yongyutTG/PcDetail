<?php

namespace App\Controllers\Pc;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


$response = service('response');
$response->setHeader('Access-Control-Allow-Origin', 'http://localhost:8080'); // อนุ

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
  // โหลด session ถ้ายังไม่ได้เปิด
    $this->session = \Config\Services::session();

    // ✅ ดึงชื่อฐานข้อมูล + วันที่
    $db = \Config\Database::connect();
    $query = $db->query("SELECT CONVERT(varchar(19), GETDATE(), 120) AS db_date");
    $row = $query->getRow();

    $this->dbName = $db->database;
    if ($row && isset($row->db_date)) {
        $date = new \DateTime($row->db_date);
        $this->dbDate = $date->format('d/m/Y H:i:s');
    } else {
        $this->dbDate = '-';
    }

    // ✅ แชร์ตัวแปรไปยังทุก view
    view()->setVar('dbName', $this->dbName);
    view()->setVar('dbDate', $this->dbDate);

        // E.g.: $this->session = \Config\Services::session();
    }
}
