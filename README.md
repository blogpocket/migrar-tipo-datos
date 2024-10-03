# Migrar tipo de datos
Añade una opción "Migrar" a las entradas de un tipo de datos personalizado para copiarlas como borradores de entradas normales.
# Activar el plugin
- Ve al panel de administración de WordPress.
- Navega a Plugins > Plugins instalados.
- Busca Migrar RSS Club Posts y haz clic en Activar.
# Cómo funciona el plugin
- Añade una opción "Migrar": En la lista de entradas del tipo de dato personalizado, por ejemplo, rss-club, ahora verás una opción llamada "Migrar" debajo del título de cada entrada. ¡Cuidado! 'rss-club' es un ejemplo. Cambia en el código con el slug del tipo de datos personalizado en cuestión.
# Al hacer clic en "Migrar"
- Verificación de seguridad: El plugin verifica un nonce para asegurar que la acción es legítima.
- Permisos del usuario: Verifica que el usuario tenga permiso para editar la entrada original.
# Copia de la entrada
- Crea una nueva entrada de tipo post (entrada normal del blog).
- Copia el título, contenido, extracto, fecha, categorías y etiquetas.
- Establece el estado de la nueva entrada como "borrador".
- Copia de metadatos e imagen destacada:
- Copia todos los metadatos personalizados.
- Copia la imagen destacada si existe.
- Redirección al editor: Después de crear la nueva entrada, el plugin te redirige al editor para que puedas revisarla y publicarla cuando desees.
# Notas adicionales
## Campos personalizados y taxonomías
- Si tu tipo de dato personalizado utiliza taxonomías personalizadas o campos personalizados específicos que no son compatibles directamente con las entradas normales, es posible que necesites ajustar el código para manejar esas taxonomías o campos adicionales.
## Pruebas
- Te recomiendo probar este plugin en un entorno de desarrollo o en un sitio de prueba antes de utilizarlo en tu sitio en producción, para asegurarte de que funciona según lo esperado y no causa conflictos con otros plugins o temas.
## Personalización
- Puedes modificar el código del plugin para adaptarlo a tus necesidades específicas, como cambiar el estado de la nueva entrada a "pendiente" en lugar de "borrador", o ajustar qué elementos se copian.
## No te olvides de cambiar 'rss-club' en el código por '<tu tdp>', donde <tu tdp> es el slug del tipo de datos personalizado que quieres migrar.
