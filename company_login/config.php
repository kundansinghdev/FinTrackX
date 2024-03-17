<?php

$conn = mysqli_connect("localhost", "root", "kundansingh!@#", "expenseman");

if (!$conn) {
    echo "Connection Failed";
}