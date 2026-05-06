# Salitre - Hotel para Nómadas Digitales

"Sal de la oficina. No del trabajo."

Salitre es un sitio web que simula la plataforma de un hotel boutique con catálogo de espacios, sistema de reservas y panel administrativo. Diseñado para nómadas digitales, freelancers y equipos remotos que buscan alojamiento con conectividad y ergonomía. Se combina una UX discreta y patrones de seguridad reales para demostrar buenas prácticas de desarrollo web y un flujo completo cliente <-> admin replicable en entornos locales.

## Stack Tecnológico

- PHP 8.2+ (Nativo, sin frameworks)
- MySQL/MariaDB (Vía XAMPP)
- HTML5, CSS3 Vanilla, JavaScript Vanilla
- Git/GitHub para control de versiones

## Instalación y Entorno

### Requisitos Reales
- XAMPP (Apache + MySQL)
- PHP 8.2 o superior
- Navegador web (Edge, Chrome, Firefox)

### Pasos de Instalación en XAMPP

1. **Clonar repositorio:** Clona el proyecto dentro de la carpeta pública de XAMPP (generalmente `C:\xampp\htdocs\`).
   ```bash
   git clone [URL_DEL_REPOSITORIO] salitre
   ```
2. **Inicializar la Base de datos:**
   - Abre phpMyAdmin a través de `http://localhost/phpmyadmin`.
   - Crea una nueva base de datos con el nombre exacto `salitre_db` (cotejamiento sugerido: `utf8mb4_unicode_ci`).
   - Ve a la pestaña Importar y sube el archivo físico `database/setup.sql` ubicado en la raíz del proyecto.
3. **Validar las Credenciales de conexión:**
   - El ecosistema utiliza credenciales base de XAMPP por defecto. Si esto varía drásticamente en tu setup local, edita libremente el archivo `config/database.php`.
   - Ajusta `$host`, `$dbname`, `$username` y `$password` según tu entorno de servidor.

### Accesos y Rutas de Módulos

El flujo se sostiene independientemente bajo dos módulos.
- **Módulo Cliente (Huéspedes):** Interfaz pública que corre al visitar la ruta `http://localhost/salitre/client/`. (Resuelve a `client/index.php`).
- **Módulo Admin (Personal/Staff):** Portal de back-office en la ruta `http://localhost/salitre/admin/`. Interceptar un acceso no validado redirecciona blindadamente a `http://localhost/salitre/admin/login.php`.
*Puente intencional:* El `footer` del cliente público contiene un enlace con la leyenda *Portal Staff*, diseñado para vincular al operador del sitio directamente con el portal de gestión.

## Estructura del Proyecto Final

El árbol esquematizado y simplificado reflejado de la siguiente forma:

```text
salitre/
├── admin/           # Lógica protegida, formularios de gestión y vistas corporativas del staff.
├── assets/          # Todos los recursos web estáticos (CSS, JS, iconos, medias y videos gráficos).
├── client/          # Controladores públicos, carrito y vistas accesibles para los clientes/huéspedes.
├── config/          # Archivos rectores de variables absolutas y conexión persistente a MySQL.
└── database/        # Script nativos .sql con DDL iniciales e inyección de testing data.
```

## Arquitectura y Convención de Archivos

Este proyecto implementa un sólido **MVC pasivo modificado** que aísla estrictamente el flujo lógico, logrando mantenibilidad gracias a la dualidad implementada:
- **Archivos Controladores de Lógica (`.php`):** Procesan variables globales como `$_POST` y sesiones, formulan las conexiones e iteraciones de base de datos (`PDO::prepare`) y almacenan estructuras de arreglos puros listos para imprimirse.
- **Archivos de Diseño y Vistas (`.view.php`):** Archivos delegados a conformar rigurosamente el esqueleto en HTML. Obtienen sus variables y dinámicas de su archivo homólogo inyectándolas usando interpolaciones protegidas con tags cortos y validaciones nativas de escapes de *Cross-Site Scripting* a través de `htmlspecialchars()`.

## Perfiles de Prueba Pre-Instalados

El script principal de base de datos incluye credenciales vivas listas para testear módulos restringidos y cruces de información integral.

| Rol Asignado | Correo Electrónico | Contraseña Activa |
|---|---|---|
| Cliente/Huésped | cliente@prueba.mx | cliente123 |
| Empleado/Staff | admin@salitre.mx | admin123 |

## Políticas de Seguridad

- Extracción e inyección protegida empleando Prepared Statements en todos los controladores.
- Contiene cifrado hermético asimétrico con `password_hash()`, resguarda tablas lógicas de credenciales.
- Sanitización de salidas sistemática a pantallas mediante bloqueos `htmlspecialchars()`.
- Middleware general unificado requiriendo verificación de tokens de sesión para blindaje de cada panel `admin/`.

## Licencia y Contacto

Proyecto universitario — Creado y delimitado para usos puramente educativos y demostrativos en diseño avanzado.

- **Firma Original:** Elías Ochoa
- **Correo Electrónico:** eliaslucinoochoamalaga@gmail.com
