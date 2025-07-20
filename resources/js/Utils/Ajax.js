import {nextTick} from "vue";

const getErrorMessages = (error) => { // axios error

    let errorMessages = {};
    const errors = _.get(error, 'response.data.errors', {});

    if(! _.isEmpty(errors)) {

        for(let key in errors) {

            errorMessages[key] = errors[key][0];

        }

    }

    return errorMessages;

}

const scrollToElement = (element, extraHeight = -100) => { // 特定の要素までスクロール

    if(element) {

        const elementPosition = element.getBoundingClientRect().top + window.scrollY + extraHeight;
        window.scrollTo({ top: elementPosition, behavior: 'smooth' });

    }

};

const scrollToFirstError = () => { // エラーがある場合、最初のエラーまでスクロール

    const firstErrorElement = document.querySelector('.input-error');

    nextTick(() => {

        setTimeout(() => {

            if(firstErrorElement) {

                scrollToElement(firstErrorElement);

            }

        }, 500);

    });

};

export { getErrorMessages, scrollToElement, scrollToFirstError };
