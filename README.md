# SalitreApp

Plataforma integral de reservas para hoteles boutique: portal de clientes y panel de administración desarrollados con PHP 8.2+ nativo, MySQL y JavaScript puro.

![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-informational) ![MySQL](https://img.shields.io/badge/MySQL-informational) ![License: MIT](https://img.shields.io/badge/License-MIT-informational)

> **Live demo:** `[deploy pending]`  
> Test credentials available in [Local Development](#local-development).

---

## Overview

SalitreApp es una plataforma de reservas end-to-end para un hotel boutique con dos contextos de acceso diferenciados: un portal público para clientes y un panel de administración para el personal del hotel. El sistema está construido en PHP 8.2+ nativo, MySQL vía PDO, y JavaScript vanilla, sin frameworks ni dependencias de runtime externas.

La plataforma cubre el ciclo completo de reserva: exploración del catálogo de espacios, selección de fechas, gestión del carrito, simulación de pago procesada en el servidor, confirmación de reserva y control administrativo completo desde el lado del hotel. Cada decisión de negocio relevante - totales, estados, permisos - es resuelta en el servidor; el cliente no puede manipular ninguno de esos valores.

Las decisiones de implementación incluyen: protección CSRF con el patrón Synchronizer Token validado mediante `hash_equals()`, arquitectura MVC pasiva donde los controladores nunca producen output HTML, recálculo del total de pago desde la base de datos en el punto de adición al carrito para prevenir manipulación de sesión, sistema de diseño CSS implementado con custom properties sin preprocesador, y recuperación de contraseña con tokens de expiración a 1 hora almacenados en base de datos.

---

## Tech Stack

- **PHP 8.2+ (nativo)** - control total del ciclo de request sin overhead de framework; `declare(strict_types=1)` aplicado en todos los controladores
- **MySQL / MariaDB vía PDO** - prepared statements nativos con `ERRMODE_EXCEPTION` para manejo explícito de errores en la capa de datos
- **HTML5 / CSS3 / JavaScript (ES2015+) vanilla** - cero dependencias de build; design system implementado con CSS custom properties y arquitectura de tokens en `variables.css`
- **Google Fonts (CDN)** - única dependencia externa; fallback serif/sans-serif configurado para entornos sin acceso a red
- **XAMPP (Apache + MySQL)** - entorno de desarrollo local; la estructura es portable a cualquier servidor con Apache y PHP 8.2+

> No hay package manager. No hay build step. No hay librerías JavaScript externas.

---

## Architecture

### Patrón

Cada módulo de funcionalidad contiene un controlador (`nombre.php`) que gestiona el estado de sesión, consultas a base de datos y lógica de negocio, y una plantilla (`nombre.view.php`) responsable exclusivamente del renderizado HTML. Los controladores nunca producen output directo; las plantillas no contienen lógica más allá del renderizado condicional.

### Estructura de Directorios

```
salitre/
├── admin/                  ← Panel administrativo: controladores y vistas por módulo
│   ├── includes/           ← auth_check.php, header, sidebar, footer, helpers compartidos
│   ├── espacios/           ← CRUD de espacios con manejo de imagen
│   ├── reservas/           ← Listado y detalle con gestión de estado y panel de pago
│   ├── clientes/           ← Listado de clientes (solo lectura)
│   ├── eventos/            ← CRUD de eventos del calendario
│   └── contacto/           ← Lectura y marcado de mensajes de contacto
├── client/                 ← Portal público del cliente
│   ├── includes/           ← header, nav, footer, lógica de procesamiento de contacto
│   ├── espacios/           ← Catálogo y vista de detalle de espacios
│   ├── carrito/            ← Carrito, pago, procesamiento y confirmación
│   └── auth/               ← Registro, login, logout, perfil, recuperación de contraseña
├── assets/
│   ├── css/
│   │   ├── shared/         ← variables.css, reset.css
│   │   ├── client/         ← main.css y CSS por módulo del portal
│   │   └── admin/          ← main.css, dashboard.css, crud.css, variables del tema oscuro
│   ├── js/
│   │   ├── client/         ← main.js, carrito.js, espacios.js
│   │   ├── admin/          ← main.js (stub para extensiones futuras)
│   │   └── shared/         ← alerts.js , animations.js
│   ├── img/                ← Logotipo, SVGs de marcas de pago, imágenes de espacios
│   └── video/              ← Hero background en MP4 y WebM
├── config/
│   ├── constants.php       ← BASE_URL, constantes de negocio , rutas internas
│   ├── database.php        ← Función conectarDB() con credenciales vía getenv() + fallback
│   └── .htaccess           ← Bloqueo de acceso HTTP directo al directorio
├── database/
│   ├── setup.sql           ← Schema completo con datos de prueba y migraciones integradas
│   └── migrations/         ← Migraciones individuales
└── docs/screenshots/       ← Capturas de referencia de ambas vistas principales
```

### Implementación de Seguridad

- **Protección CSRF** - Synchronizer Token Pattern en todos los formularios que mutan estado; tokens generados con `bin2hex(random_bytes(32))`, validados con `hash_equals()` y regenerados tras cada envío exitoso
- **Prevención de session fixation** - `session_regenerate_id(true)` ejecuta antes de cualquier asignación de variable de sesión en login y registro
- **Recálculo del total en servidor** - el total de pago es calculado en `agregar.php` directamente desde el registro de base de datos y almacenado en sesión; `procesar_pago.php` consume ese valor de sesión generado por el servidor, no datos del formulario de pago
- **Prepared statements** - PDO con `ERRMODE_EXCEPTION` en toda la capa de datos; sin concatenación de queries en ningún punto del codebase
- **Prevención de acceso directo** - `.htaccess` con `Require all denied` en los directorios `includes/` y `config/`
- **Hashing de contraseñas** - bcrypt vía `password_hash()` / `password_verify()`; longitud mínima de 8 caracteres aplicada en el controlador
- **Escape de output** - `htmlspecialchars()` aplicado exclusivamente en el momento de renderizado dentro de los archivos `.view.php`; los valores crudos se almacenan en base de datos sin pre-escape

---

## Features

- Catálogo de espacios con vista de detalle y galería de imágenes; cuatro tipos de espacio con amenidades en formato JSON
- Selección de fechas con validación de rango, cálculo de noches y resumen de costos desglosado
- Carrito de reserva gestionado en sesión de servidor; el total no es un parámetro editable por el cliente
- Simulación de pago con detección de marca de tarjeta por prefijo, aprobación o rechazo determinado por el dígito final del número, y registro transaccional en la tabla `pagos` dentro de una transacción PDO
- Registro y login de clientes con validación de email único y contraseña hasheada
- Edición de perfil: nombre, teléfono y cambio de contraseña con verificación de la contraseña actual
- Recuperación de contraseña mediante tokens con expiración de 1 hora; tokens anteriores invalidados al generar uno nuevo; enlace escrito en `error_log()` en entorno local
- Cancelación de reserva desde el perfil del cliente, restringida a estados `pendiente` y `confirmada`; propiedad de la reserva verificada por `cliente_id` antes de ejecutar
- Dashboard administrativo con métricas en tiempo real: reservas pendientes, espacios activos y total de clientes registrados
- Gestión de espacios con upload de imagen validado por MIME real, soft delete y slug URL-amigable con validación por regex
- Listado y detalle de reservas con cambio de estado y panel de información del pago asociado
- Listado de clientes (solo lectura)
- CRUD completo de eventos del calendario de agenda
- Gestión de mensajes de contacto con marcado de leído / no leído
- Paginación del lado del servidor en todos los listados administrativos
- Layout completamente responsivo en mobile, tablet y desktop; breakpoints en 480 px, 768 px, 1024 px y 1280 px; respeta `prefers-reduced-motion`
- Sidebar del admin deslizable en viewports menores a 768 px con overlay y cierre por tecla Escape

---

## Local Development

**Prerrequisitos**

- XAMPP con PHP 8.2+ y MySQL / MariaDB
- Git

**Instalación**

1. Clona el repositorio y ubícalo dentro de `htdocs`:

```bash
git clone https://github.com/Ochoa-Stack/SalitreApp.git
# Mover la carpeta a: C:/xampp/htdocs/salitre/
```

2. Crea la base de datos:
   - Inicia Apache y MySQL desde el Panel de Control de XAMPP
   - Abre `http://localhost/phpmyadmin`
   - Crea la base de datos `salitre_db` con collation `utf8mb4_unicode_ci`
   - Importa `database/setup.sql` - incluye el schema completo, datos de prueba y todas las migraciones integradas

3. Verifica `config/database.php` - los valores por defecto funcionan en instalaciones estándar de XAMPP:

```php
$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'salitre_db';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
```

4. Verifica `config/constants.php`:

```php
define('BASE_URL', getenv('SALITRE_BASE_URL') ?: 'http://localhost/salitre/');
```

5. Ajusta permisos del directorio de uploads (Linux / macOS únicamente):

```bash
chmod 755 assets/img/client/espacios/
```

**Acceso**

| Módulo | URL |
|---|---|
| Portal cliente | `http://localhost/salitre/` |
| Panel admin | `http://localhost/salitre/admin/` |

**Credenciales de prueba**

| Rol | Email | Contraseña |
|---|---|---|
| Admin | `admin@salitre.mx` | Ver `database/setup.sql` línea 92 |
| Cliente | `cliente@prueba.mx` | `cliente123` |

**Simulación de pago**

| Marca | Número | CVV | Resultado |
|---|---|---|---|
| Visa | `4242 4242 4242 4242` | Cualquier 3 dígitos | Aprobado |
| Mastercard | `5555 5555 5555 4444` | Cualquier 3 dígitos | Aprobado |
| Amex | `3782 822463 10005` | Cualquier 4 dígitos | Aprobado |
| Cualquier marca | Número terminado en dígito impar | — | Rechazado |

---

## Roadmap

- [ ] Gestión de cuentas de staff desde la UI (actualmente requiere acceso directo a la base de datos)
- [ ] Búsqueda y filtros en los listados del panel administrativo
- [ ] Integración con pasarela de pago real (Stripe / MercadoPago — actualmente simulado para entornos de desarrollo local)
- [ ] Envío de correo en recuperación de contraseña (requiere servidor de correo configurado; en entorno local el enlace se escribe en `error_log()`)
- [ ] Política de cancelación basada en tiempo (las reglas de ventana de cancelación no están implementadas)
- [ ] Modificación de reserva por parte del cliente

---

## License

Distributed under the MIT License. See `LICENSE` for details.
