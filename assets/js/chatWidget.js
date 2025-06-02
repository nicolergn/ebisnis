function toggleChat() {
  const widget = document.getElementById("chat-widget");
  widget.style.display = widget.style.display === "none" ? "flex" : "none";
}

function sendMessage(event) {
  if (event.key === "Enter") {
    const input = document.getElementById("chat-input");
    const message = input.value.trim();
    if (message !== "") {
      const chatBox = document.getElementById("chat-messages");

      const userDiv = document.createElement("div");
      userDiv.className = "user-msg";
      userDiv.textContent = message;
      chatBox.appendChild(userDiv);

      const botDiv = document.createElement("div");
      botDiv.className = "bot-msg";
      botDiv.textContent = "Terima kasih atas pesan Anda!";
      chatBox.appendChild(botDiv);

      chatBox.scrollTop = chatBox.scrollHeight;
      input.value = "";
    }
  }
}

function selectChat(chatName) {
  const items = document.querySelectorAll(".chat-item");
  items.forEach(item => item.classList.remove("active"));
  event.target.classList.add("active");

  const chatBox = document.getElementById("chat-messages");
  chatBox.innerHTML = `<div class="bot-msg">Kamu membuka ${chatName}</div>`;
}
