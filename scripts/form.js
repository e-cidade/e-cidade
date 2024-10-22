function serializarFormulario(form) {
  const formData = new FormData(form);
  const objeto = {};
  formData.forEach((value, key) => {
      if (objeto[key]) {
          if (!Array.isArray(objeto[key])) {
              objeto[key] = [objeto[key]];
          }
          objeto[key].push(value);
      } else {
          objeto[key] = value;
      }
  });
  return JSON.stringify(objeto);
}
