## 🛠️ Instalación local del proyecto en VS Code

Sigue los siguientes pasos para clonar, instalar dependencias y ejecutar el entorno de desarrollo localmente con VS Code.

---

### 🔁 1. Clona el repositorio

```bash
git clone https://github.com/tuusuario/nombre-del-repositorio.git
cd nombre-del-repositorio
```

---

### ⚙️ 2. Abre el proyecto en VS Code

```bash
code .
```

Asegúrate de tener la extensión de PHP, Laravel y soporte para Blade instaladas en VS Code para una mejor experiencia.

---

### 📦 3. Instala dependencias de backend (Laravel)

```bash
composer install
```

---

### 📦 4. Instala dependencias de frontend (Vite, Tailwind, etc.)

```bash
npm install
```

---

### 🔑 5. Copia y configura el archivo `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` con tus credenciales de base de datos locales si es necesario.

---

### 🛢️ 6. Ejecuta migraciones (si aplica)

```bash
php artisan migrate
```

---

### 🧵 7. Compila los assets de frontend (modo desarrollo)

```bash
npm run dev
```

---

### 🚀 8. Inicia el servidor de desarrollo

```bash
php artisan serve
```

---

### ✅ Acceso local

Una vez ejecutado todo, accede a tu API o frontend en:

```
http://127.0.0.1:8000
```

---

## 🚀 Despliegue de la API Laravel en Coolify con Dockerfile

Este proyecto está preparado para desplegarse automáticamente en [Coolify](https://coolify.io) utilizando un repositorio de GitHub y un `Dockerfile` personalizado.

---

### 📦 Requisitos previos

- Tener Coolify instalado y en ejecución.
- Tener acceso a un dominio (ej. `api.tudominio.com`) y poder modificar sus DNS.
- Repositorio de GitHub con este proyecto Laravel y su `Dockerfile`.

---

### 🔧 Pasos de despliegue

#### 1. Crear nueva aplicación en Coolify

- Tipo de aplicación: **Dockerfile**
- Fuente: **Repositorio de GitHub**
- Rama: `main` (o la que estés usando)
- Ruta del `Dockerfile`: raíz del repositorio (por defecto)

---

#### 2. Añadir variables de entorno

En el apartado `Environment Variables` del servicio, añade al menos:

```env
APP_NAME=PesquerApp
APP_ENV=production
APP_KEY=base64:XXXXXXXXXXXXXXX
APP_DEBUG=false
APP_URL=https://api.tudominio.com

LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_HOST=nombre-servicio-db                
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

> ⚠️ Genera previamente el `APP_KEY` si no está generado en tu repositorio, o hazlo dentro del contenedor con:
>
> ```bash
> php artisan key:generate
> ```

---

#### 3. Montar volúmenes persistentes

En la pestaña **Volumes** del servicio Laravel:

| Ruta del contenedor           | Nombre del volumen (crea si no existe) |
|-------------------------------|----------------------------------------|
| `/app/storage`                | `laravel-storage`                      |
| `/app/bootstrap/cache`        | `laravel-bootstrap-cache`              |

---

#### 4. Configurar dominio personalizado

En la pestaña **Domains** del servicio:

- Añade el dominio completo (ej. `api.lapesquerapp.es`)
- Activa **HTTPS con Let's Encrypt**
- En tu proveedor DNS (ej. IONOS), crea un registro **A**:

```
Nombre: api
Tipo: A
Valor: <IP de tu servidor Coolify>
```

---

#### 5. Verificar y ajustar puertos

En la pestaña **Ports**, asegúrate de que:

| Host Port | Container Port |
|-----------|----------------|
| `80`      | `80`           |

> ⚠️ Si estás usando `php:apache`, este es el puerto correcto.  
> Si usas `php artisan serve`, el mapeo debe ser `8000:8000`.

---

#### 6. Desactivar caché de build

- Ve a la pestaña **Build Options**
- Activa ✅ `Disable build cache`

Esto evita problemas con versiones anteriores del contenedor.

---

#### 7. Post-deploy: comandos recomendados

Desde la Terminal de Coolify o como script post-deploy, ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan migrate --force
php artisan storage:link
```

---

### ✅ Resultado esperado

Después del despliegue exitoso, la API estará accesible en:

```
https://api.lapesquerapp.es
```

Puedes probar accediendo a una ruta pública como `/api/status` o `/`.

---
