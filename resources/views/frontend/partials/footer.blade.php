<footer class="bg-gray-900 text-gray-300 py-16">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">

            <!-- Cột 1 -->
            <div>
                <h3 class="text-white text-2xl font-bold mb-4">Smart<span class="text-red-600">Store</span></h3>
                <p class="text-sm leading-relaxed">Cửa hàng điện thoại, laptop và phụ kiện chính hãng hàng đầu Việt Nam.</p>
                <div class="mt-6 flex gap-4">
                    <i class="fab fa-facebook text-2xl hover:text-blue-500 cursor-pointer"></i>
                    <i class="fab fa-youtube text-2xl hover:text-red-500 cursor-pointer"></i>
                    <i class="fab fa-tiktok text-2xl hover:text-pink-500 cursor-pointer"></i>
                </div>
            </div>

            <!-- Cột 2 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Sản phẩm</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('category.products', 'dien-thoai') }}" class="hover:text-white transition-colors">Điện thoại</a></li>
                    <li><a href="{{ route('category.products', 'laptop') }}" class="hover:text-white transition-colors">Laptop</a></li>
                    <li><a href="{{ route('category.products', 'may-tinh-bang') }}" class="hover:text-white transition-colors">Máy tính bảng</a></li>
                    <li><a href="{{ route('category.products', 'phu-kien') }}" class="hover:text-white transition-colors">Phụ kiện</a></li>
                </ul>
            </div>

            <!-- Cột 3 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Thông tin</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('page.about') }}" class="hover:text-white">Về chúng tôi</a></li>
                    <li><a href="{{ route('page.warranty') }}" class="hover:text-white">Chính sách bảo hành</a></li>
                    <li><a href="{{ route('page.return-policy') }}" class="hover:text-white">Chính sách đổi trả</a></li>
                    <li><a href="{{ route('page.shipping') }}" class="hover:text-white">Chính sách vận chuyển</a></li>
                    <li><a href="{{ route('page.privacy') }}" class="hover:text-white">Chính sách bảo mật</a></li>
                    <li><a href="{{ route('page.terms') }}" class="hover:text-white">Điều khoản dịch vụ</a></li>
                    <li><a href="{{ route('page.contact') }}" class="hover:text-white">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Cột 4 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Liên hệ</h4>
                <p class="text-sm">Hotline: <span class="text-white font-medium">1900 1234</span></p>
                <p class="text-sm mt-1">Email: chammut0009@gmail.com</p>
                <p class="text-sm mt-4">Cao đẳng FPT, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-xs">
            © {{ date('Y') }} Smart Store. All rights reserved.
        </div>
    </div>


<!-- CHAT BUTTON -->
<!-- CHAT BUTTON -->
<div id="chat-toggle" 
class="fixed bottom-6 right-6 w-14 h-14 rounded-full flex items-center justify-center cursor-pointer shadow-xl transition z-[9999]
bg-gradient-to-r from-indigo-500 to-purple-500 hover:scale-110 hover:shadow-indigo-400/40">
    💬
</div>

<!-- CHAT BOX -->
<div id="chat-box" 
class="hidden fixed bottom-24 right-6 w-[380px] h-[560px]
bg-white rounded-3xl shadow-2xl flex flex-col overflow-hidden
border border-gray-200 z-[9999]">

    <!-- Header -->
    <div class="bg-white px-4 py-3 flex justify-between items-center border-b">
    
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full flex items-center justify-center font-bold shadow">
                AI
            </div>
            <div>
                <p class="font-semibold text-gray-800 leading-none">Smart AI</p>
                <span class="text-xs text-green-500">● Online</span>
            </div>
        </div>

        <button onclick="toggleChat()" class="text-gray-400 hover:text-gray-700 text-lg">✕</button>
    </div>

    <!-- Messages -->
    <div id="chat-messages" 
    class="flex-1 p-4 space-y-4 overflow-y-auto text-sm bg-gray-50">

        <!-- welcome -->
        <div class="flex items-start gap-2">
            <div class="w-8 h-8 bg-indigo-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow">
                AI
            </div>
            <div class="bg-white text-gray-800 px-4 py-2 rounded-2xl shadow-sm max-w-[75%]">
                Xin chào 👋 Tôi có thể giúp gì cho bạn?
            </div>
        </div>

    </div>

    <!-- Input -->
    <div class="p-3 border-t bg-white flex gap-2 items-center">
    
        <input 
            id="chat-input"
            class="flex-1 bg-gray-100 text-gray-800 px-4 py-2 rounded-full outline-none focus:ring-2 focus:ring-indigo-400 placeholder-gray-400"
            placeholder="Nhập tin nhắn..."
        />

        <button onclick="sendMessage()" 
        class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-full font-semibold hover:opacity-90 transition">
            ➤
        </button>

    </div>

</div>
    

</div>
</footer>
<script>
let loaded = false;

function toggleChat() {
    const chatBox = document.getElementById('chat-box');
    chatBox.classList.toggle('hidden');

    if (!chatBox.classList.contains('hidden') && !loaded) {
        loadChatHistory();
        loaded = true;
    }
}

document.getElementById('chat-toggle').onclick = toggleChat;

// =======================
// ✅ UI MESSAGE
// =======================
function appendMessage(text, type = 'bot', isHTML = false) {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');

    if (type === 'user') {
        div.innerHTML = `
        <div class="flex justify-end">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-4 py-2 rounded-2xl rounded-br-sm max-w-[75%] shadow-md">
                ${text}
            </div>
        </div>`;
    } else {
        if (isHTML) {
            div.innerHTML = text;
        } else {
            div.innerHTML = `
            <div class="flex items-start gap-2">
                <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold shadow">
                    AI
                </div>
                <div class="bg-white text-gray-800 px-4 py-2 rounded-2xl rounded-bl-sm max-w-[75%] shadow-md border border-gray-100">
                    ${text}
                </div>
            </div>`;
        }
    }

    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

// =======================
// ✅ RENDER PRODUCT (FIX LỖI CHÍNH)
// =======================
function renderBotMessage(message) {
    let lines = message.split("\n");

    let textPart = '';
    let productHTML = `<div class="flex flex-col gap-3 mt-2">`;
    let hasProduct = false;

    lines.forEach(line => {
        if (line.includes("|")) {
            let parts = line.split("|");

            if (parts.length === 4) {
                let name = parts[0]?.trim();
                let price = parts[1]?.trim();
                let link = parts[2]?.trim();
                let image = parts[3]?.trim() || '';

                // ✅ FIX IMAGE
                if (!image || image === 'null') {
                    image = 'https://via.placeholder.com/80?text=No+Image';
                }

                if (!image.startsWith('http')) {
                    image = window.location.origin + image;
                }

                hasProduct = true;

                productHTML += `
                <a href="${link}" target="_blank"
                class="flex gap-3 bg-white border border-gray-100 p-3 rounded-2xl hover:shadow-lg hover:-translate-y-0.5 transition">

                    <div class="w-16 h-16 flex-shrink-0">
                        <img src="${image}" 
                            class="w-full h-full object-cover rounded-xl bg-gray-100"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/80?text=No+Image';" />
                    </div>

                    <div class="flex-1">
                        <div class="text-gray-800 font-semibold text-sm line-clamp-2">
                            ${name}
                        </div>
                        <div class="text-indigo-600 font-bold text-sm">
                            ${price}
                        </div>
                    </div>

                </a>`;
            }
        } else {
            textPart += line + "<br>";
        }
    });

    productHTML += `</div>`;

    if (textPart.trim()) {
        appendMessage(textPart, 'bot');
    }

    if (hasProduct) {
        appendMessage(productHTML, 'bot', true);
    }
}

// =======================
// ✅ CHỌN ĐƠN (UI FIX)
// =======================
function appendOrderButtons(orders) {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');

    let html = `<div class="flex flex-col gap-3">`;

    orders.forEach(o => {
        html += `
        <button 
            onclick="selectOrder('${o.order_number}')"
            class="flex items-center gap-3 bg-white border border-gray-100 p-3 rounded-xl hover:shadow-md transition text-left"
        >
            <img src="${o.image}" 
                class="w-12 h-12 object-cover rounded bg-gray-100"
                onerror="this.onerror=null;this.src='https://via.placeholder.com/48'" />

            <div class="flex-1">
                <div class="text-gray-800 text-sm font-semibold line-clamp-2">
                    ${o.product_name || 'Sản phẩm'}
                </div>
                <div class="text-gray-400 text-xs">
                    ${o.label}
                </div>
            </div>
        </button>
        `;
    });

    html += `</div>`;

    div.innerHTML = `
    <div class="flex items-start gap-2">
        <div class="w-7 h-7 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">AI</div>
        <div class="bg-white text-gray-800 px-4 py-3 rounded-2xl max-w-[75%] shadow-md border border-gray-100">
            ${html}
        </div>
    </div>`;

    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

function selectOrder(orderNumber) {
    appendMessage(orderNumber, 'user');
    sendMessage(orderNumber);
}

// =======================
// ✅ SEND MESSAGE (GIỮ NGUYÊN LOGIC)
// =======================
function sendMessage(customMessage = null) {
    let input = document.getElementById('chat-input');
    let message = customMessage ?? input.value.trim();

    if (!message) return;

    if (!customMessage) {
        appendMessage(message, 'user');
        input.value = '';
    }

    const box = document.getElementById('chat-messages');
    const loading = document.createElement('div');
    loading.innerHTML = `<div class="text-gray-400 text-sm">AI đang trả lời...</div>`;
    box.appendChild(loading);

    fetch('/chat-ai', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        loading.remove();

        if (data.type === 'choose_order') {
            appendMessage(data.reply, 'bot');
            appendOrderButtons(data.orders);
            return;
        }

        renderBotMessage(data.reply || '');
    });
}

// =======================
// ✅ LOAD HISTORY (FIX LỖI CHÍNH)
// =======================
async function loadChatHistory() {
    try {
        const res = await fetch('/ai/history');
        const data = await res.json();

        const box = document.getElementById('chat-messages');
        box.innerHTML = "";

        if (data.length === 0) {
            appendMessage("Xin chào 👋 Tôi có thể giúp gì cho bạn?", 'bot');
            return;
        }

        data.forEach(msg => {
            if (msg.sender === 'user') {
                appendMessage(msg.message, 'user');
            } else {
                renderBotMessage(msg.message); // ✅ FIX Ở ĐÂY
            }
        });

        box.scrollTop = box.scrollHeight;

    } catch (e) {
        console.error("Lỗi load history", e);
    }
}

// ENTER gửi
document.getElementById("chat-input").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        sendMessage();
    }
});
</script>