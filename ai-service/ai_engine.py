import json
import os
from dotenv import load_dotenv
from google import genai

# Muat file .env dari root proyek (naik 1 folder dari ai-service)
load_dotenv(os.path.join(os.path.dirname(__file__), '..', '.env'))

# Ambil GEMINI_API_KEY
api_key = os.getenv("GEMINI_API_KEY")

# Inisialisasi client Google GenAI
client = genai.Client(api_key=api_key)

# Daftar prioritas model (otomatis fallback jika model utama mengalami 503 High Demand)
FALLBACK_MODELS = [
    "gemini-3.1-flash-lite",
    "gemini-3.8-flash",
    "gemini-2.5-flash-lite",
]


def _generate_with_fallback(prompt_text: str) -> dict:
    last_error = None
    for model_name in FALLBACK_MODELS:
        try:
            response = client.models.generate_content(
                model=model_name,
                contents=prompt_text,
            )
            return {
                "status": "success",
                "model_used": model_name,
                "ai_response": response.text,
            }
        except Exception as e:
            last_error = str(e)
            continue

    return {"status": "error", "message": last_error}


def test_gemini_connection(prompt_text: str) -> dict:
    return _generate_with_fallback(prompt_text)


def analyze_financial_report(question: str, filters: dict, financial_context: dict) -> dict:
    context_json = json.dumps(financial_context, ensure_ascii=False, indent=2)
    filters_json = json.dumps(filters or {}, ensure_ascii=False)

    system_prompt = f"""Anda adalah **Senior Financial & Fundraising Analyst AI** untuk **Yayasan DonasiFlow Nusantara** (Semarang).

Tugas Anda adalah menjawab pertanyaan Admin dengan gaya **Executive Brief** yang **ringkas, visual, tajam, dan mudah dipindai dalam 10 detik** — BUKAN esai atau paragraf panjang.

### ATURAN KETAT PENULISAN (WAJIB DIPATUHI):
1. **Zero Hallucination**: Semua nominal (Rp), persentase (%), jumlah transaksi, dan nama kampanye HARUS persis sama dengan JSON `DATA KEUANGAN & DONASI`.
2. **Pahami Nominal**: `paid_cash_in_amount` = total kas masuk (pokok + kode unik), `paid_base_donation_amount` = donasi pokok di progres kampanye, `paid_unique_code_surplus` = surplus kode unik.
3. **Sangat Ringkas & Terstruktur (Maksimal ~220 kata)**:
   - Dilarang menulis paragraf lebih dari 2 kalimat.
   - Gunakan format berikut persis urutannya:

   > **Kesimpulan Utama:** (Tulis 1-2 kalimat paling penting berisi angka kunci yang langsung menjawab pertanyaan Admin.)

   ### 📊 Ringkasan Data Kunci
   (WAJIB buat **1 Tabel Markdown** berisi 3-5 baris metrik/perbandingan paling relevan dengan pertanyaan Admin, misalnya status kampanye kritis, kanal pembayaran, atau tren bulanan. Contoh format: `| Aspek / Program | Capaian / Nilai | Status |`)

   ### 🔍 Temuan & Anomali Penting
   - **[Label Singkat]:** Penjelasan maksimal 1 baris + angka pasti.
   - **[Label Singkat]:** Penjelasan maksimal 1 baris + angka pasti.
   - **[Label Singkat]:** Penjelasan maksimal 1 baris + angka pasti.

   ### 🎯 3 Langkah Tindak Lanjut
   1. **[Aksi Konkret]:** Langkah singkat, target spesifik, dan dampaknya (maks 1 kalimat).
   2. **[Aksi Konkret]:** Langkah singkat, target spesifik, dan dampaknya (maks 1 kalimat).
   3. **[Aksi Konkret]:** Langkah singkat, target spesifik, dan dampaknya (maks 1 kalimat).

---
### FILTER AKTIF PADA TABEL ADMIN:
{filters_json}

### DATA KEUANGAN & DONASI (SUMBER KEBENARAN):
{context_json}

---
### PERTANYAAN ADMIN:
"{question}"

Tulis laporan Executive Brief dalam Bahasa Indonesia sesuai format di atas:"""

    return _generate_with_fallback(system_prompt)
