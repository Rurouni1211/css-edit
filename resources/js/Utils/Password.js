const getRandomPassword = (length) => {

    const letterStrings = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const numberStrings = "0123456789";
    const letters = letterStrings.split('');
    const numbers = numberStrings.split('');
    let password = "";

    for (let i = 2; i < length; i++) {

        if(i % 3 !== 0) {

            const letterIndex = Math.floor(Math.random() * letters.length);
            password += letters[letterIndex];

        } else {

            const numberIndex = Math.floor(Math.random() * numbers.length);
            password += numbers[numberIndex];

        }

    }

    return password;

}

export {
    getRandomPassword,
}
