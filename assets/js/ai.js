window.addEventListener("DOMContentLoaded", () => {
    const savedChat = sessionStorage.getItem("cutesyChat");

    if (savedChat) {
        chatBox.innerHTML = savedChat;
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});

const form = document.getElementById("aiForm");
const input = document.getElementById("userInput");
const chatBox = document.getElementById("chatBox");

const aiToggle = document.getElementById("aiToggle");
const button = form.querySelector("button");
const aiWindow = document.getElementById("aiWindow");
const closeAI = document.getElementById("closeAI");

let isSending = false; // guards against firing sendMessage twice for one reply

aiToggle.addEventListener("click", () => {
    aiWindow.style.display = "flex";
});
closeAI.addEventListener("click", () => {
    aiWindow.style.display = "none";
});

form.addEventListener("submit", sendMessage);

input.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        form.requestSubmit();
    }
});

async function sendMessage(event) {
    event.preventDefault();

    if (isSending) return; // already waiting on a reply — ignore extra submits

    const message = input.value.trim();
    if (!message) return;

    isSending = true;
    input.disabled = true;
    button.disabled = true;

    appendUserMessage(message);
    input.value = "";

    const loading = typingIndicator();

    // Safety net: if the server / network never responds, don't wait forever.
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 30000); // 30s

    try {
        const res = await fetch("./ai_handler.php", {
            method: "post",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ message }),
            signal: controller.signal
        });

        const reply = await res.text();

        if (!res.ok) {
            throw new Error(reply || ("Server returned " + res.status));
        }

        loading.classList.remove("typing-indicator");
        loading.innerHTML = marked.parse(reply);
        chatBox.scrollTop = chatBox.scrollHeight;
        saveChat();

    } catch (error) {
        loading.classList.remove("typing-indicator");
        loading.textContent = error.name === "AbortError"
            ? "That took too long to respond. Please try again. ⏳"
            : "Sorry, something went wrong. Please try again. 💕";
        chatBox.scrollTop = chatBox.scrollHeight;
        saveChat();

    } finally {
        clearTimeout(timeoutId);
        isSending = false;
        input.disabled = false;
        button.disabled = false;
        input.focus();
    }
}

function saveChat() {
    sessionStorage.setItem("cutesyChat", chatBox.innerHTML);
}

function appendUserMessage(message) {
    const div = document.createElement("div");
    div.className = "msg user-msg";
    div.textContent = message;
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
    saveChat();
}

function typingIndicator() {
    const div = document.createElement("div");
    div.className = "msg ai-msg typing-indicator";
    div.innerHTML = '<span class="dot"></span><span class="dot"></span><span class="dot"></span>';
    chatBox.appendChild(div);
    chatBox.scrollTop = chatBox.scrollHeight;
    return div;
}
