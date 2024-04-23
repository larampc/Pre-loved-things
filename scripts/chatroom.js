'use strict'


const chatrooms = document.querySelectorAll('.chat')


if (chatrooms) {
    for (const chatroom of chatrooms) {
        chatroom.addEventListener("click", async () => {
            const curr = document.querySelector('.current-chat')
            chatroom.classList.add("current-chat");
            if(curr) curr.classList.remove("current-chat");
            const chatPage = document.querySelector('.chat-page')

            const header = document.createElement('header')
            header.classList.add("message-header")
            const figure = document.createElement('figure')
            figure.classList.add("item-info")

            const item_image = document.createElement('img')
            item_image.classList.add("item-msg-img")
            item_image.alt = "item image"

            //to be changeddddddd --> make dynamic

            item_image.src = "../images/flower.png"
            const figure_caption = document.createElement('figcaption')
            figure_caption.innerText = "Item"
            figure.appendChild(item_image)
            figure.appendChild(figure_caption)

            const aside = document.createElement('aside')
            aside.classList.add("user-info")

            const addressee_image = document.createElement('img')
            addressee_image.classList.add("addressee-img")
            addressee_image.alt = "addressee profile image"
            addressee_image.src = "../images/profile.png"

            const addressee_name = document.createElement('p')
            addressee_name.innerText = "User Name"

            aside.appendChild(addressee_image)
            aside.appendChild(addressee_name)
            header.appendChild(figure)
            header.appendChild(aside)

            const response = await fetch('../api/api_current_chatroom.php?chatroom_id=' + chatroom.id.substring(4))
            const messages = await response.json()
            console.log(messages)
            const user_response = await fetch('../api/api_user.php')
            const user = await user_response.json()
            const msg_inbox = document.createElement('article')
            msg_inbox.classList.add("msg-inbox")
            msg_inbox.appendChild(fill_messages(messages, user))

            const send_msg_div = document.createElement('div')
            send_msg_div.classList.add("input-group")
            const input = document.createElement('input')
            input.classList.add("form-control")
            input.type = "text"
            input.placeholder = "Write message..."
            const button = document.createElement('button')
            button.type = "button"
            button.classList.add("send-icon")
            const send_icon = document.createElement('i')
            send_icon.classList.add("material-symbols-outlined-filled-color")
            send_icon.innerText = "send"
            button.appendChild(send_icon)
            send_msg_div.appendChild(input)
            send_msg_div.appendChild(button)
            msg_inbox.appendChild(send_msg_div)

            chatPage.innerHTML = ''
            chatPage.appendChild(header)
            chatPage.appendChild(msg_inbox)
        })
    }

}

function fill_messages( messages, user){
    const section = document.createElement('section')
    section.classList.add('scroll')

    messages.forEach(m => {
        const  {message, sender, sentTime} = m;
        const sent = sender === user
        const message_div = document.createElement('div')
        message_div.classList.add(sent ? "sent-message" : "received-message")

        const message_p = document.createElement('p')
        message_p.classList.add(sent ? "sent-msg-text" : "received-msg-text")
        message_p.innerText = message

        const message_time = document.createElement('time')
        const date = new Date(sentTime);
        message_time.dateTime = date.toISOString()
        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        const dateOptions = {
            month: 'long',
            day: '2-digit'
        };
        const formattedTime = new Intl.DateTimeFormat('en-US', timeOptions).format(date);
        const formattedDate = new Intl.DateTimeFormat('en-US', dateOptions).format(date);
        message_time.innerText = `${formattedTime} | ${formattedDate}`

        message_div.appendChild(message_p)
        message_div.appendChild(message_time)
        section.appendChild(message_div)
    })

    // for (let i = 0; i < messages.length; i++) {
    //     const  {chatroom, message, readTime,sender, sentTime} = messages[i];
    //     const sent = sender === user
    //     console.log(messages[i])
    //     console.log(user)
    //     const message_div = document.createElement('div')
    //     message_div.classList.add(sent ? "sent-message" : "received-message")
    //
    //     const message_p = document.createElement('p')
    //     message_p.classList.add(sent ? "sent-msg-text" : "received-msg-text")
    //     message_p.innerText = message
    //
    //     const message_time = document.createElement('time')
    //     const date = new Date(sentTime);
    //     message_time.dateTime = date.toISOString()
    //
    //     const timeOptions = {
    //         hour: '2-digit',
    //         minute: '2-digit',
    //         hour12: true
    //     };
    //
    //     const dateOptions = {
    //         month: 'long',
    //         day: '2-digit'
    //     };
    //
    //     const formattedTime = new Intl.DateTimeFormat('en-US', timeOptions).format(date);
    //     const formattedDate = new Intl.DateTimeFormat('en-US', dateOptions).format(date);
    //
    //     message_time.innerText = `${formattedTime} | ${formattedDate}`
    //
    //     message_div.appendChild(message_p)
    //     message_div.appendChild(message_time)
    //     section.appendChild(message_div)
    // }
    return section
}