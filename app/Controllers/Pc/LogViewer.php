<?php
namespace App\Controllers\Pc;

use CodeIgniter\Controller;

class LogViewer extends Controller
{
    public function index()
    {
        $logPath = WRITEPATH . 'logs/';
        $latestLogFile = $this->getLatestLogFile($logPath);

        if (!$latestLogFile) {
            return 'ไม่พบ log';
        }

        $logContent = file_get_contents($latestLogFile);
        return $this->response->setContentType('text/plain')->setBody($logContent);
    }

    private function getLatestLogFile($path)
    {
        $files = glob($path . 'log-*.log');
        if (!$files) return null;

        rsort($files); // เรียงจากล่าสุด
        return $files[0];
    }
}
