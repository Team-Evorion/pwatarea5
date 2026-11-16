# 📚 Biblioteca PWA

### Sistema de gestión de biblioteca online | Programación Web Avanzada

**Biblioteca PWA** es una aplicación web desarrollada como proyecto académico para la asignatura de **Programación Web Avanzada** de la **Universidad Bolivariana del Ecuador**.

La plataforma permite organizar un catálogo de libros, controlar los ejemplares disponibles y registrar préstamos y devoluciones mediante tres roles: **Administrador**, **Bibliotecario** y **Lector**. Cada usuario accede a un panel y a las funciones correspondientes a su rol.

El proyecto integra Laravel 12, una base de datos relacional y una interfaz en español construida con Blade, Bootstrap y estilos personalizados, adaptable a computadoras y dispositivos móviles.

---

## 🌐 Enlaces del proyecto

- **Repositorio:** [GitHub — Team-Evorion/pwatarea5](https://github.com/Team-Evorion/pwatarea5)
- **Aplicación publicada:** [Biblioteca PWA en InfinityFree](https://zahiddev.infinityfreeapp.com/pwatarea5/public/index.php/login)

---

## ✨ Características principales

- Registro de nuevas cuentas con el rol Lector.
- Inicio de sesión mediante correo electrónico y contraseña, y cierre de sesión.
- Paneles diferenciados para Administrador, Bibliotecario y Lector.
- Catálogo con búsqueda por título, autor o género y paginación de diez registros.
- Registro y edición de libros; eliminación exclusiva para el Administrador y sujeta al historial de préstamos.
- Subida y reemplazo de portadas en JPG, JPEG, PNG o WebP, de hasta 2 MB.
- Registro de años históricos **a. C.** y **d. C.**, o libros sin fecha conocida.
- Control de ejemplares totales, prestados y disponibles.
- Registro de préstamos a lectores y de sus devoluciones.
- Historial de préstamos con filtros para todos, pendientes de devolución y devueltos.
- Consulta de usuarios y modificación de roles por el Administrador.
- Validación de datos en el servidor y control de acceso mediante middleware de autenticación y roles.
- Interfaz responsive con navegación adaptada a pantallas pequeñas.

---

## 👥 Roles y permisos

| Función | Administrador | Bibliotecario | Lector |
|---|:---:|:---:|:---:|
| Consultar y buscar libros | ✅ | ✅ | ✅ |
| Registrar y editar libros y portadas | ✅ | ✅ | — |
| Eliminar libros sin historial de préstamos | ✅ | — | — |
| Registrar préstamos a lectores | ✅ | ✅ | — |
| Registrar devoluciones | ✅ | ✅ | — |
| Consultar todos los préstamos | ✅ | ✅ | — |
| Consultar su propio historial de préstamos | ✅ | ✅ | ✅ |
| Listar y buscar usuarios | ✅ | — | — |
| Cambiar el rol de otros usuarios | ✅ | — | — |

El Administrador y el Bibliotecario consultan el historial general; el Lector únicamente ve los préstamos vinculados a su cuenta. Los préstamos y las devoluciones del Lector son registrados por el personal autorizado.

### Paneles principales

- **Administrador:** cantidad de títulos, usuarios, préstamos activos y préstamos devueltos.
- **Bibliotecario:** cantidad de títulos, ejemplares disponibles y préstamos activos.
- **Lector:** cantidad de títulos del catálogo y sus préstamos activos y devueltos.

Los nombres internos de los roles son `Administrator`, `Librarian` y `Reader`.

---

## 📖 Reglas de funcionamiento

- La cantidad de un libro representa el **total de ejemplares**, no únicamente los disponibles. La disponibilidad se calcula restando los préstamos activos.
- No se registra un préstamo si no quedan ejemplares disponibles.
- Un lector no puede tener dos préstamos activos del mismo libro.
- Un préstamo se considera activo mientras `date_of_return` sea nulo. Al devolverlo se registra la fecha actual.
- No se puede registrar dos veces la devolución de un mismo préstamo.
- No se permite reducir el total de ejemplares por debajo de los que están prestados.
- Un libro con cualquier historial de préstamos, incluso devueltos, no puede eliminarse.
- El Administrador no puede cambiar su propio rol ni quitar el rol Lector a un usuario con préstamos activos.
- El año es opcional. Si se especifica, debe estar entre 1 y 9999; para d. C. no se admiten años posteriores al actual. Los años a. C. se almacenan como números negativos.

Las operaciones de préstamo y devolución utilizan transacciones de base de datos y bloqueos de registros para controlar las actualizaciones concurrentes.

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Aplicación |
|---|---|
| **Laravel 12** | Rutas, controladores, autenticación, validaciones y acceso a datos |
| **PHP 8.2 o superior, compatible con las dependencias** | Lógica del servidor; requisito declarado `^8.2` |
| **MySQL / MariaDB** | Base de datos para la instalación local descrita abajo |
| **Eloquent ORM** | Modelos y relaciones entre entidades |
| **Blade** | Plantillas de la interfaz |
| **Bootstrap 5.3.3 y Bootstrap Icons** | Componentes responsive e iconos cargados mediante CDN |
| **HTML5, CSS3 y JavaScript** | Estructura, estilos personalizados e interacciones |
| **Vite 6** | Compilación de recursos frontend |
| **Composer y npm** | Gestión de dependencias |
| **Git y GitHub** | Control de versiones y colaboración |
| **InfinityFree** | Alojamiento de la aplicación publicada |

El proyecto también incluye Tailwind CSS 4 en su configuración de compilación. La plantilla principal utiliza Bootstrap y carga `biblioteca.css` desde `resources/js/app.js`.

---

## 🗄️ Modelo de datos

| Tabla principal | Contenido |
|---|---|
| `roles` | Nombres de los tres roles |
| `users` | Nombre de usuario, correo, contraseña y rol |
| `books` | Título, autor, año, género, cantidad total y ruta de portada |
| `transactions` | Usuario, libro, fecha de préstamo y fecha de devolución |

### Relaciones principales

- Un rol puede pertenecer a varios usuarios; cada usuario tiene un rol.
- Un usuario puede tener varios préstamos.
- Un libro puede aparecer en varios préstamos.
- Cada préstamo relaciona un usuario con un libro.

Las migraciones también incluyen tablas de infraestructura de Laravel para sesiones, caché, colas y tokens de restablecimiento de contraseña. La existencia de estas tablas no implica que haya una interfaz implementada para cada función.

Las portadas se guardan en `storage/app/public/book-covers`; la base de datos conserva su ruta en `books.cover_path`.

---

## 🏗️ Estructura del proyecto

```text
pwatarea5/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Autenticación, libros, usuarios, paneles y préstamos
│   │   └── Middleware/        # Control de acceso por roles
│   ├── Models/               # Book, Role, Transaction y User
│   └── Services/             # LoanService: préstamos y devoluciones
├── bootstrap/                # Inicialización y middleware de Laravel
├── config/                   # Configuración de la aplicación
├── database/
│   ├── factories/            # Factoría de usuarios
│   ├── migrations/           # Estructura de la base de datos
│   └── seeders/              # Roles y cuenta administrativa inicial
├── public/                   # Punto de entrada y recursos públicos
├── resources/
│   ├── css/                  # Estilos de la aplicación
│   ├── js/                   # Entrada JavaScript y carga de estilos
│   └── views/
│       ├── auth/             # Registro e inicio de sesión
│       ├── books/            # Catálogo y formularios de libros
│       ├── dashboards/       # Paneles de los tres roles
│       ├── layouts/          # Plantilla y navegación
│       ├── transactions/     # Historial y registro de préstamos
│       └── users/            # Listado y asignación de roles
├── routes/web.php            # Rutas web y permisos por rol
├── storage/                  # Archivos, sesiones y registros según configuración
├── tests/                    # Pruebas de ejemplo del proyecto base
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## 🚀 Instalación local en Windows

### Requisitos

- PHP compatible con `^8.2` y las extensiones requeridas por Laravel y el controlador de base de datos.
- Composer.
- MySQL o MariaDB, por ejemplo mediante XAMPP o Laragon.
- Node.js y npm compatibles con Vite 6.
- Git y un navegador web moderno.

Los siguientes comandos se ejecutan en **PowerShell**, dentro de la carpeta del proyecto. Inicia el servicio de base de datos antes de ejecutar las migraciones.

### 1. Clonar el repositorio

```powershell
git clone https://github.com/Team-Evorion/pwatarea5.git
cd pwatarea5
```

### 2. Instalar dependencias y crear la configuración

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

### 3. Configurar la base de datos

Crea una base de datos vacía, por ejemplo `pwatarea5`, desde phpMyAdmin u otra herramienta. Edita `.env` con tus valores locales:

```env
APP_NAME="Biblioteca PWA"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pwatarea5
DB_USERNAME=root
DB_PASSWORD=
```

El archivo `.env.example` viene configurado para SQLite; reemplaza esa configuración por los valores MySQL anteriores. El nombre `pwatarea5` es una propuesta para el entorno local, no un nombre fijo exigido por el código.

### 4. Crear tablas, roles y administrador inicial

```powershell
php artisan migrate --seed
```

`DatabaseSeeder` ejecuta `RoleSeeder` y crea la cuenta administrativa inicial si no existe. No incluye un catálogo de libros ni préstamos de demostración.

### 5. Habilitar las portadas y compilar los recursos

```powershell
php artisan storage:link
npm ci
npm run build
```

El enlace `public/storage` permite mostrar las imágenes guardadas en el disco público. En Windows, la creación del enlace puede requerir permisos para enlaces simbólicos.

### 6. Iniciar la aplicación

```powershell
php artisan serve
```

Abre [http://127.0.0.1:8000](http://127.0.0.1:8000). La ruta inicial redirige al inicio de sesión o al panel del usuario autenticado.

Para desarrollar con actualización automática de recursos, ejecuta `npm run dev` en otra terminal mientras mantienes activo el servidor PHP.

---

## 🔑 Acceso inicial local

El seeder incluido define la siguiente cuenta para una instalación nueva:

| Campo | Valor |
|---|---|
| Correo | `admin@biblioteca.com` |
| Contraseña | `admin123` |
| Rol | Administrador |

Estas son credenciales iniciales del código, no una confirmación de acceso al sitio publicado. Sustituye la contraseña antes de utilizar la aplicación en un entorno público. La versión revisada no incluye una pantalla para cambiar contraseñas; el cambio debe realizarse desde un procedimiento administrativo, como Laravel Tinker, utilizando hash.

Las cuentas creadas desde **Registrarse** reciben el rol Lector. Para habilitar un Bibliotecario, registra una cuenta y cambia su rol desde la gestión de usuarios del Administrador.

---

## ✅ Flujo recomendado de verificación manual

1. Iniciar sesión como Administrador y revisar los indicadores del panel.
2. Registrar un libro con portada, año histórico y varios ejemplares; comprobar su visualización y búsqueda en el catálogo.
3. Crear cuentas mediante el registro público y asignar el rol Bibliotecario a una de ellas.
4. Iniciar sesión como Bibliotecario y registrar un préstamo para una cuenta Lector.
5. Comprobar que disminuya la disponibilidad del libro y que se rechace un segundo préstamo activo del mismo título para ese lector.
6. Entrar como Lector y comprobar que solo aparezca su historial y que no tenga acceso a las operaciones de gestión.
7. Registrar la devolución desde una cuenta Administrador o Bibliotecario y verificar que el ejemplar vuelva a estar disponible.
8. Comprobar que no se pueda eliminar un libro con historial ni reducir su cantidad por debajo de los préstamos activos.
9. Revisar catálogo, formularios y navegación en una pantalla móvil.

### Estado de las pruebas automáticas

La carpeta `tests/` contiene únicamente dos pruebas de ejemplo: una comprobación básica de `true` y una petición a `/` que espera HTTP 200. No incluye una suite específica para autenticación, permisos, libros, préstamos o devoluciones.

La prueba de `/` no refleja el comportamiento actual para visitantes, ya que esa ruta redirige al inicio de sesión. Esta documentación se basa en la revisión del código del ZIP; no afirma que las pruebas se hayan ejecutado ni superado, ni que la instalación o el despliegue hayan sido verificados durante esa revisión.

---

## 🌍 Consideraciones para el despliegue

- Configurar `APP_URL`, la conexión de base de datos y una clave de aplicación propia del entorno.
- Utilizar `APP_ENV=production` y `APP_DEBUG=false` en la publicación.
- Instalar las dependencias PHP y generar los recursos de Vite antes de servir la aplicación.
- Mantener accesibles los recursos compilados de `public/build` y las portadas mediante `public/storage`, o una configuración equivalente admitida por el alojamiento.
- Permitir escritura en `storage` y `bootstrap/cache`.
- Configurar el servidor para exponer el directorio `public` y proteger los archivos internos del proyecto.
- Tener en cuenta que Bootstrap, los iconos y las fuentes se cargan desde servicios externos.

La URL de InfinityFree indicada en este README corresponde al enlace de publicación proporcionado por el equipo. La configuración concreta del alojamiento puede requerir ajustes respecto de la instalación local.

---

## 🔒 Seguridad implementada y configuración

- Contraseñas almacenadas mediante hash.
- Regeneración de sesión tras el registro y el inicio de sesión.
- Invalidación de sesión y regeneración del token al cerrar sesión.
- Protección CSRF en los formularios que modifican datos.
- Validación del lado del servidor, incluidas las portadas.
- Rutas protegidas con autenticación y middleware de roles.
- Restricción del historial del Lector a su propia cuenta.

El archivo `.env`, las credenciales reales y las copias de la base de datos deben mantenerse fuera del repositorio. El `.gitignore` incluido excluye `.env`, `vendor`, `node_modules` y otros archivos generados. Estas medidas no sustituyen una auditoría de seguridad.

---

## 👨‍💻 Equipo de desarrollo

Proyecto desarrollado por estudiantes de la **Universidad Bolivariana del Ecuador**:

- Jesús Rafael Fernández Pérez
- José Andrés Merlin Gamboa
- José Alberto Rosado Domínguez
- Terry Steven Ponce Farías
- Elkin Daniel Velastegui Zambrano
- Zahid Joel Zambrano Moreira

---

## 🎓 Contexto académico y alcance

- **Institución:** Universidad Bolivariana del Ecuador.
- **Asignatura:** Programación Web Avanzada.
- **Proyecto:** Sistema de Biblioteca Online.
- **Año:** 2026.

El proyecto aplica arquitectura MVC, autenticación, control de acceso por roles, bases de datos relacionales, validación, gestión de archivos y diseño responsive.

**Biblioteca PWA** es el nombre del proyecto. La versión revisada no incluye un manifiesto web ni un service worker; por tanto, no se documentan instalación como aplicación progresiva, funcionamiento sin conexión ni notificaciones push. Tampoco incluye reservas, multas, fechas de vencimiento, lectura de libros digitales ni recuperación de contraseña mediante una interfaz propia.

La aplicación tiene fines académicos y no representa una plataforma institucional oficial de la universidad.

---

### 📚 Biblioteca PWA

**Organiza el catálogo. Controla cada préstamo.**
