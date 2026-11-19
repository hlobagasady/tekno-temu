<?php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['ASUS Vivobook 14 Ryzen 5 7520U','ASUS',7499000,'Laptop & Komputer','Mahasiswa','Ryzen 5 · 16 GB · 512 GB','https://shopee.co.id/Laptop-cat.11044364.11044440',1],
            ['Lenovo IdeaPad Slim 3 Core i5','Lenovo',8299000,'Laptop & Komputer','Mahasiswa','Core i5 · 16 GB · 512 GB','https://shopee.co.id/list/Laptop',1],
            ['Acer Aspire Lite 14 Ryzen 5','Acer',6999000,'Laptop & Komputer','WFH','Ryzen 5 · 8 GB · 512 GB','https://shopee.co.id/Laptop-cat.11044364.11044440',0],
            ['HP 14s Core i3 12th Gen','HP',5899000,'Laptop & Komputer','Mahasiswa','Core i3 · 8 GB · 512 GB','https://shopee.co.id/Laptop-cat.11044364.11044440',0],
            ['MSI Thin 15 RTX 4050','MSI',13999000,'Laptop & Komputer','Gaming','Core i7 · 16 GB · 512 GB · RTX 4050','https://shopee.co.id/list/Laptop',1],
            ['ASUS TUF Gaming A15 RTX 4060','ASUS',17999000,'Laptop & Komputer','Gaming','Ryzen 7 · 16 GB · 1 TB · RTX 4060','https://shopee.co.id/list/Laptop',1],
            ['Dell Latitude 5420 Refurbished','Dell',4999000,'Laptop & Komputer','WFH','Core i5 · 16 GB · 512 GB','https://shopee.co.id/Laptop-cat.11044364.11044440',0],
            ['MacBook Air M2 13 inch','Apple',15999000,'Laptop & Komputer','WFH','Apple M2 · 8 GB · 256 GB','https://shopee.co.id/list/Laptop',1],
            ['AMD Ryzen 5 5600 Processor','AMD',1599000,'Komponen PC','Gaming','6 Core · AM4 · 65 W','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',1],
            ['Intel Core i5 12400F Processor','Intel',2199000,'Komponen PC','Gaming','6 Core · LGA1700 · 65 W','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',1],
            ['NVIDIA RTX 4060 8GB Graphics Card','NVIDIA',5499000,'Komponen PC','Gaming','8 GB VRAM · GDDR6 · PCIe 4.0','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',1],
            ['AMD Radeon RX 7600 8GB Graphics Card','AMD',4299000,'Komponen PC','Gaming','8 GB VRAM · GDDR6 · PCIe 4.0','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',0],
            ['Kingston Fury Beast 16GB DDR4','Kingston',649000,'Komponen PC','Gaming','16 GB · DDR4 · 3200 MHz','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',0],
            ['Corsair Vengeance 32GB DDR5','Corsair',1699000,'Komponen PC','Gaming','32 GB · DDR5 · 5600 MHz','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',0],
            ['MSI B550M Pro-VDH WiFi Motherboard','MSI',1899000,'Komponen PC','Gaming','AM4 · DDR4 · Wi-Fi 6','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',0],
            ['Cooler Master MWE 650W Bronze','Cooler Master',899000,'Komponen PC','Gaming','650 W · 80+ Bronze · ATX','https://shopee.co.id/Komponen-Desktop-Laptop-cat.11044364.11044372',0],
            ['AOC 24G2SP 24 inch Gaming Monitor','AOC',2149000,'Monitor & Display','Gaming','24 inci · 165 Hz · IPS','https://shopee.co.id/Monitor-cat.11044364.11044371',1],
            ['LG 24MR400 24 inch IPS Monitor','LG',1599000,'Monitor & Display','WFH','24 inci · 100 Hz · IPS','https://shopee.co.id/Monitor-cat.11044364.11044371',0],
            ['Samsung Essential Monitor 24 inch','Samsung',1799000,'Monitor & Display','WFH','24 inci · 75 Hz · IPS','https://shopee.co.id/Monitor-cat.11044364.11044371',0],
            ['Xiaomi Curved Gaming Monitor 34 inch','Xiaomi',4999000,'Monitor & Display','Gaming','34 inci · 144 Hz · UWQHD','https://shopee.co.id/Monitor-cat.11044364.11044371',1],
            ['Lenovo ThinkVision 22 inch','Lenovo',1899000,'Monitor & Display','WFH','22 inci · 75 Hz · IPS','https://shopee.co.id/Monitor-cat.11044364.11044371',0],
            ['Dell P2422H USB Hub Monitor','Dell',3499000,'Monitor & Display','WFH','24 inci · 60 Hz · USB Hub','https://shopee.co.id/Monitor-cat.11044364.11044371',0],
            ['Logitech MX Master 3S Mouse','Logitech',1199000,'Periferal','WFH','Bluetooth · 8K DPI · USB-C','https://shopee.co.id/list/Mouse',1],
            ['Logitech M330 Silent Mouse','Logitech',299000,'Periferal','WFH','Wireless · Silent Click · 1000 DPI','https://shopee.co.id/list/Mouse',0],
            ['Rexus Daxa M71 Pro Mechanical Keyboard','Rexus',599000,'Periferal','Gaming','Mechanical · RGB · Wireless','https://shopee.co.id/Keyboard-cat.11044364.11044434.11044436',1],
            ['Keychron K2 Wireless Mechanical Keyboard','Keychron',1399000,'Periferal','WFH','Mechanical · Bluetooth · 75%','https://shopee.co.id/Keyboard-cat.11044364.11044434.11044436',0],
            ['Fantech Maxfit61 MK910 Keyboard','Fantech',699000,'Periferal','Gaming','Mechanical · RGB · 60%','https://shopee.co.id/Keyboard-cat.11044364.11044434.11044436',0],
            ['HyperX Cloud Stinger 2 Headset','HyperX',699000,'Periferal','Gaming','3.5 mm · 50 mm Driver · Mic','https://shopee.co.id/Headset-cat.11044364.11044434',0],
            ['JBL Quantum 100 Gaming Headset','JBL',499000,'Periferal','Gaming','3.5 mm · Boom Mic · PC','https://shopee.co.id/Headset-cat.11044364.11044434',0],
            ['Logitech C920 Webcam Full HD','Logitech',1099000,'Periferal','WFH','1080p · Autofocus · Stereo Mic','https://shopee.co.id/Webcam-cat.11044364.11044434',1],
            ['TP-Link Archer AX23 Wi-Fi 6 Router','TP-Link',699000,'Jaringan','WFH','Wi-Fi 6 · 1.8 Gbps · 4 Antena','https://shopee.co.id/Router-cat.11044364.11044372',1],
            ['TP-Link Archer C64 AC1200 Router','TP-Link',449000,'Jaringan','WFH','AC1200 · MU-MIMO · 4 Antena','https://shopee.co.id/Router-cat.11044364.11044372',0],
            ['Tenda Nova MW6 Mesh Wi-Fi 3 Pack','Tenda',1699000,'Jaringan','WFH','AC1200 · Mesh · 3 Unit','https://shopee.co.id/Router-cat.11044364.11044372',0],
            ['Ubiquiti UniFi U6 Lite Access Point','Ubiquiti',1899000,'Jaringan','WFH','Wi-Fi 6 · 1200 Mbps · PoE','https://shopee.co.id/Router-cat.11044364.11044372',0],
            ['TP-Link TL-SG108 Gigabit Switch','TP-Link',329000,'Jaringan','WFH','8 Port · Gigabit · Plug and Play','https://shopee.co.id/Networking-cat.11044364.11044372',0],
            ['Vention Cat6 LAN Cable 10m','Vention',99000,'Jaringan','WFH','Cat6 · 10 meter · RJ45','https://shopee.co.id/Kabel-LAN-cat.11044364.11044372',0],
            ['Kingston NV2 NVMe SSD 1TB','Kingston',899000,'Penyimpanan','Gaming','1 TB · NVMe · 3500 MB/s','https://shopee.co.id/SSD-cat.11044364.11044372.11044383',1],
            ['WD Blue SN580 NVMe SSD 1TB','Western Digital',1199000,'Penyimpanan','Gaming','1 TB · NVMe · 4150 MB/s','https://shopee.co.id/SSD-cat.11044364.11044372.11044383',0],
            ['Samsung 990 Pro NVMe SSD 1TB','Samsung',1899000,'Penyimpanan','Gaming','1 TB · NVMe · 7450 MB/s','https://shopee.co.id/SSD-cat.11044364.11044372.11044383',1],
            ['SanDisk Ultra microSD 256GB','SanDisk',299000,'Penyimpanan','Mahasiswa','256 GB · UHS-I · A1','https://shopee.co.id/Memory-Card-cat.11044364.11044372',0],
            ['Seagate Expansion Portable HDD 2TB','Seagate',1099000,'Penyimpanan','WFH','2 TB · USB 3.0 · Portable','https://shopee.co.id/Harddisk-cat.11044364.11044372',0],
            ['Epson L3250 EcoTank Printer','Epson',2299000,'Printer & Perkantoran','WFH','Print Scan Copy · Wi-Fi · Ink Tank','https://shopee.co.id/Printer-cat.11044364.11044440',1],
            ['Canon PIXMA G2020 Printer','Canon',1899000,'Printer & Perkantoran','Mahasiswa','Print Scan Copy · Ink Tank · USB','https://shopee.co.id/Printer-cat.11044364.11044440',0],
            ['Brother DCP-T420W Printer','Brother',2199000,'Printer & Perkantoran','WFH','Wi-Fi · Ink Tank · A4','https://shopee.co.id/Printer-cat.11044364.11044440',0],
            ['APC Easy UPS 650VA','APC',799000,'Aksesori & Kabel','WFH','650 VA · AVR · 4 Outlet','https://shopee.co.id/UPS-cat.11044364.11044372',0],
            ['Anker 735 Charger GaNPrime 65W','Anker',899000,'Aksesori & Kabel','Mahasiswa','GaN · 65 W · 2 USB-C + USB-A','https://shopee.co.id/Charger-cat.11044364.11044372',1],
            ['UGREEN USB-C Hub 6-in-1','UGREEN',499000,'Aksesori & Kabel','WFH','HDMI · USB 3.0 · PD · SD','https://shopee.co.id/USB-Hub-cat.11044364.11044372',0],
            ['Baseus USB-C Cable 100W 2m','Baseus',129000,'Aksesori & Kabel','Mahasiswa','100 W · USB-C to USB-C · 2 meter','https://shopee.co.id/Kabel-Data-cat.11044364.11044372',0],
            ['Fantech Go W608 Wireless Speaker','Fantech',299000,'Periferal','Mahasiswa','Bluetooth 5.0 · 10 W · 8 Jam','https://shopee.co.id/Speaker-cat.11044364.11044372',0],
            ['Xiaomi Redmi Pad SE 11 inch','Xiaomi',1999000,'Smartphone & Tablet','Mahasiswa','11 inci · 6 GB · 128 GB','https://shopee.co.id/Tablet-cat.11044364.11044440',1],
        ];
        foreach ($items as [$name,$brand,$price,$category,$collection,$spec,$url,$top]) {
            Product::updateOrCreate(['slug' => str($name)->slug()], [
                'name'=>$name,'brand'=>$brand,'price_idr'=>$price,'sold_count'=>fake()->numberBetween(120,12000),
                'category'=>$category,'collection_name'=>$collection,'spec'=>$spec,
                'image_url'=>'https://placehold.co/600x600/e5eadb/17251f?text='.urlencode($brand),
                'affiliate_url'=>$url,'is_top'=>$top,'status'=>'published',
            ]);
        }
    }
}
