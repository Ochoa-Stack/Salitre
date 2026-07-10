# 001. Arquitectura MVC con Servicios y Repositorios

Date: 2026-07-10

## Status

Accepted

## Contexto

El proyecto requiere una clara separación de responsabilidades para garantizar la mantenibilidad y testabilidad del código base. Sin embargo, existe una restricción estricta de cero dependencias en *runtime*, lo que impide el uso de contenedores de inyección de dependencias complejos o frameworks externos que habiliten una Arquitectura Hexagonal pura sin agregar un volumen significativo de código repetitivo de infraestructura.

## Decisión

Se adopta el patrón "MVC con Servicios y Repositorios" como compromiso arquitectónico:

- **Controladores (Controllers):** Únicamente gestionan el ciclo HTTP (recibir Request, delegar a servicios, y retornar Response/Vista).
- **Servicios (Services):** Encapsulan de forma aislada toda la lógica de negocio y las reglas del dominio.
- **Repositorios (Repositories):** Abstraen las consultas y la persistencia hacia la base de datos (utilizando PDO de forma nativa).

## Consecuencias

- **Positivas:** Se mantiene la claridad arquitectónica y se facilita en gran medida la testabilidad (los servicios se pueden probar independientemente del ciclo HTTP) sin el *overhead* de desarrollar o importar capas de infraestructura complejas.
- **Negativas:** Se sacrifica la independencia absoluta del motor de base de datos. Existe un acoplamiento inherente a PDO y MariaDB en la capa de repositorios que requeriría reescritura manual en caso de migrar a otro paradigma de almacenamiento de datos.
