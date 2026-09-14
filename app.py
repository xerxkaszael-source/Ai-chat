import os

from flask import Flask, jsonify, request
from flask_cors import CORS
from openai import OpenAI

app = Flask(__name__)
CORS(app)

OPENAI_API_KEY = os.environ.get("OPENAI_API_KEY")

if not OPENAI_API_KEY:
    raise RuntimeError("OPENAI_API_KEY is not configured")

client = OpenAI(api_key=OPENAI_API_KEY)
MODEL = os.environ.get("OPENAI_MODEL", "gpt-5.6-luna")


@app.get("/")
def root():
    return jsonify({
        "service": "AI Chat API",
        "status": "online"
    })


@app.get("/health")
def health():
    return jsonify({"status": "ok"})


@app.post("/chat")
def chat():
    data = request.get_json(silent=True) or {}
    message = str(data.get("message", "")).strip()

    if not message:
        return jsonify({"error": "Message is required"}), 400

    try:
        response = client.responses.create(
            model=MODEL,
            input=message
        )

        return jsonify({"reply": response.output_text})

    except Exception as error:
        app.logger.exception("AI request failed")
        return jsonify({
            "error": "AI request failed",
            "details": str(error)
        }), 500


if __name__ == "__main__":
    port = int(os.environ.get("PORT", "10000"))
    app.run(host="0.0.0.0", port=port)
