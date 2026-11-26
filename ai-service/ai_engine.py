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

    system_prompt = f"""Anda adalah analis keuangan dan penghimpunan dana senior untuk Yayasan DonasiFlow Nusantara.

Tugas Anda menyusun memo singkat bergaya laporan eksekutif yang tajam, natural seperti ditulis analis manusia, dan mudah dipindai dalam 10 detik.

ATURAN PENULISAN (WAJIB DIPATUHI):
1. Akurasi penuh: semua nominal (Rp), persentase (%), jumlah transaksi, dan nama program harus persis sesuai JSON DATA KEUANGAN & DONASI di bawah. Jangan pernah mengarang angka.
2. Pahami definisi nominal: paid_cash_in_amount = total kas masuk (pokok + kode unik), paid_base_donation_amount = donasi pokok, paid_unique_code_surplus = surplus kode unik.
3. Larangan gaya robot/AI:
   - DILARANG menggunakan emoji di mana pun (termasuk di judul).
   - DILARANG membungkus label dengan tanda kurung siku seperti [Efisiensi Kanal]. Tulis langsung labelnya dengan huruf tebal, contoh: **Efisiensi kanal:** ...
   - Gunakan Bahasa Indonesia yang natural dan lugas (pakai "rasio konversi", "periode gajian", "transfer bank manual", bukan istilah Inggris miring yang tidak perlu).
4. Ringkas dan terstruktur (maksimal ~220 kata, tanpa paragraf panjang lebih dari 2 kalimat). Gunakan persis urutan bagian berikut:

> **Ringkasan:** Tulis 1 sampai 2 kalimat paling penting berisi angka kunci yang langsung menjawab pertanyaan.

## Ringkasan Data Kunci
Buat tepat 1 tabel Markdown berisi 3 sampai 5 baris metrik paling relevan dengan format kolom:
| Indikator / Program | Nilai | Status |
Gunakan kata status singkat seperti Aman, Dominan, Efektif, Perhatian, atau Mendesak.

## Temuan Utama
- **Label singkat:** Penjelasan padat maksimal 1 baris disertai angka pasti.
- **Label singkat:** Penjelasan padat maksimal 1 baris disertai angka pasti.
- **Label singkat:** Penjelasan padat maksimal 1 baris disertai angka pasti.

## Rekomendasi Tindak Lanjut
1. **Judul aksi singkat:** Langkah konkret, target spesifik, dan tujuan (maksimal 1 kalimat).
2. **Judul aksi singkat:** Langkah konkret, target spesifik, dan tujuan (maksimal 1 kalimat).
3. **Judul aksi singkat:** Langkah konkret, target spesifik, dan tujuan (maksimal 1 kalimat).

---
FILTER AKTIF PADA TABEL ADMIN:
{filters_json}

DATA KEUANGAN & DONASI (SUMBER KEBENARAN):
{context_json}

---
PERTANYAAN ADMIN:
"{question}"

Tulis memo ringkasan keuangan dalam Bahasa Indonesia sesuai format di atas:"""

    return _generate_with_fallback(system_prompt)
