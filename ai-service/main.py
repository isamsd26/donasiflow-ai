from fastapi import FastAPI
from ai_engine import test_gemini_connection

app = FastAPI(title="DonasiFlow AI Microservice", version="1.0")

@app.get("/")
def read_root():
    return {"status": "success", "message": "AI Microservice DonasiFlow aktif!"}

# Ubah path-nya jadi /test-ai saja biar tidak panjang
@app.get("/test-ai")
def test_ai(prompt: str = "Halo Gemini, berikan ringkasan singkat kenapa AI penting untuk aplikasi donasi."):
    result = test_gemini_connection(prompt)
    return result