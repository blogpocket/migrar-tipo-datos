# Migrar tipo de datos
Añade una opción "Migrar" a los tipos de entradas seleccionados para copiarlas como borradores de entradas normales.
# Activar el plugin
- Ve al panel de administración de WordPress.
- Navega a Plugins > Plugins instalados.
- Busca Migrar Entradas Personalizadas y haz clic en Activar.
# Configurar los tipos de entrada
- Después de activar el plugin, ve a Ajustes > Migrar Entradas.
- Verás una lista de los tipos de entrada personalizados públicos disponibles.
- Marca las casillas de los tipos de entrada que deseas habilitar para la migración.
- Haz clic en Guardar cambios.
# Cómo funciona el plugin
## Interfaz de administración
- Se agrega una nueva página de ajustes bajo Ajustes > Migrar Entradas.
- En esta página, puedes seleccionar los tipos de entrada personalizados que deseas habilitar para la migración.
- Los tipos de entrada seleccionados se almacenan en la opción migrar_entradas_tipos.
## Añadir la opción "Migrar"
- En la lista de entradas de los tipos seleccionados, ahora verás una opción llamada "Migrar" debajo del título de cada entrada.
- La función migrar_entradas_agregar_opcion verifica si el tipo de entrada de la publicación actual está en la lista de tipos seleccionados y añade la opción "Migrar" si es así.
## Al hacer clic en "Migrar"
- Verificación de seguridad: El plugin verifica un nonce para asegurar que la acción es legítima.
- Permisos del usuario: Verifica que el usuario tenga permiso para editar la entrada original.
## Copia de la entrada
- Crea una nueva entrada de tipo post (entrada normal del blog).
- Copia el título, contenido, extracto, fecha, categorías y etiquetas.
- Establece el estado de la nueva entrada como "borrador".
## Copia de metadatos e imagen destacada
- Copia todos los metadatos personalizados.
- Copia la imagen destacada si existe.
## Redirección al editor
- Después de crear la nueva entrada, el plugin te redirige al editor para que puedas revisarla y publicarla cuando desees.
# Notas adicionales
## Campos personalizados y taxonomías
- Si tu tipo de dato personalizado utiliza taxonomías personalizadas o campos personalizados específicos que no son compatibles directamente con las entradas normales, es posible que necesites ajustar el código para manejar esas taxonomías o campos adicionales.
## Pruebas
- Te recomiendo probar este plugin en un entorno de desarrollo o en un sitio de prueba antes de utilizarlo en tu sitio en producción, para asegurarte de que funciona según lo esperado y no causa conflictos con otros plugins o temas.
## Personalización
- Puedes modificar el código del plugin para adaptarlo a tus necesidades específicas, como cambiar el estado de la nueva entrada a "pendiente" en lugar de "borrador", o ajustar qué elementos se copian.
# Ejemplo de cómo configurar y utilizar el plugin
- Configurar los tipos de entrada:
- Ver la opción "Migrar" en la lista de entradas:
- Después de migrar, serás redirigido al editor de la nueva entrada:
