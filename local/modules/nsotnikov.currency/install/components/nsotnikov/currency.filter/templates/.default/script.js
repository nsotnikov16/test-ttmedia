const courseReplace = (input) => {
    const cleanedStr = input.value.replace(/[^0-9.]/g, '').replace(/^(\d*\.\d*).*$/, '$1');
    if (cleanedStr.endsWith('.')) {
        input.value = cleanedStr;
        return;
    }
    const result = parseFloat(cleanedStr);
    if (isNaN(result) || result < 0) {
        input.value = 0;
    } else {
        input.value = result;
    }
}

const codeReplace = (input) => {
    input.value = input.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
    if (input.value.length > 3) input.value = input.value.slice(0, 3);
}

const validateFilterForm = () => {
    const courseInputs = document.querySelectorAll('.field-COURSE');
    const codeInputs = document.querySelectorAll('.field-CODE');

    if (courseInputs.length) {
        courseInputs.forEach(input => input.addEventListener('input', ({ target }) => courseReplace(target)))
    }

    if (codeInputs.length) {
        codeInputs.forEach(input => input.addEventListener('input', ({ target }) => codeReplace(target)))
    }
}


document.addEventListener('DOMContentLoaded', validateFilterForm);