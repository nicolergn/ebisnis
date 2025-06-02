function openModal(type) {
  const overlay = document.getElementById('modalOverlay');
  const modal = document.getElementById(`${type}Modal`);
  if (overlay && modal) {
    overlay.style.display = 'block';
    modal.style.display = 'block';
  }
}

function closeModal() {
  const overlay = document.getElementById('modalOverlay');
  const modals = document.querySelectorAll('.modal');
  if (overlay) overlay.style.display = 'none';
  modals.forEach(modal => modal.style.display = 'none');
}

function switchModal(to) {
  closeModal();
  openModal(to);
}
