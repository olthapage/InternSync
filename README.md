<p align="center">
  <a href="https://yourwebsite.com" target="_blank">
    <img src="softTemplate/assets/img/LogoInternSync.png" width="300" alt="InternSync Logo">
  </a>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/tests-passing-brightgreen.svg" alt="Build Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/downloads-10k-blue.svg" alt="Total Downloads"></a>
  <a href="#"><img src="https://img.shields.io/badge/version-v1.0.0-blue.svg" alt="Latest Stable Version"></a>
  <a href="#"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

---

## About PWL_POS

**PWL_POS** is a simple and modern Point of Sale web application built using **Laravel 10**.  
It is designed to help small businesses and shops easily manage:

- Inventory and stock tracking
- Sales transactions
- Financial reporting
- Clean and intuitive user interface

> This project is ideal for educational purposes or small-scale POS system deployment.

---

## Features

- 🔄 Real-time inventory updates  
- 🧾 Exportable sales reports  
- 🔐 Secure authentication system  
- 📊 Dashboard with key metrics  

---

## Installation

```bash
git clone https://github.com/yourusername/pwl_pos.git
cd pwl_pos
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
