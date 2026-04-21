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
                    <li><a href="#" class="hover:text-white">Điện thoại</a></li>
                    <li><a href="#" class="hover:text-white">Laptop</a></li>
                    <li><a href="#" class="hover:text-white">Máy tính bảng</a></li>
                    <li><a href="#" class="hover:text-white">Phụ kiện</a></li>
                </ul>
            </div>

            <!-- Cột 3 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Thông tin</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('page.about') }}" class="hover:text-white">Về chúng tôi</a></li>
                    <li><a href="{{ route('page.warranty') }}" class="hover:text-white">Chính sách bảo hành</a></li>
                    <li><a href="{{ route('page.return-policy') }}" class="hover:text-white">Chính sách đổi trả</a></li>
                    <li><a href="{{ route('page.contact') }}" class="hover:text-white">Liên hệ</a></li>
                </ul>
            </div>

            <!-- Cột 4 -->
            <div>
                <h4 class="font-semibold text-white mb-4">Liên hệ</h4>
                <p class="text-sm">Hotline: <span class="text-white font-medium">1900 1234</span></p>
                <p class="text-sm mt-1">Email: nguyenductinh08092005@gmail.com</p>
                <p class="text-sm mt-4">Cao đẳng FPT, Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</p>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-xs">
            © {{ date('Y') }} Smart Store. All rights reserved.
        </div>
    </div>


<!-- CHAT BUTTON -->
<div id="chat-toggle" 
class="fixed bottom-6 right-6 bg-black text-white w-14 h-14 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:scale-110 transition z-[9999]">
    💬
</div>

<!-- CHAT BOX -->
<div id="chat-box" 
class="hidden fixed bottom-24 right-6 w-96 h-[500px] bg-gray-900 rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-800 z-[9999]">

    <!-- Header -->
    <div class="bg-black text-white px-4 py-3 flex justify-between items-center border-b border-gray-800">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-white text-black rounded-full flex items-center justify-center font-bold">AI</div>
            <span class="font-semibold">Smart AI</span>
        </div>
        <button onclick="toggleChat()" class="text-gray-400 hover:text-white">✕</button>
    </div>

    <!-- Messages -->
    <div id="chat-messages" 
    class="flex-1 p-4 space-y-4 overflow-y-auto text-sm">

        <!-- welcome -->
        <div class="flex items-start gap-2">
            <div class="w-7 h-7 bg-white text-black rounded-full flex items-center justify-center text-xs font-bold">AI</div>
            <div class="bg-gray-800 text-white px-4 py-2 rounded-2xl max-w-[75%]">
                Xin chào 👋 Tôi có thể giúp gì cho bạn?
            </div>
        </div>

    </div>

    <!-- Input -->
    <div class="p-3 border-t border-gray-800 flex gap-2 bg-gray-900">
        <input 
            id="chat-input"
            class="flex-1 bg-gray-800 text-white px-4 py-2 rounded-xl outline-none focus:ring-2 focus:ring-white"
            placeholder="Nhập tin nhắn..."
        />
        <button onclick="sendMessage()" 
        class="bg-white text-black px-4 py-2 rounded-xl font-semibold hover:bg-gray-200">
            Gửi
        </button>
    </div>
    

</div>
</footer>
<script>
let loaded = false;

function toggleChat() {
    const chatBox = document.getElementById('chat-box');
    chatBox.classList.toggle('hidden');

    // 👉 THÊM ĐOẠN NÀY
    if (!chatBox.classList.contains('hidden') && !loaded) {
        loadChatHistory();
        loaded = true;
    }
}

document.getElementById('chat-toggle').onclick = toggleChat;

function appendMessage(text, type = 'bot') {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');

    if (type === 'user') {
        div.innerHTML = `
        <div class="flex justify-end">
            <div class="bg-white text-black px-4 py-2 rounded-2xl max-w-[75%]">
                ${text}
            </div>
        </div>`;
    } else {
        div.innerHTML = `
        <div class="flex items-start gap-2">
            <div class="w-7 h-7 bg-white text-black rounded-full flex items-center justify-center text-xs font-bold">AI</div>
            <div class="bg-gray-800 text-white px-4 py-2 rounded-2xl max-w-[75%]">
                ${text}
            </div>
        </div>`;
    }

    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

// 👉 render button chọn đơn
function appendOrderButtons(orders) {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');

    let html = `<div class="flex flex-col gap-3">`;

    orders.forEach(o => {
        html += `
        <button 
            onclick="selectOrder('${o.order_number}')"
            class="flex items-center gap-3 bg-gray-800 p-3 rounded-xl hover:bg-gray-700 transition text-left"
        >
            <img src="${o.image}" 
                class="w-12 h-12 object-cover rounded bg-gray-700"
                onerror="this.src='https://via.placeholder.com/48'" />

            <div class="flex-1">
                <div class="text-white text-sm font-semibold line-clamp-2">
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
        <div class="w-7 h-7 bg-white text-black rounded-full flex items-center justify-center text-xs font-bold">AI</div>
        <div class="bg-gray-800 text-white px-4 py-2 rounded-2xl max-w-[75%]">
            ${html}
        </div>
    </div>`;

    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

// 👉 khi click đơn
function selectOrder(orderNumber) {
    appendMessage(orderNumber, 'user');
    sendMessage(orderNumber);
}

function sendMessage(customMessage = null) {
    let input = document.getElementById('chat-input');
    let message = customMessage ?? input.value.trim();

    if (!message) return;

    if (!customMessage) {
        appendMessage(message, 'user');
        input.value = '';
    }

    // loading
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

        // =========================
        // 🔥 CASE CHỌN ĐƠN (QUAN TRỌNG)
        // =========================
        if (data.type === 'choose_order') {
            appendMessage(data.reply, 'bot');
            appendOrderButtons(data.orders);
            return;
        }

        let reply = data.reply || '';
        let lines = reply.split("\n");

        let textPart = '';
        let productHTML = `<div class="flex flex-col gap-3 mt-2">`;
        let hasProduct = false;

        lines.forEach(line => {
            if (line.includes("|")) {

                let parts = line.split("|");

                let name = parts[0]?.trim();
                let price = parts[1]?.trim();
                let link = parts[2]?.trim();
                let image = parts[3]?.trim();

                hasProduct = true;

                productHTML += `
                <a href="${link}" target="_blank"
                class="flex gap-3 bg-gray-800 p-3 rounded-xl hover:bg-gray-700 transition">

                    <img src="${image}" 
                        class="w-16 h-16 object-cover rounded bg-gray-700"
                        onerror="this.src='https://via.placeholder.com/64'" />

                    <div class="flex-1">
                        <div class="text-white font-semibold text-sm line-clamp-2">
                            ${name}
                        </div>
                        <div class="text-red-400 font-bold">
                            ${price}
                        </div>
                    </div>

                </a>
                `;
            } else {
                textPart += line + "<br>";
            }
        });

        productHTML += `</div>`;

        if (textPart.trim()) {
            appendMessage(textPart, 'bot');
        }

        if (hasProduct) {
            appendMessage(productHTML, 'bot');
        }
    });
}

// enter để gửi
document.getElementById("chat-input").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        sendMessage();
    }
});
async function loadChatHistory() {
    try {
        const res = await fetch('/ai/history');
        const data = await res.json();

        const box = document.getElementById('chat-messages');
        box.innerHTML = ""; // xoá welcome cũ

        if (data.length === 0) {
            appendMessage("Xin chào 👋 Tôi có thể giúp gì cho bạn?", 'bot');
            return;
        }

        data.forEach(msg => {
            if (msg.sender === 'user') {
                appendMessage(msg.message, 'user');
            } else {
                appendMessage(msg.message, 'bot');
            }
        });

        box.scrollTop = box.scrollHeight;

    } catch (e) {
        console.error("Lỗi load history", e);
    }
}
</script>