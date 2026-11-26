import os
import secrets
from typing import Any, Dict, List, Optional, Union
from fastapi import Depends, FastAPI, Header, HTTPException
from pydantic import BaseModel, Field
from ai_engine import test_gemini_connection, analyze_financial_report

app = FastAPI(title="DonasiFlow AI Microservice", version="1.2")


def verify_internal_api_key(x_ai_service_key: Optional[str] = Header(default=None)):
    expected = (os.getenv("AI_SERVICE_SECRET") or "").strip()
    if not expected:
        return
    if not x_ai_service_key or not secrets.compare_digest(x_ai_service_key, expected):
        raise HTTPException(status_code=401, detail="Invalid or missing X-AI-Service-Key header.")


class AnalyzeFinanceRequest(BaseModel):
    question: str = Field(..., min_length=3, description="Pertanyaan analisis dari Admin")
    filters: Optional[Union[Dict[str, Any], List[Any]]] = Field(default_factory=dict, description="Filter aktif pada halaman donasi")
    financial_context: Dict[str, Any] = Field(..., description="Data agregat keuangan & donasi dari Laravel")


@app.get("/")
def read_root():
    return {"status": "success", "message": "AI Microservice DonasiFlow aktif!", "version": "1.2"}


@app.get("/test-ai", dependencies=[Depends(verify_internal_api_key)])
def test_ai(prompt: str = "Halo Gemini, berikan ringkasan singkat kenapa AI penting untuk aplikasi donasi."):
    result = test_gemini_connection(prompt)
    return result


@app.post("/api/analyze-finance", dependencies=[Depends(verify_internal_api_key)])
def api_analyze_finance(payload: AnalyzeFinanceRequest):
    normalized_filters = payload.filters if isinstance(payload.filters, dict) else {}
    result = analyze_financial_report(
        question=payload.question,
        filters=normalized_filters,
        financial_context=payload.financial_context,
    )
    return result
