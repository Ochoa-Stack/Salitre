# Guía de Contribución

Gracias por considerar contribuir a Salitre. Esta guía establece los estándares de ingeniería y gobernanza del proyecto.

## Flujo de Ramas (Git Flow Simplificado)

El proyecto utiliza un flujo de trabajo estructurado para garantizar la estabilidad:

- **`main`**: Rama de producción. Refleja el estado estable y desplegado de la aplicación.
- **`develop`**: Rama de integración. Aquí se unen las nuevas características antes de pasar a producción.
- **Ramas temporales**: Toda contribución debe realizarse en una rama específica, partiendo de `develop`, utilizando los siguientes prefijos:
  - `feature/` para funcionalidad nueva.
  - `fix/` para corrección de error en desarrollo.
  - `hotfix/` para corrección urgente en producción.
  - `refactor/` para reorganización sin cambiar comportamiento.
  - `chore/` para mantenimiento, dependencias, configuración.
  - `docs/` para documentación únicamente.
  - `test/` para pruebas nuevas o actualizadas.
  - `release/` para preparación de versión para producción.

Los nombres van en minúsculas, con guiones y sin acentos.

## Mensajes de Commit (Conventional Commits)

El proyecto requiere el uso estricto de [Conventional Commits](https://www.conventionalcommits.org/es/v1.0.0/). El formato es el siguiente:

```
tipo(alcance): descripción en imperativo
```

Tipos permitidos: `feat`, `fix`, `docs`, `style`, `refactor`, `perf`, `test`, `chore`.
Ejemplo: `feat(reservas): añadir validación de disponibilidad por fechas`.

## Entorno de Desarrollo Local

El entorno de desarrollo está encapsulado mediante Docker. Para inicializar el proyecto:

1. Copiar el archivo de variables de entorno: `cp .env.example .env`
2. Configurar los valores en `.env` (si difieren de los predeterminados).
3. Levantar los contenedores: `docker compose up -d`
4. Ejecutar las migraciones y seeders (si aplica).
