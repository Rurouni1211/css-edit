const getDataList = (modes, callback) => {

    const url = route('admin.data_list.index');
    const data = {
        params: {
            modes: modes,
        },
    };

    axios.get(url, data)
        .then(response => {

            if(typeof callback === 'function') {

                callback(response.data);

            } else {

                console.log('callback is not a function');

            }

        });

}

export { getDataList };
