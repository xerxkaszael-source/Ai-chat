# AI Chat — Flask API + PHP Frontend

Simple Git-ready AI chat project for Render.

## Structure

- `app.py` — Flask API
- `index.php` — simple browser chat UI
- `requirements.txt` — Python dependencies
- `render.yaml` — Render Blueprint
- `.env.example` — local environment variable template

## Flask API

Endpoints:

- `GET /` — API status
- `GET /health` — health check
- `POST /chat` — AI chat endpoint

Example request:

```json
{
  "message": "Hello"
}
```

## Render

The included `render.yaml` creates:

- Service: `ai-chat-api`
- Runtime: Python
- Region: Singapore
- Plan: Free
- Build: `pip install -r requirements.txt`
- Start: `gunicorn app:app`
- Auto deploy: enabled

Set `OPENAI_API_KEY` in Render Environment Variables. Do not commit the real API key.

`OPENAI_MODEL` defaults to `gpt-5.6-luna`.

## PHP frontend

Set:

```text
FLASK_API_URL=https://YOUR-FLASK-SERVICE.onrender.com
```

Then serve `index.php` from a PHP-capable host.

## Important

The PHP frontend is separate from the Flask API. PHP is not executed by the Python Render service.
