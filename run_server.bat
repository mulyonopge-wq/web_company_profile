@echo off
title Company Profile + Marketplace Server
color 0b
echo ===================================================
echo  WEBSITE & MARKETPLACE SERVER (PHP BUILT-IN)
echo ===================================================
echo.
echo  Website Publik : http://localhost:8000
echo  Admin Panel    : http://localhost:8000/admin/login
echo.
echo  Username Admin : admin
echo  Password       : Admin12345!
echo.
echo  Server sedang aktif. JANGAN tutup jendela ini
echo  selama website sedang digunakan.
echo ===================================================
echo.

php -S localhost:8000 -t public public/index.php
pause
