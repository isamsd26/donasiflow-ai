from typing import Any, Dict, Optional, Union, List
from fastapi import FastAPI
from pydantic import BaseModel, Field
from ai_engine import test_gemini_connection, analyze_financial_report

app = FastAPI(title="DonasiFlow AI Microservice", version="1.1")


class AnalyzeFinanceRequest(BaseModel):
    question: str = Field(..., min_length=3, description="Pertanyaan analisis dari Admin")
    filters: Optional[Union[Dict[str, Any], List[Any]]] = Field(default_factory=dict, description="Filter aktif pada halaman donasi")
    financial_context: Dict[str, Any] = Field(..., description="Data agregat keuangan & donasi dari Laravel")


@app.get("/")
def read_root():
    return {"status": "success", "message": "AI Microservice DonasiFlow aktif!"}


@app.get("/test-ai")
def test_ai(prompt: str = "Halo Gemini, berikan ringkasan singkat kenapa AI penting untuk aplikasi donasi."):
    result = test_gemini_connection(prompt)
    return result


@app.post("/api/analyze-finance")
def api_analyze_finance(payload: AnalyzeFinanceRequest):
    normalized_filters = payload.filters if isinstance(payload.filters, dict) else {}
    result = analyze_financial_report(
        question=payload.question,
        filters=normalized_filters,
        financial_context=payload.financial_context,
    )
    return result
