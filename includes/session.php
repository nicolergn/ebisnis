<?php
// Pastikan hanya dipanggil sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
