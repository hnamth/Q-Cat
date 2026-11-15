<?php

$conn = mysqli_connect(
    "localhost",     
    "root",
    "",
    "qcat_catering",
    "3306"
);

if (mysqli_connect_errno()) {
    die("Database gagal konek : " . mysqli_connect_error());
}