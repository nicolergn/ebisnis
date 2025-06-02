function tampilkanKeterangan() {
    const select = document.getElementById("kondisi-select");
    const keterangan = document.getElementById("keterangan-kondisi");

    const deskripsi = {
      "baru": "Tidak pernah dipakai.",
      "seperti-baru": "Digunakan satu atau dua kali. Bagus seperti baru.",
      "tidak-sering": "Digunakan dengan hati-hati. Jika ada kekurangan, hampir tidak terlihat.",
      "digunakan-baik": "Ada sedikit kekurangan atau kecacatan.",
      "sering-digunakan": "Terlihat sudah terpakai lama dan ada kekurangan."
    };

    keterangan.textContent = deskripsi[select.value] || "";
}
