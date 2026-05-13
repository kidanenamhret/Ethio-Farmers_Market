<p align="center">
  <img src="https://readme-typing-svg.demolab.com?font=Outfit&weight=600&size=24&pause=1000&color=2ECC71&center=true&vCenter=true&width=600&lines=Harvesting+Excellence.+Empowering+Communities.;Connecting+Local+Farmers+to+Urban+Tables.;Freshness+Delivered.+Directly+from+Ethiopia.;Built+with+Passion.+Designed+for+Impact." alt="Typing SVG" />
</p>

<p align="center">
  <img src="assets/image/animated_divider.svg" width="100%" />
</p>

[![PHP Version](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![UI/UX](https://img.shields.io/badge/UI/UX-Glassmorphism-2ECC71?style=for-the-badge&logo=figma&logoColor=white)](https://github.com/kidanenamhret/Ethio-Farmers_Market)

🔗 **Official Repository:** [github.com/kidanenamhret/Ethio-Farmers_Market](https://github.com/kidanenamhret/Ethio-Farmers_Market)

<p align="center">
  <img src="assets/image/animated_divider.svg" width="100%" />
</p>

---

> [!IMPORTANT]
> **Ethio Farmers Market** is a premium, full-stack e-commerce solution designed specifically for the Ethiopian agricultural landscape. It connects local highland farmers directly to urban consumers through a high-performance, secure, and visually stunning digital marketplace.

---

## 📌 Project Overview

**Ethio Farmers Market** solves a critical real-world problem by eliminating exploitative middlemen in the agricultural supply chain. By providing a direct bridge, we ensure that farmers from regions like Debre Zeit, Ambo, and Bishoftu receive fair profits while consumers in Addis Ababa enjoy the freshest, organic produce.

This project was developed as a flagship submission for the **Web Programming** course, demonstrating mastery of the Vanilla PHP/MySQL/JS stack without the use of external frameworks.

---

## 🛠️ Technologies Used

<p align="left">
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=php,mysql,js,html,css,git,vscode,figma&theme=dark" />
  </a>
</p>

---

## 📈 Project Metrics & Vitality

> [!NOTE]
> The following metrics provide real-time proof of active development and project vitality, ensuring full compliance with **Academic Integrity** standards by demonstrating the iterative progress of the codebase.

<p align="center">
  <img src="https://github-readme-stats.vercel.app/api/pin/?username=kidanenamhret&repo=Ethio-Farmers_Market&theme=dracula&hide_border=true&bg_color=0D1117" alt="Repo Stats" />
</p>

<p align="center">
  <img src="https://github-readme-activity-graph.vercel.app/graph?username=kidanenamhret&theme=dracula&bg_color=0D1117&hide_border=true&color=2ECC71&line=2ECC71&point=F1C40F&area=true&hide_grid=true" width="100%" />
</p>

---

## 👥 Team Contributions & Task Classification

To ensure a robust and specialized development process, the project was divided into core functional modules:

| Team Member | Primary Role | Key Responsibilities & Tasks |
| :--- | :--- | :--- |
| **Mesfin Alemayehu** | **System Architect** | Database schema design, ERD modeling, and MySQL normalization. |
| **Biruktawit Geresu** | **UI/UX Lead** | Glassmorphism design system, responsive CSS architecture, and branding. |
| **Yonas Tadese** | **Backend Developer** | PHP logic controller, Session management, and Role-Based Access (RBAC). |
| **Edget Adissu** | **Full-Stack Engineer** | AJAX-powered live search engine and asynchronous product filtering. |
| **Ebsitu Birhanu** | **QA & Documentation** | System testing, SQL seeding, and technical documentation/README. |

---

## 🏗️ Project Architecture

```mermaid
graph TD
    User((User)) -->|Browser| UI[Glassmorphism UI]
    UI -->|AJAX/Fetch| Search[Live Search Engine]
    UI -->|POST/GET| PHP[PHP Logic Controller]
    PHP -->|PDO| DB[(MySQL Database)]
    DB -->|Result Set| PHP
    PHP -->|HTML Response| UI
    PHP -->|JSON| Search
```

---

## 🌟 Key Features

<table>
  <tr>
    <td width="33%" align="center">
      <img src="assets/image/feature_search.svg" width="80" />
      <br />
      <b>Live AJAX Search</b>
      <br />
      Instant product discovery powered by asynchronous API requests, eliminating page reloads.
    </td>
    <td width="33%" align="center">
      <img src="assets/image/feature_farmer.svg" width="80" />
      <br />
      <b>Farmer Dashboard</b>
      <br />
      Dynamic previews and real-time inventory management with custom Glassmorphism UI.
    </td>
    <td width="33%" align="center">
      <img src="assets/image/feature_security.svg" width="80" />
      <br />
      <b>Enterprise Security</b>
      <br />
      Full PDO prepared statements, RBAC (Role-Based Access Control), and session safety.
    </td>
  </tr>
</table>

---

## 📂 Database Architecture (ERD)

The system utilizes a high-performance relational database with 5 interconnected tables. Below is the Entity Relationship Diagram:

```mermaid
erDiagram
    USERS ||--o{ PRODUCTS : "Farmer lists products"
    USERS ||--o{ ORDERS : "Customer places orders"
    CATEGORIES ||--o{ PRODUCTS : "Classifies products"
    ORDERS ||--o{ ORDER_ITEMS : "Order contains items"
    PRODUCTS ||--o{ ORDER_ITEMS : "Product is in order_items"

    USERS {
        int id PK
        string username
        string full_name
        string email
        enum role
    }

    CATEGORIES {
        int id PK
        string name
    }

    PRODUCTS {
        int id PK
        int farmer_id FK
        int category_id FK
        string name
        decimal price
    }

    ORDERS {
        int id PK
        int customer_id FK
        decimal total_amount
        enum status
    }

    ORDER_ITEMS {
        int id PK
        int order_id FK
        int product_id FK
        int quantity
    }
```

### Relationship Breakdown:
*   **One-to-Many**: Users to Products, Users to Orders, Categories to Products.
*   **Many-to-Many**: Orders to Products (linked via the `order_items` bridge table).

---

## 🏗️ Core System Modules

<p align="center">
  <img src="assets/image/module_basket.svg" width="60" /> <b>Marketplace Engine</b> &nbsp;&nbsp;&nbsp;
  <img src="assets/image/module_profile.svg" width="60" /> <b>RBAC User Portal</b> &nbsp;&nbsp;&nbsp;
  <img src="assets/image/feature_security.svg" width="60" /> <b>Secure Transaction Gateway</b>
</p>

---

<p align="center">
  <img src="assets/image/animated_divider.svg" width="100%" />
</p>

---

## 🚀 Setup & Installation Instructions

1. **Environment**: Ensure you have **XAMPP** or **WAMP** installed on your system.
2. **Database Setup**:
   - Open **phpMyAdmin**.
   - Create a new database named `ethio_farmers_market`.
   - Import the SQL file located at: `/sql/ethio_farmers_market.sql`.
3. **File Deployment**:
   - Copy the entire `ethio-farmers-market` project folder into your server's root directory (e.g., `C:/xampp/htdocs/`).
4. **Configuration**:
   - If your MySQL credentials differ from the default (host: localhost, user: root, pass: ""), update the configuration in `/config/database.php`.
5. **Access**:
   - Open your browser and navigate to: `http://localhost/ethio-farmers-market/public/index.php`.

---

<p align="center">
  <strong>Web Programming Course - Final Group Project</strong>
</p>

<p align="center">
  <b>Group Members:</b><br>
  Mesfin Alemayehu • Biruktawit Geresu • Yonas Tadese • Edget Adissu • Ebsitu Birhanu
</p>

<p align="center">
  <img src="assets/image/animated_divider.svg" width="100%" />
</p>

---

## 🔐 User Roles & Demo Credentials

To test the full functionality of the marketplace, you can use the following pre-seeded demo accounts. **The password for all accounts is `password123`.**

| Role | Email Address | Description |
| :--- | :--- | :--- |
| **Admin** | `admin@ethiofarmers.com` | Full marketplace oversight. Can manage all users, view global sales, and verify product categories. |
| **Farmer** | `farmer@demo.com` | Can list new products, manage inventory (Edit/Delete), and view incoming orders for their produce. |
| **Customer** | `tigist@buyer.com` | Standard buyer role. Can browse the marketplace, manage their cart, place orders, and track history. |

### Role Permissions Details

- **Customer**:
  - Access to the public marketplace.
  - Session-based shopping cart management.
  - Checkout and order tracking (My Orders).
- **Farmer**:
  - All Customer features (Farmers can also buy).
  - Personal **Farmer Dashboard**.
  - **Add/Edit/Delete** products.
  - Track sales and stock levels.
- **Administrator**:
  - All Customer features.
  - **Admin Panel** access.
  - View all registered users and global transaction history.
  - User verification and category management.

---

## 📜 Academic Integrity & Compliance

- **No Frameworks**: Built entirely using vanilla technologies as per course restrictions.
- **Security**: Implements PDO prepared statements to prevent SQL Injection and `password_hash()` for security.
- **AJAX Requirement**: Asynchronous live search implemented in `assets/js/live-search.js` and `ajax/live-search.php`.

---

<p align="center">
  <img src="assets/image/animated_divider.svg" width="100%" />
</p>

<p align="center">
  <a href="#-ethio-farmers-market"><b>Back to Top ⬆️</b></a>
</p>

<p align="center">
  <strong>Web Programming Course - Group Project</strong><br>
  © 2026 Ethio Farmers Market. Supporting local agriculture through technology.
</p>
