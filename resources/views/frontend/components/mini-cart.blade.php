<div x-data="{ open: false }">

    <!-- overlay -->
    <div x-show="open"
         @click="open = false"
         class="fixed inset-0 bg-black/50 z-40">
    </div>

    <!-- cart -->
    <div x-show="open"
         class="fixed right-0 top-0 w-80 h-full bg-white z-50">
        Giỏ hàng
    </div>

</div>
