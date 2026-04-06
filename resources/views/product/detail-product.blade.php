<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Bakers Street 193</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

    <div class="bg-[#4DB6AC] pt-10 pb-20 px-8 rounded-b-[50px] relative z-0">
        <div class="flex justify-between items-start text-white mb-8">
            <div>
                <div class="flex items-center gap-1 mb-1">
                    <span class="text-sm font-semibold underline underline-offset-4">Atur Lokasi</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
                <p class="font-extrabold text-xl">Lokasi saat ini</p>
            </div>
            <div class="flex gap-8 pt-2">
                <i class="far fa-heart text-3xl"></i>
                <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
        </div>
        
        <div class="relative z-10">
            <input type="text" placeholder="Mau selamatkan produk apa hari ini?" 
                class="w-full py-4 px-6 rounded-2xl shadow-lg focus:outline-none text-base text-gray-400 border-none">
            <i class="fas fa-search absolute right-6 top-5 text-gray-300 text-xl"></i>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mt-12 px-6 pb-20">
        <div class="bg-white rounded-[30px] shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-50 p-8">
            
            <div class="flex flex-col lg:flex-row gap-10">
                <div class="relative w-full lg:w-[48%]">
                    <img src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?q=80&w=1000&auto=format&fit=crop" 
                         alt="Strawberry Cake" class="w-full aspect-square object-cover rounded-2xl">
                    <div class="absolute bottom-0 left-0 right-0 bg-[#FF5252] text-white flex justify-between items-center px-4 py-3 rounded-b-2xl">
                        <span class="text-sm font-black uppercase tracking-wider">Selamatkan Segera!</span>
                        <span class="text-sm font-black">13:20:52</span>
                    </div>
                </div>

                <div class="flex-1 flex flex-col justify-center">
                    <h2 class="text-3xl font-extrabold text-gray-800 leading-tight mb-4">Strawberry Cream Cake Slice</h2>
                    
                    <div class="flex gap-3 mb-4">
                        <span class="bg-[#E0F7FA] text-[#4DB6AC] text-xs px-4 py-1.5 rounded-full font-extrabold">8+ tersedia</span>
                        <span class="bg-[#FFE5E5] text-[#FF5252] text-xs px-4 py-1.5 rounded-full font-extrabold">Diskon 13%</span>
                    </div>

                    <div class="flex items-center gap-2 text-[#FF5252] mb-6">
                        <i class="fas fa-heart text-base"></i>
                        <span class="text-sm font-bold">Favorit (32)</span>
                    </div>

                    <div class="flex items-baseline gap-5 mb-6">
                        <span class="text-gray-400 line-through text-xl font-medium">45.000</span>
                        <span class="text-4xl font-black text-gray-900 tracking-tighter">25.000</span>
                    </div>

                    <div class="flex items-center gap-3 text-gray-500 text-sm mb-8">
                        <i class="far fa-calendar-alt text-lg"></i>
                        <span class="font-medium">Waktu pengambilan hari ini, 10.00 - 23.59</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border-2 border-gray-100 rounded-xl overflow-hidden">
                            <button class="px-4 py-2 text-gray-400 text-2xl hover:bg-gray-50">-</button>
                            <span class="px-6 py-2 text-xl font-black text-gray-800 border-x-2 border-gray-100">1</span>
                            <button class="px-4 py-2 text-gray-400 text-2xl hover:bg-gray-50">+</button>
                        </div>
                        <button class="flex-1 bg-[#338A7E] hover:bg-[#2a7369] text-white py-4 rounded-2xl text-lg font-black flex justify-center items-center gap-3 transition-colors shadow-lg">
                            <i class="fas fa-plus text-sm"></i>
                            <span>keranjang</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="h-[2px] bg-gray-50 my-10"></div>

            <div class="flex items-center gap-6">
                <div class="w-24 h-24 bg-[#F3D9B1] rounded-2xl flex flex-col items-center justify-center p-3 shadow-sm">
                    <span class="font-black text-[#4A3228] text-lg leading-none">Bakers</span>
                    <span class="text-[#4A3228] text-[9px] font-black mt-1.5 tracking-widest uppercase">Street 193</span>
                </div>
                
                <div>
                    <h3 class="font-black text-gray-800 text-2xl mb-1">Bakers Street 193</h3>
                    <p class="text-sm text-gray-400 font-bold mb-2">Food - 2,3 Km</p>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-400 text-lg"></i>
                        <span class="text-[#4DB6AC] font-black text-xl">5</span>
                        <span class="text-gray-400 font-bold text-lg">/5 dari 58 ulasan</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
</html>