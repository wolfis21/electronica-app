# Sistema de Soporte Técnico

## Configuración de Google Forms

Este sistema utiliza Google Forms para recopilar reportes de bugs y sugerencias de los usuarios. Para configurarlo correctamente:

### 1. Crear los Google Forms

#### Formulario para Reportes de Bugs:
1. Ve a [Google Forms](https://forms.google.com)
2. Crea un nuevo formulario llamado "Reporte de Bugs - Electronica App"
3. Agrega los siguientes campos:

**Campos Obligatorios:**
- **Nombre del Usuario** (Respuesta corta)
- **Email** (Respuesta corta)
- **Descripción del Problema** (Párrafo)
- **Pasos para Reproducir** (Párrafo)
- **Comportamiento Esperado** (Párrafo)
- **Comportamiento Actual** (Párrafo)

**Campos Automáticos (se llenan via URL):**
- **URL donde ocurrió** (Respuesta corta)
- **Fecha y Hora** (Respuesta corta)
- **Información del Navegador** (Párrafo)
- **Resolución de Pantalla** (Respuesta corta)

**Campos Opcionales:**
- **Nivel de Prioridad** (Selección múltiple: Baja, Media, Alta, Crítica)
- **Capturas de Pantalla** (Subir archivo)
- **Información Adicional** (Párrafo)

#### Formulario para Sugerencias:
1. Crea otro formulario llamado "Sugerencias - Electronica App"
2. Agrega los siguientes campos:

**Campos Obligatorios:**
- **Nombre del Usuario** (Respuesta corta)
- **Email** (Respuesta corta)
- **Tipo de Sugerencia** (Selección múltiple: Mejora de Funcionalidad, Nueva Funcionalidad, Mejora de UI/UX, Otro)
- **Descripción de la Sugerencia** (Párrafo)
- **Beneficio Esperado** (Párrafo)

**Campos Automáticos:**
- **URL de la Página** (Respuesta corta)
- **Fecha y Hora** (Respuesta corta)

### 2. Configurar las URLs en el Código

1. Abre el archivo `resources/js/config/support.js`
2. Reemplaza las URLs por las de tus formularios:

```javascript
export const supportConfig = {
    bugReportUrl: 'https://docs.google.com/forms/d/TU_ID_DE_FORMULARIO_BUGS/viewform',
    suggestionUrl: 'https://docs.google.com/forms/d/TU_ID_DE_FORMULARIO_SUGERENCIAS/viewform',
    // ... resto de la configuración
};
```

### 3. Configurar Parámetros Pre-llenados (Opcional)

Para que los campos se llenen automáticamente:

1. En tu Google Form, haz clic en "Prellenar enlace"
2. Llena los campos con valores de ejemplo
3. Copia el enlace generado
4. Identifica los parámetros `entry.XXXXXX` en la URL
5. Actualiza la función `generateBugReportUrl` en `support.js` con los IDs correctos:

```javascript
generateBugReportUrl: (additionalData = {}) => {
    const systemInfo = supportConfig.getSystemInfo();
    const params = new URLSearchParams({
        'entry.123456789': systemInfo.url,        // ID para campo "URL"
        'entry.987654321': systemInfo.timestamp,  // ID para campo "Fecha"
        'entry.555666777': systemInfo.userAgent,  // ID para campo "Navegador"
        // ... más campos
    });
    
    return `${supportConfig.bugReportUrl}?${params.toString()}`;
}
```

### 4. Configurar Notificaciones por Email

1. En cada Google Form, ve a "Respuestas"
2. Haz clic en los tres puntos (⋮) > "Obtener notificaciones por correo electrónico"
3. Activa las notificaciones para recibir emails cuando alguien envíe un reporte

### 5. Configurar Hoja de Cálculo (Opcional)

1. En "Respuestas", haz clic en el icono de Google Sheets
2. Crea una hoja de cálculo para almacenar las respuestas
3. Esto te permitirá analizar los datos y hacer seguimiento de los reportes

### 6. Uso del Sistema

#### En el Dashboard:
- Los usuarios verán una sección de soporte en la parte inferior
- Pueden hacer clic en "Reportar Bug" o "Enviar Sugerencia"
- Se abrirá el Google Form en una nueva pestaña

#### En el Menú Lateral:
- Hay una sección "Soporte" con accesos directos
- Los enlaces tienen un icono de "enlace externo" para indicar que abren en nueva pestaña

#### En Otras Páginas:
- Puedes agregar el componente `<SupportSection />` en cualquier página Vue
- También puedes personalizar las URLs pasando props

### 7. Personalización

#### Cambiar URLs específicas por página:
```vue
<SupportSection 
    :bug-report-url="'https://forms.google.com/url-especifica-bugs'"
    :suggestion-url="'https://forms.google.com/url-especifica-sugerencias'"
    :additional-data="{ page: 'Dashboard', module: 'Analytics' }"
/>
```

#### Agregar datos adicionales:
```vue
<SupportSection 
    :additional-data="{ 
        orderId: order.id, 
        customerName: customer.name,
        errorCode: 'ERR_404'
    }"
/>
```

### 8. Mejores Prácticas

1. **Revisa los reportes regularmente** - Configura notificaciones por email
2. **Categoriza los problemas** - Usa etiquetas o columnas adicionales en la hoja de cálculo
3. **Responde a los usuarios** - Considera enviar emails de seguimiento
4. **Analiza patrones** - Identifica problemas recurrentes para priorizarlos
5. **Actualiza el sistema** - Mejora los formularios basándote en el feedback recibido

### 9. URLs del Menú Lateral

Para actualizar las URLs en el menú lateral, edita:
`resources/js/Layouts/AuthenticatedLayout.vue`

Busca la sección de "Soporte" y actualiza las URLs:

```javascript
{
    title: 'Soporte',
    items: [
        { 
            name: 'Reportar Problema', 
            icon: ExclamationTriangleIcon, 
            href: 'https://docs.google.com/forms/d/TU_ID_BUGS/viewform', 
            current: false, 
            permission: true,
            external: true
        },
        // ...
    ]
}
```

¡Con esta configuración tendrás un sistema completo de soporte técnico integrado en tu aplicación!
