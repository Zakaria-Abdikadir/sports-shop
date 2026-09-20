<?php

$stmt = $conn->query("SELECT * FROM settings LIMIT 1");

$settings = $stmt->fetch_assoc();