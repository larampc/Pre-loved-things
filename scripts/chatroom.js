'use strict'

async function updateUserChatrooms(chatroom_id) {
    const inbox = document.querySelector('section.chat-rooms')
    const chat = document.querySelector('#chat' + chatroom_id)
    const response = await fetch('../api/api_get_current_chatroom.php?chatroom_id=' + chatroom_id)
    const chatroom = await response.json()
    const removed = inbox.removeChild(chat)
    inbox.insertBefore(removed, inbox.firstChild)
    const {last_message} = chatroom
    const text = document.querySelector(".current-chat .chat-content > p")
    text.innerText = last_message.message
}

async function addClickListeners() {
    const chatrooms = document.querySelectorAll('.chat')

    if (chatrooms) {
        const user_response = await fetch('../api/api_user.php');
        const user = await user_response.json();
        for (const chatroom of chatrooms) {
            chatroom.addEventListener("click", async () => handleClick(chatroom, user))
        }
    }
}

function create_message(user, message, sender, sentTime) {
    const sent = sender === user
    const message_div = document.createElement('div')
    message_div.classList.add(sent ? "sent-message" : "received-message")

    const message_p = document.createElement('p')
    message_p.classList.add(sent ? "sent-msg-text" : "received-msg-text")
    message_p.innerText = message

    const message_time = document.createElement('time')
    const date = new Date(sentTime*1000);
    message_time.dateTime = date.toISOString()
    message_time.innerText = formatDateTime(date)

    message_div.appendChild(message_p)
    message_div.appendChild(message_time)
    return message_div
}
function fill_messages( messages, user){
    const section = document.createElement('section')
    section.classList.add('scroll')
    messages.forEach(m => {
            const  {message, sender, sentTime} = m;
            section.appendChild(create_message(user, message, sender, sentTime))
        }
    )
    return section
}

function formatDateTime(date) {
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
    return `${formattedTime} | ${formattedDate}`
}

async function handleClick(chatroom, user) {
    updateCurrentChatroom(chatroom);
    const response = await fetch('../api/api_get_current_chatroom.php?chatroom_id=' + chatroom.id.substring(4))
    const chatroom_data = await response.json()
    const chatPage = document.querySelector('.chat-page');
    chatPage.innerHTML = '';

    const header = createChatHeader(chatroom_data, user);
    const msgInbox = await createMessageInbox(chatroom, user);

    chatPage.appendChild(header);
    chatPage.appendChild(msgInbox);
}

function updateCurrentChatroom(chatroom) {
    const curr = document.querySelector('.current-chat');
    if (curr) curr.classList.remove("current-chat");
    chatroom.classList.add("current-chat");
}

function createChatHeader(chatroom_data, user) {
    const header = document.createElement('header');
    header.classList.add("message-header");

    const figure = createFigure(chatroom_data);
    const aside = createUserAside(chatroom_data, user);

    header.appendChild(figure);
    header.appendChild(aside);

    return header;
}

function createFigure(chatroom) {
    const figure = document.createElement('figure');
    figure.classList.add("item-info");

    const item_image = document.createElement('img');
    item_image.classList.add("item-msg-img");
    item_image.alt = "item image";
    item_image.src = "../images/" + chatroom.item.mainImage;

    const figure_caption = document.createElement('figcaption');
    figure_caption.innerText = chatroom.item.name;

    figure.appendChild(item_image);
    figure.appendChild(figure_caption);

    return figure;
}

function createUserAside(chatroom, user) {
    const aside = document.createElement('aside');
    aside.classList.add("user-info");

    const addressee = chatroom.buyer.id === user ? chatroom.seller : chatroom.buyer
    const addressee_image = document.createElement('img');
    addressee_image.classList.add("addressee-img");
    addressee_image.alt = "addressee profile image";
    addressee_image.src = "../images/" + addressee.photoPath;

    const addressee_name = document.createElement('p');
    addressee_name.innerText = addressee.name;

    aside.appendChild(addressee_image);
    aside.appendChild(addressee_name);

    return aside;
}

async function createMessageInbox(chatroom, user) {
    const response = await fetch('../api/api_current_chatroom.php?chatroom_id=' + chatroom.id.substring(4));
    const messages = await response.json();

    const msg_inbox = document.createElement('article');
    msg_inbox.classList.add("msg-inbox");

    const message_section = fill_messages(messages, user);
    msg_inbox.appendChild(message_section);
    msg_inbox.appendChild(createSendMessageDiv(chatroom, user));

    message_section.scrollTo(0, message_section.scrollHeight);

    return msg_inbox;
}

function createSendMessageDiv(chatroom, user) {
    const send_msg_div = document.createElement('div');
    send_msg_div.classList.add("input-group");

    const input = document.createElement('input');
    input.classList.add("form-control");
    input.type = "text";
    input.placeholder = "Write message...";

    const button = createSendButton(chatroom, user, input);

    send_msg_div.appendChild(input);
    send_msg_div.appendChild(button);

    return send_msg_div;
}

function createSendButton(chatroom, user, input) {
    const button = document.createElement('button');
    button.type = "button";
    button.classList.add("send-icon");
    button.addEventListener("click", async () => {
        const text = input.value.trim();
        if (!text.length) return;
        await fetch("../api/api_send_messages.php?chatroom=" + chatroom.id.substring(4) + "&sender=" + user + "&message=" + text);
        input.value = '';
        const msg_section = document.querySelector('section.scroll');
        msg_section.insertBefore(create_message(user, text, user, Date.now()/1000), msg_section.firstChild);
        msg_section.scrollTo(0, msg_section.scrollHeight);
        await updateUserChatrooms(chatroom.id.substring(4));
    });

    const send_icon = document.createElement('i');
    send_icon.classList.add("material-symbols-outlined");
    send_icon.classList.add("filled-color");
    send_icon.innerText = "send";
    button.appendChild(send_icon);

    return button;
}

addClickListeners()