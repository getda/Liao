(function(win) {
    const createDom = (className = '', tag = 'div') => {
        const ele = document.createElement(tag)
        ele.className = className
        return ele
    }
    /**
     * 根据驼峰字符串生成css属性的键
     * @param camelCaseName
     * @returns {string}
     */
    const getCssAttrName = (camelCaseName) => {
        if (typeof camelCaseName !== 'string' || camelCaseName === '') {
            return ''
        }
        // 获取所有的大写字符
        let upperCaseChars = camelCaseName.match(/[A-Z]/g)
        // 去除重复的项
        upperCaseChars = [...(new Set(upperCaseChars))]
        // 循环将大写字母换为 “-?”的格式
        upperCaseChars.forEach(char => {
            camelCaseName = camelCaseName.replace(new RegExp(char, 'g'), `-${char.toLocaleLowerCase()}`)
        })
        return camelCaseName
    }
    /**
     * 根据css对象生成相应的css字符串
     * @param obj
     * @returns {string}
     */
    const cssFromObj = (obj) => {
        if (typeof obj === 'object' && obj !== null) {
            const cssArr = []
            Object.keys(obj).forEach(key => {
                cssArr.push(`${getCssAttrName(key)}: ${obj[key]};`)
            })
            return cssArr.join('')
        }
        return ''
    }
    const commonCss = {
        width: '70%',
        // height: '30px',
        backgroundColor: '#ebeef5',
        border: '1px solid #ebeef5',
        borderRadius: '4px',
        padding: '15px 15px 15px 20px',
        overflow: 'hidden',
        textOverFlow: 'ellipsis',
        wordBreak: 'nowrap',
        color: '#fff',
        fontSize: '14px',
        position: 'fixed',
        margin: '0 calc(30% / 2)',
        boxSizing: 'border-box',
        display: 'flex',
        flexWrap: 'nowrap',
        zIndex: 1024
    }
    const errorCss = {
        ...commonCss,
        backgroundColor: '#fef0f0',
        borderColor: '#fde2e2',
        color: '#f56c6c'
    }
    const successCss = {
        ...commonCss,
        backgroundColor: '#f0f9eb',
        borderColor: '#e1f3d8',
        color: '#67c23a'
    }
    const warnCss = {
        ...commonCss,
        backgroundColor: '#fdf6ec',
        borderColor: '#faecd8',
        color: '#e6a23c'
    }
    const infoCss = {
        ...commonCss,
        borderColor: '#edf2fc',
        color: '#909399'
    }
    class Message {
        constructor (text, config, cssConfig = {}) {
            this.config = {
                // 类型 info,error,success,warn
                type: 'info',
                // 提示内容
                text: text || '提示',
                // 位置： top, bottom
                position: 'top',
                // 距离窗口的距离，取决于position(top即位距离顶部的距离，bottom即为距离底部的距离)
                distance: '30px',
                // 弹出的时间
                duration: 3000,
                // 弹出延迟
                delay: 0,
                closable: true,
                icon: ''
            }
            this.init(config, cssConfig)
            this.addEventListener()
        }

        init (config, cssConfig) {
            // 取一个id值，方便之后精确查找该元素
            this.id = `message-${Date.now()}`
            this.cssConfig = cssConfig
            Object.assign(this.config, config)
            // 组css
            const cssObj = {
                // eslint-disable-next-line no-eval
                ...eval(`${this.config.type}Css`),
                ...this.cssConfig,
                [this.config.position]: this.config.distance
            }
            // 消息容器
            const box = createDom('hxl-message', 'div')
            box.style = cssFromObj(cssObj)
            box.id = this.id
            // 生成小图标
            const icon = createDom(`icon ${this.config.icon}`, 'i')
            box.appendChild(icon)
            // 生成消息体u
            const text = createDom('hxl-message-text', 'span')
            text.innerHTML = this.config.text
            text.style = cssFromObj({
                flex: '1'
            })
            box.appendChild(text)
            if (this.config.closable) {
                const close = createDom('hxl-close', 'div')
                this.closeDom = close
                close.style = cssFromObj({
                    cursor: 'pointer'
                    // color: '#FFF'
                })
                // 实际上这里应该是一个图标，但是懒得整了
                close.innerText = 'x'
                box.appendChild(close)
            }
            this.box = box
        }

        addEventListener () {
            this.closeDom && (this.closeDom.onclick = function () {
                this.hide()
            }.bind(this))
        }

        show () {
            const that = this
            // 延迟几秒在显示
            this.t1 = setTimeout(() => {
                document.body.appendChild(that.box)
                that.timeout = setTimeout(() => {
                    that.hide()
                    clearTimeout(that.timeout)
                }, that.config.duration)
                clearTimeout(this.t1)
            }, this.config.delay)
        }

        hide () {
            const box = document.querySelector(`#${this.id}`)
            box && typeof box.remove === 'function' && box.remove()
            clearTimeout(this.timeout)
        }

        static error (text) {
            Message.handleShow(text, 'error')
        }

        static handleShow (text, type) {
            const message = new Message(text, { type })
            message.show()
        }

        static success (text) {
            Message.handleShow(text, 'success')
        }

        static info (text) {
            Message.handleShow(text, 'info')
        }

        static warn (text) {
            Message.handleShow(text, 'warn')
        }
    }

    win.Message = Message
})(window)