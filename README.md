# Giunti Psychometrics (Laravel)

Catálogo web de test psicométricos y cursos de formación. Es la migración a Laravel del proyecto `giunti-psychometrics-next`: mantiene la misma apariencia, navegación y funcionalidades, pero con el stack propio de Laravel.

Está construido con **Laravel 13**, **Blade**, **Stylus** con nomenclatura **BEM**, **jQuery 4** y **Vite**.

La interfaz está en italiano.

---

## Requisitos

- **PHP 8.4** o superior, con las extensiones `pdo_sqlite` y `mbstring`. No hace falta `intl`: los formatos de precio y fecha se calculan en PHP.
- **Composer**.
- **Node.js 24** (la versión exacta está en `mise.toml`, que también usa Railway). Vite 8 funciona desde Node 20.19 o 22.12.
- **npm** (incluido con Node).
- **Git**.

## Instalación

1. Clona el repositorio:

   ```bash
   git clone <url-del-repositorio>
   cd giunti-psychometrics-laravel
   ```

2. Instala las dependencias de PHP y de Node:

   ```bash
   composer install
   npm install
   ```

3. Crea el archivo de entorno y la clave de la aplicación:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Crea la base de datos SQLite y ejecuta las migraciones. La aplicación no guarda productos en la base de datos, pero las **sesiones** (y con ellas el **carrito**) sí se guardan ahí:

   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

5. Arranca el entorno de desarrollo:

   ```bash
   composer run dev
   ```

   La aplicación queda disponible en [http://localhost:8000](http://localhost:8000).

## Comandos

| Comando | Para qué sirve |
|---|---|
| `composer run dev` | Arranca todo el entorno de desarrollo a la vez: servidor de Laravel, Vite (con recarga en caliente) y la cola. |
| `php artisan serve` | Solo el servidor de Laravel en [http://localhost:8000](http://localhost:8000). |
| `npm run dev` | Solo Vite: compila Stylus y JavaScript al vuelo y recarga el navegador. |
| `npm run build` | Genera los assets optimizados para producción en `public/build`. |
| `php artisan test` | Ejecuta los tests con Pest. |
| `vendor/bin/pint` | Formatea el código PHP con Laravel Pint. |
| `php artisan route:list --except-vendor` | Lista las rutas de la aplicación. |

Para comprobar el build de producción en local:

```bash
npm run build
php artisan serve
```

> Si `npm run dev` está apagado y aparece el error *"Unable to locate file in Vite manifest"*, ejecuta `npm run build` (o vuelve a arrancar `npm run dev`).

> La web se abre en [http://localhost:8000](http://localhost:8000) (Laravel), **no** en `localhost:5173`: esa dirección es el servidor de Vite y solo muestra una página de aviso.

---

## Despliegue en Railway

Railway despliega automáticamente cada `git push` a la rama conectada: instala las dependencias, ejecuta `npm run build` y arranca la aplicación.

### Variables de entorno

En el servicio → pestaña **Variables**:

| Variable | Valor | Por qué |
|---|---|---|
| `APP_KEY` | `base64:...` | Obligatoria. Cifra la sesión y las cookies. |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | Activarla solo un momento para ver un error en pantalla. |
| `APP_URL` | `https://<tu-dominio>.up.railway.app` | Debe empezar por `https://`. |
| `SESSION_DRIVER` | `cookie` | Railway no tiene la base de datos SQLite (ver abajo). |
| `CACHE_STORE` | `file` | Mismo motivo. |
| `QUEUE_CONNECTION` | `sync` | Mismo motivo; no hay worker de colas. |

La `APP_KEY` se genera en local con el siguiente comando, que solo la muestra y no modifica tu `.env`:

```bash
php artisan key:generate --show
```

Usa una clave distinta de la de tu `.env` local y no la subas al repositorio. Si se cambia, las sesiones abiertas (y los carritos) se pierden.

### Por qué no se usa la base de datos en producción

La aplicación no guarda nada en base de datos: los productos salen de `config/catalog.php` y el carrito vive en la sesión. En local la sesión se guarda en SQLite, pero `database/database.sqlite` está en `.gitignore` y no llega a Railway. Con `SESSION_DRIVER=database` cada visita falla con **500 Internal Server Error** (`Database file at path [...] does not exist`).

Con `SESSION_DRIVER=cookie`, la sesión va cifrada en una cookie del navegador: no necesita base de datos y sobrevive a los redespliegues. El carrito solo guarda `slug => cantidad`, así que cabe de sobra.

### HTTPS detrás del proxy

Railway recibe las visitas por HTTPS y las reenvía a Laravel por HTTP. Para que Laravel genere las URLs de CSS, JS e imágenes con `https://`, `bootstrap/app.php` confía en el proxy:

```php
$middleware->trustProxies(at: '*');
```

Sin esa línea, el navegador bloquea los assets por **contenido mixto** y la página se ve sin estilos. El test `tests/Feature/TrustProxiesTest.php` comprueba que se mantenga.

### Si algo falla

| Síntoma | Causa probable |
|---|---|
| 500 en todas las páginas | Falta `APP_KEY`, o `SESSION_DRIVER` / `CACHE_STORE` siguen en `database`. |
| La página carga sin estilos | Consola del navegador (F12): *Mixed Content* → falta el cambio de `trustProxies` en el despliegue; `.css` con 404 → el build no ejecutó `npm run build`. |
| *Vite manifest not found* | El build no generó `public/build`. Revisa los **Build Logs** de Railway. |

Para cualquier otro error, revisa los **Deploy Logs** del servicio o activa `APP_DEBUG=true` un momento.

---

## Versiones

### v0.1.1

#### Despliegue en Railway

- **Sesión y caché sin base de datos en producción:** `SESSION_DRIVER=cookie` y `CACHE_STORE=file` evitan el 500 por la falta del archivo SQLite.
- **HTTPS detrás del proxy:** `trustProxies(at: '*')` en `bootstrap/app.php`, para que los assets carguen con `https://`.
- **Requisitos actualizados:** PHP 8.4 y Node 24 (`mise.toml`).
- **Favicon:** el logotipo (`public/images/Logotip.png`) aparece en la pestaña del navegador.
- **Comentarios del código** resumidos: sin equivalencias de Tailwind ni banners decorativos.

### v0.1.0

#### Migración desde Next.js

El proyecto Next.js es la referencia: cada página, componente y estado se ha trasladado a su equivalente en Laravel.

| Next.js | Laravel |
|---|---|
| App Router y rutas dinámicas | `routes/web.php` y controladores |
| Componentes React | Componentes Blade con props |
| Tailwind CSS | Stylus + BEM |
| Hooks y estado de React | Módulos jQuery |
| `lib/services`, `lib/data` | `app/Services`, `config/catalog.php` |
| Interfaces y tipos de TypeScript | Enums y DTOs de PHP |
| Carrito en `localStorage` | Carrito en la sesión de Laravel |
| Validación solo en el cliente | Validación en el cliente (jQuery) y en el servidor (Form Request) |

#### Estructura modular

Cada responsabilidad vive en la carpeta que le corresponde según las convenciones de Laravel. El JavaScript y los estilos solo se ocupan de la presentación: el filtrado, la búsqueda y los totales se calculan en PHP.

```text
├── app/
│   ├── Contracts/            Interfaces: ProductRepository
│   ├── DTOs/                 Datos de producto, filtros y carrito (readonly)
│   ├── Enums/                Tipo, formato, categoría y acción del producto
│   ├── Http/
│   │   ├── Controllers/      Home, catálogo, búsqueda, carrito y contacto
│   │   └── Requests/         ContactRequest: reglas y mensajes del formulario
│   ├── Services/             Lógica de negocio: productos, carrito y contacto
│   ├── Support/              Formatter (euros, fechas, iniciales) y Link
│   └── Providers/            Registro del repositorio y view composer del carrito
├── config/
│   ├── catalog.php           Datos de ejemplo (se sustituirán por una API)
│   ├── products.php          Pestañas, ventajas y tiempos de búsqueda
│   ├── contact.php           Datos de contacto y reglas del formulario
│   └── site.php              Navegación, footer y tarjetas del hero
├── resources/
│   ├── views/
│   │   ├── layouts/          app (todo el sitio) y page (páginas interiores)
│   │   ├── components/       Componentes reutilizables: ui, layout, product, cart
│   │   ├── sections/         Secciones de cada página: home y products
│   │   ├── pages/            Vistas de las páginas
│   │   ├── errors/           Página 404 global
│   │   └── icons/            Iconos SVG (lucide)
│   ├── stylus/               Estilos: abstracts, base, components, layout, pages, utilities
│   ├── js/
│   │   ├── modules/          Un módulo jQuery por funcionalidad
│   │   └── utils/            AJAX, debounce, clic fuera, bloqueo de scroll
│   └── fonts/                Inter variable (normal e itálica)
├── routes/web.php            Rutas de la aplicación
├── public/videos/            Vídeo del hero
└── tests/                    Tests de Pest
```

| Carpeta | Contiene | Ejemplo |
|---|---|---|
| `config/` | Constantes, opciones y datos de ejemplo | `products.search.debounce_ms`, `site.navigation` |
| `app/Enums/` | Valores cerrados con su etiqueta | `ProductType::Course->label()` → `Formazione` |
| `app/DTOs/` | Forma de los datos | `ProductData`, `CartSummary` |
| `app/Contracts/` | Interfaces en los límites del sistema | `ProductRepository` |
| `app/Services/` | Lógica de negocio y acceso a datos | `ProductService::filter()`, `CartService::add()` |
| `resources/views/components/` | UI compartida | `<x-product.card>`, `<x-ui.select>` |
| `resources/views/sections/` | UI de una sola sección | `sections.home.hero`, `sections.products.detail` |
| `resources/js/modules/` | Interactividad | `catalog.js`, `cart.js` |

#### Rutas y slugs

| Ruta | Página |
|---|---|
| `/` | Home: hero, productos destacados y contacto (`#contatti`) |
| `/products?type=test` | Catálogo de test (pestaña por defecto) |
| `/products?type=course` | Catálogo de cursos |
| `/products/{slug}` | Detalle de producto, por ejemplo `/products/scala-umore` |
| `/resources` | Página en construcción |

- **Slugs:** el detalle se resuelve por slug a través de `ProductService`. Si el slug no existe, se muestra la página propia de **"Prodotto non trovato"** con estado 404.
- **404 global:** cualquier otra ruta inexistente muestra `errors/404.blade.php`, sin header ni footer, como en el original.
- **Filtros en la URL:** el catálogo acepta `search`, `category` y `format` (`/products?type=test&format=Cartaceo&search=umore`). La URL se actualiza al filtrar, así que se puede compartir y la página carga ya filtrada.
- **Endpoints AJAX:**

  | Método | Ruta | Uso |
  |---|---|---|
  | `GET` | `/products` | Con `Accept: application/json` devuelve los resultados y el catálogo renderizados |
  | `GET` | `/products/search?q=` | Resultados del buscador del header |
  | `GET` | `/cart` | Estado del carrito |
  | `POST` | `/cart/items` | Añadir un producto |
  | `DELETE` | `/cart/items/{slug}` | Quitar un producto |
  | `POST` | `/contact` | Enviar el formulario de contacto |

  Las respuestas incluyen el HTML ya renderizado por los mismos componentes Blade, así que el JavaScript no duplica ninguna plantilla.

#### Componentes Blade

- **Card y grid separados:**
  - `<x-product.card>` recibe un solo producto.
  - `<x-product.grid>` recibe la lista y decide si pinta skeleton, estado vacío o cards.
  - La card no sabe nada de los filtros: el controlador le pasa la lista ya filtrada.
- **Componentes genéricos:**
  - `<x-ui.select>` funciona con cualquier lista de opciones, se usa con el teclado y guarda el valor en un input oculto.
  - `<x-ui.form-field>` agrupa etiqueta, control y mensaje de error o pista.
  - `<x-cart.add-button>` tiene tres variantes: `pill`, `icon` y `block`.
  - `<x-ui.icon name="...">` pinta cualquiera de los iconos de `resources/views/icons`, sin dependencias.
- **Interfaz basada en datos:** las pestañas, los filtros, la navegación, el footer y las tarjetas del hero se generan a partir de `config/` y de los enums. Añadir un producto o una opción no requiere tocar vistas.

#### Estilos: Stylus + BEM

- **BEM en todas las clases:** `bloque`, `bloque__elemento` y `bloque--modificador`. Las utilidades llevan el prefijo `u-` para distinguirlas de los bloques.
- **Variables globales** en `abstracts/_variables.styl`: colores de marca, tipografía, espaciados, radios, sombras, transiciones y capas (`z()`).
- **Fidelidad con Tailwind:** cada clase del original se tradujo a sus propiedades reales. Los breakpoints son los mismos (`sm` 640, `md` 768, `lg` 1024, `xl` 1280) y se usan con mixins mobile first:

  ```stylus
  .product-grid
    grid-template-columns: minmax(0, 1fr)

    +md()
      grid-template-columns: repeat(2, minmax(0, 1fr))
  ```

- **Mixins propios:** `dark()` (equivale a `dark:`), `motion-safe()`, `text-ink()`, `surface-section()`, `glass-card()`, `line-clamp()`, entre otros.

#### Skeletons y búsqueda con debounce

- **Carga simulada con skeleton:** al entrar en la home o en el catálogo, y al cambiar de pestaña, se muestra un skeleton con la misma forma que el contenido durante 800 ms.
  - Lo resuelve el bloque `.loadable`. Un script en `<head>` marca la página con la clase `js` antes del primer pintado, así que el skeleton aparece sin parpadeo.
  - Sin JavaScript se ve el contenido directamente.
- **Debounce en los buscadores** (header y catálogo):
  - El filtro no se ejecuta en cada tecla.
  - Mientras se escribe, los resultados se atenúan.
  - 500 ms después de la última pulsación se muestra el skeleton durante 500 ms, junto con la petición al servidor, y luego los resultados.
  - Los tiempos están en `config/products.php` y el layout los pasa al JavaScript: no hay valores duplicados.
- **Sin animaciones repetidas:** al cambiar un filtro, las cards que siguen en el resultado se conservan y no vuelven a animarse.

#### Cambio de tema (claro / oscuro)

- **Botón en el header:** alterna entre tema claro y oscuro.
- **Preferencia guardada:** se guarda en `localStorage`. Si el usuario nunca eligió, se usa la del sistema operativo.
- **Sin parpadeo:** un script inline en `<head>` aplica el tema antes del primer pintado.
- **Sincronizado entre pestañas:** el cambio se aplica en todas las pestañas abiertas.

#### Diseño 100% responsive

Probado comparando cada página con el proyecto Next.js en cinco anchos:

| Dispositivo | Ancho | Comportamiento |
|---|---|---|
| Mobile | 375 px | Menú hamburguesa, filtros apilados, chips con scroll horizontal, una columna y carrito a pantalla completa |
| — | 640 px | Botones del header visibles, filtros en fila y formularios a dos columnas |
| Tablet | 768 px | Grids de 2 columnas y carrito de 420 px |
| — | 1024 px | Navegación completa, buscador en el header, grid de 3 columnas, tarjetas del hero en 4 y detalle a dos columnas con columna de compra fija |
| Desktop | 1280 px o más | Navegación más espaciada y buscador del header más ancho |

Las animaciones de entrada solo animan `opacity` y `transform`, y respetan la opción "reducir movimiento" del sistema (mixin `motion-safe()`).

#### Interactividad con jQuery

Cada funcionalidad es un módulo en `resources/js/modules/`. Cada módulo comprueba si su marcado existe antes de inicializarse:

| Módulo | Se encarga de |
|---|---|
| `theme.js` | Cambio de tema y sincronización entre pestañas |
| `mobile-menu.js` | Menú mobile: abrir, cerrar, Esc y clic fuera |
| `select.js` | Select accesible (listbox WAI-ARIA) con ratón y teclado |
| `header-search.js` | Buscador del header con debounce y skeleton |
| `catalog.js` | Filtros, chips, buscador, URL y cambio de pestaña sin recargar |
| `cart.js` | Panel del carrito, añadir y quitar productos |
| `toast.js` | Notificaciones: 6 variantes, máximo 4, pausa al pasar el ratón |
| `contact-form.js` | Validación, contador de caracteres y envío por AJAX |
| `loadable.js` | Carga simulada con skeleton |

- **Pestañas:** **Test** y **Formazione** cambian sin recargar la página: solo se sustituye el catálogo. La URL y el título se actualizan, y funcionan los botones atrás y adelante del navegador.
- **Estados con clases BEM:** el JavaScript no escribe estilos; añade o quita modificadores como `cart-drawer--open`, `select--open` o `loadable--loading`.

#### Carrito

- **Añadir:** desde la card, desde el buscador o desde el detalle, con un toast de confirmación.
- **Panel lateral:** sale por la derecha y muestra los productos, las cantidades, el número de artículos y el total. Desde ahí se puede eliminar cada producto.
  - Al abrirse, lleva el foco al botón de cerrar, bloquea el scroll y se cierra con Esc o al pulsar fuera.
- **Persistencia:** el carrito se guarda en la **sesión de Laravel** (`CartService`): en SQLite en local y en una cookie cifrada en producción. Sobrevive a una recarga y se actualiza al volver a la pestaña si se modificó en otra.

#### Formulario de contacto

- **Validación en el cliente:** los errores aparecen al salir de un campo o al enviar, y el foco va al primer campo con error.
- **Validación en el servidor:** `ContactRequest` aplica las mismas reglas y los mismos mensajes en italiano.
- **Estados:** idle → *Invio in corso...* → *Inviato*, con toasts de aviso, éxito y error.
- **Sin JavaScript** el formulario sigue funcionando: se envía de forma normal y los errores vuelven a la sección de contacto.
- **Envío:** de momento `ContactService` registra el mensaje en el log. Cuando exista el destino real (correo o API), se conecta ahí.

#### Tests

Los tests de Pest cubren las rutas, los slugs y el 404, los filtros del catálogo, la búsqueda, el carrito, la validación del formulario, los formatos de precio y fecha, y el `ProductService`.

```bash
php artisan test
```
