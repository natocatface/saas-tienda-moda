@echo off
REM ============================================================
REM  Genera / regenera los datos demo de la tienda con fechas
REM  ancladas a la fecha ACTUAL para que el Dashboard y todos
REM  los graficos (Ventas 7 dias, Metodos de Pago, etc.) se
REM  vean poblados correctamente.
REM
REM  OJO: reemplaza los datos demo existentes (categorias,
REM  productos, clientes, ventas, compras y caja).
REM ============================================================
cd /d "%~dp0"
echo.
echo == Regenerando datos demo (10 registros por modulo + ventas en varias fechas)...
echo.
php artisan db:seed --class=DemoSeeder --force
echo.
echo == Listo. Abre el Dashboard y refresca (Ctrl + F5).
pause
