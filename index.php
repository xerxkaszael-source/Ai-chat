<?php
$apiUrl = getenv("FLASK_API_URL") ?: "https://YOUR-FLASK-SERVICE.onrender.com";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chat</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; background: #111; color: #fff;
            font-family: Arial, sans-serif; display: flex;
            justify-content: center; align-items: center;
        }
        .chat {
            width: min(700px, 95vw); height: 85vh; background: #181818;
            border: 1px solid #333; border-radius: 16px; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .header { padding: 18px; border-bottom: 1px solid #333; font-weight: bold; }
        #messages { flex: 1; overflow-y: auto; padding: 20px; }
        .message {
            max-width: 80%; margin-bottom: 12px; padding: 12px 15px;
            border-radius: 12px; white-space: pre-wrap; line-height: 1.5;
        }
        .user { margin-left: auto; background: #333; }
        .ai { background: #222; border: 1px solid #333; }
        .input {
            display: flex; gap: 10px; padding: 15px; border-top: 1px solid #333;
        }
        textarea {
            flex: 1; resize: none; height: 45px; padding: 12px;
            background: #111; color: white; border: 1px solid #333;
            border-radius: 10px; outline: none;
        }
        button {
            width: 90px; border: 0; border-radius: 10px; cursor: pointer;
            font-weight: bold;
        }
        button:disabled { opacity: .5; cursor: not-allowed; }
    </style>
</head>
<body>
<div class="chat">
    <div class="header">AI Chat</div>
    <div id="messages"></div>
    <div class="input">
        <textarea id="message" placeholder="Ask something..."></textarea>
        <button id="send">Send</button>
    </div>
</div>

<script>
const API_URL = <?php echo json_encode(rtrim($apiUrl, "/")); ?>;
const messageInput = document.getElementById("message");
const sendButton = document.getElementById("send");
const messages = document.getElementById("messages");

function addMessage(text, type) {
    const element = document.createElement("div");
    element.className = "message " + type;
    element.textContent = text;
    messages.appendChild(element);
    messages.scrollTop = messages.scrollHeight;
    return element;
}

async function sendMessage() {
    const message = messageInput.value.trim();
    if (!message || sendButton.disabled) return;

    messageInput.value = "";
    addMessage(message, "user");
    const responseMessage = addMessage("Thinking...", "ai");
    sendButton.disabled = true;

    try {
        const response = await fetch(API_URL + "/chat", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({message})
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || "Request failed");
        }

        responseMessage.textContent = data.reply;
    } catch (error) {
        responseMessage.textContent = "Error: " + error.message;
    } finally {
        sendButton.disabled = false;
        messageInput.focus();
    }
}

sendButton.addEventListener("click", sendMessage);

messageInput.addEventListener("keydown", (event) => {
    if (event.key === "Enter" && !event.shiftKey) {
        event.preventDefault();
        sendMessage();
    }
});
</script>
</body>
</html>
