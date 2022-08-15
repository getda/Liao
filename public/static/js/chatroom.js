const data = function () {
    return {
        roomInfoShow: false,
        roomInfo: {
            online: 0,
            roomOnline: 0,
        },
        websocket: undefined,
        message: "",
        pingTimer: undefined,
        messageBox: [],
        uid: undefined,
        identity: undefined,
        room: 1,
        // 发送 ping
        ping() {
            return setInterval(() => {
                this.websocket.send(JSON.stringify({
                    "type": "ping"
                }));
                console.log("发送ping");
            }, 20000);
        },
        init() {
            this.uid = Cookies.get('uid');
            this.identity = Cookies.get('identity');
            this.websocket = new WebSocket(`ws://${window.location.hostname}:10033?uid=${this.uid}&identity=${this.identity}&room=${this.room}`);
            let pingTimer = this.ping();

            this.websocket.onopen = (event) => {
                console.log("与服务器建立链接成功！");
            }
            this.websocket.onmessage = (data) => {
                console.log("接收到来自服务器的消息：", data);
                let dataJson = this.parseJson(data.data);
                // 处理消息内容
                this.handle(dataJson);
            }
            this.websocket.onclose = function (event) {
                clearInterval(pingTimer);
                alert("服务器连接断开了！")
            }

            // 聚焦
            document.getElementById('message').focus();

        },
        /**
         * 发送文本消息
         * @returns {boolean}
         */
        sendMessage() {
            if (this.websocket.readyState !== WebSocket.OPEN) {
                Message.error("已与服务器断开连接，请刷新重试！");
                return false;
            }
            if (!this.message) {
                Message.warn("消息内容不能为空");
                return false;
            }
            let data = {
                type: "text",
                content: this.message
            };
            this.websocket.send(JSON.stringify(data));
            this.message = "";
        },
        handle(data) {
            let type = data?.type, privateObject = {};

            /**
             * 授权验证失败
             * @returns {boolean}
             */
            privateObject.auth = () => {
                if (data.code === 401) {
                    Message.error("授权验证失败！");
                    return false;
                }
            };

            /**
             * ping
             */
            privateObject.ping = () => {
                console.log("pong");
            };

            /**
             * 房间信息更新
             */
            privateObject.roomInfo = () => {
                this.roomInfo.online = data.online;
                this.roomInfo.roomOnline = data.roomOnline;
            };

            /**
             * 初始化消息内容
             */
            privateObject.init = () => {
                if (data.code === 0 && data.list.length > 0) {
                    data.list.map(item => {
                        let itemArray = this.parseJson(item);
                        itemArray && this.messageBox.push({
                            uid: itemArray.uid,
                            nickname: itemArray.nickname,
                            content: itemArray.content,
                            send_time: itemArray.send_time
                        });
                    });
                }
                this.scrollBottom();
            };

            /**
             * 聊天内容
             */
            privateObject.text = () => {
                this.messageBox.push({
                    uid: data.uid,
                    nickname: data.nickname,
                    content: data.content,
                    send_time: data.send_time
                });
                this.scrollBottom();
            };

            /**
             * 提示消息
             */
            privateObject.error = () => {
                Message.warn(data.msg);
            };

            privateObject.default = () => {
                console.warn("不存在的类型！");
            };

            let func = privateObject[type] || privateObject['default'];
            return func();
        },
        /**
         * 解析 JSON
         * @param str
         * @returns {null|any}
         */
        parseJson(str) {
            try {
                return JSON.parse(str);
            } catch (e) {
                return null;
            }
        },
        scrollBottom() {
            let element = document.getElementById("message-box");
            setTimeout(() => {
                element.scrollTop = element.scrollHeight + 150;
            }, 10);
        }
    };
}