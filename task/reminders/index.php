<?php
session_start();
include '../../config/koneksi.php';

$user_id = $_SESSION['user']['id'];

// Delete reminder
if (isset($_GET['hapus']))
