crud.field('type').onChange(function(field) {
    crud.field('bank').show(!Number(field.value));
}).change();