# Salitre

Plataforma integral de reservas para hoteles boutique: portal de clientes y panel de administración desarrollados con **PHP 8.2+ Enterprise-Grade**, **MariaDB** y JavaScript puro. Diseñado bajo principios de escalabilidad, seguridad y análisis de datos continuo.

![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-informational) ![MariaDB 10.6](https://img.shields.io/badge/MariaDB-10.6-informational) ![License: MIT](https://img.shields.io/badge/License-MIT-informational) ![PHPStan](https://img.shields.io/badge/PHPStan-Level%206-brightgreen) ![PHPUnit](https://img.shields.io/badge/PHPUnit-100%25-brightgreen)

> **Deploy:*https://salitre-salitre-prod.up.railway.app/* ``
> Credenciales de prueba disponibles en [Local Development](#despliegue-local-docker).

---

## Resumen

SalitreApp es una plataforma de reservas end-to-end con dos contextos de acceso diferenciados: un portal público para clientes y un panel de administración para el personal del hotel. 

El sistema destaca por su cumplimiento estricto de la regla de **cero dependencias en runtime**. Esto significa que la aplicación en producción corre íntegramente con PHP nativo y PDO, garantizando un rendimiento y control de memoria excepcionales, reservando las dependencias externas (Composer) únicamente para las herramientas de Aseguramiento de Calidad.

---

## Arquitectura

El proyecto se apega a rigurosos estándares de desarrollo de software:

### MVC Pasivo
Cada módulo de funcionalidad mantiene una estricta separación de responsabilidades:
- **Controladores (`*.php`)**: Gestionan la sesión, consultas a base de datos mediante clases de configuración `strict_types=1`, cálculos de negocio en helpers centralizados (DRY) y nunca producen output directo.
- **Plantillas (`*.view.php`)**: Responsables exclusivamente del renderizado HTML. Todas las variables se protegen con `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` de forma obligatoria.

### Prevención Anti-Manipulación para Carrito y Pagos
El total de pago se recalcula obligatoriamente desde la base de datos en el controlador mediante el helper financiero y se inyecta en la capa de sesión segura. No existen parámetros alterables por el usuario.

### Security by Design
- **Protección CSRF Centralizada:** Implementación de tokens efímeros validados en tiempo de procesamiento con `hash_equals()`. Todo formulario y mutación de estado delega la generación de tokens al controlador.
- **Prevención de session fixation:** Regeneración de IDs en cada salto de contexto de autenticación.
- **Tipado Fuerte:** Declaración `strict_types=1` obligatoria y cast explícito en todos los accesos a datos.

---

## ML Prep

La persistencia de datos de Salitre ha sido migrada para integrarse nativamente con infraestructuras de Data Science, eliminando el sesgo temporal y habilitando la trazabilidad.

- **Change Data Capture:** Rastreo automático de mutaciones mediante columnas `updated_at` en espacios y reservas.
- **Normalización para One-Hot Encoding:** Separación de características categóricas (amenidades) mediante tablas puente.
- **Series de Tiempo Continuas:** Vista analítica `v_ocupacion_diaria` construida sobre una matriz cuadrícula de 365 días mediante expresiones CTE recursivas, garantizando que el sesgo de supervivencia matemática se elimine para futuros modelos estadísticos y de Machine Learning predictivos.
- **Queries Óptimas (No SELECT *):** Reducción de overhead de memoria mediante selección explícita de columnas en todo el ecosistema.

---

## Aseguramineto de la Calidad y Dev-Tooling

Salitre incorpora un pipeline robusto de análisis estático y pruebas en su entorno local (totalmente excluido de los despliegues de producción para mantener el contenedor limpio).

```bash
# Instalar herramientas en modo local
composer install

# Ejecutar la suite completa de calidad (PHPStan, CodeSniffer, Tests)
composer run check
```

---

## Despliegue Local (Docker)

El proyecto está contenerizado con infraestructura como código para facilitar su lanzamiento e independencia de ecosistemas heredados como XAMPP.

**Prerrequisitos**
- Docker & Docker Compose
- Git

**Instrucciones**
1. Clona el repositorio:
```bash
git clone https://github.com/Ochoa-Stack/Salitre.git
cd Salitre
```

2. Levanta los servicios y el entorno:
```bash
docker-compose up -d
```
*MariaDB se inicializará y autoejecutará las migraciones desde la carpeta `/database`.*

**Acceso a Servicios**
---
| Servicio | URL |
|---|---|
| Portal Cliente | `http://localhost:8080/` |
| Panel Admin | `http://localhost:8080/admin/` |
| PHPMyAdmin | `http://localhost:8081/` |

**Credenciales de Prueba**
---
| Rol | Email | Contraseña |
|---|---|---|
| Admin | `admin@salitre.mx` | Ver la tabla en base de datos |
| Cliente | `cliente@prueba.mx` | `cliente123` |

---

## License

Distribuido bajo la Licencia MIT. Consulta `LICENSE` para más detalles.
