const getFormData = (obj, form, namespace) => {

    let fd = form || new FormData();
    let formKey;

    for (let property in obj) {

        if (namespace) {

            formKey = namespace + '[' + property + ']';

        } else {

            formKey = property;

        }

        if (typeof obj[property] === 'object' && !(obj[property] instanceof File)) {

            getFormData(obj[property], fd, formKey);

        } else {

            const value = obj[property] === null ? '' : obj[property];
            fd.append(formKey, value);

        }

    }

    return fd;

};

export {
    getFormData,
}
