
const form = document.getElementById("profileForm");
const input = document.getElementById("userInput");
const chatBox = document.getElementById("chatBox");

form.addEventListener("submit", sendMessage);

async function sendMessage(event) {
    event.preventDefault();

    const message = input.value.trim();

    if (!message) return;

    appendUserMessage(message);
const loading = appendAIMessage("Thinking...");
    input.value = "";

    try {

const res = await fetch("./mine/ai_handler.php", {
    method: "POST",
    headers:{
        "Content-Type":"application/json"
    },
    body: JSON.stringify({
        message
    })
});

const reply = await res.text();

loading.textContent = reply;

} catch(error) {
    alert(error);
}


    chatBox.scrollTop = chatBox.scrollHeight;
}

function appendUserMessage(message) {
    const div = document.createElement("div");

    div.className = "msg user-msg";

    div.textContent = message;

    chatBox.appendChild(div);
}
function appendAIMessage(message) {
  
    const div = document.createElement("div");

    div.className = "msg ai-msg";
div.textContent = "Thinking...";
   div.textContent = message;

    chatBox.appendChild(div);
    return div;
}