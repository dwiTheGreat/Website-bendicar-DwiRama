// Validasi Form Penyewaan
document.addEventListener("DOMContentLoaded", function () {
    const sewaForm = document.querySelector("#form-sewa");
    if (sewaForm) {
        sewaForm.addEventListener("submit", function (e) {
            const tanggalPinjam = document.querySelector("input[name='tanggal_pinjam']");
            if (!tanggalPinjam.value) {
                e.preventDefault();
                alert("Tanggal pinjam harus diisi!");
            }
        });
    }
});

// Konfirmasi Penghapusan Data
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-button");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                window.location.href = button.href;
            }
        });
    });
});

// Toggle Menu Navigasi (Jika ada fitur responsif di masa depan)
const menuToggle = document.querySelector(".menu-toggle");
const navMenu = document.querySelector(".nav-menu");
if (menuToggle && navMenu) {
    menuToggle.addEventListener("click", function () {
        navMenu.classList.toggle("show");
    });
}