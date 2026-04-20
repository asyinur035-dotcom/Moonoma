<style>
.chat-box{
    height:60vh;
    overflow-y:auto;
    display:flex;
    flex-direction:column;
    gap:12px;
}

/* ITEM */
.chat-item{
    max-width:70%;
    display:flex;
    flex-direction:column;
}

.chat-item.me{
    align-self:flex-end;
    text-align:right;
}

.chat-item.other{
    align-self:flex-start;
}

/* NAME */
.msg-name{
    font-size:11px;
    color:#6f8a75;
    margin-bottom:4px;
}

/* MESSAGE */
.message{
    padding:10px 14px;
    border-radius:12px;
    font-size:13px;
    line-height:1.4;
}

.me .message{
    background:#3E5641;
    color:#fff;
}

.other .message{
    background:#1c1f1d;
    border:1px solid #3E5641;
}

/* INPUT */
.chat-input{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.chat-input input{
    flex:1;
    padding:10px;
    border-radius:20px;
    border:1px solid #3E5641;
    background:#0d0f0d;
    color:#fff;
    outline:none;
}

.chat-input input::placeholder{
    color:#6f8a75;
}

/* BUTTON */
.send-btn{
    width:40px;
    height:40px;
    border-radius:50%;
    background:#3E5641;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:0.2s;
}

.send-btn:hover{
    opacity:0.8;
}
</style>

<div id="chatBox" class="chat-box"></div>

<div class="chat-input">
    <input type="text" id="msgInput" placeholder="Message...">
    <div class="send-btn" onclick="sendMsg()">➤</div>
</div>

<script>
let room = currentRoom; // 🔥 FIX
let chats = JSON.parse(localStorage.getItem('roomChats')) || {};
let currentUser = "User";

/* RENDER */
function renderChat(){
    let box = document.getElementById('chatBox');
    box.innerHTML = '';

    let roomChat = chats[room] || [];

    roomChat.forEach(m => {
        let isMe = m.user === currentUser;

        box.innerHTML += `
            <div class="chat-item ${isMe ? 'me' : 'other'}">
                <div class="msg-name">${m.user}</div>
                <div class="message">${m.text}</div>
            </div>
        `;
    });

    box.scrollTop = box.scrollHeight;
}

/* SEND */
function sendMsg(){
    let input = document.getElementById('msgInput');
    let text = input.value.trim();
    if(!text) return;

    if(!chats[room]) chats[room] = [];

    chats[room].push({
        user: currentUser,
        text: text
    });

    localStorage.setItem('roomChats', JSON.stringify(chats));

    input.value = '';
    renderChat();
}

/* ENTER KEY SEND */
document.getElementById('msgInput').addEventListener('keypress', function(e){
    if(e.key === 'Enter'){
        sendMsg();
    }
});

/* INIT */
renderChat();
</script>