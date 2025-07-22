<!-- <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Storage;
// use Carbon\Carbon;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\File;

// class DatabaseController extends Controller
// {
//     public function backup()
//     {
//         try {
//             // إنشاء اسم الملف مع التاريخ والوقت
//             $filename = 'backup-' . Carbon::now()->format('Y-m-d-H-i-s') . '.sql';
//             $backupPath = storage_path('app/backups/' . $filename);

//             // الحصول على معلومات الاتصال بقاعدة البيانات
//             $host = config('database.connections.mysql.host');
//             $database = config('database.connections.mysql.database');
//             $username = config('database.connections.mysql.username');
//             $password = config('database.connections.mysql.password');

//             // تنظيف النسخ القديمة (احتفظ فقط بآخر 5 نسخ)
//             $this->cleanOldBackups();

//             // إنشاء الأمر مع تجنب استخدام كلمة المرور في سطر الأوامر
//             $command = sprintf(
//                 'mysqldump --host=%s --user=%s --password=%s %s > %s',
//                 escapeshellarg($host),
//                 escapeshellarg($username),
//                 escapeshellarg($password),
//                 escapeshellarg($database),
//                 escapeshellarg($backupPath)
//             );

//             // تنفيذ الأمر
//             exec($command, $output, $returnVar);

//             if ($returnVar !== 0) {
//                 throw new \Exception('فشل في إنشاء النسخة الاحتياطية');
//             }

//             // التحقق من وجود الملف
//             if (!file_exists($backupPath)) {
//                 throw new \Exception('لم يتم إنشاء ملف النسخة الاحتياطية');
//             }

//             // تحميل الملف
//             return response()->download($backupPath)->deleteFileAfterSend(true);

//         } catch (\Exception $e) {
//             // إغلاق أي اتصالات مفتوحة بقاعدة البيانات
//             DB::disconnect();
            
//             return back()->with('error', 'حدث خطأ أثناء إنشاء النسخة الاحتياطية: ' . $e->getMessage());
//         }
//     }

//     private function cleanOldBackups()
//     {
//         $backupPath = storage_path('app/backups');
//         $files = File::files($backupPath);
        
//         // ترتيب الملفات حسب تاريخ التعديل (الأحدث أولاً)
//         usort($files, function($a, $b) {
//             return filemtime($b) - filemtime($a);
//         });

//         // حذف الملفات القديمة (احتفظ فقط بآخر 5 نسخ)
//         foreach (array_slice($files, 5) as $file) {
//             File::delete($file);
//         }
//     }

//     public function listBackups()
//     {
//         $backupPath = storage_path('app/backups');
//         $files = File::files($backupPath);
        
//         $backups = [];
//         foreach ($files as $file) {
//             $backups[] = [
//                 'name' => basename($file),
//                 'size' => File::size($file),
//                 'date' => Carbon::createFromTimestamp(File::lastModified($file))->format('Y-m-d H:i:s')
//             ];
//         }

//         return view('database.backups', compact('backups'));
//     }
// }  -->