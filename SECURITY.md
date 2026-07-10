# Política de Seguridad

Salitre es un proyecto de portafolio que aplica los más altos estándares de seguridad en aplicaciones web PHP nativas.

## Reporte de Vulnerabilidades

Dado que este repositorio es público, **NO** reporte vulnerabilidades mediante Issues convencionales. Las vulnerabilidades deben reportarse abriendo un Issue privado directamente en GitHub. Esto permite revisar y parchear el fallo de manera segura.

## Prácticas de Seguridad

El proyecto implementa de forma estricta las siguientes medidas:

- **Prevención de Inyección SQL**: Uso exclusivo de la extensión PDO con _prepared statements_ (sentencias preparadas) para todas las consultas.
- **Gestión de Contraseñas**: Uso nativo de `password_hash()` (algoritmos seguros como bcrypt o Argon2) y `password_verify()` para el almacenamiento y verificación de credenciales.
- **Prevención de CSRF**: Generación y validación de tokens CSRF efímeros por sesión/formulario en cada petición mutante (POST, PUT, DELETE).
- **Seguridad de Sesiones**: Regeneración constante del ID de sesión (`session_regenerate_id()`) para mitigar fijación de sesión y cookies seguras.
- **Hardening del Servidor**: Configuración estricta de directivas de seguridad en Nginx (ocultación de versión, cabeceras de seguridad HTTP, denegación de acceso a archivos ocultos/sensibles).
