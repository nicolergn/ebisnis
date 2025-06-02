<!-- Floating Chat Button -->
<div id="chat-button" onclick="toggleChat()">💬 Chat</div>

<!-- Chat Widget -->
<div id="chat-widget">
  <div id="chat-header">
    Chat <span id="unread-count">(20)</span>
    <span id="chat-close" onclick="toggleChat()">✖</span>
  </div>

  <div id="chat-body">
    <!-- Sidebar -->
    <div id="chat-list">
      <input type="text" id="search-user" placeholder="Cari pengguna..." />

      <!-- Chat List Item -->
      <div class="chat-item active" onclick="selectChat('celahlangit')">
        <img src="sekening_02/assets/images/pic_placehoder_potrait.jpg" class="avatar" />
        <div class="chat-info">
          <div class="chat-title-row">
            <span class="chat-name">BEAUTYHAUL ID</span>
            <span class="chat-time">Kemarin</span>
          </div>
          <div class="chat-preview">Halo Kak, pesanan Anda sudah dikirim</div>
        </div>
        <span class="chat-badge">3</span>
      </div>

      <div class="chat-item" onclick="selectChat('User2')">
        <img src="sekening_02/assets/images/pic_placehoder_potrait.jpg" class="avatar" />
        <div class="chat-info">
          <div class="chat-title-row">
            <span class="chat-name">Bantex Indonesia</span>
            <span class="chat-time">06/05</span>
          </div>
          <div class="chat-preview">dan dapat simpan 20%</div>
        </div>
        <span class="chat-badge">2</span>
      </div>

      <div class="chat-item" onclick="selectChat('User3')">
        <img src="sekening_02/assets/images/pic_placehoder_potrait.jpg" class="avatar" />
        <div class="chat-info">
          <div class="chat-title-row">
            <span class="chat-name">ESROCTE Official</span>
            <span class="chat-time">03/03</span>
          </div>
          <div class="chat-preview">Ananda akan menerima invoice</div>
        </div>
        <span class="chat-badge">1</span>
      </div>
    </div>

    <!-- Chat Content Area -->
    <div id="chat-content">
      <div class="chat-topbar">
        <img src="sekening_02/assets/images/pic_placehoder_potrait.jpg" class="avatar" />
        <span class="chat-username">celahlangit</span>
      </div>

      <div class="chat-messages" id="chat-messages">
        <div class="chat-date-separator">Hari ini</div>

        <div class="bot-msg">
          Halo! Ada yang bisa dibantu?
          <span class="msg-time">09:00</span>
        </div>

        <div class="user-msg">
          Saya mau tanya stok warna hitam
          <span class="msg-time">09:01</span>
        </div>
      </div>

      <div class="chat-input-container">
        <input type="text" id="chat-input" placeholder="Tulis pesan..." onkeypress="sendMessage(event)" />
      </div>
    </div>
  </div>
</div>
