# Guía de Despliegue en la Nube

Esta guía documenta cómo desplegar Salitre en producción sin incurrir en costos de infraestructura, aprovechando la contenerización (Dockerfile) y plataformas *Platform as a Service (PaaS)*.

---

## 1. Base de Datos en la Nube (MySQL/MariaDB)

Dado que las plataformas de contenedores gratuitos suelen tener almacenamiento efímero, necesitamos externalizar nuestra base de datos relacional.

**Opciones Recomendadas:**
- **Aiven:** Ofrece instancias MySQL gratuitas administradas y altamente estables.
- **Railway.app:** Permite provisionar bases de datos MySQL en segundos dentro de su plan gratuito.

### Inicialización de Datos
1. Conéctate a tu nueva base de datos remota mediante un cliente como DBeaver o TablePlus usando las credenciales proporcionadas.
2. Ejecuta el archivo raíz `database/setup.sql` para crear las tablas y registros semilla.
3. Ejecuta la migración `database/migrations/001_data_engineering_prep.sql` para construir las vistas de Machine Learning y habilitar el rastro de auditoría.

---

## 2. Despliegue Web en Render.com

Render es ideal para alojar aplicaciones Docker de forma sencilla y gratuita.

### Pasos de Configuración:
1. Inicia sesión en [Render.com](https://render.com) y enlaza tu GitHub.
2. Selecciona **New +** y elige **Web Service**.
3. Conecta el repositorio de **Salitre**.
4. En los detalles de configuración:
   - **Environment:** Selecciona `Docker` (Render identificará nuestro `Dockerfile`).
   - **Instance Type:** `Free`.

### Variables de Entorno (Environment Variables)
Dirígete a **Advanced > Environment Variables** y añade las credenciales apuntando a la base de datos externa que creaste en el Paso 1:

| Key | Value (Ejemplo) |
|---|---|
| `DB_HOST` | `tu-instancia.aivencloud.com` |
| `DB_NAME` | `salitre_db` |
| `DB_USER` | `avnadmin` |
| `DB_PASS` | `password_seguro` |
| `SALITRE_BASE_URL` | `https://salitre.onrender.com/` |

*(El contenedor consumirá estas variables automáticamente vía `getenv()`).*

---

## 3. Despliegue en Railway.app

Si prefieres tener BD y Web en el mismo ecosistema:

1. Crea un proyecto en [Railway.app](https://railway.app) y añade el servicio de **MySQL**.
2. En el mismo proyecto, conecta el repositorio de GitHub de Salitre.
3. Railway construirá la imagen optimizada desde el `Dockerfile`.
4. Ve a las variables de entorno de tu servicio web y vincula las variables mágicas de Railway (ej. `DB_HOST` apuntando a `${{ MySQL.MYSQL_HOST }}`).
5. Activa un dominio público en **Settings > Networking** y úsalo en tu `SALITRE_BASE_URL`.

---

## Limitaciones del Entorno Gratuito

- **Hibernación (Cold Starts):** Los servicios de Render entran en reposo tras periodos de inactividad. El primer request tardará unos segundos más en responder.
- **Uploads de Archivos:** Las imágenes subidas desde el panel Admin se guardan en el sistema de archivos del contenedor. Dado que los contenedores en Render Free son efímeros, estas imágenes desaparecerán al reiniciar. Para un entorno *Enterprise* definitivo, sugerimos modificar la capa de controlador para usar AWS S3 en la nube.
