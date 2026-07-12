# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Renombrar `deploy_guide.md` a `DEPLOY_GUIDE.md` para seguir convención de nombres en mayúsculas
- Excluir `.env.testing` del versionado por contener credenciales de entorno local

### Fixed

- Corregir lógica de fechas en vista v_ocupacion_diaria para contar reservas durante toda su estancia (rango fecha_entrada a fecha_salida) en lugar de solo en la fecha de entrada, respetando el agrupamiento macro por tipo de espacio.

## [1.0.0] - 2024-05-15

### Added

- Lanzamiento inicial de la plataforma de reservas Salitre.
- Estructura base del proyecto con arquitectura MVC con Servicios.
- Integración continua básica (CI) con validación estática de PHP.
