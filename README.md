# AQUARIUM

Aplicación omnicanal en Laravel 12, Inertia.js, React y Tailwind CSS. Incluye tienda, reservas Click & Collect, caja, almacén y clínica veterinaria.

## Demo local

La configuración de desarrollo puede usar SQLite y no requiere credenciales de PostgreSQL. En Windows PowerShell, desde esta carpeta:

```powershell
composer install
npm install
Copy-Item .env.example .env
New-Item -ItemType File database/database.sqlite
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Para poblar la demo, configura `DB_CONNECTION=sqlite` y `DB_DATABASE=database/database.sqlite` en `.env`; crea el archivo SQLite vacío antes de migrar. Para crear el usuario administrador, define `AQUARIUM_ADMIN_EMAIL` y `AQUARIUM_ADMIN_PASSWORD` en `.env` antes de ejecutar el seeder. El seeder también agrega productos de muestra. Las solicitudes para restablecer contraseña se escriben al log local cuando `MAIL_MAILER=log`.

## PostgreSQL para despliegue

El catálogo puede usar PostgreSQL/Supabase. Configura `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y `DB_SSLMODE=require`. El índice trigram de búsqueda se crea solo con PostgreSQL. En producción, configura las credenciales de correo y ejecuta el scheduler de Laravel cada minuto para liberar reservas vencidas.

## Alcance implementado

- Interfaz de tienda inspirada en los mockups: inicio, catálogo con filtros, ficha, carrito, checkout, confirmación de pedido, historial, perfil y citas médicas.
- Registro/login de clientes y recuperación de contraseña. El middleware `role` compara sin distinguir mayúsculas.
- Inicio de sesión por rol: Cajero abre `/pos`, Almacenista `/almacen/picking`, Veterinario `/veterinaria/agenda` y Administrador `/admin`. El panel de administración tiene su navegación separada; solo Administrador puede crear cuentas de Cajero, Almacenista y Veterinario. El registro público crea cuentas Cliente.
- Compatibilidad biológica por rangos de pH/temperatura y temperamento; carta de cuidado responsable para fauna viva.
- Checkout Click & Collect con horarios L-V 09:00–23:00 y S-D 10:00–17:00, aforo configurable (`AQUARIUM_PICKUP_CAPACITY`), reserva de inventario por 30 minutos, cálculo de IVA y pase QR.
- POS para código de barras, efectivo/cambio, pedidos reservados, entrega por QR, pagos veterinarios y corte Z por cajero.
- Roles operativos separados: Cajero, Almacenista, Veterinario y Administrador. El módulo de picking queda reservado a Almacenista y Administrador.
- Agenda veterinaria, reserva de cita por 30 minutos, pago en POS, historial clínico por tutor, carga privada de recetas o estudios PDF/Word, formulario clínico completo, receta PDF con medicamento/dosis/frecuencia y bitácora de auditoría.

## Datos locales

Con SQLite, los datos persistentes están en database/database.sqlite. La conexión se configura con DB_CONNECTION y DB_DATABASE en .env (archivo local ignorado por Git). Los adjuntos se guardan de forma privada en storage/app/private/expedientes/ y solo los descargan el tutor de la cita o el personal clínico autorizado. En producción se pueden usar PostgreSQL o Supabase.

## Pago

No hay pasarela conectada. El pedido queda reservado y el cliente confirma el pago presencialmente con el personal de caja, que registra el pago después de verificar efectivo o la terminal bancaria externa. La integración de pagos queda pendiente para una fase posterior.

Los tickets y cortes son comprobantes internos; no sustituyen un CFDI. El hash HMAC de los PDF ayuda a detectar cambios, pero no representa por sí solo un sello NOM-151 ni una firma de un prestador acreditado. El modelo actual maneja una sola sucursal.
