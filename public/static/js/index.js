const data = function () {
    return {
        form: {
            nickname: undefined,
            password: undefined
        },
        join() {
            if (!this.form.nickname || !this.form.password) {
                return false;
            }
            request('/join', this.form).then(res => {
                if (res.code === 0) {
                    Message.warn(res.msg)
                    return false;
                }
                window.location.href = res.url;
            })
        }
    };
}