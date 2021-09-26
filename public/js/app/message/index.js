
function saveMessage() {
 
  ajaxRequest($('#action_save').val(), {
    type: 'POST',
    data: $('#messages_type').serialize(),
    loading_message: 'Guardando mensajes...',
    error_message: 'Error al guardar mensajes',
    success_message: 'Los mensajes se guardaron correctamente',
    success_callback: function(data) {
    },
  });
}
