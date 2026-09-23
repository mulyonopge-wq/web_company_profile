<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Helpers\CsrfHelper;
use App\Helpers\FlashHelper;
use App\Helpers\UrlHelper;
use Throwable;

class UpdateController extends AdminBaseController
{
    private string $repoPath;

    public function __construct()
    {
        parent::__construct();
        // Project root directory
        $this->repoPath = dirname(__DIR__, 3);
    }

    public function index(): void
    {
        $execMethod = $this->getAvailableExecMethod();
        $execAllowed = ($execMethod !== null);
        $gitInfo = [
            'installed' => false,
            'version' => '',
            'branch' => 'main',
            'current_commit' => '',
            'current_tag' => '',
            'remote_url' => '',
            'status' => '',
            'behind_count' => 0,
            'commits_behind' => [],
            'has_uncommitted' => false,
        ];

        $outputLog = $_SESSION['update_log'] ?? null;
        unset($_SESSION['update_log']);

        if ($execAllowed) {
            try {
                $gitVer = $this->runCommand('git --version');
                if ($gitVer['success'] && !empty($gitVer['output'])) {
                    $gitInfo['installed'] = true;
                    $gitInfo['version'] = trim($gitVer['output']);

                    // Current branch
                    $branchCmd = $this->runCommand('git rev-parse --abbrev-ref HEAD');
                    $gitInfo['branch'] = trim($branchCmd['output'] ?: 'main');

                    // Current commit
                    $commitCmd = $this->runCommand('git log -1 --format="%h - %s (%cr)"');
                    $gitInfo['current_commit'] = trim($commitCmd['output'] ?: '-');

                    // Current tag
                    $tagCmd = $this->runCommand('git describe --tags --always');
                    $gitInfo['current_tag'] = trim($tagCmd['output'] ?: '-');

                    // Remote URL
                    $remoteCmd = $this->runCommand('git remote get-url origin');
                    $gitInfo['remote_url'] = trim($remoteCmd['output'] ?: '-');

                    // Check local changes
                    $statusCmd = $this->runCommand('git status --porcelain');
                    $gitInfo['status'] = trim($statusCmd['output']);
                    $gitInfo['has_uncommitted'] = !empty($gitInfo['status']);
                }
            } catch (Throwable $e) {
                // Silently fallback if git command fails
            }
        }

        $this->renderAdminView('admin/update/index', [
            'title' => 'Update dari GitHub',
            'execAllowed' => $execAllowed,
            'execMethod' => $execMethod,
            'gitInfo' => $gitInfo,
            'outputLog' => $outputLog,
            'repoPath' => $this->repoPath,
        ]);
    }

    public function check(): void
    {
        CsrfHelper::verifyPost();

        if ($this->getAvailableExecMethod() === null) {
            FlashHelper::danger('Fungsi eksekusi perintah (exec / shell_exec) dinonaktifkan di konfigurasi PHP server.');
            UrlHelper::redirect('/admin/update');
            return;
        }

        try {
            // Fetch updates from origin
            $fetch = $this->runCommand('git fetch origin main 2>&1');
            
            // Count commits behind
            $countCmd = $this->runCommand('git rev-list HEAD..origin/main --count');
            $count = (int) trim($countCmd['output'] ?: '0');

            if ($count > 0) {
                $logCmd = $this->runCommand('git log HEAD..origin/main --oneline');
                $_SESSION['update_log'] = [
                    'type' => 'info',
                    'title' => "Tersedia {$count} pembaruan baru dari GitHub!",
                    'command' => 'git fetch origin main',
                    'output' => $logCmd['output'] ?: 'Ada pembaruan siap ditarik.',
                ];
                FlashHelper::info("Ditemukan {$count} commit pembaruan baru di GitHub. Silakan klik tombol 'Tarik Pembaruan'.");
            } else {
                $_SESSION['update_log'] = [
                    'type' => 'success',
                    'title' => 'Aplikasi sudah menggunakan versi terbaru dari GitHub!',
                    'command' => 'git fetch origin main',
                    'output' => "Status: Up-to-date with origin/main.\n" . ($fetch['output'] ?: 'Tidak ada pembaruan baru yang ditemukan.'),
                ];
                FlashHelper::success('Aplikasi Anda sudah mutakhir (Up-to-date) dengan versi GitHub!');
            }
        } catch (Throwable $e) {
            FlashHelper::danger('Gagal memeriksa pembaruan: ' . $e->getMessage());
        }

        UrlHelper::redirect('/admin/update');
    }

    public function pull(): void
    {
        CsrfHelper::verifyPost();

        if ($this->getAvailableExecMethod() === null) {
            FlashHelper::danger('Fungsi eksekusi perintah (exec / shell_exec) dinonaktifkan di konfigurasi PHP server.');
            UrlHelper::redirect('/admin/update');
            return;
        }

        try {
            $result = $this->runCommand('git pull origin main 2>&1');

            $_SESSION['update_log'] = [
                'type' => $result['success'] ? 'success' : 'danger',
                'title' => $result['success'] ? 'Proses Git Pull Berhasil!' : 'Proses Git Pull Mengalami Kendala',
                'command' => 'git pull origin main',
                'output' => $result['output'],
            ];

            if ($result['success']) {
                FlashHelper::success('Pembaruan dari GitHub berhasil ditarik dan diterapkan ke aplikasi!');
            } else {
                if (str_contains($result['output'], 'Permission denied') || str_contains($result['output'], 'FETCH_HEAD')) {
                    FlashHelper::danger('Izin akses ditolak (Permission denied): Folder .git dimiliki oleh user root. Silakan jalankan perintah <code>chown -R www:www ' . htmlspecialchars($this->repoPath, ENT_QUOTES, 'UTF-8') . '</code> di Terminal aaPanel Anda.');
                } else {
                    FlashHelper::warning('Git pull gagal diterapkan. Jika terjadi konflik file lokal, gunakan opsi "Reset Paksa ke Versi GitHub".');
                }
            }
        } catch (Throwable $e) {
            FlashHelper::danger('Error saat menarik pembaruan: ' . $e->getMessage());
        }

        UrlHelper::redirect('/admin/update');
    }

    public function resetHard(): void
    {
        CsrfHelper::verifyPost();

        if ($this->getAvailableExecMethod() === null) {
            FlashHelper::danger('Fungsi eksekusi perintah (exec / shell_exec) dinonaktifkan di konfigurasi PHP server.');
            UrlHelper::redirect('/admin/update');
            return;
        }

        try {
            $fetch = $this->runCommand('git fetch origin main 2>&1');
            $reset = $this->runCommand('git reset --hard origin/main 2>&1');

            $combinedOutput = "--- GIT FETCH ---\n" . $fetch['output'] . "\n\n--- GIT RESET HARD ---\n" . $reset['output'];

            $_SESSION['update_log'] = [
                'type' => $reset['success'] ? 'success' : 'danger',
                'title' => $reset['success'] ? 'Reset Paksa ke Versi GitHub Berhasil!' : 'Gagal Melakukan Reset Paksa',
                'command' => 'git fetch origin main && git reset --hard origin/main',
                'output' => $combinedOutput,
            ];

            if ($reset['success']) {
                FlashHelper::success('Kode program berhasil disinkronkan 100% dengan GitHub repository (file .env & folder upload tetap aman)!');
            } else {
                if (str_contains($combinedOutput, 'Permission denied') || str_contains($combinedOutput, 'FETCH_HEAD')) {
                    FlashHelper::danger('Izin akses ditolak (Permission denied): Folder .git dimiliki oleh user root. Silakan jalankan perintah <code>chown -R www:www ' . htmlspecialchars($this->repoPath, ENT_QUOTES, 'UTF-8') . '</code> di Terminal aaPanel Anda.');
                } else {
                    FlashHelper::danger('Gagal melakukan reset paksa. Silakan cek pesan log terminal di bawah.');
                }
            }
        } catch (Throwable $e) {
            FlashHelper::danger('Error saat reset paksa: ' . $e->getMessage());
        }

        UrlHelper::redirect('/admin/update');
    }

    /**
     * Check which command execution function is available and enabled
     */
    private function getAvailableExecMethod(): ?string
    {
        $rawDisabled = (string) ini_get('disable_functions');
        $disabled = array_map('trim', explode(',', $rawDisabled));

        // Prefer exec, then shell_exec, then proc_open
        if (function_exists('exec') && !in_array('exec', $disabled, true)) {
            return 'exec';
        }
        if (function_exists('shell_exec') && !in_array('shell_exec', $disabled, true)) {
            return 'shell_exec';
        }
        if (function_exists('proc_open') && !in_array('proc_open', $disabled, true)) {
            return 'proc_open';
        }
        return null;
    }

    /**
     * Safely execute shell command across OS and server environments
     */
    private function runCommand(string $command): array
    {
        $method = $this->getAvailableExecMethod();
        if ($method === null) {
            return [
                'success' => false,
                'output' => 'Fungsi eksekusi perintah (exec/shell_exec) dinonaktifkan di server.',
                'exitCode' => 1,
            ];
        }

        // Prepend git safe.directory option if it's a git command to prevent dubious ownership error on Linux
        if (str_starts_with(ltrim($command), 'git ')) {
            $command = 'git -c safe.directory=* ' . substr(ltrim($command), 4);
        }

        try {
            $isWindows = (DIRECTORY_SEPARATOR === '\\');
            $cdCmd = $isWindows 
                ? 'cd /D "' . str_replace('/', '\\', $this->repoPath) . '"'
                : 'cd ' . escapeshellarg($this->repoPath);

            $fullCmd = "{$cdCmd} && {$command} 2>&1";

            if ($method === 'exec') {
                $outputLines = [];
                $exitCode = 0;
                @exec($fullCmd, $outputLines, $exitCode);
                return [
                    'success' => ($exitCode === 0),
                    'output' => trim(implode("\n", $outputLines)),
                    'exitCode' => $exitCode,
                ];
            }

            if ($method === 'shell_exec') {
                $output = @shell_exec($fullCmd);
                return [
                    'success' => ($output !== null && $output !== false),
                    'output' => trim((string) $output),
                    'exitCode' => 0,
                ];
            }

            if ($method === 'proc_open') {
                $descriptors = [
                    0 => ['pipe', 'r'],
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ];
                $process = @proc_open($command, $descriptors, $pipes, $this->repoPath);
                if (is_resource($process)) {
                    fclose($pipes[0]);
                    $stdout = stream_get_contents($pipes[1]) ?: '';
                    fclose($pipes[1]);
                    $stderr = stream_get_contents($pipes[2]) ?: '';
                    fclose($pipes[2]);
                    $exitCode = proc_close($process);
                    return [
                        'success' => ($exitCode === 0),
                        'output' => trim($stdout . ($stderr ? "\n" . $stderr : '')),
                        'exitCode' => $exitCode,
                    ];
                }
            }
        } catch (Throwable $e) {
            return [
                'success' => false,
                'output' => 'Error: ' . $e->getMessage(),
                'exitCode' => 1,
            ];
        }

        return [
            'success' => false,
            'output' => 'Tidak dapat menjalankan command shell.',
            'exitCode' => 1,
        ];
    }
}
