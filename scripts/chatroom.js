'use strict'

async function updateUserChatrooms(chatroom_id) {
    const inbox = document.querySelector('section.chat-rooms')
    const chat = document.querySelector('#chat' + chatroom_id)
    const response = await fetch('../api/api_get_current_chatroom.php?' + encodeForAjax({chatroom_id: chatroom_id}))
    const chatroom = await response.json()
    const removed = inbox.removeChild(chat)
    inbox.insertBefore(removed, inbox.firstChild)
    const {last_message} = chatroom
    const text = document.querySelector(".current-chat .chat-content > p")
    text.innerText = last_message.message
}

async function addClickListeners() {
    const chatrooms = document.querySelectorAll('.chat')
    const temporary = document.querySelector('.temporary')
    const existing_chatroom = document.querySelector('.chat-page')
    const existing = !existing_chatroom.classList.contains('temporary') && existing_chatroom.id.includes('chat-page')
    const user_response = await fetch('../api/api_user.php');
    const user = await user_response.json();
    if (chatrooms) {
        for (const chatroom of chatrooms) {
            chatroom.addEventListener("click", async () => handleClick(chatroom, user))
        }
    }
    const sendMessageInput = document.querySelector('.chat-page input')
    const sendMessageButton = document.querySelector('.chat-page input')
    if(temporary) {
        sendMessageButton.addEventListener("click", async () => {
            const text = sendMessageInput.value.trim();
            if (!text.length) return;
            const id = temporary.id.split('&')
            const response = await fetch('../api/api_register_chatroom.php?' + encodeForAjax({item_id: id[1], seller_id: id[0]}))
            const chatroom = await response.json();
            await sendMessage(chatroom['chatroomId'], chatroom['buyer']['user_id'], sendMessageInput)
            const new_chatroom = createSmallChatroom(chatroom, chatroom['buyer'], chatroom['seller'],text)
            const chatroom_section = document.querySelector('section .chat-rooms')
            temporary.classList.remove("temporary")
            temporary.id = "chat-page" + chatroom['chatroomId']
            chatroom_section.insertBefore(new_chatroom, chatroom_section.firstChild)
            updateCurrentChatroom(new_chatroom)
            new_chatroom.addEventListener("click", async () => handleClick(new_chatroom, user))
        })
    }
    if(existing){
        const small_chatroom = document.querySelector('#chat' + existing_chatroom.id.substring(9))
        updateCurrentChatroom(small_chatroom)
        sendMessageButton.addEventListener("click", async () => {
            const text = sendMessageInput.value.trim();
            if (!text.length) return;
            const id = existing_chatroom.id.substring(9)
            await handleButtonClick(id, user, text)
        })
    }
}
function createSmallChatroom(chatroom, buyer,seller,  message){
    const chat_div = document.createElement('div')
    chat_div.classList.add('chat')
    chat_div.id = "chat" + chatroom['chatroomId']

    const seller_img = document.createElement('img')
    seller_img.src = "../uploads/profile_pics/" + seller['image'] + ".png"

    const last_msg_div = document.createElement('div')
    last_msg_div.classList.add('chat-content')

    const seller_name = document.createElement('h4')
    seller_name.innerText = seller['name']
    const message_p = document.createElement('p')
    message_p.innerText = message

    last_msg_div.appendChild(seller_name)
    last_msg_div.appendChild(message_p)

    chat_div.appendChild(seller_img)
    chat_div.appendChild(last_msg_div)
    return chat_div
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
    const formattedTime = new Date(date).toLocaleTimeString([], {
        hourCycle: 'h23',
        hour: '2-digit',
        minute: '2-digit'})
    const formattedDate = new Date(date).toLocaleTimeString([], {
        month: 'narrow',
        day: '2-digit',
    })
    return `${formattedTime} | ${formattedDate.substring(0,5)}`
}

async function handleClick(chatroom, user) {
    const response = await fetch('../api/api_get_current_chatroom.php?' + encodeForAjax({chatroom_id: chatroom.id.substring(4)}))
    const chatroom_data = await response.json()
    const msgInbox = await createMessageInbox(chatroom.id.substring(4), user);
    updateCurrentChatroom(chatroom);
    const chatPage = document.querySelector('.chat-page');
    chatPage.innerHTML = '';

    const header = createChatHeader(chatroom_data, user);

    chatPage.appendChild(header);
    chatPage.appendChild(msgInbox);
    closeInbox();
}

function updateCurrentChatroom(chatroom) {
    const curr = document.querySelector('.current-chat');
    if (curr) curr.classList.remove("current-chat");

    chatroom.classList.add("current-chat");
    const msg_count = document.querySelector('.current-chat #message-count');

    if(msg_count) chatroom.removeChild(msg_count)
}

function createChatHeader(chatroom_data, user) {
    const header = document.createElement('header');
    header.classList.add("message-header");

    const figure = createItemInfo(chatroom_data);
    const aside = createUserInfo(chatroom_data, user);

    header.appendChild(figure);
    header.appendChild(aside);

    return header;
}

function createItemInfo(chatroom_json) {
    const aside = document.createElement('aside');
    aside.classList.add("item-info");

    const item_image = document.createElement('img');
    item_image.classList.add("item-msg-img");
    item_image.alt = "item image";
    item_image.src = "../uploads/thumbnails/" + chatroom_json['item']['mainImage'] + ".png";
    const item_link = document.createElement('a')
    item_link.href = "../pages/item.php?id=" + chatroom_json['item']['id']
    const item_link2 = item_link.cloneNode()
    item_link.appendChild(item_image)

    const item_name = document.createElement('p');
    item_name.innerText = chatroom_json['item']['name'];
    item_link2.appendChild(item_name)

    const back_inbox = document.createElement('button');
    const back_inbox_icon = document.createElement('i');
    back_inbox_icon.classList.add("material-symbols-outlined");
    back_inbox.id = "back-inbox";
    back_inbox_icon.append("arrow_back_ios");
    back_inbox.append(back_inbox_icon);
    back_inbox.onclick = function () {openInbox()}

    aside.appendChild(back_inbox);
    aside.appendChild(item_link);
    aside.appendChild(item_link2);

    return aside;
}

function createUserInfo(chatroom_json, user) {
    const aside = document.createElement('aside');
    aside.classList.add("user-info");
    const addressee = chatroom_json['buyer']['user_id'] === user ? chatroom_json['seller'] : chatroom_json['buyer']
    const addressee_image = document.createElement('img');
    addressee_image.classList.add("addressee-img");
    addressee_image.alt = "addressee profile image";
    addressee_image.src = "../uploads/profile_pics/" + addressee['image'] + ".png";
    const addressee_link = document.createElement('a')
    addressee_link.href = "../pages/user.php?user_id=" + addressee['user_id']
    const addressee_link2 = addressee_link.cloneNode()
    addressee_link.appendChild(addressee_image)

    const addressee_name = document.createElement('p');
    addressee_name.innerText = addressee.name;
    addressee_link2.appendChild(addressee_name)

    aside.appendChild(addressee_link);
    aside.appendChild(addressee_link2);

    return aside;
}

async function createMessageInbox(chatroomId, user) {
    const response = await fetch('../api/api_current_chatroom.php?' + encodeForAjax({chatroom_id: chatroomId}));
    const messages = await response.json();
    const msg_inbox = document.createElement('article');
    msg_inbox.classList.add("msg-inbox");

    const message_section = fill_messages(messages, user);
    msg_inbox.appendChild(message_section);
    msg_inbox.appendChild(createSendMessageDiv(chatroomId, user));

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
async function  sendMessage(chatroom, user, input) {
    const text = input.value.trim();
    if (!text.length) return;
    await fetch("../api/api_send_messages.php?" + encodeForAjax({chatroom_id: chatroom, sender: user, message: text}))
    input.value = '';
    const msg_section = document.querySelector('section.scroll');
    msg_section.insertBefore(create_message(user, text, user, Date.now() / 1000), msg_section.firstChild);
    msg_section.scrollTo(0, msg_section.scrollHeight);
}
async function handleButtonClick(chatroom, user, input) {
    await sendMessage(chatroom, user, input)
    await updateUserChatrooms(chatroom)
}
function createSendButton(chatroom, user, input) {
    const button = document.createElement('button');
    button.type = "button";
    button.classList.add("send-icon");
    button.addEventListener("click", async () => {
        await handleButtonClick(chatroom, user, input)
    });
    const send_icon = document.createElement('i');
    send_icon.classList.add("material-symbols-outlined");
    send_icon.classList.add("filled-color");
    send_icon.innerText = "send";
    button.appendChild(send_icon);

    return button;
}

let inTableMode = window.matchMedia("(max-width: 60em)");
const inbox = document.getElementsByClassName("chat-inbox");
const chat = document.getElementsByClassName("chat-page");

function openInbox() {
    if (inTableMode.matches) {
        chat[0].style.gridArea = "none";
        chat[0].style.display = "none";
        inbox[0].style.gridArea = "page";
        inbox[0].style.display = "block";
    }
}
function closeInbox() {
    if (inTableMode.matches) {
        inbox[0].style.gridArea = "none";
        inbox[0].style.display = "none";
        chat[0].style.gridArea = "page";
        chat[0].style.display = "block";
    }
}


addClickListeners()