// ===================
// CAROUSEL LOGIC
// ===================

const pdTrack = document.querySelector('.pd-carousel-track');
const pdPrevBtn = document.querySelector('.pd-carousel-btn.pd-prev');
const pdNextBtn = document.querySelector('.pd-carousel-btn.pd-next');
const pdSlides = pdTrack.querySelectorAll('img.pd-carousel-img');

if (pdTrack && pdSlides.length > 0) {
  // Clone untuk infinite loop
  const pdFirstClone = pdSlides[0].cloneNode(true);
  const pdLastClone = pdSlides[pdSlides.length - 1].cloneNode(true);

  pdTrack.appendChild(pdFirstClone);
  pdTrack.insertBefore(pdLastClone, pdTrack.firstChild);

  let pdNewSlides = pdTrack.querySelectorAll('img.pd-carousel-img');
  let pdCurrentIndex = 1;

  updatePdCarousel();

  // Tampilkan tombol navigasi jika ada
  if (pdPrevBtn) pdPrevBtn.style.display = 'block';
  if (pdNextBtn) pdNextBtn.style.display = 'block';

  function updatePdCarousel() {
    const slideWidth = pdTrack.clientWidth;
    pdTrack.style.transition = 'transform 0.4s ease-in-out';
    pdTrack.style.transform = `translateX(-${pdCurrentIndex * slideWidth}px)`;
  }

  pdNextBtn?.addEventListener('click', () => {
    if (pdCurrentIndex >= pdNewSlides.length - 1) return;
    pdCurrentIndex++;
    updatePdCarousel();
  });

  pdPrevBtn?.addEventListener('click', () => {
    if (pdCurrentIndex <= 0) return;
    pdCurrentIndex--;
    updatePdCarousel();
  });

  pdTrack.addEventListener('transitionend', () => {
    const slideWidth = pdTrack.clientWidth;

    if (pdNewSlides[pdCurrentIndex].src === pdFirstClone.src) {
      pdTrack.style.transition = 'none';
      pdCurrentIndex = 1;
      pdTrack.style.transform = `translateX(-${pdCurrentIndex * slideWidth}px)`;
    }

    if (pdNewSlides[pdCurrentIndex].src === pdLastClone.src) {
      pdTrack.style.transition = 'none';
      pdCurrentIndex = pdNewSlides.length - 2;
      pdTrack.style.transform = `translateX(-${pdCurrentIndex * slideWidth}px)`;
    }
  });
}

// ===================
// POPUP LOGIC
// ===================

let pdPopupCurrentIndex = 0;
const pdPopup = document.getElementById('pdImagePopup');
const pdPopupImg = document.getElementById('pdPopupImg');
const pdOriginalImages = [...pdSlides].map(img => img.src);

function openImagePopup(src) {
  pdPopupCurrentIndex = pdOriginalImages.indexOf(src);
  pdPopupImg.src = src;
  pdPopup.classList.add('active');
  updatePopupNav();
}

function closeImagePopup(event) {
  if (event.target === pdPopup || event.target === pdPopupImg) {
    pdPopup.classList.remove('active');
  }
}

function prevPopupImage(event) {
  event.stopPropagation();
  pdPopupCurrentIndex = (pdPopupCurrentIndex - 1 + pdOriginalImages.length) % pdOriginalImages.length;
  pdPopupImg.src = pdOriginalImages[pdPopupCurrentIndex];
  updatePopupNav();
}

function nextPopupImage(event) {
  event.stopPropagation();
  pdPopupCurrentIndex = (pdPopupCurrentIndex + 1) % pdOriginalImages.length;
  pdPopupImg.src = pdOriginalImages[pdPopupCurrentIndex];
  updatePopupNav();
}

function updatePopupNav() {
  const prevPopupBtn = document.querySelector('.pd-popup-nav.pd-prev');
  const nextPopupBtn = document.querySelector('.pd-popup-nav.pd-next');
  const onlyOne = pdOriginalImages.length <= 1;

  if (prevPopupBtn) prevPopupBtn.style.display = onlyOne ? 'none' : 'block';
  if (nextPopupBtn) nextPopupBtn.style.display = onlyOne ? 'none' : 'block';
}
