<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAssistant
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
    }

    public function askQuestion(string $userQuestion): string
    {
        // إذا كان السؤال عن البيانات (كلمات مفتاحية)
        if ($this->isDatabaseQuestion($userQuestion)) {
            $schemaDescription = $this->getDatabaseSchema();
            $prompt = <<<PROMPT
            Your task is to convert a user's question in Arabic into a standard SQL SELECT query.
            You must follow these rules strictly:
            1. The database schema is as follows: {$schemaDescription}
            2. The user's question is: "{$userQuestion}"
            3. Your response MUST be ONLY the raw SQL query.
            4. Do NOT include any explanations, comments, or markdown formatting like ```sql.
            5. The query must be a SELECT query. Do not generate INSERT, UPDATE, or DELETE queries.
            Example:
            User question: "ما هي أسماء المنتجات؟"
            Your response: SELECT name FROM products;
            PROMPT;
        } else {
            // سؤال عام
            $prompt = "أجب على السؤال التالي بالعربية باختصار ووضوح:\n" . $userQuestion;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $this->apiKey,
        ])->post($this->apiUrl, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
        ]);

        $result = $response->json();
        Log::info('Gemini raw response: ' . json_encode($result));
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            $answer = trim(str_replace(['```sql', '```'], '', $result['candidates'][0]['content']['parts'][0]['text']));
            return $answer;
        }
        return 'Error: Could not parse Gemini API response.';
    }

    // دالة بسيطة لتحديد هل السؤال عن البيانات أم عام
    private function isDatabaseQuestion(string $question): bool
    {
        $keywords = ['منتج', 'منتجات', 'عميل', 'عملاء', 'توريد', 'صرف', 'كم', 'عدد', 'اسم', 'بيانات', 'جدول', 'قائمة', 'supplier', 'product', 'customer', 'supply', 'disbursement'];
        foreach ($keywords as $word) {
            if (mb_stripos($question, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    private function getDatabaseSchema(): string
    {
        // وصف كامل للجداول والعلاقات
        return <<<SCHEMA
Table 'categories' has columns: id, name, created_at, updated_at. كل فئة (category) لديها منتجات (products) كثيرة عبر category_id في جدول المنتجات.

Table 'companies' has columns: id, name, created_at, updated_at. كل شركة (company) لديها منتجات (products) كثيرة عبر company_id في جدول المنتجات.

Table 'unit_types' has columns: id, name, symbol, created_at, updated_at. كل نوع وحدة (unit_type) لديه منتجات (products) كثيرة عبر unit_type_id في جدول المنتجات.

Table 'products' has columns: id, name, description, company_id, category_id, unit_type_id, minimum_quantity, available_quantity, supply_quantity, created_at, updated_at. المنتج ينتمي إلى شركة (company)، فئة (category)، ونوع وحدة (unit_type). المنتج لديه عناصر توريد (supply_items) وعناصر صرف (disbursement_items). المنتج يرتبط بتوريدات (supplies) وصرفيات (disbursements) عبر جداول وسيطة supply_items وdisbursement_items.

Table 'suppliers' has columns: id, name, created_at, updated_at. كل مورد (supplier) لديه توريدات (supplies) كثيرة عبر supplier_id في جدول supplies.

Table 'supplies' has columns: id, supplier_id, supply_date, created_at, updated_at. التوريد ينتمي إلى مورد (supplier) ويحتوي على عناصر توريد (supply_items). التوريد يرتبط بمنتجات (products) عبر supply_items.

Table 'supply_items' has columns: id, supply_id, product_id, quantity, created_at, updated_at. عنصر التوريد ينتمي إلى توريد (supply) ومنتج (product).

Table 'customers' has columns: id, name, phone, address, email, created_at, updated_at. كل عميل (customer) لديه صرفيات (disbursements) كثيرة عبر customer_id في جدول disbursements.

Table 'disbursements' has columns: id, customer_id, disbursement_date, created_at, updated_at. الصرفية تنتمي إلى عميل (customer) وتحتوي على عناصر صرف (disbursement_items). الصرفية ترتبط بمنتجات (products) عبر disbursement_items.

Table 'disbursement_items' has columns: id, disbursement_id, product_id, quantity, created_at, updated_at. عنصر الصرف ينتمي إلى صرفية (disbursement) ومنتج (product).

Table 'users' has columns: id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role.

Table 'clients' has columns: id, membership_number, full_name, national_id, phone, city, address, created_at, updated_at.

Table 'proxies' has columns: id, client_id, full_name, national_id, phone, city, address, created_at, updated_at.

Table 'representatives' has columns: id, customer_id, full_name, national_id, phone, city, address, created_at, updated_at.

SCHEMA;
    }
}
