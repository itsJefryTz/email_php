# Email PHP API

Esta es una API simple en PHP para enviar correos electrónicos utilizando componentes de Symfony.

## Instalación

1. Asegúrate de tener PHP y Composer instalados en tu sistema.
2. Clona o descarga este repositorio.
3. Ejecuta `composer install` en la raíz del proyecto para instalar las dependencias.

## Ejecutar el servidor

Para ejecutar la API, puedes usar el servidor integrado de PHP:

```bash
php -S localhost:8000 index.php
```

Esto iniciará el servidor en `http://localhost:8000`.

## Endpoints

### GET /api

Devuelve un mensaje de bienvenida.

**Respuesta de ejemplo:**
```json
{
  "message": "Hello, API!",
  "status": "success"
}
```

### POST /api/v1/sendEmail

Envía un correo electrónico.

**Cuerpo de la solicitud (JSON):**
```json
{
  "settings": {
    "host": "smtp.example.com",
    "port": 465,
    "user": "tuemail@example.com",
    "pass": "tucontraseña",
    "encryption": "smtps"
  },
  "email": {
    "to": "destinatario@example.com",
    "subject": "Asunto del correo",
    "body": "<p>Contenido del correo en HTML</p>"
  }
}
```

**Campos requeridos:**
- `settings.host`: Host del servidor SMTP
- `settings.port`: Puerto del servidor SMTP (por defecto 465)
- `settings.user`: Usuario del correo
- `settings.pass`: Contraseña del correo
- `settings.encryption`: Tipo de encriptación (ej. smtps)
- `email.to`: Dirección de correo del destinatario (debe ser un email válido)
- `email.subject`: Asunto del correo
- `email.body`: Cuerpo del correo en HTML

**Respuestas posibles:**

- **Éxito (200):**
  ```json
  {
    "message": "Email sent successfully!",
    "status": "success"
  }
  ```

- **Error (400 - Bad Request):**
  - Datos faltantes o inválidos
  - Email no válido

- **Error (405 - Method Not Allowed):**
  - Método no permitido (solo POST)

- **Error (500 - Internal Server Error):**
  - Problemas con el envío del correo

## Notas

- Asegúrate de que tu servidor SMTP permita conexiones desde esta aplicación.
- El campo `body` acepta HTML para el contenido del correo.
- La API valida que el email del destinatario sea válido antes de intentar enviar.

## Dependencias

- Symfony Routing
- Symfony HTTP Foundation
- Symfony Mailer