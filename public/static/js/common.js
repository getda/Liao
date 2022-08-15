const request = function (url, data, method = "POST") {
   return fetch(url, {
        headers: {
            'content-type': 'application/json'
        },
        mode: 'cors',
        method,
        body: JSON.stringify(data)
    }).then(res => {
        return res.json()
    }).catch(res => {
        console.log("ERROR", res)
    })
}