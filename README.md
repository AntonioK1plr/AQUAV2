# AQUARIUM

Aplicación inicial basada en Laravel 11, Inertia.js, React, Tailwind CSS y PostgreSQL. Incluye flujos para tienda, Click & Collect, caja y clínica veterinaria.

## Requisitos y arranque

PHP 8.2 con extensiones `pdo_pgsql`, Composer, Node.js y PostgreSQL/Supabase. Desde esta carpeta:

```sh
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

Configura `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y `DB_SSLMODE=require` para Supabase. Define `AQUARIUM_ADMIN_EMAIL` y una contraseña fuerte en `.env` antes de `php artisan db:seed`; si no están definidas, el seeder no crea una cuenta privilegiada. Para ejecutar la caducidad de reservas en desarrollo usa `php artisan schedule:work`; en producción configura el scheduler Laravel cada minuto.

Dependencias de aplicación: Inertia Laravel/React, chillerlan QR Code y DomPDF. El código del pase QR es un token aleatorio y se presenta como SVG.

## Alcance implementado

- Registro/login de clientes, perfil y alta de empleados solo por Administrador. El middleware `role` compara sin distinguir mayúsculas.
- Catálogo con filtros y búsqueda PostgreSQL, fichas con parámetros biológicos y alerta de incompatibilidad por rangos de pH/temperatura y temperamento.
- Checkout Click & Collect con horarios L-V 09:00–23:00 y S-D 10:00–17:00, aforo configurable (`AQUARIUM_PICKUP_CAPACITY`), aceptación obligatoria de cuidado responsable cuando hay fauna viva, reserva transaccional de stock por 30 minutos, cálculo de IVA y pase QR.
- POS de caja para código de barras, efectivo/cambio, ventas registradas, confirmación de pedidos, entrega con token QR y corte Z por cajero. Los cobros con tarjeta se registran después de verificarse en una terminal externa.
- Picking ordenado por fecha y hora, con pasillo de almacén; los pedidos confirmados vencen a las 48 horas y liberan stock. Reservas impagas vencen a los 30 minutos.
- Agenda veterinaria dentro del horario de tienda, reserva de cita por 30 minutos, pago en POS, historial clínico, PDF de receta y hash HMAC-SHA256 de integridad. Las cancelaciones pagadas requieren motivo médico y autorización antes de registrar un reembolso completo en POS.
- Bitácora de auditoría con encadenamiento de hashes y corte Z persistido.

## Límites operativos

El registro de tarjeta supone que el cajero confirmó el cargo o reembolso en la terminal externa; no integra una pasarela. El PDF lleva un hash HMAC para detectar cambios y respaldar la integridad interna, pero no representa por sí mismo un sello de tiempo/certificación NOM-151 ni una firma emitida por un prestador acreditado. Los tickets y cortes son comprobantes internos; no sustituyen un CFDI. El modelo actual maneja una sola sucursal y toma su capacidad de una variable de entorno.
