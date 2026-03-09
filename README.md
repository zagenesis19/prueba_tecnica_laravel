# Prueba Técnica Laravel - API de Pedidos

Esta es una API REST funcional para la gestión de productos y pedidos, desarrollada como parte de una prueba técnica. El proyecto sigue el patrón MVC y utiliza Eloquent ORM.

## 1. Pasos para ejecutar el proyecto

Sigue estos comandos para configurar el entorno localmente:

```bash
# 1. Instalar dependencias de PHP
composer install

# 2. Configurar el archivo de entorno
cp .env.example .env

# 3. Generar la clave de la aplicación
php artisan key:generate

# 4. Crear la base de datos SQLite (si no existe)
# En Windows: type nul > database/database.sqlite
# En Linux/Mac: touch database/database.sqlite

# 5. Ejecutar las migraciones
php artisan migrate

# 6. Iniciar el servidor de desarrollo
php artisan serve
```

*Nota: Asegúrate de tener habilitada la extensión `pdo_sqlite` en tu `php.ini`.*

---

## 2. Endpoints Disponibles

### Productos
- `GET /api/products`: Lista todos los productos (id, nombre, precio).
- `POST /api/products`: Crea un nuevo producto.
  - Body: `{"name": "Producto A", "price": 100.50}`

### Pedidos
- `GET /api/orders/{id}`: Obtiene el detalle de un pedido, incluyendo el usuario y la lista de productos con sus cantidades.
- `POST /api/orders`: Crea un pedido para un usuario.
  - Body: 
    ```json
    {
      "user_id": 1,
      "products": [
        {"product_id": 1, "quantity": 2},
        {"product_id": 2, "quantity": 1}
      ]
    }
    ```

### Frontend (Vista Blade)
- `GET /`: Muestra una lista sencilla de los productos disponibles.

---

## 3. Mejoras Posibles

Si se dispusiera de más tiempo para la evolución del proyecto, se implementarían las siguientes mejoras:

1.  **Manejo de Errores y Excepciones**: Implementar un Exception Handler centralizado para retornar respuestas JSON consistentes en caso de errores de base de datos o rutas no encontradas.
2.  **Uso de FormRequests**: Extraer la lógica de validación de los controladores a clases `FormRequest` especializadas para mantener los controladores más limpios.
3.  **API Resources**: Utilizar `JsonResource` para transformar los modelos y controlar exactamente qué campos se exponen en la API, facilitando el versionado.
4.  **Tests Automatizados**: Crear pruebas unitarias y de integración (Feature Tests) para asegurar que el cálculo del total y la lógica de la tabla pivote funcionen correctamente.
5.  **Autenticación**: Implementar Laravel Sanctum o JWT para proteger los endpoints de creación de productos y pedidos.
6.  **Optimización de Consultas (Eager Loading)**: Aunque ya se usa en algunos puntos, se podría optimizar aún más para evitar el problema de consultas N+1 en listados complejos.

### ⚠️ Nota sobre datos de prueba para Pedidos
Para probar el endpoint de creación de pedidos (`POST /api/orders`), el sistema valida estrictamente que el `user_id` exista en la base de datos. Como la prueba no requería un endpoint de registro de usuarios ni seeders, puedes generar un usuario de prueba rápidamente ejecutando en tu terminal:

```bash
php artisan tinker
\App\Models\User::factory()->create();