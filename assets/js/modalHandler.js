// Fungsi untuk membuka modal
function openModal(type) {
  const overlay = document.getElementById('modalOverlay');
  const modal = document.getElementById(`${type}Modal`);
  if (overlay && modal) {
    overlay.style.display = 'block';
    modal.style.display = 'block';
  }
}

// Fungsi untuk menutup modal
function closeModal() {
  const overlay = document.getElementById('modalOverlay');
  const modals = document.querySelectorAll('.modal');
  if (overlay) overlay.style.display = 'none';
  modals.forEach(modal => modal.style.display = 'none');
}

// Fungsi untuk mengganti modal
function switchModal(to) {
  closeModal();
  openModal(to);
}

// Timer untuk menyembunyikan notifikasi error secara otomatis
document.addEventListener("DOMContentLoaded", function() {
  var errorModal = document.getElementById("errorModal");
  var modalOverlay = document.getElementById("modalOverlay");
  if (errorModal && modalOverlay) {
    // Sembunyikan error dan overlay setelah 5 detik (5000 milidetik)
    setTimeout(function() {
      errorModal.style.display = "none";
      modalOverlay.style.display = "none";
    }, 5000);
  }
});