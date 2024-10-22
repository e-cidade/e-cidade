function restrictAndFormatInput(input, decimalPlaces) {
  input.addEventListener('input', function(e) {
    let value = input.value;

    value = value.replace(/\D/g, '');
    if (decimalPlaces > 0) {
        if (value.length <= decimalPlaces) {
            value = value.padStart(decimalPlaces + 1, '0');
        }
    }

    let integerPart = value.slice(0, -decimalPlaces) || '0';
    let decimalPart = value.slice(-decimalPlaces);

    integerPart = integerPart.replace(/^0+/, '') || '0';
    if (integerPart.length > 3) {
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    let formattedValue = decimalPlaces > 0 ? `${integerPart},${decimalPart}` : integerPart;

    input.value = formattedValue;
  });

  input.addEventListener('keydown', function(e) {
      if (e.ctrlKey || e.metaKey || [8, 9, 27, 13, 46].includes(e.keyCode) ||
          (e.keyCode >= 35 && e.keyCode <= 40)) {
          return;
      }

      const isNumberKey = (e.keyCode >= 48 && e.keyCode <= 57);
      const isNumpadKey = (e.keyCode >= 96 && e.keyCode <= 105);

      if (!isNumberKey && !isNumpadKey) {
          e.preventDefault();
      }
  });
}
