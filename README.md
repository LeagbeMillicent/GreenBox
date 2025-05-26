# GreenBox

# ♻️ GreenBox – E-Waste Management System

**GreenBox** is a smart, eco-centric electronic waste (e-waste) management system designed for Ghana. It enables individuals, businesses, collectors, and government bodies to participate in a tech-driven, reward-based recycling ecosystem.

---

## 📌 Table of Contents

- [Overview](#overview)
- [Core Features](#core-features)
- [User Roles](#user-roles)
- [API Endpoints](#api-endpoints)
- [UI Pages & User Flow](#ui-pages--user-flow)
- [Future Enhancements](#future-enhancements)

---

## 🧭 Overview

GreenBox is designed to tackle Ghana’s growing e-waste problem by combining:
- Smart pickup scheduling
- Eco-point-based reward system
- Drop-off center locator

---

## 🚀 Core Features

### 🎯 User-Focused Features
- Register via Email, Phone, 
- Schedule e-waste pickups
- Locate nearby drop-off 
- DIY repair tutorials and content


### 🧰 Collector Features
- View assigned pickup requests
- Map-based route optimization
- Update pickup & delivery status
- Log recyclable items collected

### 🛠️ Admin Features
- User & collector management
- Pickup & route assignment
- Reporting: compliance, waste stats, engagement

---

## 🧑‍🤝‍🧑 User Roles

| Role | Abilities |
|------|-----------|
| **General User** | Register, schedule pickup|
| **Collector** | Handle pickups, log collection details, navigate routes |
| **Admin** | Full system control: users, pickups, analytics|

---

## 🔌 API Endpoints (Laravel)

**Base URL**: `https://api.greenboxgh.com/api/`

### 🔐 Auth
```http
POST   /auth/register          → Register user  
POST   /auth/login             → Login user  
POST   /auth/logout            → Logout session  

👤 Users

GET    /users/{id}             → Get profile  
GET    /users/{id}/history     → Get pickup history  
PUT    /users/{id}/update      → Update profile  

📦 Pickup Management

POST   /pickup/request         → Submit pickup request
GET    /pickup/status/{id}     → Check pickup status
GET    /pickup/my-requests     → List user pickups
POST   /pickup/assign          → Assign to collector (admin)

🗺️ Drop-off Center Locator

GET    /centers                → List all centers
GET    /centers/nearby         → Find nearby by coordinates

🏆 Challenges & Leaderboard(FI)

GET    /challenges             → Active campaigns
POST   /challenges/join        → Join challenge

📊 Admin & Reporting

GET    /admin/users            → All users
GET    /admin/reports          → Analytics & compliance


```

## 📱 UI Pages & Flow
👥 General Users
Login/Register Page

Dashboard (eco-points, pickups, news)

Pickup Request Form

Locate Drop-off Center (Map/GPS)

Leaderboard & Challenges

Agents Details
DIY Tutorials Page

---
## 🚚 Collectors
Collector Dashboard

Assigned Pickups Page

Collection Route Map

Waste Logging Interface

---

## 🛠️ Admin
Admin Dashboard

User & Pickup Management

Reporting & Analytics


---

## 👏 Credits
Made with 💚 for Ghana. A sustainable initiative powered by GreenBox GH.
