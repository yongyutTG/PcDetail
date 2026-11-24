<?php
namespace App\Controllers;

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
        return view('log_view', ['logContent' => nl2br($logContent)])
            .view('templates/header', ['title' => 'Log Viewer'])
            . view('templates/navbar', ['title' => 'Log Viewer'])
            . view('pages/home', ['title' => 'Log Viewer'])
            . view('templates/footer', ['title' => 'Log Viewer']);
    }

    private function getLatestLogFile($path)
    {
        $files = glob($path . 'log-*.log');
        if (!$files) return null;

        rsort($files); // เรียงจากล่าสุด
        return $files[0];
    }
}
