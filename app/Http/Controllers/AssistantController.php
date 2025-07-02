<?php

namespace App\Http\Controllers;

use App\Services\GeminiAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AssistantController extends Controller
{
    protected $assistant;

    // نستخدم حقن التبعية (Dependency Injection) لجعل الكود نظيفًا وقابلًا للاختبار
    public function __construct(GeminiAssistant $assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * يستقبل السؤال، يحوله إلى SQL، ينفذه، ويعيد النتيجة.
     */
    public function handle(Request $request)
    {
        try {
            // التحقق من صحة المدخلات
            $validated = $request->validate([
                'question' => 'required|string|max:255',
            ]);

            $userQuestion = $validated['question'];

            // استدعاء الخدمة للحصول على استعلام SQL أو إجابة نصية
            $sqlOrText = $this->assistant->askQuestion($userQuestion);

            // إذا كانت الإجابة استعلام SELECT نفذه، وإلا أعدها كنص
            if (Str::of(strtoupper($sqlOrText))->startsWith('SELECT')) {
                $results = DB::select($sqlOrText);
                return response()->json([
                    'success' => true,
                    'query' => $sqlOrText,
                    'data' => $results,
                ]);
            } else {
                // إجابة نصية عامة
                return response()->json([
                    'success' => true,
                    'query' => null,
                    'data' => $sqlOrText,
                ]);
            }

        } catch (ValidationException $e) {
            // في حالة فشل التحقق من المدخلات
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // للتعامل مع أي خطأ آخر (من الـ API أو قاعدة البيانات)
            Log::error("AssistantController Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
