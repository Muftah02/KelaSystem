<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Support\Facades\Log;

class BackupController extends Controller
{
    public function create()
    {
        try {
            // إنشاء اسم الملف مع التاريخ والوقت
            $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
            
            // المسار الديناميكي للنسخ الاحتياطي
            $backupPath = storage_path('app/backups');

            
            // التأكد من وجود المجلد
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0777, true);
            }

            // مسار الملف
            $filePath = $backupPath . '\\' . $filename;
            
            // فتح الملف للكتابة
            $handle = fopen($filePath, 'w');
            if (!$handle) {
                throw new \Exception('فشل في فتح الملف للكتابة');
            }

            // استخدام اتصال Laravel
            $pdo = DB::connection()->getPdo();
           
            // الحصول على قائمة الجداول
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            
            // كتابة رأس الملف
            fwrite($handle, "-- Backup created at " . Carbon::now() . "\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // نسخ كل جدول
            foreach ($tables as $table) {
                Log::info('جاري نسخ الجدول: ' . $table);
                
                // الحصول على هيكل الجدول
                $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
                if (!$createTable) {
                    continue;
                }
                fwrite($handle, "\n\n" . $createTable['Create Table'] . ";\n\n");

                // الحصول على بيانات الجدول
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
               
                if (!empty($rows)) {
                    $columns = array_keys($rows[0]);
                    $values = [];
                    
                    foreach ($rows as $row) {
                        $rowValues = [];
                        foreach ($row as $value) {
                            $rowValues[] = $value === null ? 'NULL' : $pdo->quote($value);
                        }
                        $values[] = '(' . implode(', ', $rowValues) . ')';
                    }
                    
                    fwrite($handle, "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES\n");
                    fwrite($handle, implode(",\n", $values) . ";\n");
                }
            }

            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);
            
            // التحقق من حجم الملف
            $fileSize = filesize($filePath);
            
            if ($fileSize === 0) {
                throw new \Exception('تم إنشاء ملف النسخ الاحتياطي ولكنه فارغ');
            }

            return redirect()->back()->with('success', 'تم إنشاء النسخة الاحتياطية بنجاح  ');
        } catch (\Exception $e) {
            Log::error('Error in backup creation:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء النسخة الاحتياطية: ' . $e->getMessage());
        }
    }
} 