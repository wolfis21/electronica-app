<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  
  <br />
  <br />

  <h1>🔧 Electrónica App</h1>
  <p><b>Plataforma Integral de Gestión para Talleres de Servicio Técnico</b></p>
  
  <p>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel"></a>
    <a href="https://vuejs.org/"><img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js" alt="Vue.js"></a>
    <a href="https://inertiajs.com/"><img src="https://img.shields.io/badge/Inertia.js-2.0-9553E9?style=for-the-badge&logo=inertia" alt="Inertia.js"></a>
    <a href="https://tailwindcss.com/"><img src="https://img.shields.io/badge/TailwindCSS-4.0-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS"></a>
    <a href="#"><img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql" alt="MySQL"></a>
  </p>
</div>

---

## 🚀 Demo en Vivo

Puedes probar la aplicación en vivo utilizando las credenciales de demostración. Este usuario tiene acceso de **solo lectura** para explorar las funcionalidades del sistema de manera segura.

- **URL:** [https://electronica-app.isaacsdev.online/](https://electronica-app.isaacsdev.online/)
- **Usuario:** `demo@electronica.com`
- **Contraseña:** `123456789`

---

## 📖 Sobre el Proyecto

**Electrónica App** es una solución de software completa diseñada específicamente para talleres de reparación y servicio técnico. Permite digitalizar y automatizar los procesos diarios de un negocio de servicio, abarcando desde la recepción de equipos, diagnóstico y cotización, hasta la entrega final y el cálculo de comisiones para los técnicos. 

El sistema está enfocado en mejorar la eficiencia operativa, proporcionar analíticas claras sobre el rendimiento del taller y ofrecer una experiencia de usuario fluida y reactiva mediante una arquitectura SPA (Single Page Application) moderna.

---

## ✨ Características Principales

- **Gestión de Órdenes de Servicio:** Flujo completo de estados (`Pendiente` → `Diagnóstico` → `Presupuestado` → `Completado` → `Entregado`).
- **Nómina y Comisiones:** Cálculo automatizado de comisiones por quincena para el personal técnico, basado en porcentajes configurables.
- **Dashboard Analítico:** Panel de control con KPIs financieros, ingresos mensuales, eficiencia de técnicos y volumen de órdenes.
- **Control Multi-divisa:** Soporte integrado para cobros mixtos (Efectivo, Tarjeta, Transferencias y distintas monedas).
- **Generación Documental (PDF):** Creación automática de recibos de entrada, constancias de diagnóstico y actas de entrega.
- **Importación y Exportación:** Integración completa con Excel para la gestión masiva de catálogos de servicios, productos y usuarios.
- **Control de Acceso (ACL):** Sistema robusto de Roles y Permisos (Spattie) para delimitar las acciones entre Administradores, Gerentes y Técnicos.

---

## 🛠️ Stack Tecnológico

La aplicación está construida utilizando tecnologías modernas de desarrollo web, asegurando rendimiento, escalabilidad y mantenibilidad.

| Capa | Tecnología / Paquete | Propósito |
|---|---|---|
| **Backend** | `Laravel 12` + `PHP 8.2` | Lógica de negocio, API e interacciones con la Base de Datos. |
| **Frontend** | `Vue 3` + `Inertia.js 2.0` | Arquitectura SPA monorepo sin necesidad de construir una API separada. |
| **Estilos** | `Tailwind CSS 4` | Diseño moderno, responsivo y utilitario (incluyendo modo oscuro/ciberpunk). |
| **Autenticación**| `Laravel Breeze` | scaffolding de autenticación robusto. |
| **Roles/Permisos**| `Spatie/laravel-permission`| Gestión avanzada de control de acceso (ACL). |
| **Reportes** | `Barryvdh/DomPDF` | Generación de facturas y comprobantes en PDF. |
| **Gráficos** | `Chart.js` + `vue-chartjs` | Visualización de datos estadísticos interactivos. |
| **Hojas de Cálculo**| `Maatwebsite/Excel` | Exportación e importación avanzada en formatos `.xlsx` / `.csv`. |

---

## 🌐 Infraestructura y Despliegue (DevOps)

El entorno de producción de este proyecto destaca por utilizar una infraestructura de alojamiento propio **(Homelab)**, demostrando conocimientos avanzados en redes y administración de servidores:

- **Host:** Servidor Bare Metal (Homelab) alojado y administrado de forma privada.
- **Redes & Seguridad:** Expuesto a la internet pública a través de **Cloudflare Tunnels** (Zero Trust), garantizando encriptación SSL/TLS sin necesidad de abrir puertos entrantes en el firewall local, previniendo ataques directos (DDoS).
- **Entorno seguro:** Se utiliza una variable de entorno `DEMO_MODE=true` en producción para salvaguardar la base de datos contra modificaciones destructivas durante las pruebas de reclutadores y usuarios invitados.

---

## ⚙️ Instalación Local

Si deseas correr este proyecto en tu entorno de desarrollo local, sigue estos pasos:

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/electronica-app.git
cd electronica-app
```

### 2. Instalar dependencias de PHP y Node
```bash
composer install
npm install
```

### 3. Configurar entorno
Copia el archivo de entorno de ejemplo y genera la clave de tu aplicación:
```bash
cp .env.example .env
php artisan key:generate
```
Configura tus credenciales de base de datos (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) en el archivo `.env`.

### 4. Migraciones y Seeders
Este comando creará la estructura de la base de datos y la poblará con datos de prueba, permisos y los usuarios base (incluyendo al usuario *Demo*):
```bash
php artisan migrate:fresh --seed
```

### 5. Compilar Assets y Levantar Servidor
Ejecuta el servidor de desarrollo de Vite (para compilar Vue y Tailwind) y el servidor de PHP de Laravel (en pestañas separadas de tu terminal):
```bash
# Pestaña 1
npm run dev

# Pestaña 2
php artisan serve
```

---

<div align="center">
  <i>Desarrollado con pasión por la tecnología y la eficiencia operativa.</i>
</div>
