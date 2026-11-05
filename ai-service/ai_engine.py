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

def test_gemini_connection(prompt_text: str):
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
